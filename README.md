
# 📘 **README.md — Budgetly (Working Title)**
*A simple, fast, privacy-focused budgeting web app built with PHP & MySQL.*

---

# 🧾 Overview
**Budgetly** is a lightweight budgeting application designed for real humans — not finance experts. It helps users track spending, manage categories, set budgets, and visualize their financial habits through simple, clear dashboards.

The platform starts as a **free tool** and includes optional **Premium** upgrades that unlock automation, reporting, shared budgets, receipts, and more.

Fully built with **PHP 8**, **MySQL**, and **vanilla JavaScript**, Budgetly is optimized for speed, security, and easy self-hosting or SaaS deployment.

---

# 🎯 Features

## ✅ Free Features (MVP)
- Add transactions (amount, category, notes, date, tags)  
- Create custom categories  
- Set monthly category limits  
- See remaining money per category  
- Dashboard with progress bars  
- Monthly spending summary  
- Transaction search & filters  
- Favorite transaction templates  
- Mobile-optimized UI  
- Chart-based reports (Chart.js)  
- Dark mode ready  
- Secure user accounts

---

## 💎 Premium Features
Premium can be sold as **monthly**, **annual**, or **lifetime** access.

### **1. Multiple Budgets**
- Monthly budget  
- Weekly budget  
- Annual budget  
- Sinking funds (“Vacation,” “Christmas,” etc.)

### **2. Shared Budgets**
- Invite another user (partner, spouse, roommate)  
- Real-time sync  
- Shared transaction notes  

### **3. Receipt Uploads**
- Upload images attached to transactions  
- Automatic compression  

### **4. Advanced Reports**
- Monthly comparisons  
- Category overspending alerts  
- Income vs. expenses  
- Category "heatmap"  
- Predictive spending trends  

### **5. Auto-Categorization Rules**
Examples:
- If description contains “AMAZON” → categorize to Shopping  
- If amount matches recurring payment → mark as Bill  

### **6. Exporting**
- CSV  
- PDF reports  
- Email monthly summary  

### **7. Extra Personalization**
- Custom color themes  
- Extra widgets for dashboard  
- Unlimited categories  

---

# 🏗️ Tech Stack

### **Backend**
- PHP 8  
- MySQL (PDO)  
- Sessions for auth  
- Stripe for subscription billing  
- Clean MVC-like structure

### **Frontend**
- Tailwind CSS (or custom CSS)  
- Vanilla JavaScript  
- Chart.js for graphs  
- Mobile-first design  

---

# 📦 Project Structure (Suggested)
```
/app
    /controllers
    /models
    /views
    /helpers
/config
    config.php
/public
    index.php
    /css
    /js
    /uploads
/routes
    web.php
/scripts
    cron/
sql/
    schema.sql
README.md
```

---

# 🗄️ Database Schema

### **users**
```
id INT PK
name VARCHAR
email VARCHAR UNIQUE
password_hash TEXT
created_at DATETIME
```

### **budgets**
```
id INT PK
user_id INT FK
name VARCHAR
period ENUM('monthly','weekly','annual')
total_amount DECIMAL(10,2)
created_at DATETIME
```

### **categories**
```
id INT PK
user_id INT FK
budget_id INT FK
name VARCHAR
limit_amount DECIMAL(10,2)
```

### **transactions**
```
id INT PK
user_id INT FK
budget_id INT FK
category_id INT FK
amount DECIMAL(10,2)
note TEXT
date DATE
created_at DATETIME
```

### **settings**
```
user_id INT PK
currency VARCHAR(10)
theme VARCHAR(20)
notifications_enabled TINYINT
```

### **shared_budgets**
```
budget_id INT FK
user_id INT FK
role ENUM('owner','editor')
PRIMARY KEY (budget_id, user_id)
```

---

# 📊 Dashboard Overview
The dashboard includes:
- Total monthly spending  
- Remaining balance  
- Category progress bars  
- Weekly spending rate  
- Income vs. expenses (Premium)  
- Recent transactions  
- Alerts for high-category usage  

---

# 🚀 Subscription Model
Suggested pricing:

- **Free tier** — full core budgeting experience  
- **Premium Monthly** — $3.99  
- **Premium Annual** — $29  
- **Lifetime Purchase** — $60–$80  

---

# 🧰 Installation

### **1. Clone the Repo**
```
git clone https://github.com/yourusername/budgetly.git
cd budgetly
```

### **2. Configure Environment**
Edit `/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'budgetly');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/budgetly/');
```

### **3. Import Database**
Import the SQL file:
```
sql/schema.sql
```

### **4. Start the App**
Place the project in your local web root and open:  
`http://localhost/budgetly`

---

# ✔ Roadmap
- [ ] Add income tracking  
- [ ] Add recurring transactions  
- [ ] Add AI-powered auto-categorization  
- [ ] Add bank-import CSV folder  
- [ ] Add notifications/reminders  
- [ ] Add budgeting calendar view  
- [ ] Add progressive web app (PWA) option  

---

# 🤝 Contributing
Pull requests are welcome.  
For major changes, open an issue first to discuss what you’d like to add or modify.

---

# 📄 License
MIT License — free for personal and commercial use.
