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

$action = $_GET['action'] ?? 'upload';

switch ($action) {
    case 'list_materials':
        listMaterials();
        break;
    case 'get_material':
        getMaterial();
        break;
    case 'delete_material':
        deleteMaterial();
        break;
    case 'save_material':
        saveMaterialText();
        break;
    case 'update_material':
        updateMaterialText();
        break;
    case 'generate_material':
        generateMaterial();
        break;
    default:
        handleUpload();
        break;
}

function listMaterials() {
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(50, intval($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;
    $search = trim($_GET['search'] ?? '');
    $subjectFilter = trim($_GET['subject'] ?? '');
    $classId = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;

    try {
        $where = 'WHERE m.user_id = ?';
        $countParams = [getCurrentUserId()];
        $queryParams = [getCurrentUserId()];

        if ($search !== '') {
            $where .= ' AND m.title LIKE ?';
            $countParams[] = '%' . $search . '%';
            $queryParams[] = '%' . $search . '%';
        }
        if ($subjectFilter !== '') {
            $where .= ' AND (s.name = ? OR m.subject_id = ?)';
            $countParams[] = $subjectFilter;
            $countParams[] = is_numeric($subjectFilter) ? intval($subjectFilter) : $subjectFilter;
            $queryParams[] = $subjectFilter;
            $queryParams[] = is_numeric($subjectFilter) ? intval($subjectFilter) : $subjectFilter;
        }
        if ($classId) {
            $where .= ' AND s.class_id = ?';
            $countParams[] = $classId;
            $queryParams[] = $classId;
        }

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM materials m LEFT JOIN subjects s ON m.subject_id = s.id $where");
        $countStmt->execute($countParams);
        $total = intval($countStmt->fetchColumn());

        $stmt = $pdo->prepare("
            SELECT m.id, m.title, m.subject_id, s.name AS subject_name, m.created_at
            FROM materials m
            LEFT JOIN subjects s ON m.subject_id = s.id
            $where
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $i = 1;
        foreach ($queryParams as $val) {
            $stmt->bindValue($i++, $val);
        }
        $stmt->bindValue($i++, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue($i++, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $materials = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'materials' => $materials,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => max(1, ceil($total / $limit)),
                'total' => $total,
                'limit' => $limit
            ]
        ]);
    } catch (Exception $e) {
        error_log('List materials error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load materials']);
    }
}

function getMaterial() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(['error' => 'Material ID required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('
            SELECT m.*, s.name AS subject_name
            FROM materials m
            LEFT JOIN subjects s ON m.subject_id = s.id
            WHERE m.id = ? AND m.user_id = ?
        ');
        $stmt->execute([$id, getCurrentUserId()]);
        $material = $stmt->fetch();

        if (!$material) {
            echo json_encode(['error' => 'Material not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'material' => $material
        ]);
    } catch (Exception $e) {
        error_log('Get material error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to load material']);
    }
}

function deleteMaterial() {
    $body = json_decode(file_get_contents('php://input'), true);
    $id = $body['id'] ?? null;

    if (!$id) {
        echo json_encode(['error' => 'Material ID required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM materials WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, getCurrentUserId()]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Material not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Material deleted successfully'
        ]);
    } catch (Exception $e) {
        error_log('Delete material error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to delete material']);
    }
}

function handleUpload()
{
    if (!isset($_FILES['image'])) {
        echo json_encode(['error' => 'No file uploaded']);
        return;
    }

    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['error' => 'Upload failed']);
        return;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $allowedExts = [
        'txt',
        'pdf',
        'doc',
        'docx',
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp'
    ];

    if (!in_array($ext, $allowedExts)) {
        echo json_encode([
            'error' => 'Only TXT, PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, WEBP files are allowed'
        ]);
        return;
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode(['error' => 'File size must be less than 10MB']);
        return;
    }

    $uploadDir = UPLOAD_DIR_MATERIALS;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = 'material_' . uniqid('', true) . '.' . $ext;
    $filepath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        echo json_encode(['error' => 'Failed to save file']);
        return;
    }

    $content = '';

    switch ($ext) {
        case 'txt':
            $content = file_get_contents($filepath);
            break;

        case 'pdf':
            $content = extractPdfText($filepath);
            break;

        // gambar tidak perlu diekstrak teksnya
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            $content = '';
            break;
    }

    echo json_encode([
        'success' => true,
        'file_path' => ASSETS_URL_MATERIALS . '/' . $filename,
        'filename' => $file['name'],
        'extension' => $ext,
        'content' => $content,
        'message' => 'File uploaded successfully'
    ]);
}

