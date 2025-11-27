-- Test Data Seed Script for Trakly
-- This file inserts sample data for testing purposes
-- Run this file with: mysql -u root -proot trakly < sql/seed_test_data.sql

USE trakly;

-- Start transaction to ensure all or nothing
START TRANSACTION;

-- Insert Test Users (Password: 'password123' hashed with PASSWORD_BCRYPT)
INSERT INTO users (name, email, password_hash, is_premium, premium_expires_at) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NULL),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, DATE_ADD(NOW(), INTERVAL 1 YEAR)),
('Bob Johnson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NULL);

-- Get the user IDs (assuming auto-increment starts fresh or these are the next IDs)
SET @user1_id = LAST_INSERT_ID();
SET @user2_id = @user1_id + 1;
SET @user3_id = @user1_id + 2;

-- Insert Budgets (Note: Default monthly budgets are auto-created by trigger)
-- Update the default budgets with proper amounts
UPDATE budgets SET total_amount = 3000.00 WHERE user_id = @user1_id AND name = 'Monthly Budget';
UPDATE budgets SET total_amount = 5000.00 WHERE user_id = @user2_id AND name = 'Monthly Budget';
UPDATE budgets SET total_amount = 2500.00 WHERE user_id = @user3_id AND name = 'Monthly Budget';

-- Get budget IDs
SET @budget1_id = (SELECT id FROM budgets WHERE user_id = @user1_id AND name = 'Monthly Budget');
SET @budget2_id = (SELECT id FROM budgets WHERE user_id = @user2_id AND name = 'Monthly Budget');
SET @budget3_id = (SELECT id FROM budgets WHERE user_id = @user3_id AND name = 'Monthly Budget');

-- Insert additional budgets
INSERT INTO budgets (user_id, name, period, total_amount, start_date, end_date, is_active) VALUES
(@user1_id, 'Vacation Fund', 'sinking_fund', 2000.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), 1),
(@user2_id, 'Emergency Fund', 'sinking_fund', 10000.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 12 MONTH), 1);

-- Insert Categories
INSERT INTO categories (user_id, budget_id, name, color, icon, limit_amount) VALUES
-- John's Categories
(@user1_id, @budget1_id, 'Groceries', '#10b981', 'shopping-cart', 500.00),
(@user1_id, @budget1_id, 'Transportation', '#3b82f6', 'car', 300.00),
(@user1_id, @budget1_id, 'Entertainment', '#f59e0b', 'film', 200.00),
(@user1_id, @budget1_id, 'Utilities', '#ef4444', 'zap', 250.00),
(@user1_id, @budget1_id, 'Dining Out', '#8b5cf6', 'utensils', 150.00),

-- Jane's Categories
(@user2_id, @budget2_id, 'Groceries', '#10b981', 'shopping-cart', 800.00),
(@user2_id, @budget2_id, 'Rent', '#ef4444', 'home', 2000.00),
(@user2_id, @budget2_id, 'Transportation', '#3b82f6', 'car', 400.00),
(@user2_id, @budget2_id, 'Health & Fitness', '#ec4899', 'heart', 300.00),
(@user2_id, @budget2_id, 'Shopping', '#f59e0b', 'shopping-bag', 500.00),

-- Bob's Categories
(@user3_id, @budget3_id, 'Groceries', '#10b981', 'shopping-cart', 400.00),
(@user3_id, @budget3_id, 'Transportation', '#3b82f6', 'car', 250.00),
(@user3_id, @budget3_id, 'Entertainment', '#f59e0b', 'film', 150.00);

-- Get category IDs
SET @cat_groceries1 = (SELECT id FROM categories WHERE user_id = @user1_id AND name = 'Groceries');
SET @cat_transport1 = (SELECT id FROM categories WHERE user_id = @user1_id AND name = 'Transportation');
SET @cat_entertainment1 = (SELECT id FROM categories WHERE user_id = @user1_id AND name = 'Entertainment');
SET @cat_utilities1 = (SELECT id FROM categories WHERE user_id = @user1_id AND name = 'Utilities');
SET @cat_dining1 = (SELECT id FROM categories WHERE user_id = @user1_id AND name = 'Dining Out');

SET @cat_groceries2 = (SELECT id FROM categories WHERE user_id = @user2_id AND name = 'Groceries');
SET @cat_rent2 = (SELECT id FROM categories WHERE user_id = @user2_id AND name = 'Rent');
SET @cat_transport2 = (SELECT id FROM categories WHERE user_id = @user2_id AND name = 'Transportation');
SET @cat_health2 = (SELECT id FROM categories WHERE user_id = @user2_id AND name = 'Health & Fitness');
SET @cat_shopping2 = (SELECT id FROM categories WHERE user_id = @user2_id AND name = 'Shopping');

