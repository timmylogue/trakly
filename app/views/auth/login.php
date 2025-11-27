<?php
$pageTitle = 'Login';
ob_start();
?>

<div class="auth-card">
    <h1>💰 Trakly</h1>
    <h2>Login to Your Account</h2>

    <form method="POST" action="<?php echo BASE_URL; ?>login">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <p class="auth-footer">
        Don't have an account? <a href="<?php echo BASE_URL; ?>register">Sign up</a>
    </p>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/auth.php';
?>