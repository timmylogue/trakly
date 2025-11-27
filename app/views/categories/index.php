<?php
$pageTitle = 'Categories';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Categories</h1>
        <div class="header-actions">
            <a href="<?php echo BASE_URL; ?>categories/create" class="btn btn-primary">+ Add Category</a>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>Current Period:</strong> <?php echo htmlspecialchars($data['currentMonth']); ?>
        <br>
        <small>Showing spending from <?php echo date('M j', strtotime($data['monthStart'])); ?> to <?php echo date('M j, Y', strtotime($data['monthEnd'])); ?></small>
    </div>

    <div class="card">
        <?php if (!empty($data['categories'])): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Budget Limit</th>
                        <th>Spent</th>
                        <th>Remaining</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['categories'] as $category): ?>
                        <?php
                        $spent = $category['spent'] ?? 0;
                        $limit = $category['limit_amount'] ?? 0;
                        $remaining = $category['remaining'] ?? 0;
                        $percentage = $limit > 0 ? ($spent / $limit) * 100 : 0;
                        ?>
                        <tr>
                            <td>
                                <span class="category-color" style="background-color: <?php echo $category['color']; ?>"></span>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </td>
                            <td><?php echo Helper::formatCurrency($limit); ?></td>
                            <td class="text-danger"><?php echo Helper::formatCurrency($spent); ?></td>
                            <td class="<?php echo $remaining < 0 ? 'text-danger' : 'text-success'; ?>">
                                <?php echo Helper::formatCurrency($remaining); ?>
                            </td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill <?php echo $percentage >= 100 ? 'over-budget' : ''; ?>"
                                        style="width: <?php echo min($percentage, 100); ?>%; background-color: <?php echo $category['color']; ?>"></div>
                                </div>
                                <small><?php echo number_format($percentage, 1); ?>%</small>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>categories/edit/<?php echo $category['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="<?php echo BASE_URL; ?>categories/delete/<?php echo $category['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure? This will affect all related transactions.')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No categories found. Create your first category to start organizing your budget.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>