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
    case 'upload':
        handleUploadImage();
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
}

function handleUploadImage() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        return;
    }

    $uploadDir = dirname(__DIR__) . '/public/assets/question/images';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            echo json_encode(['error' => 'Failed to create upload directory']);
            return;
        }
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['error' => 'No file uploaded or upload error']);
        return;
    }

    $file = $_FILES['image'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if ($file['size'] > $maxSize) {
        echo json_encode(['error' => 'File too large. Maximum 5MB.']);
        return;
    }

    $mimeType = null;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
    } elseif (function_exists('mime_content_type')) {
        $mimeType = mime_content_type($file['tmp_name']);
    } else {
        // Fallback: cek dari magic bytes file
        $handle = fopen($file['tmp_name'], 'rb');
        $bytes  = fread($handle, 12);
        fclose($handle);
        if (substr($bytes, 0, 3) === "\xFF\xD8\xFF") {
            $mimeType = 'image/jpeg';
        } elseif (substr($bytes, 0, 8) === "\x89PNG\r\n\x1a\n") {
            $mimeType = 'image/png';
        } elseif (substr($bytes, 0, 6) === 'GIF87a' || substr($bytes, 0, 6) === 'GIF89a') {
            $mimeType = 'image/gif';
        } elseif (substr($bytes, 0, 4) === 'RIFF' && substr($bytes, 8, 4) === 'WEBP') {
            $mimeType = 'image/webp';
        }
    }

    if (!$mimeType || !in_array($mimeType, $allowedTypes)) {
        echo json_encode(['error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP']);
        return;
    }

    if (!in_array($mimeType, $allowedTypes)) {
        echo json_encode(['error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP']);
        return;
    }

    $ext = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg',
    };

    $filename = 'q_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destPath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        echo json_encode(['error' => 'Failed to save file']);
        return;
    }

    $url = '/public/assets/question/images/' . $filename;

    echo json_encode([
        'success' => true,
        'url' => $url,
        'filename' => $filename
    ]);
}
