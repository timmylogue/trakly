-- Migration: Add default budgets for existing users
-- Run this if you have existing users without budgets

-- Create default budgets for users who don't have any
INSERT INTO budgets (user_id, name, period, total_amount, is_active)
SELECT 
    u.id,
    'Monthly Budget',
    'monthly',
    0.00,
    1
FROM users u
LEFT JOIN budgets b ON u.id = b.user_id
WHERE b.id IS NULL;

-- Verify
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(b.id) as budget_count
FROM users u
LEFT JOIN budgets b ON u.id = b.user_id
GROUP BY u.id, u.name, u.email;