-- Insert Transactions for Current Month
INSERT INTO transactions (user_id, budget_id, category_id, amount, type, note, date) VALUES
-- John's Transactions (Current Month)
(@user1_id, @budget1_id, @cat_groceries1, 85.50, 'expense', 'Weekly grocery shopping at Whole Foods', CURDATE()),
(@user1_id, @budget1_id, @cat_groceries1, 127.32, 'expense', 'Costco bulk shopping', DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(@user1_id, @budget1_id, @cat_transport1, 45.00, 'expense', 'Gas station fill-up', DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
(@user1_id, @budget1_id, @cat_transport1, 60.00, 'expense', 'Uber rides', DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
(@user1_id, @budget1_id, @cat_entertainment1, 15.99, 'expense', 'Netflix subscription', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@user1_id, @budget1_id, @cat_entertainment1, 45.00, 'expense', 'Movie tickets for 2', DATE_SUB(CURDATE(), INTERVAL 10 DAY)),
(@user1_id, @budget1_id, @cat_utilities1, 120.00, 'expense', 'Electric bill', DATE_SUB(CURDATE(), INTERVAL 15 DAY)),
(@user1_id, @budget1_id, @cat_dining1, 35.75, 'expense', 'Dinner at Italian restaurant', DATE_SUB(CURDATE(), INTERVAL 4 DAY)),
(@user1_id, @budget1_id, @cat_dining1, 22.50, 'expense', 'Lunch with colleagues', DATE_SUB(CURDATE(), INTERVAL 8 DAY)),
(@user1_id, @budget1_id, NULL, 3000.00, 'income', 'Monthly salary', DATE_SUB(CURDATE(), INTERVAL 1 DAY)),

-- Jane's Transactions (Current Month)
(@user2_id, @budget2_id, @cat_groceries2, 156.78, 'expense', 'Weekly groceries', CURDATE()),
(@user2_id, @budget2_id, @cat_rent2, 2000.00, 'expense', 'Monthly rent payment', DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
(@user2_id, @budget2_id, @cat_transport2, 80.00, 'expense', 'Gas and car wash', DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
(@user2_id, @budget2_id, @cat_health2, 50.00, 'expense', 'Gym membership', DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(@user2_id, @budget2_id, @cat_shopping2, 125.50, 'expense', 'New running shoes', DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
(@user2_id, @budget2_id, NULL, 5500.00, 'income', 'Monthly salary', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@user2_id, @budget2_id, NULL, 300.00, 'income', 'Freelance project', DATE_SUB(CURDATE(), INTERVAL 10 DAY)),

-- Bob's Transactions (Current Month)
(@user3_id, @budget3_id, @cat_groceries1, 95.25, 'expense', 'Grocery shopping', CURDATE()),
(@user3_id, @budget3_id, @cat_transport1, 40.00, 'expense', 'Gas', DATE_SUB(CURDATE(), INTERVAL 4 DAY)),
(@user3_id, @budget3_id, @cat_entertainment1, 12.99, 'expense', 'Spotify subscription', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@user3_id, @budget3_id, NULL, 2800.00, 'income', 'Monthly salary', DATE_SUB(CURDATE(), INTERVAL 3 DAY));

-- Insert Transactions for Previous Month
INSERT INTO transactions (user_id, budget_id, category_id, amount, type, note, date) VALUES
-- John's Previous Month
(@user1_id, @budget1_id, @cat_groceries1, 110.00, 'expense', 'Weekly shopping', DATE_SUB(CURDATE(), INTERVAL 1 MONTH)),
(@user1_id, @budget1_id, @cat_transport1, 55.00, 'expense', 'Gas', DATE_SUB(CURDATE(), INTERVAL 35 DAY)),
(@user1_id, @budget1_id, @cat_entertainment1, 25.00, 'expense', 'Concert tickets', DATE_SUB(CURDATE(), INTERVAL 40 DAY)),
(@user1_id, @budget1_id, NULL, 3000.00, 'income', 'Salary', DATE_SUB(CURDATE(), INTERVAL 32 DAY)),

-- Jane's Previous Month
(@user2_id, @budget2_id, @cat_groceries2, 200.00, 'expense', 'Groceries', DATE_SUB(CURDATE(), INTERVAL 1 MONTH)),
(@user2_id, @budget2_id, @cat_rent2, 2000.00, 'expense', 'Rent', DATE_SUB(CURDATE(), INTERVAL 33 DAY)),
(@user2_id, @budget2_id, NULL, 5500.00, 'income', 'Salary', DATE_SUB(CURDATE(), INTERVAL 31 DAY));

-- Insert Transaction Templates
INSERT INTO transaction_templates (user_id, category_id, name, amount, note) VALUES
(@user1_id, @cat_groceries1, 'Weekly Groceries', 100.00, 'Regular weekly grocery shopping'),
(@user1_id, @cat_transport1, 'Gas Fill-up', 50.00, 'Fill up gas tank'),
(@user2_id, @cat_rent2, 'Monthly Rent', 2000.00, 'Apartment rent payment'),
(@user2_id, @cat_health2, 'Gym Membership', 50.00, 'Monthly gym fee');

-- Insert Auto-Categorization Rules (Premium Feature for Jane)
INSERT INTO categorization_rules (user_id, category_id, keyword, match_type, is_active) VALUES
(@user2_id, @cat_groceries2, 'grocery', 'contains', 1),
(@user2_id, @cat_groceries2, 'whole foods', 'contains', 1),
(@user2_id, @cat_transport2, 'gas', 'contains', 1),
(@user2_id, @cat_transport2, 'uber', 'contains', 1),
(@user2_id, @cat_health2, 'gym', 'contains', 1);

-- Update Settings
UPDATE settings SET theme = 'dark' WHERE user_id = @user2_id;
UPDATE settings SET currency = 'EUR', currency_symbol = '€' WHERE user_id = @user3_id;

COMMIT;

-- Display summary
SELECT 
    'Test data inserted successfully!' AS message,
    (SELECT COUNT(*) FROM users WHERE email LIKE '%@example.com') AS test_users_created,
    (SELECT COUNT(*) FROM transactions WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) AS test_transactions_created,
    (SELECT COUNT(*) FROM categories WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) AS test_categories_created;

SELECT '--------------------------------------' AS '';
SELECT 'Test User Credentials:' AS '';
SELECT 'Email: john@example.com | Password: password123' AS '';
SELECT 'Email: jane@example.com | Password: password123 (Premium)' AS '';
SELECT 'Email: bob@example.com | Password: password123' AS '';