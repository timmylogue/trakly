<?php
// Simple test file to verify PHP and paths are working
echo "<h1>MAMP Configuration Test</h1>";

echo "<h2>Environment Information</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</li>";
echo "<li><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</li>";
echo "<li><strong>HTTP Host:</strong> " . $_SERVER['HTTP_HOST'] . "</li>";
echo "<li><strong>Server Port:</strong> " . $_SERVER['SERVER_PORT'] . "</li>";
echo "</ul>";

echo "<h2>Path Tests</h2>";
echo "<ul>";
echo "<li><strong>Config file exists:</strong> " . (file_exists(__DIR__ . '/../config/config.php') ? 'YES ✓' : 'NO ✗') . "</li>";
echo "<li><strong>Routes file exists:</strong> " . (file_exists(__DIR__ . '/../routes/web.php') ? 'YES ✓' : 'NO ✗') . "</li>";
echo "<li><strong>Database file exists:</strong> " . (file_exists(__DIR__ . '/../config/database.php') ? 'YES ✓' : 'NO ✗') . "</li>";
echo "</ul>";

echo "<h2>Try Loading Config</h2>";
try {
    require_once __DIR__ . '/../config/config.php';
    echo "<p style='color: green;'>✓ Config loaded successfully!</p>";
    echo "<ul>";
    echo "<li><strong>BASE_URL:</strong> " . BASE_URL . "</li>";
    echo "<li><strong>APP_ENV:</strong> " . APP_ENV . "</li>";
    echo "<li><strong>DB_NAME:</strong> " . DB_NAME . "</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error loading config: " . $e->getMessage() . "</p>";
}

echo "<h2>Database Connection Test</h2>";
try {
    require_once __DIR__ . '/../config/database.php';
    $db = Database::getInstance()->getConnection();
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p><small>Make sure MySQL is running in MAMP and the database 'trakly' exists</small></p>";
}

echo "<h2>Next Steps</h2>";
echo "<p>If all tests pass, delete this file and visit <a href='/login'>https://trakly:8890/login</a></p>";
