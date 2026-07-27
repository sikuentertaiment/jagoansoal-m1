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
require_once __DIR__ . '/admin-tutorials.php';
require_once __DIR__ . '/landing-settings.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'check':
        handleCheck();
        break;
    case 'dashboard':
        requireAdmin();
        handleDashboard();
        break;
    case 'users':
        requireAdmin();
        handleUsers();
        break;
    case 'transactions':
        requireAdmin();
        handleTransactions();
        break;
    case 'manual_topup':
        requireAdmin();
        handleManualTopup();
        break;
    case 'subjects':
        // requireAdmin();
        handleSubjects();
        break;
    case 'social_links':
        handleSocialLinks();
        break;
    case 'faq_items':
        handleFaqItems();
        break;
    case 'tutorials':
        handleTutorials();
        break;
    case 'landing_settings':
        handleLandingSettings();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
}

function requireAdmin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        exit();
    }

    $email = $_SESSION['user_email'] ?? '';
    $admins = array_map('trim', explode(',', ADMIN_EMAILS));

    if (!in_array($email, $admins)) {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        exit();
    }
}

function isAdminUser() {
    if (!isLoggedIn()) return false;
    $email = $_SESSION['user_email'] ?? '';
    $admins = array_map('trim', explode(',', ADMIN_EMAILS));
    return in_array($email, $admins);
}

function handleCheck() {
    echo json_encode([
        'success' => true,
        'isAdmin' => isAdminUser()
    ]);
}

function handleDashboard() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalQuestions = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
        $totalTopups = $pdo->query("SELECT COUNT(*) FROM topup_transactions WHERE status = 'success'")->fetchColumn();

        $stmt = $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM topup_transactions WHERE status = 'success'");
        $totalRevenue = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COALESCE(SUM(credits), 0) FROM topup_transactions WHERE status = 'success'");
        $totalCreditsSold = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM topup_transactions WHERE status = 'pending'");
        $pendingTopups = $stmt->fetchColumn();

        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE DATE(created_at) = ?");
            $stmt->execute([$date]);
            $count = $stmt->fetchColumn();
            $dailyStats[] = [
                'date' => $date,
                'label' => date('d M', strtotime($date)),
                'count' => (int)$count
            ];
        }

        $userGrowthStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE DATE(created_at) = ?");
            $stmt->execute([$date]);
            $count = $stmt->fetchColumn();
            $userGrowthStats[] = [
                'date' => $date,
                'label' => date('d M', strtotime($date)),
                'count' => (int)$count
            ];
        }

        echo json_encode([
            'success' => true,
            'stats' => [
                'total_users' => (int)$totalUsers,
                'total_generated' => (int)$totalQuestions,
                'total_topups' => (int)$totalTopups,
                'total_revenue' => (int)$totalRevenue,
                'total_credits_sold' => (int)$totalCreditsSold,
                'pending_topups' => (int)$pendingTopups,
            ],
            'daily_generated' => $dailyStats,
            'daily_users' => $userGrowthStats
        ]);
    } catch (Exception $e) {
        error_log('Admin dashboard error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load dashboard']);
    }
}

function handleUsers() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->query("
            SELECT u.id, u.email, u.display_name, u.photo_url, u.credit, u.created_at, u.last_login,
                   (SELECT COUNT(*) FROM questions WHERE user_id = u.id) as total_generated,
                   (SELECT COUNT(*) FROM topup_transactions WHERE user_id = u.id AND status = 'success') as total_topups,
                   (SELECT COALESCE(SUM(total_price), 0) FROM topup_transactions WHERE user_id = u.id AND status = 'success') as total_spent
            FROM users u
            ORDER BY u.created_at DESC
        ");
        $users = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'users' => $users
        ]);
    } catch (Exception $e) {
        error_log('Admin users error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load users']);
    }
}

