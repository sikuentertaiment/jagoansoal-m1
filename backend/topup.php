<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'history':
        handleHistory();
        break;
    case 'credit_history':
        handleCreditHistory();
        break;
    case 'notification':
        handleNotification();
        break;
    case 'verify':
        handleVerify();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}

function handleCreate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $credits = intval($body['credits'] ?? 0);

    if ($credits < CREDITS_PER_PACKAGE) {
        echo json_encode(['error' => 'Minimum ' . CREDITS_PER_PACKAGE . ' credits per top-up']);
        return;
    }

    if ($credits % CREDITS_PER_PACKAGE !== 0) {
        $credits = ceil($credits / CREDITS_PER_PACKAGE) * CREDITS_PER_PACKAGE;
    }

    if ($credits > 99) {
        echo json_encode(['error' => 'Maximum 99 credits per top-up']);
        return;
    }

    $packages = $credits / CREDITS_PER_PACKAGE;
    $totalPrice = $packages * PRICE_PER_PACKAGE;
    $userId = getCurrentUserId();
    $orderId = 'HJ-' . strtoupper(substr($userId, 0, 8)) . '-' . time();

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $redirectUrl = createMidtransTransaction($orderId, $totalPrice, $credits);

        $stmt = $pdo->prepare('INSERT INTO topup_transactions (user_id, credits, total_price, status, midtrans_order_id, redirect_url) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $credits, $totalPrice, 'pending', $orderId, $redirectUrl]);

        echo json_encode([
            'success' => true,
            'redirect_url' => $redirectUrl,
            'order_id' => $orderId
        ]);
    } catch (Exception $e) {
        error_log('Topup create error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create top-up transaction']);
    }
}

function createMidtransTransaction($orderId, $totalPrice, $credits) {
    $payload = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $totalPrice
        ],
        'item_details' => [
            [
                'id' => 'credit-pack',
                'price' => PRICE_PER_PACKAGE,
                'quantity' => $credits / CREDITS_PER_PACKAGE,
                'name' => $credits . ' Credits'
            ]
        ],
        'customer_details' => [
            'first_name' => $_SESSION['user_name'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? '',
        ],
        'callbacks' => [
            'finish' => APP_URL . '/?page=tools-account&topup_status=finish&order_id=' . $orderId,
            'unfinish' => APP_URL . '/?page=tools-account&topup_status=unfinish',
            'error' => APP_URL . '/?page=tools-account&topup_status=error',
        ]
    ];

    $serverKey = MIDTRANS_SERVER_KEY;
    $auth = base64_encode($serverKey . ':');

    $ch = curl_init(MIDTRANS_SNAP_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . $auth
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log('[Midtrans] Create transaction response: ' . $response);

    if ($httpCode !== 201) {
        $data = json_decode($response, true);
        $errorMsg = $data['error_messages'][0] ?? 'Failed to create Midtrans transaction';
        throw new Exception($errorMsg);
    }

    $data = json_decode($response, true);
    return $data['redirect_url'];
}

