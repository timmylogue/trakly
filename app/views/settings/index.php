<?php
$pageTitle = 'Settings';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Settings</h1>
    </div>

    <div class="settings-grid">
        <!-- Profile Settings -->
        <div class="card section">
            <h2>Profile Information</h2>
            <form action="<?php echo BASE_URL; ?>settings/profile" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['user']['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>

        <!-- Password Settings -->
        <div class="card section">
            <h2>Change Password</h2>
            <form action="<?php echo BASE_URL; ?>settings/password" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <small>Must be at least 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Appearance Settings -->
        <div class="card section">
            <h2>Appearance</h2>
            <div class="form-group">
                <label for="theme-toggle">Theme</label>
                <div class="theme-toggle-wrapper">
                    <button type="button" id="theme-toggle" class="btn btn-secondary">
                        <span class="theme-icon">🌙</span>
                        <span class="theme-text">Toggle Dark Mode</span>
                    </button>
                </div>
                <small>Switch between light and dark themes</small>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card section">
            <h2>Account Information</h2>
            <div class="account-info">
                <div class="info-row">
                    <span class="info-label">Account Status:</span>
                    <span class="info-value">
                        <?php if (Auth::isPremium()): ?>
                            <span class="badge-premium">Premium</span>
                        <?php else: ?>
                            <span class="badge badge-primary">Free</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value"><?php echo Helper::formatDate($data['user']['created_at']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.querySelector('.theme-icon');
        const themeText = document.querySelector('.theme-text');
        const html = document.documentElement;

        // Get current theme from user's settings
        const currentTheme = '<?php echo $data['settings']['theme'] ?? 'light'; ?>';
        html.setAttribute('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme); // Also save to localStorage for faster loading
        updateThemeButton(currentTheme);
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);

            // Save to database
            fetch('<?php echo BASE_URL; ?>settings/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'theme=' + newTheme
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        console.log('Theme saved successfully to database');
                    } else {
                        console.error('Failed to save theme preference:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving theme:', error);
                });
        });

        function updateThemeButton(theme) {
            if (theme === 'dark') {
                themeIcon.textContent = '☀️';
                themeText.textContent = 'Toggle Light Mode';
            } else {
                themeIcon.textContent = '🌙';
                themeText.textContent = 'Toggle Dark Mode';
            }
        }
    });
</script><?php
            $content = ob_get_clean();
            require_once __DIR__ . '/../layouts/app.php';
            ?>