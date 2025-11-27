<?php
class Helper
{
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        if ($data === null) {
            return '';
        }
        return htmlspecialchars(strip_tags(trim((string)$data)), ENT_QUOTES, 'UTF-8');
    }

    public static function redirect($path)
    {
        header('Location: ' . BASE_URL . $path);
        exit();
    }

    public static function formatCurrency($amount, $symbol = '$')
    {
        return $symbol . number_format($amount, 2);
    }

    public static function formatDate($date, $format = 'M d, Y')
    {
        if (empty($date)) {
            return '';
        }
        return date($format, strtotime($date));
    }

    public static function flashMessage($key, $message = null)
    {
        Auth::startSession();

        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
    }

    public static function old($field, $default = '')
    {
        Auth::startSession();

        if (isset($_SESSION['old'][$field])) {
            $value = $_SESSION['old'][$field];
            unset($_SESSION['old'][$field]);
            return $value;
        }

        return $default;
    }

    public static function setOldInput($data)
    {
        Auth::startSession();
        $_SESSION['old'] = $data;
    }

    public static function uploadFile($file, $allowedTypes = null, $maxSize = null)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload failed'];
        }

        $allowedTypes = $allowedTypes ?? ALLOWED_UPLOAD_TYPES;
        $maxSize = $maxSize ?? MAX_UPLOAD_SIZE;

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size exceeds limit'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $destination = UPLOAD_PATH . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $filename, 'path' => 'uploads/' . $filename];
        }

        return ['success' => false, 'message' => 'Failed to save file'];
    }

    public static function deleteFile($path)
    {
        $fullPath = PUBLIC_PATH . '/' . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public static function getCurrentMonth()
    {
        return date('Y-m');
    }

    public static function getMonthStart($date = null)
    {
        $date = $date ?? date('Y-m-d');
        return date('Y-m-01', strtotime($date));
    }

    public static function getMonthEnd($date = null)
    {
        $date = $date ?? date('Y-m-d');
        return date('Y-m-t', strtotime($date));
    }

    public static function csrfToken()
    {
        Auth::startSession();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken($token)
    {
        Auth::startSession();

        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }

        return true;
    }
}
