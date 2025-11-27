<?php
$pageTitle = 'Register';
ob_start();
?>

<div class="auth-card">
    <h1>💰 Trakly</h1>
    <h2>Create Your Account</h2>

    <form method="POST" action="<?php echo BASE_URL; ?>register">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo Helper::old('name'); ?>" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo Helper::old('email'); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <small>Minimum 8 characters</small>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="<?php echo BASE_URL; ?>login">Login</a>
    </p>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/auth.php';
?>