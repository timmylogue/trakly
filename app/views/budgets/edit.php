<?php
$pageTitle = 'Edit Budget';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Edit Budget</h1>
        <a href="<?php echo BASE_URL; ?>budgets" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="<?php echo BASE_URL; ?>budgets/update/<?php echo $data['budget']['id']; ?>">
            <div class="form-group">
                <label for="name">Budget Name</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($data['budget']['name']); ?>" required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="total_amount">Total Budget Amount *</label>
                    <input type="number" step="0.01" id="total_amount" name="total_amount"
                        value="<?php echo $data['budget']['total_amount']; ?>" required>
                    <small>This is your total budget for the period</small>
                </div>

                <div class="form-group">
                    <label for="period">Period</label>
                    <select id="period" name="period" required>
                        <option value="monthly" <?php echo $data['budget']['period'] === 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                        <option value="weekly" <?php echo $data['budget']['period'] === 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                        <option value="annual" <?php echo $data['budget']['period'] === 'annual' ? 'selected' : ''; ?>>Annual</option>
                        <option value="sinking_fund" <?php echo $data['budget']['period'] === 'sinking_fund' ? 'selected' : ''; ?>>Sinking Fund</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Budget</button>
                <a href="<?php echo BASE_URL; ?>budgets" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="card">
        <h3>💡 Tips</h3>
        <ul>
            <li><strong>Monthly:</strong> Best for regular monthly budgeting</li>
            <li><strong>Weekly:</strong> For tight weekly expense tracking</li>
            <li><strong>Annual:</strong> For yearly planning</li>
            <li><strong>Sinking Fund:</strong> For saving toward specific goals (vacation, gifts, etc.)</li>
        </ul>
        <p><em>After setting your total budget, go to Categories to set limits for each spending category.</em></p>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>