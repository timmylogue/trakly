<?php
class Auth
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS

            session_name(SESSION_NAME);
            session_start();

            // Regenerate session ID periodically
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } else if (time() - $_SESSION['created'] > SESSION_LIFETIME) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    public static function login($user)
    {
        self::startSession();

        // Get user settings
        require_once __DIR__ . '/../models/Settings.php';
        $settingsModel = new Settings();
        $settings = $settingsModel->findByUserId($user['id']);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_theme'] = $settings['theme'] ?? 'light';
        $_SESSION['is_premium'] = $user['is_premium'];
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();

        // Regenerate session ID on login for security
        session_regenerate_id(true);
    }

    public static function logout()
    {
        self::startSession();

        $_SESSION = [];

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
    }

    public static function isLoggedIn()
    {
        self::startSession();

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }

        // Check for session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
            self::logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    public static function requireGuest()
    {
        if (self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit();
        }
    }

    public static function userId()
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function userName()
    {
        self::startSession();
        return $_SESSION['user_name'] ?? null;
    }

    public static function userEmail()
    {
        self::startSession();
        return $_SESSION['user_email'] ?? null;
    }

    public static function userTheme()
    {
        self::startSession();
        return $_SESSION['user_theme'] ?? 'light';
    }

    public static function isPremium()
    {
        self::startSession();
        return isset($_SESSION['is_premium']) && $_SESSION['is_premium'] == 1;
    }

    public static function requirePremium()
    {
        self::requireLogin();

        if (!self::isPremium()) {
            header('Location: ' . BASE_URL . 'premium');
            exit();
        }
    }

    public static function updatePremiumStatus($isPremium)
    {
        self::startSession();
        $_SESSION['is_premium'] = $isPremium;
    }

    public static function updateTheme($theme)
    {
        self::startSession();
        $_SESSION['user_theme'] = $theme;
    }
}
