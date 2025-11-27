# 🚀 MAMP Setup Guide for Trakly

## Current Configuration

Your MAMP is set up to use:

- **URL**: https://trakly:8890/
- **Database**: trakly
- **DB User**: root
- **DB Password**: root

## Step-by-Step Setup

### 1. Create the Database

1. Open **phpMyAdmin** in MAMP (usually at http://localhost:8888/phpMyAdmin or similar)
2. Click "New" to create a database
3. Name it: `trakly`
4. Choose collation: `utf8mb4_unicode_ci`
5. Click "Create"

### 2. Import the Schema

**Option A - Using phpMyAdmin:**

1. Select the `trakly` database
2. Click "Import" tab
3. Choose file: `/Users/timothylogue/Desktop/Trakly/sql/schema.sql`
4. Click "Go"

**Option B - Using Terminal:**

```bash
cd /Users/timothylogue/Desktop/Trakly
/Applications/MAMP/Library/bin/mysql -u root -proot trakly < sql/schema.sql
```

### 3. Test Your Setup

Visit: **https://trakly:8890/test.php**

This will show you:

- PHP version and configuration
- Whether all files are accessible
- Database connection status
- Environment variables

### 4. Common MAMP Issues & Fixes

#### Issue: "Internal Server Error"

**Fix:**

- Make sure MAMP's document root is set to `/Users/timothylogue/Desktop/Trakly/public`
- Check that mod_rewrite is enabled in Apache

#### Issue: "Database connection failed"

**Fix:**

- Make sure MySQL is running in MAMP
- Verify database credentials in `config/config.php`
- Create the `trakly` database if it doesn't exist

#### Issue: "404 Not Found"

**Fix:**

- Verify .htaccess is being read (check MAMP Apache config)
- Make sure AllowOverride is set to All

#### Issue: Blank white page

**Fix:**

- Check MAMP error logs: `/Applications/MAMP/logs/apache_error.log`
- Make sure display_errors is on in development

### 5. Set MAMP Document Root

1. Open **MAMP** application
2. Click **Preferences**
3. Go to **Web Server** tab
4. Set Document Root to: `/Users/timothylogue/Desktop/Trakly/public`
5. Click **OK** and restart servers

### 6. Verify Apache Configuration

Your virtual host should point to the **public** folder:

```apache
<VirtualHost *:8890>
    DocumentRoot "/Users/timothylogue/Desktop/Trakly/public"
    ServerName trakly

    <Directory "/Users/timothylogue/Desktop/Trakly/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 7. Start Using Trakly

Once everything is working:

1. **Delete the test file:**

   ```bash
   rm /Users/timothylogue/Desktop/Trakly/public/test.php
   ```

2. **Visit the app:**

   - Login: https://trakly:8890/login
   - Register: https://trakly:8890/register

3. **Create your first account** and start budgeting!

## Troubleshooting

### Check MAMP Logs

```bash
# Apache Error Log
tail -f /Applications/MAMP/logs/apache_error.log

# PHP Error Log
tail -f /Applications/MAMP/logs/php_error.log
```

### Verify Database

```bash
/Applications/MAMP/Library/bin/mysql -u root -proot -e "SHOW DATABASES;"
```

### Test Database Connection

```bash
/Applications/MAMP/Library/bin/mysql -u root -proot trakly -e "SHOW TABLES;"
```

## Quick Checklist

- [ ] MAMP is running (Apache & MySQL)
- [ ] Document root is set to `/Users/timothylogue/Desktop/Trakly/public`
- [ ] Database `trakly` exists
- [ ] Schema is imported (8 tables should exist)
- [ ] test.php shows all green checkmarks
- [ ] Can access https://trakly:8890/test.php
- [ ] Can access https://trakly:8890/login

## Need Help?

If you're still getting errors:

1. Check the test.php output
2. Look at MAMP error logs
3. Verify all files are in the right location
4. Make sure permissions are correct: `chmod -R 755 /Users/timothylogue/Desktop/Trakly`

---

**Once everything works, you're ready to budget! 💰**
