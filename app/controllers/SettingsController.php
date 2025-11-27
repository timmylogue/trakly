<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Settings.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../helpers/Validator.php';

class SettingsController
{
    private $userModel;
    private $settingsModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->settingsModel = new Settings();
        $this->validator = new Validator();
    }

    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $user = $this->userModel->findById($userId);
        $settings = $this->settingsModel->findByUserId($userId);

        $data = [
            'user' => $user,
            'settings' => $settings
        ];

        require_once __DIR__ . '/../views/settings/index.php';
    }

    public function updateProfile()
    {
        Auth::requireLogin();

        $userId = Auth::userId();

        $data = [
            'name' => Helper::sanitize($_POST['name'] ?? ''),
            'email' => Helper::sanitize($_POST['email'] ?? '')
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('settings');
        }

        // Check if email is already taken by another user
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $userId) {
            Helper::flashMessage('error', 'Email is already in use by another account');
            Helper::redirect('settings');
        }

        if ($this->userModel->updateProfile($userId, $data['name'], $data['email'])) {
            Helper::flashMessage('success', 'Profile updated successfully');
        } else {
            Helper::flashMessage('error', 'Failed to update profile');
        }

        Helper::redirect('settings');
    }

    public function updatePassword()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $user = $this->userModel->findById($userId);

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Verify current password
        if (!password_verify($currentPassword, $user['password_hash'])) {
            Helper::flashMessage('error', 'Current password is incorrect');
            Helper::redirect('settings');
        }

        // Validate new password
        if (strlen($newPassword) < 6) {
            Helper::flashMessage('error', 'New password must be at least 6 characters');
            Helper::redirect('settings');
        }

        if ($newPassword !== $confirmPassword) {
            Helper::flashMessage('error', 'New passwords do not match');
            Helper::redirect('settings');
        }

        if ($this->userModel->updatePassword($userId, $newPassword)) {
            Helper::flashMessage('success', 'Password updated successfully');
        } else {
            Helper::flashMessage('error', 'Failed to update password');
        }

        Helper::redirect('settings');
    }

    public function updateTheme()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $theme = $_POST['theme'] ?? 'light';

        // Validate theme value
        if (!in_array($theme, ['light', 'dark'])) {
            Helper::json(['success' => false, 'message' => 'Invalid theme'], 400);
            return;
        }

        try {
            $result = $this->settingsModel->updateTheme($userId, $theme);
            if ($result) {
                Auth::updateTheme($theme); // Update session
                Helper::json(['success' => true, 'theme' => $theme]);
            } else {
                Helper::json(['success' => false, 'message' => 'Failed to update theme in database'], 500);
            }
        } catch (Exception $e) {
            Helper::json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