function handleTransactions() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->query("
            SELECT t.*, u.email, u.display_name
            FROM topup_transactions t
            LEFT JOIN users u ON t.user_id = u.id
            ORDER BY t.created_at DESC
            LIMIT 200
        ");
        $transactions = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'transactions' => $transactions
        ]);
    } catch (Exception $e) {
        error_log('Admin transactions error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load transactions']);
    }
}

function handleManualTopup() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $userId = trim($body['user_id'] ?? '');
    $credits = intval($body['credits'] ?? 0);

    if (!$userId) {
        echo json_encode(['error' => 'User ID is required']);
        return;
    }

    if ($credits < 1 || $credits > 999) {
        echo json_encode(['error' => 'Credits must be between 1 and 999']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, email, display_name FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $stmt = $pdo->prepare('UPDATE users SET credit = credit + ? WHERE id = ?');
        $stmt->execute([$credits, $userId]);

        $stmt = $pdo->prepare('INSERT INTO credit_usage (user_id, amount, description) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $credits, 'Manual top-up by admin: +' . $credits . ' credits']);

        $stmt = $pdo->prepare('SELECT credit FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $newCredit = $stmt->fetchColumn();

        echo json_encode([
            'success' => true,
            'message' => $credits . ' credits added to ' . ($user['display_name'] ?: $user['email']),
            'new_credit' => (int)$newCredit
        ]);
    } catch (Exception $e) {
        error_log('Manual topup error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to add credits']);
    }
}

function handleSubjects() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        try {
            $stmt = $pdo->query('SELECT * FROM subjects ORDER BY name ASC');
            $subjects = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (Exception $e) {
            error_log('List subjects error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to load subjects']);
        }
    } elseif ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        $action = $body['sub_action'] ?? '';

        if ($action === 'add') {
            $name = trim($body['name'] ?? '');
            $description = trim($body['description'] ?? '');

            if (empty($name)) {
                echo json_encode(['error' => 'Subject name is required']);
                return;
            }

            try {
                $stmt = $pdo->prepare('INSERT INTO subjects (name, description) VALUES (?, ?)');
                $stmt->execute([$name, $description]);

                echo json_encode([
                    'success' => true,
                    'subject_id' => $pdo->lastInsertId(),
                    'message' => 'Subject added successfully'
                ]);
            } catch (Exception $e) {
                error_log('Add subject error: ' . $e->getMessage());
                echo json_encode(['error' => 'Failed to add subject']);
            }
        } elseif ($action === 'edit') {
            $id = intval($body['id'] ?? 0);
            $name = trim($body['name'] ?? '');
            $description = trim($body['description'] ?? '');

            if (!$id || empty($name)) {
                echo json_encode(['error' => 'ID and name are required']);
                return;
            }

            try {
                $stmt = $pdo->prepare('UPDATE subjects SET name = ?, description = ? WHERE id = ?');
                $stmt->execute([$name, $description, $id]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Subject updated successfully'
                ]);
            } catch (Exception $e) {
                error_log('Edit subject error: ' . $e->getMessage());
                echo json_encode(['error' => 'Failed to update subject']);
            }
        } elseif ($action === 'delete') {
            $id = intval($body['id'] ?? 0);

            if (!$id) {
                echo json_encode(['error' => 'Subject ID is required']);
                return;
            }

            try {
                $stmt = $pdo->prepare('DELETE FROM subjects WHERE id = ?');
                $stmt->execute([$id]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Subject deleted successfully'
                ]);
            } catch (Exception $e) {
                error_log('Delete subject error: ' . $e->getMessage());
                echo json_encode(['error' => 'Failed to delete subject']);
            }
    } else {
        echo json_encode(['error' => 'Unknown sub action']);
    }
}
}

