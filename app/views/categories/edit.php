<?php
$pageTitle = 'Edit Category';
ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Edit Category</h1>
        <a href="<?php echo BASE_URL; ?>categories" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="<?php echo BASE_URL; ?>categories/update/<?php echo $data['category']['id']; ?>">
            <div class="form-group">
                <label for="name">Category Name *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['category']['name']); ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="limit_amount">Monthly Budget Limit *</label>
                <input type="number" step="0.01" id="limit_amount" name="limit_amount"
                    value="<?php echo $data['category']['limit_amount']; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="color">Color</label>
                    <div class="color-picker-wrapper">
                        <input type="hidden" id="color" name="color" value="<?php echo $data['category']['color']; ?>">
                        <div class="color-palette" id="colorPalette">
                            <button type="button" class="color-option" data-color="#3b82f6" style="background-color: #3b82f6;" title="Blue"></button>
                            <button type="button" class="color-option" data-color="#10b981" style="background-color: #10b981;" title="Green"></button>
                            <button type="button" class="color-option" data-color="#ef4444" style="background-color: #ef4444;" title="Red"></button>
                            <button type="button" class="color-option" data-color="#f59e0b" style="background-color: #f59e0b;" title="Orange"></button>
                            <button type="button" class="color-option" data-color="#8b5cf6" style="background-color: #8b5cf6;" title="Purple"></button>
                            <button type="button" class="color-option" data-color="#ec4899" style="background-color: #ec4899;" title="Pink"></button>
                            <button type="button" class="color-option" data-color="#06b6d4" style="background-color: #06b6d4;" title="Cyan"></button>
                            <button type="button" class="color-option" data-color="#84cc16" style="background-color: #84cc16;" title="Lime"></button>
                            <button type="button" class="color-option" data-color="#f97316" style="background-color: #f97316;" title="Deep Orange"></button>
                            <button type="button" class="color-option" data-color="#14b8a6" style="background-color: #14b8a6;" title="Teal"></button>
                            <button type="button" class="color-option" data-color="#6366f1" style="background-color: #6366f1;" title="Indigo"></button>
                            <button type="button" class="color-option" data-color="#eab308" style="background-color: #eab308;" title="Yellow"></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="icon">Icon</label>
                    <select id="icon" name="icon">
                        <option value="tag" <?php echo $data['category']['icon'] === 'tag' ? 'selected' : ''; ?>>Tag</option>
                        <option value="shopping-cart" <?php echo $data['category']['icon'] === 'shopping-cart' ? 'selected' : ''; ?>>Shopping Cart</option>
                        <option value="home" <?php echo $data['category']['icon'] === 'home' ? 'selected' : ''; ?>>Home</option>
                        <option value="car" <?php echo $data['category']['icon'] === 'car' ? 'selected' : ''; ?>>Car</option>
                        <option value="food" <?php echo $data['category']['icon'] === 'food' ? 'selected' : ''; ?>>Food</option>
                        <option value="entertainment" <?php echo $data['category']['icon'] === 'entertainment' ? 'selected' : ''; ?>>Entertainment</option>
                        <option value="health" <?php echo $data['category']['icon'] === 'health' ? 'selected' : ''; ?>>Health</option>
                        <option value="education" <?php echo $data['category']['icon'] === 'education' ? 'selected' : ''; ?>>Education</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="<?php echo BASE_URL; ?>categories" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color');
        const colorOptions = document.querySelectorAll('.color-option');

        // Set initial active state
        const currentColor = colorInput.value.toLowerCase();
        colorOptions.forEach(option => {
            if (option.dataset.color === currentColor) {
                option.classList.add('active');
            }
        });

        // Handle color selection
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                colorOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                colorInput.value = this.dataset.color;
            });
        });
    });
</script>