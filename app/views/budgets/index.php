<?php
$pageTitle = 'Budgets';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>My Budgets</h1>
    </div>

    <div class="card">
        <div class="alert alert-info">
            ℹ️ <strong>Note:</strong> The "Budget Amount" below is no longer used for calculations.
            Your income from transactions is now your total budget.
            Categories help you allocate your income across different spending areas.
        </div>

        <?php if (!empty($data['budgets'])): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['budgets'] as $budget): ?>
                        <tr class="<?php echo $budget['is_active'] ? 'active-budget' : ''; ?>">
                            <td>
                                <strong><?php echo htmlspecialchars($budget['name']); ?></strong>
                                <?php if ($budget['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo ucfirst($budget['period']); ?></td>
                            <td>
                                <?php if ($budget['is_active']): ?>
                                    <span class="text-success">✓ In Use</span>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>budgets/activate/<?php echo $budget['id']; ?>" class="btn btn-sm">Activate</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted">Budget settings removed</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No budgets found.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>💡 How Your Budget Works Now</h3>
        <ul>
            <li><strong>Your Income = Your Budget:</strong> Add income transactions to set your monthly budget</li>
            <li><strong>Categories:</strong> Set spending limits for each category (Groceries, Rent, etc.)</li>
            <li><strong>Track Savings:</strong> Income minus Expenses = Your Savings</li>
            <li><strong>Simple & Clear:</strong> No confusion between budget amount and income!</li>
        </ul>

        <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: 0.375rem;">
            <strong>Example:</strong> If you earn $5,000 in income transactions, that's your budget.
            Set category limits like $500 for groceries, $1,500 for rent, etc. to allocate your income.
        </div>
    </div>
</div>

<style>
    .active-budget {
        background-color: #f0fdf4;
    }

    .help-text {
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>