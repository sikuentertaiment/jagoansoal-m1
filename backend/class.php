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
        $stmt = $pdo->prepare('SELECT id, name FROM classes WHERE user_id = ? OR user_id IS NULL ORDER BY name ASC');
        $stmt->execute([$userId]);
        $classes = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'classes' => $classes
        ]);
    } catch (Exception $e) {
        error_log('List classes error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load classes']);
    }
}

function handleGet() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(['error' => 'Class ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, name, user_id FROM classes WHERE id = ? AND (user_id = ? OR user_id IS NULL)');
        $stmt->execute([$id, getCurrentUserId()]);
        $class = $stmt->fetch();

        if (!$class) {
            echo json_encode(['error' => 'Class not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'class' => $class
        ]);
    } catch (Exception $e) {
        error_log('Get class error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load class']);
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

    if (empty($name)) {
        echo json_encode(['error' => 'Class name is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO classes (user_id, name) VALUES (?, ?)');
        $stmt->execute([getCurrentUserId(), $name]);

        echo json_encode([
            'success' => true,
            'class_id' => $pdo->lastInsertId(),
            'message' => 'Class added successfully'
        ]);
    } catch (Exception $e) {
        error_log('Add class error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to add class']);
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
        $stmt = $pdo->prepare('UPDATE classes SET name = ?, user_id = COALESCE(user_id, ?) WHERE id = ? AND (user_id = ? OR user_id IS NULL)');
        $stmt->execute([$name, $userId, $id, $userId]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Class not found or no changes']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Class updated successfully'
        ]);
    } catch (Exception $e) {
        error_log('Edit class error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to update class']);
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
        echo json_encode(['error' => 'Class ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM classes WHERE id = ? AND (user_id = ? OR user_id IS NULL)');
        $stmt->execute([$id, getCurrentUserId()]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Class not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Class deleted successfully'
        ]);
    } catch (Exception $e) {
        error_log('Delete class error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to delete class']);
    }
}
