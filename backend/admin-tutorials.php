<?php

function extractYoutubeId($url) {
    $url = trim($url);
    if (empty($url)) return '';
    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
    return $matches[1] ?? '';
}

function handleTutorials() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        try {
            $stmt = $pdo->query('SELECT * FROM tutorials ORDER BY sort_order ASC');
            $items = $stmt->fetchAll();
            echo json_encode(['success' => true, 'items' => $items]);
        } catch (Exception $e) {
            error_log('List tutorials error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to load tutorials']);
        }
        return;
    }

    requireAdmin();

    $body = json_decode(file_get_contents('php://input'), true);
    $action = $body['sub_action'] ?? '';

    if ($action === 'add') {
        $title = trim($body['title'] ?? '');
        $description = trim($body['description'] ?? '');
        $videoUrl = trim($body['video_url'] ?? '');

        if (empty($title) || empty($videoUrl)) {
            echo json_encode(['error' => 'Title and video URL are required']);
            return;
        }

        $videoId = extractYoutubeId($videoUrl);
        if (empty($videoId)) {
            echo json_encode(['error' => 'Invalid YouTube URL']);
            return;
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO tutorials (title, description, video_url, video_id, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $description, $videoUrl, $videoId, intval($body['sort_order'] ?? 0), intval($body['is_active'] ?? 1)]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'Tutorial added']);
        } catch (Exception $e) {
            error_log('Add tutorial error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to add tutorial']);
        }
    } elseif ($action === 'edit') {
        $id = intval($body['id'] ?? 0);
        $title = trim($body['title'] ?? '');
        $description = trim($body['description'] ?? '');
        $videoUrl = trim($body['video_url'] ?? '');

        if (!$id || empty($title) || empty($videoUrl)) {
            echo json_encode(['error' => 'ID, title, and video URL are required']);
            return;
        }

        $videoId = extractYoutubeId($videoUrl);
        if (empty($videoId)) {
            echo json_encode(['error' => 'Invalid YouTube URL']);
            return;
        }

        try {
            $stmt = $pdo->prepare('UPDATE tutorials SET title = ?, description = ?, video_url = ?, video_id = ?, sort_order = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$title, $description, $videoUrl, $videoId, intval($body['sort_order'] ?? 0), intval($body['is_active'] ?? 1), $id]);
            echo json_encode(['success' => true, 'message' => 'Tutorial updated']);
        } catch (Exception $e) {
            error_log('Edit tutorial error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to update tutorial']);
        }
    } elseif ($action === 'delete') {
        $id = intval($body['id'] ?? 0);
        if (!$id) {
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM tutorials WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Tutorial deleted']);
        } catch (Exception $e) {
            error_log('Delete tutorial error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete tutorial']);
        }
    } else {
        echo json_encode(['error' => 'Unknown sub action']);
    }
}
