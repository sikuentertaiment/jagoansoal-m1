<?php

$envLoaded = false;

function loadEnv($path = null) {
    global $envLoaded;
    if ($envLoaded) return;

    $path = $path ?? __DIR__ . '/.env';
    if (!file_exists($path)) {
        error_log('[Env] .env file not found at: ' . $path);
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;

        if (strpos($line, '=') === false) continue;

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        $len = strlen($value);
        if ($len >= 2) {
            $first = $value[0];
            $last = $value[$len - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    $envLoaded = true;
}

function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false || $value === null) {
        return $default;
    }
    return $value;
}

loadEnv();
