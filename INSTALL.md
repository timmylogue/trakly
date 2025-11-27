# 🚀 Trakly - Installation Guide

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.3 or higher
- Apache/Nginx web server
- Composer (optional, for future dependencies)

## Installation Steps

### 1. Clone or Download the Project

```bash
git clone https://github.com/timmylogue/trakly.git
cd trakly
```

### 2. Configure Database

1. Create a new MySQL database:

```sql
CREATE DATABASE trakly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the database schema:

```bash
mysql -u root -p trakly < sql/schema.sql
```

Or import via phpMyAdmin by importing the `sql/schema.sql` file.

### 3. Configure Application

Edit `/config/config.php` and update your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trakly');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

Also update the `BASE_URL`:

```php
define('BASE_URL', 'http://localhost/trakly/public/');
```

### 4. Set Permissions

Make sure the upload directory is writable:

```bash
chmod 755 public/uploads
```

### 5. Configure Web Server

#### Apache

If using Apache, mod_rewrite should be enabled:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

The `.htaccess` file is already configured in the `public` folder.

#### Nginx

Add this to your Nginx configuration:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 6. Access the Application

Open your browser and navigate to:

```
http://localhost/budgetly/public/
```

Or if using a virtual host:

```
http://trakly.local
```

### 7. Create Your First Account

1. Click "Sign up" on the login page
2. Fill in your details
3. Login with your new account
4. Start adding categories and transactions!

## Development Setup

### Local Development Server (PHP Built-in)

For quick testing, you can use PHP's built-in server:

```bash
cd public
php -S localhost:8000
```

Then visit: `http://localhost:8000`

**Note:** Update `BASE_URL` in config.php to `http://localhost:8000/` when using built-in server.

## Default Categories (Optional)

After creating your account, you may want to add these common categories:

- Groceries
- Rent/Mortgage
- Utilities
- Transportation
- Entertainment
- Healthcare
- Shopping
- Dining Out
- Savings

## Troubleshooting

### Database Connection Issues

- Verify database credentials in `config/config.php`
- Ensure MySQL service is running
- Check if the database exists

### 404 Errors

- Make sure mod_rewrite is enabled (Apache)
- Check .htaccess file exists in public folder
- Verify BASE_URL is correct

### Permission Errors

- Ensure `public/uploads` directory is writable
- Check PHP has permission to write session files

### Blank Page

- Enable error reporting in `config/config.php`
- Check PHP error logs
- Verify PHP version is 8.0+

## Production Deployment

### Security Checklist

1. Change `APP_ENV` to `'production'` in config.php
2. Disable error display:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```
3. Use HTTPS (set session.cookie_secure to 1)
4. Change database password to a strong password
5. Add Stripe API keys for Premium features
6. Set up regular database backups
7. Keep PHP and MySQL updated

### Performance

- Enable OPcache
- Use a CDN for static assets
- Configure MySQL query caching
- Consider using Redis for sessions

## Next Steps

- [ ] Set up Premium subscription with Stripe
- [ ] Configure email notifications
- [ ] Add recurring transactions
- [ ] Implement data export features
- [ ] Set up automated backups

## Support

For issues and questions, please open an issue on GitHub or contact support.

## License

MIT License - See LICENSE file for details
