<?php
require_once __DIR__ . '/Router.php';

$router = new Router();

// Home/Landing
$router->get('/', 'AuthController', 'showLogin');

// Authentication Routes
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard Routes
$router->get('/dashboard', 'DashboardController', 'index');

// Transaction Routes
$router->get('/transactions', 'TransactionController', 'index');
$router->get('/transactions/create', 'TransactionController', 'create');
$router->post('/transactions/create', 'TransactionController', 'store');
$router->get('/transactions/edit/:id', 'TransactionController', 'edit');
$router->post('/transactions/update/:id', 'TransactionController', 'update');
$router->get('/transactions/delete/:id', 'TransactionController', 'delete');

// Category Routes
$router->get('/categories', 'CategoryController', 'index');
$router->get('/categories/create', 'CategoryController', 'create');
$router->post('/categories/create', 'CategoryController', 'store');
$router->get('/categories/edit/:id', 'CategoryController', 'edit');
$router->post('/categories/update/:id', 'CategoryController', 'update');
$router->get('/categories/delete/:id', 'CategoryController', 'delete');

// Budget Routes
$router->get('/budgets', 'BudgetController', 'index');
$router->get('/budgets/edit/:id', 'BudgetController', 'edit');
$router->post('/budgets/update/:id', 'BudgetController', 'update');
$router->get('/budgets/activate/:id', 'BudgetController', 'setActive');

// Settings Routes
$router->get('/settings', 'SettingsController', 'index');
$router->post('/settings/profile', 'SettingsController', 'updateProfile');
$router->post('/settings/password', 'SettingsController', 'updatePassword');
$router->post('/settings/theme', 'SettingsController', 'updateTheme');

// Test Data Routes (Development)
$router->get('/testdata', 'TestDataController', 'index');
$router->post('/testdata/seed', 'TestDataController', 'seed');
$router->post('/testdata/clean', 'TestDataController', 'clean');

return $router;
