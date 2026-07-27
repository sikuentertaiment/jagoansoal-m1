<?php

function handleLandingSettings() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $action = $_GET['sub'] ?? 'all';
        try {
            if ($action === 'settings') {
                $stmt = $pdo->query('SELECT * FROM landing_settings');
                $rows = $stmt->fetchAll();
                $settings = [];
                foreach ($rows as $row) {
                    $settings[$row['key']] = $row['value'];
                }
                echo json_encode(['success' => true, 'settings' => $settings]);
            } elseif ($action === 'how_it_works') {
                $stmt = $pdo->query('SELECT * FROM how_it_works ORDER BY step_number ASC');
                $items = $stmt->fetchAll();
                echo json_encode(['success' => true, 'items' => $items]);
            } else {
                $stmtSettings = $pdo->query('SELECT * FROM landing_settings');
                $rows = $stmtSettings->fetchAll();
                $settings = [];
                foreach ($rows as $row) {
                    $settings[$row['key']] = $row['value'];
                }
                $stmtHow = $pdo->query('SELECT * FROM how_it_works ORDER BY step_number ASC');
                $howItems = $stmtHow->fetchAll();
                echo json_encode(['success' => true, 'settings' => $settings, 'how_it_works' => $howItems]);
            }
        } catch (Exception $e) {
            error_log('Landing settings GET error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to load landing data']);
        }
        return;
    }

    requireAdmin();

    $body = json_decode(file_get_contents('php://input'), true);
    $action = $body['sub_action'] ?? '';

    if ($action === 'update_settings') {
        $settings = $body['settings'] ?? [];
        if (empty($settings)) {
            echo json_encode(['error' => 'No settings provided']);
            return;
        }
        try {
            $allowedKeys = ['hero_title', 'hero_subtitle', 'hero_stat_soal', 'hero_stat_guru', 'hero_video_url', 'hero_image_url'];
            $stmt = $pdo->prepare('INSERT INTO landing_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
            foreach ($settings as $key => $value) {
                if (in_array($key, $allowedKeys)) {
                    $stmt->execute([$key, trim($value)]);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Settings updated']);
        } catch (Exception $e) {
            error_log('Update landing settings error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to update settings']);
        }
    } elseif ($action === 'add_how_it_works') {
        $stepNumber = intval($body['step_number'] ?? 0);
        $title = trim($body['title'] ?? '');
        $description = trim($body['description'] ?? '');

        if (!$stepNumber || empty($title) || empty($description)) {
            echo json_encode(['error' => 'Step number, title, and description are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO how_it_works (step_number, title, description) VALUES (?, ?, ?)');
            $stmt->execute([$stepNumber, $title, $description]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'Item added']);
        } catch (Exception $e) {
            error_log('Add how_it_works error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to add item']);
        }
    } elseif ($action === 'edit_how_it_works') {
        $id = intval($body['id'] ?? 0);
        $stepNumber = intval($body['step_number'] ?? 0);
        $title = trim($body['title'] ?? '');
        $description = trim($body['description'] ?? '');

        if (!$id || !$stepNumber || empty($title) || empty($description)) {
            echo json_encode(['error' => 'ID, step number, title, and description are required']);
            return;
        }

        try {
            $stmt = $pdo->prepare('UPDATE how_it_works SET step_number = ?, title = ?, description = ? WHERE id = ?');
            $stmt->execute([$stepNumber, $title, $description, $id]);
            echo json_encode(['success' => true, 'message' => 'Item updated']);
        } catch (Exception $e) {
            error_log('Edit how_it_works error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to update item']);
        }
    } elseif ($action === 'delete_how_it_works') {
        $id = intval($body['id'] ?? 0);
        if (!$id) {
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM how_it_works WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Item deleted']);
        } catch (Exception $e) {
            error_log('Delete how_it_works error: ' . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete item']);
        }
    } else {
        echo json_encode(['error' => 'Unknown sub action']);
    }
}
