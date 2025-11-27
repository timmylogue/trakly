<?php
$pageTitle = 'Test Data Management';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>🧪 Test Data Management</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Current Status</h2>

        <?php if ($data['hasTestData']): ?>
            <div class="alert alert-info">
                ✅ Test data is currently loaded
            </div>

            <div class="account-info">
                <div class="info-row">
                    <span class="info-label">Test Users</span>
                    <span class="info-value"><?php echo $data['testDataInfo']['users']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Test Transactions</span>
                    <span class="info-value"><?php echo $data['testDataInfo']['transactions']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Test Categories</span>
                    <span class="info-value"><?php echo $data['testDataInfo']['categories']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Test Budgets</span>
                    <span class="info-value"><?php echo $data['testDataInfo']['budgets']; ?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                ℹ️ No test data currently loaded
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Actions</h2>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php if (!$data['hasTestData']): ?>
                <div>
                    <h3 style="margin-bottom: 0.5rem;">Seed Test Data</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">
                        Creates 3 test users with budgets, categories, and transactions.
                        Perfect for testing and development.
                    </p>

                    <div style="background: var(--gray-50); padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                        <strong>Test User Credentials:</strong>
                        <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                            <li>📧 john@example.com | 🔑 password123</li>
                            <li>📧 jane@example.com | 🔑 password123 <span class="badge-premium">Premium</span></li>
                            <li>📧 bob@example.com | 🔑 password123</li>
                        </ul>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>testdata/seed"
                        onsubmit="return confirm('This will create test users and sample data. Continue?');">
                        <button type="submit" class="btn btn-primary">
                            🌱 Seed Test Data
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div>
                    <h3 style="margin-bottom: 0.5rem;">Clean Test Data</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">
                        Removes all test users and their associated data (budgets, categories, transactions, etc.)
                    </p>

                    <form method="POST" action="<?php echo BASE_URL; ?>testdata/clean"
                        onsubmit="return confirm('⚠️ This will permanently delete all test data. Are you sure?');">
                        <button type="submit" class="btn btn-danger">
                            🗑️ Clean Test Data
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h2>ℹ️ About Test Data</h2>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <strong>What gets created:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    <li>3 test users (John, Jane as Premium, Bob)</li>
                    <li>Monthly budgets with realistic amounts</li>
                    <li>Multiple categories (Groceries, Transportation, Entertainment, etc.)</li>
                    <li>Sample transactions for current and previous month</li>
                    <li>Transaction templates</li>
                    <li>Auto-categorization rules (for premium user)</li>
                    <li>Different themes and currency settings</li>
                </ul>
            </div>

            <div>
                <strong>Safety:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    <li>Test users are identified by @example.com email addresses</li>
                    <li>Cleaning only removes test data, not your real data</li>
                    <li>All operations are confirmed before execution</li>
                </ul>
            </div>

            <div style="background: var(--gray-50); padding: 1rem; border-radius: 0.375rem;">
                <strong>💡 Tip:</strong> You can seed and clean test data multiple times.
                This is useful for testing different scenarios or resetting to a known state.
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-secondary">
            ← Back to Dashboard
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>