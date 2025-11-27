# 📊 Trakly

_A simple, fast, privacy-focused budgeting web app built with PHP & MySQL._

![Dashboard](https://img.shields.io/badge/Status-Active-success)
![PHP](https://img.shields.io/badge/PHP-8.0+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 🧾 Overview

**Trakly** is a lightweight budgeting application designed for real humans — not finance experts. It helps users track spending, manage categories, and visualize their financial habits through simple, clear dashboards.

The platform starts as a **free tool** and includes optional **Premium** upgrades that unlock automation, reporting, shared budgets, receipts, and more.

Fully built with **PHP 8**, **MySQL**, and **vanilla JavaScript**, Trakly is optimized for speed, security, and easy self-hosting or SaaS deployment.

---

## ✨ Key Features

### ✅ Currently Implemented

- 💰 **Income-Based Budgeting** - Your income = your budget (simple & clear)
- 📊 **Beautiful Dashboard** - Track income, expenses, and savings at a glance
- 🎨 **Category Management** - Custom categories with gorgeous color picker (12 preset colors)
- 📈 **Visual Progress Tracking** - See spending vs. limits with progress bars
- 📅 **Month Navigation** - View historical data and compare months
- 💸 **Transaction Management** - Add, edit, search, and filter transactions
- 🌙 **Dark Mode** - Full theme support with database persistence
- 📱 **Mobile Responsive** - Optimized for all screen sizes
- 📊 **Spending Breakdown** - Interactive pie charts with Chart.js
- 🧪 **Test Data Management** - Web interface to seed/clean test users
- 🔐 **Secure Authentication** - User accounts with bcrypt password hashing

### 🎯 Dashboard Highlights

- **Monthly Income** - Track all income sources
- **Total Expenses** - See what you've spent (% of income)
- **Savings** - Automatic calculation with savings rate
- **Category Budgets** - Visual progress bars with over-budget alerts
- **Recent Transactions** - Quick overview of latest activity
- **Spending Chart** - Category breakdown visualization

---

## 💎 Planned Premium Features

Premium will be available as **monthly**, **annual**, or **lifetime** access.

### **Upcoming Features**

- 📁 **Multiple Budget Types** - Monthly, weekly, annual, and sinking funds
- 👥 **Shared Budgets** - Collaborate with partners or family
- 📎 **Receipt Uploads** - Attach images to transactions
- 📈 **Advanced Reports** - Monthly comparisons, trends, and insights
- 🤖 **Auto-Categorization** - Smart rules to categorize transactions automatically
- 📤 **Export Options** - CSV, PDF reports, and email summaries
- 🎨 **Premium Themes** - Additional customization options

---

## 🏗️ Tech Stack

### **Backend**

- **PHP 8** - Modern PHP with type declarations
- **MySQL** - Relational database with PDO
- **MVC Architecture** - Clean separation of concerns
- **Session-based Auth** - Secure user authentication

### **Frontend**

- **Custom CSS** - CSS variables for theming
- **Vanilla JavaScript** - No framework bloat
- **Chart.js** - Beautiful, responsive charts
- **Mobile-First Design** - Fully responsive layout

---

## 📦 Project Structure

```
/app
  /controllers      - Application controllers (MVC)
  /models          - Database models
  /views           - PHP view templates
  /helpers         - Auth, Validator, Helper utilities
/config
  config.php       - Application configuration
  database.php     - Database connection
/public
  index.php        - Entry point
  /css             - Stylesheets
  /js              - JavaScript files
  /uploads         - User uploads
/routes
  Router.php       - Custom router class
  web.php          - Route definitions
/sql
  schema.sql              - Database schema
  seed_test_data.sql      - Test data
  clean_test_data.sql     - Clean test data
README.md
```

---

## 🗄️ Database Schema

### **users**

```sql
id INT PK
name VARCHAR
email VARCHAR UNIQUE
password_hash TEXT
is_premium TINYINT(1)
premium_expires_at DATETIME
created_at DATETIME
updated_at DATETIME
```

### **budgets**

```sql
id INT PK
user_id INT FK
name VARCHAR
period ENUM('monthly','weekly','annual','sinking_fund')
total_amount DECIMAL(10,2)
start_date DATE
end_date DATE
is_active TINYINT(1)
created_at DATETIME
updated_at DATETIME
```

### **categories**

```sql
id INT PK
user_id INT FK
budget_id INT FK
name VARCHAR
color VARCHAR(7)
icon VARCHAR(50)
limit_amount DECIMAL(10,2)
created_at DATETIME
updated_at DATETIME
```

### **transactions**

```sql
id INT PK
user_id INT FK
budget_id INT FK
category_id INT FK
amount DECIMAL(10,2)
type ENUM('expense','income')
note TEXT
date DATE
tags VARCHAR(500)
receipt_path VARCHAR(500)
is_recurring TINYINT(1)
created_at DATETIME
updated_at DATETIME
```

### **settings**

```sql
user_id INT PK FK
currency VARCHAR(10)
currency_symbol VARCHAR(5)
theme VARCHAR(20)
notifications_enabled TINYINT(1)
date_format VARCHAR(20)
created_at DATETIME
updated_at DATETIME
```

---

## 🚀 Installation

### **Prerequisites**

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer (optional, for future dependencies)

### **Quick Start**

1. **Clone the Repository**

   ```bash
   git clone https://github.com/timmylogue/trakly.git
   cd trakly
   ```

2. **Configure Environment**

   Copy `.env.example` to `.env` and update with your settings:

   ```bash
   cp .env.example .env
   ```

   Edit `.env`:

   ```env
   DB_HOST=localhost
   DB_NAME=trakly
   DB_USER=root
   DB_PASS=your_password
   BASE_URL=http://localhost/
   APP_ENV=development
   ```

3. **Create Database**

   ```bash
   mysql -u root -p -e "CREATE DATABASE trakly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

4. **Import Schema**

   ```bash
   mysql -u root -p trakly < sql/schema.sql
   ```

5. **Optional: Seed Test Data**

   ```bash
   mysql -u root -p trakly < sql/seed_test_data.sql
   ```

   **Test Users:**

   - `john@example.com` / `password123`
   - `jane@example.com` / `password123` (Premium)
   - `bob@example.com` / `password123`

6. **Configure Web Server**

   Point your web server to the `/public` directory as the document root.

   **Apache Example** (`.htaccess` included):

   ```apache
   DocumentRoot "/path/to/trakly/public"
   ```

7. **Access the App**

   Navigate to `http://localhost` in your browser.

---

## 🧪 Test Data Management

Trakly includes a built-in web interface for managing test data:

- Visit `/testdata` in your browser
- Click **"Seed Test Data"** to create 3 test users with sample transactions
- Click **"Clean Test Data"** to remove all test users and their data
- Perfect for development and testing!

---

## 📊 How It Works

### **Simplified Budgeting Model**

Trakly uses an **income-based budgeting** approach:

1. **Add Income Transactions** → This sets your monthly budget
2. **Set Category Limits** → Allocate your income (Groceries, Rent, etc.)
3. **Track Expenses** → See spending vs. limits
4. **View Savings** → Automatic calculation: Income - Expenses

**Benefits:**

- ✅ No confusion between "budget" and "income"
- ✅ Clear savings calculation
- ✅ Simple mental model
- ✅ Category limits help allocate spending

---

## 🎨 Features in Detail

### **Color Picker**

Beautiful preset color palette with 12 carefully selected colors:

- Click to select
- Visual active state
- Consistent across create/edit

### **Dark Mode**

- Toggle in settings
- Persisted to database
- Smooth transitions
- All components themed

### **Responsive Design**

- Mobile-first approach
- Stacking cards on small screens
- Touch-friendly buttons
- Optimized navigation menu

### **Month Navigation**

- Browse historical data
- Compare month-to-month
- Navigate forward/backward
- Current month indicator

---

## 🔒 Security

- ✅ Password hashing with bcrypt
- ✅ Session-based authentication
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ CSRF protection (planned)
- ✅ Environment variables for sensitive data

---

## 🚦 Roadmap

### **Phase 1: Core Improvements** (Current)

- [ ] Add recurring transactions
- [ ] CSV import for transactions
- [ ] Enhanced search/filtering
- [ ] Transaction notes improvement

### **Phase 2: Premium Features**

- [ ] Stripe integration
- [ ] Shared budgets
- [ ] Receipt uploads
- [ ] Advanced reports
- [ ] Auto-categorization rules

### **Phase 3: Enhancements**

- [ ] PWA support
- [ ] Calendar view
- [ ] Budget templates
- [ ] Email notifications
- [ ] Export to PDF

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 💡 Support

- **Issues**: [GitHub Issues](https://github.com/timmylogue/trakly/issues)
- **Discussions**: [GitHub Discussions](https://github.com/timmylogue/trakly/discussions)

---

## 🙏 Acknowledgments

- Chart.js for beautiful charts
- PHP community for excellent documentation
- All contributors and testers

---

**Made with ❤️ for better personal finance management**
