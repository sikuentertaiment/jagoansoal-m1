<?php

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/env.php';

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'examgenerator'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

define('FIREBASE_API_KEY', env('FIREBASE_API_KEY'));
define('FIREBASE_AUTH_DOMAIN', env('FIREBASE_AUTH_DOMAIN'));
define('FIREBASE_PROJECT_ID', env('FIREBASE_PROJECT_ID'));
define('FIREBASE_STORAGE_BUCKET', env('FIREBASE_STORAGE_BUCKET'));
define('FIREBASE_MESSAGING_SENDER_ID', env('FIREBASE_MESSAGING_SENDER_ID'));
define('FIREBASE_APP_ID', env('FIREBASE_APP_ID'));

define('UPLOAD_DIR_MATERIALS', dirname(__DIR__) . '/public/assets/uploaded_materials');
define('ASSETS_URL_MATERIALS', '/public/assets/uploaded_materials');

define('AI_API_KEY', env('AI_API_KEY', ''));
define('AI_API_URL', env('AI_API_URL', 'https://gen.pollinations.ai/v1/chat/completions'));
define('AI_MODEL', env('AI_MODEL', 'deepseek'));

define('MIDTRANS_SERVER_KEY', env('MIDTRANS_SERVER_KEY', ''));
define('MIDTRANS_CLIENT_KEY', env('MIDTRANS_CLIENT_KEY', ''));
define('MIDTRANS_IS_PRODUCTION', env('MIDTRANS_IS_PRODUCTION', 'false') === 'true');
define('MIDTRANS_SNAP_URL', MIDTRANS_IS_PRODUCTION
    ? 'https://app.midtrans.com/snap/v1/transactions'
    : 'https://app.sandbox.midtrans.com/snap/v1/transactions');

define('CREDITS_PER_PACKAGE', 3);
define('PRICE_PER_PACKAGE', 1000);
define('FREE_CREDITS', 3);

define('RATE_LIMIT_ENABLED', true);
define('RATE_LIMIT_MAX', 10);
define('RATE_LIMIT_WINDOW', 60);

define('ADMIN_EMAILS', env('ADMIN_EMAILS', ''));
define('APP_URL', env('APP_URL', 'http://localhost:8000'));
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID', ''));

function getDbConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            return null;
        }
    }

    return $pdo;
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: #login');
        exit;
    }
}

function ensureRateLimitTable($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rate_limits` (
        `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `user_id` VARCHAR(50) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_rate_user_time` (`user_id`, `created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function checkRateLimit($pdo, $userId) {
    if (!RATE_LIMIT_ENABLED) return true;

    ensureRateLimitTable($pdo);

    $stmt = $pdo->prepare("DELETE FROM rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL ? SECOND)");
    $stmt->execute([RATE_LIMIT_WINDOW * 2]);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rate_limits WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)");
    $stmt->execute([$userId, RATE_LIMIT_WINDOW]);
    $count = (int)$stmt->fetchColumn();

    return $count < RATE_LIMIT_MAX;
}

function logApiRequest($pdo, $userId) {
    $stmt = $pdo->prepare("INSERT INTO rate_limits (user_id) VALUES (?)");
    $stmt->execute([$userId]);
}