function saveMaterialText() {
    $body = json_decode(file_get_contents('php://input'), true);

    $title = trim($body['title'] ?? '');
    $content = trim($body['content'] ?? '');
    $subjectId = $body['subject_id'] ?? null;

    if (empty($title) || empty($content)) {
        echo json_encode(['error' => 'Title and content are required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO materials (user_id, subject_id, title, content) VALUES (?, ?, ?, ?)');
        $stmt->execute([getCurrentUserId(), $subjectId, $title, $content]);

        $materialId = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'material_id' => $materialId,
            'message' => 'Material saved successfully'
        ]);
    } catch (Exception $e) {
        error_log('Save material error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to save material']);
    }
}

function updateMaterialText() {
    $body = json_decode(file_get_contents('php://input'), true);

    $id = $body['id'] ?? null;
    $title = trim($body['title'] ?? '');
    $content = trim($body['content'] ?? '');
    $subjectId = $body['subject_id'] ?? null;

    if (!$id || empty($title) || empty($content)) {
        echo json_encode(['error' => 'ID, title and content are required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    try {
        $stmt = $pdo->prepare('UPDATE materials SET title = ?, subject_id = ?, content = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$title, $subjectId, $content, $id, getCurrentUserId()]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Material not found or no changes']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Material updated successfully'
        ]);
    } catch (Exception $e) {
        error_log('Update material error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to update material']);
    }
}

function extractPdfText($filepath) {
    $output = '';
    if (class_exists('\\Smalot\\PdfParser\\Parser')) {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($filepath);
            $output = $pdf->getText();
        } catch (Exception $e) {
            $output = '[PDF text extraction failed]';
        }
    } else {
        $cmd = 'pdftotext ' . escapeshellarg($filepath) . ' - 2>/dev/null';
        $output = shell_exec($cmd);
        if (!$output) {
            $output = '[PDF text extraction not available]';
        }
    }
    return $output;
}

function generateMaterial() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    $topic = trim($body['topic'] ?? '');
    $subject = trim($body['subject'] ?? '');
    $class = trim($body['class'] ?? '');
    $extraInstructions = trim($body['extra_instructions'] ?? '');

    if (empty($topic) || empty($subject) || empty($class)) {
        echo json_encode(['error' => 'Topic, subject, and class are required']);
        return;
    }

    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(['error' => 'Database connection failed']);
        return;
    }

    $userId = getCurrentUserId();

    try {
        $stmt = $pdo->prepare('SELECT credit FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        $cost = 1;

        if (!$user || $user['credit'] < $cost) {
            echo json_encode(['error' => 'insufficient_credits', 'message' => "Minimum {$cost} credit required"]);
            return;
        }

        $prompt = "Buatkan materi pembelajaran untuk mata pelajaran {$subject} kelas {$class} tentang topik \"{$topic}\". ";
        $prompt .= "Buat materi yang lengkap, terstruktur, dan mudah dipahami. Gunakan bahasa Indonesia.";
        if (!empty($extraInstructions)) {
            $prompt .= " Catatan tambahan: {$extraInstructions}";
        }
        $prompt .= " Format: output hanya teks materi saja, tanpa kata pengantar, tanpa penutup, tanpa markdown. Gunakan paragraf yang rapi.";

        $content = callAI($prompt);

        if (!$content || empty($content)) {
            echo json_encode(['error' => 'Failed to generate material. Please try again.']);
            return;
        }

        $stmt = $pdo->prepare("UPDATE users SET credit = credit - ? WHERE id = ?");
        $stmt->execute([$cost, $userId]);

        $stmt = $pdo->prepare('INSERT INTO credit_usage (user_id, amount, description) VALUES (?, ?, ?)');
        $stmt->execute([$userId, -$cost, 'Generate materi: ' . $topic]);

        echo json_encode([
            'success' => true,
            'content' => $content,
            'remaining_credits' => $user['credit'] - $cost
        ]);

    } catch (Exception $e) {
        error_log('Generate material error: ' . $e->getMessage());
        echo json_encode(['error' => 'Generation failed: ' . $e->getMessage()]);
    }
}

function callAI($prompt) {
    $apiKey = AI_API_KEY;

    $payload = [
        'model' => AI_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => 'Kamu adalah asisten pembuat materi pembelajaran untuk guru Indonesia. Selalu gunakan bahasa Indonesia.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
        'max_tokens' => 4000,
    ];

    $headers = ['Content-Type: application/json'];
    if (!empty($apiKey)) {
        $headers[] = 'Authorization: Bearer ' . $apiKey;
    }

    $ch = curl_init(AI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $data = json_decode($response, true);
        $errorMsg = $data['error']['message'] ?? 'AI API error (HTTP ' . $httpCode . ')';
        throw new Exception($errorMsg);
    }

    $data = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '';

    $content = preg_replace('/```json\s*/i', '', $content);
    $content = preg_replace('/```\s*/', '', $content);
    $content = trim($content);

    return $content;
}
