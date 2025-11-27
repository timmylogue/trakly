# Test Data Management

This directory contains scripts for managing test data in the Trakly database.

## Files

- **seed_test_data.sql** - Inserts sample data for testing
- **clean_test_data.sql** - Removes all test data

## Usage

### Seed Test Data

Insert sample users, budgets, categories, and transactions:

```bash
mysql -u root -proot trakly < sql/seed_test_data.sql
```

**Test User Credentials:**

- Email: `john@example.com` | Password: `password123`
- Email: `jane@example.com` | Password: `password123` (Premium User)
- Email: `bob@example.com` | Password: `password123`

**What Gets Created:**

- 3 test users (John, Jane, Bob)
- Monthly budgets with realistic amounts
- Various categories (Groceries, Transportation, Entertainment, etc.)
- Transactions for current and previous month
- Transaction templates
- Auto-categorization rules (for premium user)
- Different theme and currency settings

### Clean Test Data

Remove all test data:

```bash
mysql -u root -proot trakly < sql/clean_test_data.sql
```

This will delete all test users and their associated data (budgets, categories, transactions, etc.) thanks to CASCADE foreign key constraints.

## Notes

- Test users have emails ending with `@example.com`
- All passwords are hashed with bcrypt (`password123`)
- Jane is a premium user with premium features enabled
- Data includes both current and previous month transactions for comparison testing
- The clean script is safe to run - it only removes test data (emails ending in @example.com)
