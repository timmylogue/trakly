-- Clean Test Data Script for Trakly
-- This file removes all test data that was created by seed_test_data.sql
-- Run this file with: mysql -u root -proot trakly < sql/clean_test_data.sql

USE trakly;

-- Start transaction to ensure all or nothing
START TRANSACTION;

-- Display what will be deleted
SELECT '--------------------------------------' AS '';
SELECT 'Test Data to be Deleted:' AS '';
SELECT 
    (SELECT COUNT(*) FROM users WHERE email LIKE '%@example.com') AS test_users,
    (SELECT COUNT(*) FROM transactions WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) AS test_transactions,
    (SELECT COUNT(*) FROM categories WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) AS test_categories,
    (SELECT COUNT(*) FROM budgets WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) AS test_budgets;
SELECT '--------------------------------------' AS '';

-- Delete all test data (CASCADE will handle related records automatically)
-- The foreign key constraints with ON DELETE CASCADE will automatically remove:
-- - settings
-- - budgets
-- - categories
-- - transactions
-- - categorization_rules
-- - transaction_templates
-- - shared_budgets
-- - sessions (if any)

DELETE FROM users WHERE email IN (
    'john@example.com',
    'jane@example.com',
    'bob@example.com'
);

COMMIT;

-- Display confirmation
SELECT 'Test data deleted successfully!' AS message;
SELECT '--------------------------------------' AS '';
SELECT 'Remaining Data:' AS '';
SELECT 
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM transactions) AS total_transactions,
    (SELECT COUNT(*) FROM categories) AS total_categories,
    (SELECT COUNT(*) FROM budgets) AS total_budgets;
