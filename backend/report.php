<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'submit':
        submitReport();
        break;
    case 'list':
        listReports();
        break;
    case 'mark_read':
        markAsRead();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}

function submitReport() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    $subject = $body['subject'] ?? 'bug';
    $description = trim($body['description'] ?? '');
    $imageUrl = $body['image_url'] ?? '';

    if (empty($description)) {
        echo json_encode(['error' => 'Description is required']);
        return;
    }

    $userId = getCurrentUserId();
    $userEmail = '';
    $userName = 'Guest';

    if ($userId) {
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT email, display_name FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            if ($user) {
                $userEmail = $user['email'];
                $userName = $user['display_name'] ?? 'User';
            }
        }
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO reports (user_id, user_email, user_name, subject, description, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $userEmail, $userName, $subject, $description, $imageUrl, 'new']);

        $reportId = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'report_id' => $reportId,
            'message' => 'Report submitted successfully'
        ]);
    } catch (Exception $e) {
        error_log('Submit report error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to submit report']);
    }
}

function listReports() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;

    try {
        $stmt = $pdo->prepare('SELECT * FROM reports ORDER BY created_at DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $reports = $stmt->fetchAll();

        $countStmt = $pdo->query('SELECT COUNT(*) as total FROM reports');
        $total = $countStmt->fetch()['total'];
        $totalPages = ceil($total / $limit);

        echo json_encode([
            'success' => true,
            'reports' => $reports,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total' => $total
            ]
        ]);
    } catch (Exception $e) {
        error_log('List reports error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load reports']);
    }
}

function markAsRead() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $reportId = $body['report_id'] ?? null;

    if (!$reportId) {
        echo json_encode(['error' => 'Report ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('UPDATE reports SET status = ? WHERE id = ?');
        $stmt->execute(['read', $reportId]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log('Mark read error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to update report']);
    }
}
