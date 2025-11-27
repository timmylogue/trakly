<?php
$pageTitle = 'Transactions';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Transactions</h1>
        <a href="<?php echo BASE_URL; ?>transactions/create" class="btn btn-primary">+ Add Transaction</a>
    </div>

    <!-- Filters -->
    <div class="card">
        <form method="GET" action="<?php echo BASE_URL; ?>transactions" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php echo ($data['filters']['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="">All Types</option>
                        <option value="expense" <?php echo ($data['filters']['type'] ?? '') == 'expense' ? 'selected' : ''; ?>>Expense</option>
                        <option value="income" <?php echo ($data['filters']['type'] ?? '') == 'income' ? 'selected' : ''; ?>>Income</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo $data['filters']['start_date'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo $data['filters']['end_date'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search notes..." value="<?php echo $data['filters']['search'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <?php if (!empty($data['transactions'])): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['transactions'] as $transaction): ?>
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
                            <td>
                                <span class="badge <?php echo $transaction['type'] === 'expense' ? 'badge-danger' : 'badge-success'; ?>">
                                    <?php echo ucfirst($transaction['type']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($transaction['note']); ?></td>
                            <td class="<?php echo $transaction['type'] === 'expense' ? 'text-danger' : 'text-success'; ?>">
                                <?php echo $transaction['type'] === 'expense' ? '-' : '+'; ?>
                                <?php echo Helper::formatCurrency($transaction['amount']); ?>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>transactions/edit/<?php echo $transaction['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="<?php echo BASE_URL; ?>transactions/delete/<?php echo $transaction['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>