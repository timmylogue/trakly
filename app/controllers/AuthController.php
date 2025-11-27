<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController
{
    private $userModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->validator = new Validator();
    }

    public function showRegister()
    {
        Auth::requireGuest();
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register()
    {
        Auth::requireGuest();

        $data = [
            'name' => Helper::sanitize($_POST['name'] ?? ''),
            'email' => Helper::sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirm' => 'required|match:password'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::setOldInput($data);
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('register');
        }

        if ($this->userModel->emailExists($data['email'])) {
            Helper::setOldInput($data);
            Helper::flashMessage('error', 'Email already exists');
            Helper::redirect('register');
        }

        if ($this->userModel->create($data['name'], $data['email'], $data['password'])) {
            Helper::flashMessage('success', 'Account created successfully! Please login.');
            Helper::redirect('login');
        } else {
            Helper::flashMessage('error', 'Registration failed. Please try again.');
            Helper::redirect('register');
        }
    }

    public function showLogin()
    {
        Auth::requireGuest();
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        Auth::requireGuest();

        $email = Helper::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->verifyPassword($email, $password);

        if ($user) {
            Auth::login($user);
            Helper::redirect('dashboard');
        } else {
            Helper::flashMessage('error', 'Invalid email or password');
            Helper::redirect('login');
        }
    }

    public function logout()
    {
        Auth::logout();
        Helper::flashMessage('success', 'You have been logged out');
        Helper::redirect('login');
    }
}
