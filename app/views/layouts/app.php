<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Trakly'; ?> - Trakly</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Apply saved theme before page renders to prevent flash
        (function() {
            <?php if (Auth::isLoggedIn()): ?>
                const userTheme = '<?php echo Auth::userTheme(); ?>';
                document.documentElement.setAttribute('data-theme', userTheme);
                localStorage.setItem('theme', userTheme);
            <?php else: ?>
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
            <?php endif; ?>
        })();
    </script>
</head>

<body class="<?php echo $bodyClass ?? ''; ?>">
    <?php if (Auth::isLoggedIn()): ?>
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a href="<?php echo BASE_URL; ?>dashboard">Trakly</a>
                </div>
                <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="navbar-menu" id="navbarMenu">
                    <li><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>transactions">Transactions</a></li>
                    <li><a href="<?php echo BASE_URL; ?>categories">Categories</a></li>
                    <li><a href="<?php echo BASE_URL; ?>budgets">Budget</a></li>
                    <li><a href="<?php echo BASE_URL; ?>testdata">🧪 Test Data</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle"><?php echo Auth::userName(); ?></a>
                        <ul class="dropdown-menu">
                            <?php if (Auth::isPremium()): ?>
                                <li><span class="badge-premium">Premium</span></li>
                            <?php else: ?>
                                <li><a href="<?php echo BASE_URL; ?>premium">Upgrade to Premium</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo BASE_URL; ?>settings">Settings</a></li>
                            <li><a href="<?php echo BASE_URL; ?>logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    <?php endif; ?>

    <main class="main-content">
        <?php
        $successMsg = Helper::flashMessage('success');
        $errorMsg = Helper::flashMessage('error');
        ?>

        <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <?php echo $successMsg; ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error">
                <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>

        <?php echo $content ?? ''; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Trakly. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>js/app.js"></script>
</body>

</html>