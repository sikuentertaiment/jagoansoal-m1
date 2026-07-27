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
    case 'list':
        handleList();
        break;
    case 'save':
        handleSave();
        break;
    case 'get':
        handleGet();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'delete':
        handleDelete();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}

function handleList() {
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

    $userId = getCurrentUserId();
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $subjectFilter = $_GET['subject'] ?? '';
    $classFilter = $_GET['class'] ?? '';
    $typeFilter = $_GET['type'] ?? '';
    $search = $_GET['search'] ?? '';

    try {
        $where = 'WHERE user_id = ?';
        $params = [$userId];

        if (!empty($subjectFilter)) {
            $where .= ' AND subject_id = ?';
            $params[] = $subjectFilter;
        }

        if (!empty($classFilter)) {
            $where .= ' AND class = ?';
            $params[] = $classFilter;
        }

        if (!empty($typeFilter)) {
            $where .= ' AND type = ?';
            $params[] = $typeFilter;
        }

        if (!empty($search)) {
            $where .= ' AND (title LIKE ? OR questions_data LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM questions {$where}");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        $totalPages = ceil($total / $limit);

        $where = 'WHERE q.user_id = ?';
        $params = [$userId];

        if (!empty($subjectFilter)) {
            $where .= ' AND q.subject_id = ?';
            $params[] = $subjectFilter;
        }

        if (!empty($classFilter)) {
            $where .= ' AND q.class = ?';
            $params[] = $classFilter;
        }

        if (!empty($typeFilter)) {
            $where .= ' AND q.type = ?';
            $params[] = $typeFilter;
        }

        if (!empty($search)) {
            $where .= ' AND (q.title LIKE ? OR q.questions_data LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $stmt = $pdo->prepare("
            SELECT q.*, s.name as subject_name
            FROM questions q
            LEFT JOIN subjects s ON q.subject_id = s.id
            {$where}
            ORDER BY q.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $i = 1;
        foreach ($params as $val) {
            $stmt->bindValue($i++, $val);
        }
        $stmt->bindValue($i++, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue($i++, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll();

        foreach ($questions as &$q) {
            $qData = json_decode($q['questions_data'], true);
            $q['total_questions'] = is_array($qData) ? count($qData) : 0;
        }

        echo json_encode([
            'success' => true,
            'questions' => $questions,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total' => (int)$total
            ]
        ]);
    } catch (Exception $e) {
        error_log('List questions error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load questions']);
    }
}

function handleSave() {
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

    $subjectId = $body['subject_id'] ?? null;
    $title = trim($body['title'] ?? '');
    $class = trim($body['class'] ?? '');
    $difficulty = $body['difficulty'] ?? 'medium';
    $type = $body['type'] ?? 'multiple_choice';
    $questionsData = $body['questions_data'] ?? [];
    $promptUsed = $body['prompt_used'] ?? '';

    if (empty($title)) {
        echo json_encode(['error' => 'Title is required']);
        return;
    }

    if (empty($questionsData) || !is_array($questionsData)) {
        echo json_encode(['error' => 'Questions data is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO questions (user_id, subject_id, title, class, difficulty, type, total_questions, questions_data, prompt_used) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            getCurrentUserId(),
            $subjectId,
            $title,
            $class,
            $difficulty,
            $type,
            count($questionsData),
            json_encode($questionsData),
            $promptUsed
        ]);

        $questionId = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'question_id' => $questionId,
            'message' => 'Question saved successfully'
        ]);
    } catch (Exception $e) {
        error_log('Save question error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to save question']);
    }
}

function handleGet() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $id = $_GET['id'] ?? 0;

    if (!$id) {
        echo json_encode(['error' => 'Question ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('
            SELECT q.*, s.name as subject_name
            FROM questions q
            LEFT JOIN subjects s ON q.subject_id = s.id
            WHERE q.id = ? AND q.user_id = ?
        ');
        $stmt->execute([$id, getCurrentUserId()]);
        $question = $stmt->fetch();

        if (!$question) {
            echo json_encode(['error' => 'Question not found']);
            return;
        }

        $question['questions_data'] = json_decode($question['questions_data'], true);

        echo json_encode([
            'success' => true,
            'question' => $question
        ]);
    } catch (Exception $e) {
        error_log('Get question error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load question']);
    }
}

function handleUpdate() {
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
    $id = $body['id'] ?? 0;
    $questionsData = $body['questions_data'] ?? [];

    if (!$id) {
        echo json_encode(['error' => 'Question ID is required']);
        return;
    }

    if (empty($questionsData) || !is_array($questionsData)) {
        echo json_encode(['error' => 'Questions data is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('UPDATE questions SET questions_data = ?, total_questions = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([
            json_encode($questionsData),
            count($questionsData),
            $id,
            getCurrentUserId()
        ]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Question not found or no changes made']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Question updated successfully'
        ]);
    } catch (Exception $e) {
        error_log('Update question error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to update question']);
    }
}

function handleDelete() {
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
    $id = $body['id'] ?? 0;

    if (!$id) {
        echo json_encode(['error' => 'Question ID is required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM questions WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, getCurrentUserId()]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Question not found or already deleted']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    } catch (Exception $e) {
        error_log('Delete question error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to delete question']);
    }
}