function handleHistory() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(50, intval($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    try {


        $stmt = $pdo->prepare('SELECT COUNT(*) FROM topup_transactions WHERE user_id = ?');
        $stmt->execute([getCurrentUserId()]);
        $total = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare(
            'SELECT id, credits, total_price, status, midtrans_order_id,
                    redirect_url, payment_method, paid_at, created_at
             FROM topup_transactions
             WHERE user_id = ?
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?'
        );

        $stmt->bindValue(1, getCurrentUserId(), PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $transactions = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'transactions' => $transactions,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
    } catch (Exception $e) {
        error_log('Topup history error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to get history']);
    }
}

function handleCreditHistory() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(50, intval($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM credit_usage WHERE user_id = ?');
        $stmt->execute([getCurrentUserId()]);
        $total = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare(
            'SELECT id, amount, description, created_at
             FROM credit_usage
             WHERE user_id = ?
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?'
        );

        $stmt->bindValue(1, getCurrentUserId(), PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $history = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'history' => $history,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
    } catch (Exception $e) {
        error_log('Credit history error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to get credit history']);
    }
}

function handleNotification() {
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid notification']);
        return;
    }

    $orderId = $body['order_id'] ?? '';
    $transactionStatus = $body['transaction_status'] ?? '';
    $statusCode = $body['status_code'] ?? '';
    $grossAmount = $body['gross_amount'] ?? '';
    $serverKey = MIDTRANS_SERVER_KEY;
    $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    if ($signature !== ($body['signature_key'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid signature']);
        exit();
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM topup_transactions WHERE midtrans_order_id = ?');
        $stmt->execute([$orderId]);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            http_response_code(404);
            echo json_encode(['error' => 'Transaction not found']);
            return;
        }

        if ($transaction['status'] === 'success') {
            echo json_encode(['success' => true, 'message' => 'Already processed']);
            return;
        }

        $paymentMethod = $body['payment_type'] ?? '';
        $transactionId = $body['transaction_id'] ?? '';
        $paymentDetails = json_encode($body);

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $stmt = $pdo->prepare("UPDATE topup_transactions SET status = 'success', midtrans_transaction_id = ?, payment_method = ?, payment_details = ?, paid_at = NOW() WHERE midtrans_order_id = ?");
            $stmt->execute([$transactionId, $paymentMethod, $paymentDetails, $orderId]);

            $stmt = $pdo->prepare('UPDATE users SET credit = credit + ? WHERE id = ?');
            $stmt->execute([$transaction['credits'], $transaction['user_id']]);

            $stmt = $pdo->prepare('INSERT INTO credit_usage (user_id, amount, description) VALUES (?, ?, ?)');
            $stmt->execute([$transaction['user_id'], $transaction['credits'], 'Top-up ' . $transaction['credits'] . ' credits']);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            $stmt = $pdo->prepare("UPDATE topup_transactions SET status = 'failed', midtrans_transaction_id = ?, payment_method = ?, payment_details = ? WHERE midtrans_order_id = ?");
            $stmt->execute([$transactionId, $paymentMethod, $paymentDetails, $orderId]);
        } elseif ($transactionStatus === 'pending') {
            $stmt = $pdo->prepare("UPDATE topup_transactions SET midtrans_transaction_id = ?, payment_method = ?, payment_details = ? WHERE midtrans_order_id = ?");
            $stmt->execute([$transactionId, $paymentMethod, $paymentDetails, $orderId]);
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log('Topup notification error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to process notification']);
    }
}

function handleVerify() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $orderId = $_GET['order_id'] ?? '';
    if (!$orderId) {
        echo json_encode(['error' => 'Order ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM topup_transactions WHERE midtrans_order_id = ? AND user_id = ?');
        $stmt->execute([$orderId, getCurrentUserId()]);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            echo json_encode(['error' => 'Transaction not found']);
            return;
        }

        if ($transaction['status'] !== 'pending') {
            echo json_encode(['success' => true, 'transaction' => $transaction]);
            return;
        }

        $serverKey = MIDTRANS_SERVER_KEY;
        $auth = base64_encode($serverKey . ':');
        $statusUrl = 'https://api.midtrans.com/v2/' . $orderId . '/status';

        $ch = curl_init($statusUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Basic ' . $auth
            ],
            CURLOPT_TIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        error_log($response);

        if ($httpCode === 200) {
            $statusData = json_decode($response, true);
            $transactionStatus = $statusData['transaction_status'] ?? '';
            $statusCode = $statusData['status_code'] ?? '';
            $grossAmount = $statusData['gross_amount'] ?? '';
            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signature === ($statusData['signature_key'] ?? '')) {
                $paymentMethod = $statusData['payment_type'] ?? '';
                $transactionId = $statusData['transaction_id'] ?? '';
                $paymentDetails = json_encode($statusData);

                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    if ($transaction['status'] !== 'success') {
                        $stmt = $pdo->prepare("UPDATE topup_transactions SET status = 'success', midtrans_transaction_id = ?, payment_method = ?, payment_details = ?, paid_at = NOW() WHERE midtrans_order_id = ?");
                        $stmt->execute([$transactionId, $paymentMethod, $paymentDetails, $orderId]);

                        $stmt = $pdo->prepare('UPDATE users SET credit = credit + ? WHERE id = ?');
                        $stmt->execute([$transaction['credits'], $transaction['user_id']]);

                        $stmt = $pdo->prepare('INSERT INTO credit_usage (user_id, amount, description) VALUES (?, ?, ?)');
                        $stmt->execute([$transaction['user_id'], $transaction['credits'], 'Top-up ' . $transaction['credits'] . ' credits']);
                    }
                } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                    $stmt = $pdo->prepare("UPDATE topup_transactions SET status = 'failed', midtrans_transaction_id = ?, payment_method = ?, payment_details = ? WHERE midtrans_order_id = ?");
                    $stmt->execute([$transactionId, $paymentMethod, $paymentDetails, $orderId]);
                }
            }
        }

        $stmt = $pdo->prepare('SELECT * FROM topup_transactions WHERE midtrans_order_id = ? AND user_id = ?');
        $stmt->execute([$orderId, getCurrentUserId()]);
        $transaction = $stmt->fetch();

        echo json_encode(['success' => true, 'transaction' => $transaction]);
    } catch (Exception $e) {
        error_log('Topup verify error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to verify transaction']);
    }
}
