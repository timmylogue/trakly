<?php
$pageTitle = 'Dashboard';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <div class="header-actions">
            <div class="month-navigation">
                <a href="<?php echo BASE_URL; ?>dashboard?month=<?php echo $data['prevMonthNav']; ?>" class="btn btn-secondary">&larr;</a>
                <span class="current-period">📅 <?php echo $data['currentMonth']; ?></span>
                <?php if ($data['canGoNext']): ?>
                    <a href="<?php echo BASE_URL; ?>dashboard?month=<?php echo $data['nextMonthNav']; ?>" class="btn btn-secondary">&rarr;</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>&rarr;</button>
                <?php endif; ?>
            </div>
            <a href="<?php echo BASE_URL; ?>transactions/create" class="btn btn-primary">+ Add Transaction</a>
        </div>
    </div>

    <?php if ($data['isCurrentMonth']): ?>
        <div class="alert alert-info">
            💡 Track your spending against your <strong>income of <?php echo Helper::formatCurrency($data['monthlyIncome']); ?></strong> for <?php echo $data['currentMonth']; ?>.
            Categories help you plan how to allocate your income!
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            📊 Viewing historical data for <strong><?php echo $data['currentMonth']; ?></strong>.
            <a href="<?php echo BASE_URL; ?>dashboard" style="color: inherit; font-weight: bold; text-decoration: underline;">Return to current month</a>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <h3>💰 Monthly Income</h3>
            <p class="amount text-success"><?php echo Helper::formatCurrency($data['monthlyIncome']); ?></p>
            <?php
            $incomeDiff = $data['monthlyIncome'] - $data['prevMonthIncome'];
            $incomeDiffPercent = $data['prevMonthIncome'] > 0 ? (($incomeDiff / $data['prevMonthIncome']) * 100) : 0;
            ?>
            <p class="comparison <?php echo $incomeDiff > 0 ? 'text-success' : 'text-danger'; ?>">
                <?php echo $incomeDiff > 0 ? '↑' : '↓'; ?> <?php echo Helper::formatCurrency(abs($incomeDiff)); ?>
                <?php if ($data['prevMonthIncome'] > 0): ?>
                    (<?php echo number_format(abs($incomeDiffPercent), 1); ?>%)
                <?php endif; ?>
                vs <?php echo $data['prevMonth']; ?>
            </p>
        </div>

        <div class="card">
            <h3>💸 Total Expenses</h3>
            <p class="amount text-danger"><?php echo Helper::formatCurrency($data['monthlyExpenses']); ?></p>
            <?php
            $spentDiff = $data['monthlyExpenses'] - $data['prevMonthExpenses'];
            $spentDiffPercent = $data['prevMonthExpenses'] > 0 ? (($spentDiff / $data['prevMonthExpenses']) * 100) : 0;
            $spentPercentOfIncome = $data['monthlyIncome'] > 0 ? ($data['monthlyExpenses'] / $data['monthlyIncome']) * 100 : 0;
            ?>
            <p class="comparison <?php echo $spentDiff > 0 ? 'text-danger' : 'text-success'; ?>">
                <?php echo number_format($spentPercentOfIncome, 1); ?>% of income
            </p>
        </div>

        <div class="card">
            <h3>💵 Savings</h3>
            <?php
            $savings = $data['monthlyIncome'] - $data['monthlyExpenses'];
            $savingsRate = $data['monthlyIncome'] > 0 ? ($savings / $data['monthlyIncome']) * 100 : 0;
            ?>
            <p class="amount <?php echo $savings >= 0 ? 'text-success' : 'text-danger'; ?>">
                <?php echo Helper::formatCurrency($savings); ?>
            </p>
            <p class="comparison <?php echo $savings >= 0 ? 'text-success' : 'text-danger'; ?>">
                <?php echo number_format($savingsRate, 1); ?>% savings rate
            </p>
        </div>
    </div>

    <!-- Category Progress -->
    <div class="dashboard-grid">
        <?php if (!empty($data['categories'])): ?>
            <div class="card section">
                <h2>Category Budgets</h2>

                <div class="category-list">
                    <?php foreach ($data['categories'] as $category): ?>
                        <?php
                        $spent = $category['spent'] ?? 0;
                        $limit = $category['limit_amount'] ?? 0;
                        $percentage = $limit > 0 ? ($spent / $limit) * 100 : 0;
                        $remaining = $limit - $spent;
                        ?>
                        <div class="category-item">
                            <div class="category-header">
                                <span class="category-name">
                                    <span class="category-color" style="background-color: <?php echo $category['color']; ?>"></span>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </span>
                                <span class="category-amounts">
                                    <?php echo Helper::formatCurrency($spent); ?> / <?php echo Helper::formatCurrency($limit); ?>
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill <?php echo $percentage >= 100 ? 'over-budget' : ''; ?>"
                                    style="width: <?php echo min($percentage, 100); ?>%; background-color: <?php echo $category['color']; ?>"></div>
                            </div>
                            <div class="category-remaining">
                                <?php if ($remaining >= 0): ?>
                                    Remaining: <?php echo Helper::formatCurrency($remaining); ?>
                                <?php else: ?>
                                    <span class="text-danger">Over budget by <?php echo Helper::formatCurrency(abs($remaining)); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Spending Chart Sidebar -->
        <?php if (!empty($data['categoryBreakdown'])): ?>
            <div class="card section chart-sidebar">
                <h2>Spending Breakdown</h2>
                <div class="chart-container">
                    <canvas id="spendingChart"></canvas>
                </div>
                <div class="chart-legend">
                    <?php foreach ($data['categoryBreakdown'] as $cat): ?>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: <?php echo $cat['color']; ?>"></span>
                            <span class="legend-label"><?php echo htmlspecialchars($cat['name']); ?></span>
                            <span class="legend-value"><?php echo Helper::formatCurrency($cat['total']); ?></span>
                            <span class="legend-percent">(<?php echo number_format(($cat['total'] / $data['monthlyExpenses']) * 100, 0); ?>%)</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recent Transactions Sidebar -->
        <?php if (!empty($data['recentTransactions'])): ?>
            <div class="card section recent-transactions-sidebar">
                <h2>Recent Transactions</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Note</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['recentTransactions'] as $transaction): ?>
                            <tr>
                                <td><?php echo Helper::formatDate($transaction['date']); ?></td>
                                <td>
                                    <?php if ($transaction['category_name']): ?>
                                        <span class="category-badge" style="background-color: <?php echo $transaction['category_color']; ?>">
                                            <?php echo htmlspecialchars($transaction['category_name']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Uncategorized</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($transaction['note']); ?></td>
                                <td class="<?php echo $transaction['type'] === 'expense' ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo $transaction['type'] === 'expense' ? '-' : '+'; ?>
                                    <?php echo Helper::formatCurrency($transaction['amount']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($data['categoryBreakdown'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('spendingChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_column($data['categoryBreakdown'], 'name')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($data['categoryBreakdown'], 'total')); ?>,
                        backgroundColor: <?php echo json_encode(array_column($data['categoryBreakdown'], 'color')); ?>,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.parsed.toFixed(2);
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>