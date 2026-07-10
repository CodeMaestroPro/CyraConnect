<?php

/**
 * Normalize Apache server variables for Laravel when served from a subdirectory.
 *
 * On Windows/XAMPP, mod_rewrite can set SCRIPT_NAME with different path casing
 * than REQUEST_URI (e.g. /Cyra-Connect/public vs /cyra-connect/public), which
 * makes Laravel resolve routes like "cyra-connect/public/login" instead of "login".
 */
$normalizePath = static function (?string $path): ?string {
    if ($path === null || $path === '') {
        return $path;
    }

    $path = str_replace('\\', '/', $path);

    return strtolower(preg_replace('#/+#', '/', $path) ?? $path);
};

foreach (['SCRIPT_NAME', 'PHP_SELF', 'REDIRECT_URL'] as $key) {
    if (isset($_SERVER[$key])) {
        $_SERVER[$key] = $normalizePath($_SERVER[$key]);
    }
}

$requestPath = $normalizePath(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$scriptDir = $normalizePath(dirname($_SERVER['SCRIPT_NAME'] ?? ''));

if ($requestPath && $scriptDir && $scriptDir !== '/' && str_starts_with($requestPath, $scriptDir)) {
    $_SERVER['SCRIPT_NAME'] = rtrim($scriptDir, '/').'/index.php';
}
