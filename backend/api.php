<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
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

$body = json_decode(file_get_contents('php://input'), true);

$subject = trim($body['subject'] ?? '');
$topic = trim($body['topic'] ?? '');
$class = trim($body['class'] ?? '');
$difficulty = $body['difficulty'] ?? 'medium';
$type = $body['type'] ?? 'multiple_choice';
$totalQuestions = intval($body['total_questions'] ?? 5);
$extraInstructions = trim($body['extra_instructions'] ?? '');
$pgCount = intval($body['pg_count'] ?? 0);
$essayCount = intval($body['essay_count'] ?? 0);
$questionOrder = $body['question_order'] ?? 'random';

if (empty($subject) || empty($topic) || empty($class)) {
    echo json_encode(['error' => 'Subject, topic, and class are required']);
    exit();
}

if ($totalQuestions < 1 || $totalQuestions > 50) {
    echo json_encode(['error' => 'Total questions must be between 1 and 50']);
    exit();
}

$pdo = getDbConnection();
if (!$pdo) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$userId = getCurrentUserId();

try {
    $stmt = $pdo->prepare('SELECT credit FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    $cost = max(1, (int)ceil($totalQuestions / 10));

    if (!$user || $user['credit'] < $cost) {
        echo json_encode(['error' => 'insufficient_credits', 'message' => "Minimum {$cost} credits required"]);
        exit();
    }

    if (!checkRateLimit($pdo, $userId)) {
        http_response_code(429);
        echo json_encode(['error' => 'rate_limit_exceeded', 'message' => 'Terlalu banyak permintaan. Silakan tunggu beberapa saat sebelum generate soal lagi.']);
        exit();
    }

    $typeLabel = $type === 'multiple_choice' ? 'Pilihan Ganda' : ($type === 'essay' ? 'Essay' : 'Campuran');
    $difficultyLabel = $difficulty === 'easy' ? 'Mudah' : ($difficulty === 'hard' ? 'Sulit' : 'Sedang');

    if ($type === 'mixed') {
        $pg = max(1, min($pgCount, $totalQuestions - 1));
        $essay = $totalQuestions - $pg;
        $prompt = "Buatkan {$totalQuestions} soal Campuran untuk mata pelajaran {$subject} kelas {$class} dengan tingkat kesulitan {$difficultyLabel} tentang topik \"{$topic}\". ";
        $prompt .= "Buat tepat {$pg} soal Pilihan Ganda (dengan 4 opsi jawaban) dan {$essay} soal Essay (tanpa opsi).";
        if (!empty($extraInstructions)) {
            $prompt .= " Catatan tambahan: {$extraInstructions}";
        }
        $prompt .= " Format: JSON array of objects. Untuk Pilihan Ganda: object memiliki key \"question\", \"options\" (array of 4 strings), \"answer\", \"explanation\" (opsional). Untuk Essay: object memiliki key \"question\", \"answer\", \"explanation\" (opsional) tanpa \"options\". Hanya output JSON, tanpa markdown, tanpa teks lain.";
    } else {
        $prompt = "Buatkan {$totalQuestions} soal {$typeLabel} untuk mata pelajaran {$subject} kelas {$class} dengan tingkat kesulitan {$difficultyLabel} tentang topik \"{$topic}\".";
        if (!empty($extraInstructions)) {
            $prompt .= " Catatan tambahan: {$extraInstructions}";
        }
        $prompt .= " Format: JSON array of objects. Setiap object memiliki key: \"question\" (string), " . ($type === 'essay' ? '' : "\"options\" (array of 4 strings), ") . "\"answer\" (string), \"explanation\" (string, optional). Hanya output JSON, tanpa markdown, tanpa teks lain.";
    }

    $questions = callAI($prompt);

    if ($questions && is_array($questions) && $type === 'mixed') {
        $pgQuestions = [];
        $essayQuestions = [];
        foreach ($questions as $q) {
            if (isset($q['options']) && is_array($q['options']) && count($q['options']) > 0) {
                $pgQuestions[] = $q;
            } else {
                $essayQuestions[] = $q;
            }
        }
        if ($questionOrder === 'pg_first') {
            $questions = array_merge($pgQuestions, $essayQuestions);
        } elseif ($questionOrder === 'essay_first') {
            $questions = array_merge($essayQuestions, $pgQuestions);
        } else {
            shuffle($questions);
        }
    }

    if (!$questions || !is_array($questions) || count($questions) === 0) {
        echo json_encode(['error' => 'Failed to generate questions. Please try again.']);
        exit();
    }

    $stmt = $pdo->prepare("UPDATE users SET credit = credit - ? WHERE id = ?");
    $stmt->execute([$cost, $userId]);

    $stmt = $pdo->prepare('INSERT INTO credit_usage (user_id, amount, description) VALUES (?, ?, ?)');
    $stmt->execute([$userId, -$cost, 'Generate soal ' . $totalQuestions . ' soal: ' . $topic]);

    logApiRequest($pdo, $userId);

    echo json_encode([
        'success' => true,
        'questions' => $questions,
        'remaining_credits' => $user['credit'] - $cost
    ]);

} catch (Exception $e) {
    error_log('API generate error: ' . $e->getMessage());
    echo json_encode(['error' => 'Generation failed: ' . $e->getMessage()]);
}

function callAI($prompt) {
    $apiKey = AI_API_KEY;

    $payload = [
        'model' => AI_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => 'Kamu adalah asisten pembuat soal ujian untuk guru Indonesia. Selalu gunakan bahasa Indonesia. Output hanya JSON array tanpa teks lain.'],
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

    $questions = json_decode($content, true);

    if (!$questions || !is_array($questions)) {
        preg_match('/\[.*\]/s', $content, $matches);
        if (!empty($matches[0])) {
            $questions = json_decode($matches[0], true);
        }
    }

    return $questions;
}
