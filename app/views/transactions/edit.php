<?php
$pageTitle = 'Edit Transaction';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Edit Transaction</h1>
        <a href="<?php echo BASE_URL; ?>transactions" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="<?php echo BASE_URL; ?>transactions/update/<?php echo $data['transaction']['id']; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="amount">Amount *</label>
                    <input type="number" step="0.01" id="amount" name="amount"
                        value="<?php echo $data['transaction']['amount']; ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" id="date" name="date"
                        value="<?php echo $data['transaction']['date']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type *</label>
                    <select id="type" name="type" required>
                        <option value="expense" <?php echo $data['transaction']['type'] === 'expense' ? 'selected' : ''; ?>>Expense</option>
                        <option value="income" <?php echo $data['transaction']['type'] === 'income' ? 'selected' : ''; ?>>Income</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">Select Category</option>
                        <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php echo $data['transaction']['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" name="note" rows="3"><?php echo htmlspecialchars($data['transaction']['note']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($data['transaction']['tags']); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Transaction</button>
                <a href="<?php echo BASE_URL; ?>transactions" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>