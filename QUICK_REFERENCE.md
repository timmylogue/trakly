# ⚡ Trakly Quick Reference Card

## 🚀 Quick Start Commands

```bash
# Start development server
./start.sh

# Or manually
cd public && php -S localhost:8000

# Create database
mysql -u root -p -e "CREATE DATABASE trakly"

# Import schema
mysql -u root -p budgetly < sql/schema.sql
```

## 📂 Key File Locations

| Purpose         | Location               |
| --------------- | ---------------------- |
| Database Config | `config/config.php`    |
| Routes          | `routes/web.php`       |
| Controllers     | `app/controllers/`     |
| Models          | `app/models/`          |
| Views           | `app/views/`           |
| CSS Styles      | `public/css/style.css` |
| JavaScript      | `public/js/app.js`     |
| Database Schema | `sql/schema.sql`       |

## 🔑 Important URLs

| Page            | URL                    |
| --------------- | ---------------------- |
| Login           | `/login`               |
| Register        | `/register`            |
| Dashboard       | `/dashboard`           |
| Transactions    | `/transactions`        |
| Add Transaction | `/transactions/create` |
| Categories      | `/categories`          |
| Add Category    | `/categories/create`   |

## 💾 Database Tables

```
users               # User accounts
budgets             # Budget periods
categories          # Spending categories
transactions        # Income/expenses
settings            # User preferences
shared_budgets      # Collaboration (Premium)
categorization_rules # Auto-categorize (Premium)
transaction_templates # Quick entry
sessions            # Session storage
```

## 🎨 CSS Variables

```css
--primary-color: #3B82F6
--success-color: #10B981
--danger-color: #EF4444
--warning-color: #F59E0B
--gray-* : Various gray shades
```

## 📝 Common Helper Functions

### Auth Helpers

```php
Auth::login($user)           # Create session
Auth::logout()               # Destroy session
Auth::isLoggedIn()           # Check if logged in
Auth::requireLogin()         # Protect route
Auth::userId()               # Get current user ID
Auth::isPremium()            # Check premium status
```

### General Helpers

```php
Helper::sanitize($data)              # Clean input
Helper::redirect($path)              # Navigate
Helper::formatCurrency($amount)      # Format money
Helper::formatDate($date)            # Format date
Helper::flashMessage($key, $msg)     # Flash message
Helper::uploadFile($file)            # Upload file
```

### Validation

```php
$validator = new Validator();
$validator->validate($data, [
    'field' => 'required|email|min:8'
]);
```

## 🗃️ Model Quick Reference

### User Model

```php
$user->create($name, $email, $password)
$user->findByEmail($email)
$user->verifyPassword($email, $password)
$user->isPremium($userId)
```

### Transaction Model

```php
$transaction->create($userId, $amount, $date, ...)
$transaction->findByUserId($userId)
$transaction->search($userId, $filters)
$transaction->getMonthlyTotal($userId, $year, $month)
```

### Category Model

```php
$category->create($userId, $name, $limitAmount)
$category->getCategoryWithSpending($userId)
$category->getSpentAmount($categoryId)
```

## 🛡️ Security Checklist

- ✅ Passwords hashed with bcrypt
- ✅ PDO prepared statements
- ✅ XSS protection via htmlspecialchars
- ✅ CSRF token support
- ✅ Session security
- ✅ Input validation
- ✅ File upload restrictions

## 🔧 Configuration Settings

### Development

```php
define('APP_ENV', 'development');
define('BASE_URL', 'http://localhost:8000/');
```

### Production

```php
define('APP_ENV', 'production');
error_reporting(0);
ini_set('display_errors', 0);
```

## 📊 Dashboard Stats

Dashboard displays:

- Monthly budget total
- Total spent (expenses)
- Remaining balance
- Monthly income
- Category progress bars
- Spending breakdown chart
- Recent transactions (10)

## 🎯 Feature Flags

Free vs Premium:

| Feature          | Free | Premium |
| ---------------- | ---- | ------- |
| Basic budgeting  | ✅   | ✅      |
| Categories       | ✅   | ✅      |
| Transactions     | ✅   | ✅      |
| Reports          | ✅   | ✅      |
| Receipt uploads  | ❌   | ✅      |
| Shared budgets   | ❌   | ✅      |
| Auto-categorize  | ❌   | ✅      |
| Advanced reports | ❌   | ✅      |
| CSV/PDF export   | ❌   | ✅      |

## 🐛 Common Issues & Fixes

### "Database connection failed"

→ Check `config/config.php` credentials

### "404 Not Found"

→ Verify `.htaccess` exists in public folder
→ Enable mod_rewrite: `sudo a2enmod rewrite`

### "Permission denied" on uploads

→ `chmod 755 public/uploads`

### Blank white page

→ Enable error display in config.php
→ Check PHP error logs

### Session issues

→ Clear browser cookies
→ Check session save path permissions

## 🔄 Common Workflows

### Adding a Transaction

1. Dashboard → "+ Add Transaction"
2. Fill amount, date, category
3. Add optional note/tags
4. Submit

### Creating a Category

1. Categories → "+ Add Category"
2. Name, monthly limit, color
3. Submit

### Viewing Reports

1. Dashboard → Spending Breakdown Chart
2. Transactions → Use filters

### Editing Settings

Future: Settings → Update preferences

## 📱 Mobile Testing

Test at these breakpoints:

- 320px (Small phone)
- 375px (iPhone)
- 768px (Tablet)
- 1024px (Desktop)

## 🚨 Emergency Commands

```bash
# Reset database
mysql -u root -p -e "DROP DATABASE trakly; CREATE DATABASE trakly"
mysql -u root -p trakly < sql/schema.sql

# Clear uploads
rm -rf public/uploads/*
touch public/uploads/.gitkeep

# Check PHP errors
tail -f /var/log/apache2/error.log

# Check permissions
ls -la public/uploads
```

## 📞 Support

- Documentation: See INSTALL.md, DEVELOPER_GUIDE.md
- Issues: GitHub Issues
- Questions: Project README.md

---

**Version**: 1.0.0  
**Last Updated**: 2025-11-25  
**License**: MIT
