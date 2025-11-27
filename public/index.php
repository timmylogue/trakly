<?php
// Error handling for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../routes/web.php';

    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    if (APP_ENV === 'development') {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>500 Internal Server Error</h1>';
    }
}
