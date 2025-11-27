<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'trakly');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'Trakly');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'https://trakly.local:8890/');
define('APP_ENV', 'development'); // development, production

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Session Configuration
define('SESSION_LIFETIME', 7200); // 2 hours in seconds
define('SESSION_NAME', 'trakly_session');

// Security
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// Premium Features
define('PREMIUM_MONTHLY_PRICE', 3.99);
define('PREMIUM_ANNUAL_PRICE', 29.00);
define('PREMIUM_LIFETIME_PRICE', 60.00);

// Stripe Configuration (for Premium)
define('STRIPE_SECRET_KEY', ''); // Add your Stripe secret key
define('STRIPE_PUBLIC_KEY', ''); // Add your Stripe public key

// Upload Configuration
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_UPLOAD_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']);

// Timezone
date_default_timezone_set('America/New_York');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