function handleSocialLinks() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        try {
            $stmt = $pdo->query('SELECT * FROM social_links ORDER BY sort_order ASC');
            $items = $stmt->fetchAll();
            echo json_encode(['success' => true, 'items' => $items]);
        } catch (Exception $e) {
            error_log('List social_links error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to load social links']);
        }
        return;
    }

    requireAdmin();

    $body = json_decode(file_get_contents('php://input'), true);
    $action = $body['sub_action'] ?? '';

    if ($action === 'add') {
        $platform = trim($body['platform'] ?? '');
        $url = trim($body['url'] ?? '');
        $icon = trim($body['icon'] ?? '');

        if (empty($platform) || empty($url) || empty($icon)) {
            echo json_encode(['error' => 'Platform, URL, and icon are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO social_links (platform, url, icon, sort_order) VALUES (?, ?, ?, ?)');
            $stmt->execute([$platform, $url, $icon, intval($body['sort_order'] ?? 0)]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'Social link added']);
        } catch (Exception $e) {
            error_log('Add social link error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to add social link']);
        }
    } elseif ($action === 'edit') {
        $id = intval($body['id'] ?? 0);
        $platform = trim($body['platform'] ?? '');
        $url = trim($body['url'] ?? '');
        $icon = trim($body['icon'] ?? '');

        if (!$id || empty($platform) || empty($url) || empty($icon)) {
            echo json_encode(['error' => 'ID, platform, URL, and icon are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('UPDATE social_links SET platform = ?, url = ?, icon = ?, sort_order = ? WHERE id = ?');
            $stmt->execute([$platform, $url, $icon, intval($body['sort_order'] ?? 0), $id]);
            echo json_encode(['success' => true, 'message' => 'Social link updated']);
        } catch (Exception $e) {
            error_log('Edit social link error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to update social link']);
        }
    } elseif ($action === 'delete') {
        $id = intval($body['id'] ?? 0);
        if (!$id) {
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM social_links WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Social link deleted']);
        } catch (Exception $e) {
            error_log('Delete social link error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete social link']);
        }
    } else {
        echo json_encode(['error' => 'Unknown sub action']);
    }
}

function handleFaqItems() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        try {
            $stmt = $pdo->query('SELECT * FROM faq_items ORDER BY sort_order ASC');
            $items = $stmt->fetchAll();
            echo json_encode(['success' => true, 'items' => $items]);
        } catch (Exception $e) {
            error_log('List faq_items error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to load FAQ items']);
        }
        return;
    }

    requireAdmin();

    $body = json_decode(file_get_contents('php://input'), true);
    $action = $body['sub_action'] ?? '';

    if ($action === 'add') {
        $question = trim($body['question'] ?? '');
        $answer = trim($body['answer'] ?? '');

        if (empty($question) || empty($answer)) {
            echo json_encode(['error' => 'Question and answer are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO faq_items (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)');
            $stmt->execute([$question, $answer, intval($body['sort_order'] ?? 0), intval($body['is_active'] ?? 1)]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'FAQ item added']);
        } catch (Exception $e) {
            error_log('Add FAQ error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to add FAQ item']);
        }
    } elseif ($action === 'edit') {
        $id = intval($body['id'] ?? 0);
        $question = trim($body['question'] ?? '');
        $answer = trim($body['answer'] ?? '');

        if (!$id || empty($question) || empty($answer)) {
            echo json_encode(['error' => 'ID, question, and answer are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('UPDATE faq_items SET question = ?, answer = ?, sort_order = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$question, $answer, intval($body['sort_order'] ?? 0), intval($body['is_active'] ?? 1), $id]);
            echo json_encode(['success' => true, 'message' => 'FAQ item updated']);
        } catch (Exception $e) {
            error_log('Edit FAQ error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to update FAQ item']);
        }
    } elseif ($action === 'delete') {
        $id = intval($body['id'] ?? 0);
        if (!$id) {
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM faq_items WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'FAQ item deleted']);
        } catch (Exception $e) {
            error_log('Delete FAQ error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete FAQ item']);
        }
    } else {
        echo json_encode(['error' => 'Unknown sub action']);
    }
}
