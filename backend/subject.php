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

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        handleList();
        break;
    case 'get':
        handleGet();
        break;
    case 'add':
        handleAdd();
        break;
    case 'edit':
        handleEdit();
        break;
    case 'delete':
        handleDelete();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}

function handleList() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $userId = getCurrentUserId();
        $classId = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;

        $sql = '
            SELECT s.id, s.name, s.description, s.class_id, c.name AS class_name
            FROM subjects s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE (s.user_id = ? OR s.user_id IS NULL)
        ';
        $params = [$userId];

        if ($classId) {
            $sql .= ' AND s.class_id = ?';
            $params[] = $classId;
        }

        $sql .= ' ORDER BY c.name ASC, s.name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $subjects = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'subjects' => $subjects
        ]);
    } catch (Exception $e) {
        error_log('List subjects error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load subjects']);
    }
}

function handleGet() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(['error' => 'Subject ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('
            SELECT s.id, s.name, s.description, s.user_id, s.class_id, c.name AS class_name
            FROM subjects s
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ? AND (s.user_id = ? OR s.user_id IS NULL)
        ');
        $stmt->execute([$id, getCurrentUserId()]);
        $subject = $stmt->fetch();

        if (!$subject) {
            echo json_encode(['error' => 'Subject not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'subject' => $subject
        ]);
    } catch (Exception $e) {
        error_log('Get subject error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load subject']);
    }
}

function handleAdd() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $name = trim($body['name'] ?? '');
    $classId = isset($body['class_id']) && $body['class_id'] !== '' ? intval($body['class_id']) : null;

    if (empty($name)) {
        echo json_encode(['error' => 'Subject name is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO subjects (user_id, name, class_id) VALUES (?, ?, ?)');
        $stmt->execute([getCurrentUserId(), $name, $classId]);

        echo json_encode([
            'success' => true,
            'subject_id' => $pdo->lastInsertId(),
            'message' => 'Subject added successfully'
        ]);
    } catch (Exception $e) {
        error_log('Add subject error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to add subject']);
    }
}

function handleEdit() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $id = $body['id'] ?? null;
    $name = trim($body['name'] ?? '');
    $classId = isset($body['class_id']) && $body['class_id'] !== '' ? intval($body['class_id']) : null;

    if (!$id || empty($name)) {
        echo json_encode(['error' => 'ID and name are required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $userId = getCurrentUserId();
        $stmt = $pdo->prepare('UPDATE subjects SET name = ?, class_id = ?, user_id = COALESCE(user_id, ?) WHERE id = ? AND (user_id = ? OR user_id IS NULL)');
        $stmt->execute([$name, $classId, $userId, $id, $userId]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Subject not found or no changes']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Subject updated successfully'
        ]);
    } catch (Exception $e) {
        error_log('Edit subject error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to update subject']);
    }
}

function handleDelete() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $id = $body['id'] ?? null;

    if (!$id) {
        echo json_encode(['error' => 'Subject ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM subjects WHERE id = ? AND (user_id = ? OR user_id IS NULL)');
        $stmt->execute([$id, getCurrentUserId()]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Subject not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Subject deleted successfully'
        ]);
    } catch (Exception $e) {
        error_log('Delete subject error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to delete subject']);
    }
}
