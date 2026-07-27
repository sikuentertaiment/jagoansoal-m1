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
    case 'login':
    case 'register':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'sync':
        handleSync();
        break;
    case 'check':
        checkAuth();
        break;
    case 'user':
        getUserData();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}

function handleSync() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    if (!isset($body['uid']) || !isset($body['email'])) {
        echo json_encode(['error' => 'Invalid user data']);
        return;
    }

    $uid = $body['uid'];
    $email = $body['email'];
    $displayName = $body['displayName'] ?? '';
    $photoUrl = $body['photoUrl'] ?? '';

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$uid]);
        $user = $stmt->fetch();

        if (!$user) {
            $stmt = $pdo->prepare('INSERT INTO users (id, email, display_name, photo_url, credit) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$uid, $email, $displayName, $photoUrl, FREE_CREDITS]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET display_name = ?, photo_url = ?, last_login = NOW() WHERE id = ?');
            $stmt->execute([$displayName, $photoUrl, $uid]);
        }

        $_SESSION['user_id'] = $uid;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $displayName;
        $_SESSION['user_photo'] = $photoUrl;

        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$uid]);
        $user = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } catch (Exception $e) {
        error_log('Sync error: ' . $e->getMessage());
        echo json_encode(['error' => 'Sync failed']);
    }
}

function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    if (!isset($body['uid']) || !isset($body['email'])) {
        echo json_encode(['error' => 'Invalid user data']);
        return;
    }

    $uid = $body['uid'];
    $email = $body['email'];
    $displayName = $body['displayName'] ?? '';
    $photoUrl = $body['photoUrl'] ?? '';

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$uid]);
        $user = $stmt->fetch();

        if (!$user) {
            $stmt = $pdo->prepare('INSERT INTO users (id, email, display_name, photo_url, credit) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$uid, $email, $displayName, $photoUrl, FREE_CREDITS]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET display_name = ?, photo_url = ?, last_login = NOW() WHERE id = ?');
            $stmt->execute([$displayName, $photoUrl, $uid]);
        }

        $_SESSION['user_id'] = $uid;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $displayName;
        $_SESSION['user_photo'] = $photoUrl;

        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$uid]);
        $user = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } catch (Exception $e) {
        error_log('Login error: ' . $e->getMessage());
        echo json_encode(['error' => 'Login failed']);
    }
}

function handleLogout() {
    session_destroy();
    echo json_encode(['success' => true]);
}

function checkAuth() {
    if (isLoggedIn()) {
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([getCurrentUserId()]);
            $user = $stmt->fetch();
            if ($user) {
                echo json_encode([
                    'loggedIn' => true,
                    'user' => $user
                ]);
                return;
            }
        }
    }

    echo json_encode(['loggedIn' => false]);
}

function getUserData() {
    if (!isLoggedIn()) {
        echo json_encode(['error' => 'Not logged in']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([getCurrentUserId()]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM questions WHERE user_id = ?');
        $stmt->execute([getCurrentUserId()]);
        $count = $stmt->fetch();
        $user['total_generated'] = $count['total'] ?? 0;

        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}
