# Smart Billing & Inventory Management System

![PHP](https://img.shields.io/badge/PHP-Core%20PHP-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/UI-Bootstrap%205-7952B3?style=flat&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green)

A complete, production-ready billing and inventory management system built with **Core PHP** and **MySQL**. Features role-based authentication, real-time billing with AJAX, inventory tracking, and comprehensive reporting.

---

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Demo Credentials](#-demo-credentials)
- [Project Structure](#-project-structure)
- [Usage Guide](#-usage-guide)
- [Screenshots](#-screenshots)
- [Development Phases](#-development-phases)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### Phase 1-3 (Current Implementation)

#### Authentication System
- ✅ Secure login with password hashing (bcrypt)
- ✅ User registration with validation
- ✅ Role-based access control (Admin/Staff)
- ✅ Session management
- ✅ Logout functionality

#### Admin Dashboard
- ✅ Real-time statistics (Total Sales, Products, Low Stock, Customers)
- ✅ Monthly sales chart using Chart.js
- ✅ Low stock alerts
- ✅ Recent invoices overview
- ✅ Responsive design

#### User Interface
- ✅ Professional Bootstrap 5 design
- ✅ Gradient color schemes
- ✅ Responsive sidebar navigation
- ✅ Flash message notifications
- ✅ Modern card-based layouts

### Upcoming Phases

- 📦 **Phase 4**: Staff Module (Billing operations)
- 📦 **Phase 5**: Billing Module (Invoice management)
- 📦 **Phase 6**: Reports Module (Analytics & exports)
- 📦 **Phase 7**: Inventory Management
- 📦 **Phase 8**: Product & Customer CRUD

---

## 🛠️ Technology Stack

| Component | Technology |
|-----------|-----------|
| **Backend** | Core PHP (No framework) |
| **Database** | MySQL 5.7+ |
| **Frontend** | HTML5, CSS3, JavaScript (ES6) |
| **UI Framework** | Bootstrap 5.3 |
| **AJAX** | Vanilla JavaScript Fetch API |
| **Charts** | Chart.js 4.4 |
| **Icons** | Font Awesome 6.4 |
| **Server** | Apache (XAMPP) |

---

## 💻 System Requirements

- **XAMPP** 8.0 or higher (includes Apache & MySQL)
- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Web Browser** (Chrome, Firefox, Edge - latest versions)
- **Operating System** Windows 10/11, macOS, or Linux

---

## 📥 Installation

### Step 1: Install XAMPP

1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP in `C:\xampp\` (Windows) or `/Applications/XAMPP` (macOS)
3. Start **Apache** and **MySQL** from the XAMPP Control Panel

### Step 2: Setup Project Files

1. Clone or extract the project into `C:\xampp\htdocs\smart-billing`

```bash
cd C:\xampp\htdocs
git clone <repository-url> smart-billing
```

Or manually copy the `smart-billing` folder to `C:\xampp\htdocs\`

### Step 3: Create Database

1. Open **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on **"New"** to create a database
3. Name it: `smart_billing`
4. Click **"Import"** tab
5. Choose file: `database/smart_billing.sql`
6. Click **"Go"** to import

### Step 4: Configure Database Connection

1. Open `config/db.php`
2. Verify database credentials (default XAMPP settings):

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_billing');
```

### Step 5: Access the Application

1. Open your browser
2. Navigate to: [http://localhost/smart-billing](http://localhost/smart-billing)
3. You'll be redirected to the login page
4. Use demo credentials below

---

## 🔐 Demo Credentials

### Admin Account
```
Email: admin@billing.com
Password: admin123
```
**Access**: Full system access including user management, reports, and all modules

### Staff Account
```
Email: staff1@billing.com
Password: staff123
```
**Access**: Billing operations, customer management, view products

**Note**: Passwords are hashed in the database using PHP's `password_hash()` function.

---

## 📁 Project Structure

```
smart-billing/
├── admin/                  # Admin dashboard and modules
│   └── dashboard.php       # Main admin dashboard
│
├── auth/                   # Authentication modules
│   ├── login.php          # Login page
│   ├── register.php       # Registration page
│   └── logout.php         # Logout handler
│
├── assets/                # Static assets
│   ├── css/
│   │   └── style.css      # Custom styles
│   └── js/
│       └── main.js        # Custom JavaScript
│
├── config/                # Configuration files
│   └── db.php            # Database connection & utilities
│
├── database/             # SQL files
│   └── smart_billing.sql # Database schema & sample data
│
├── includes/             # Reusable components
│   ├── header.php       # Header & navbar
│   ├── sidebar.php      # Sidebar navigation
│   └── footer.php       # Footer component
│
├── staff/                # Staff module (Phase 4 - Empty)
├── reports/              # Reports module (Phase 6 - Empty)
├── billing/              # Billing module (Phase 5 - Empty)
│
├── index.php            # Main entry point
└── README.md            # This file
```

---

## 📖 Usage Guide

### For Administrators

1. **Login** with admin credentials
2. **Dashboard** shows:
   - Total sales amount
   - Active products count
   - Low stock alerts
   - Customer count
   - Monthly sales chart
   - Recent invoices
3. **Navigation** (upcoming phases):
   - Users: Add/manage system users
   - Products: Manage inventory
   - Customers: Customer database
   - Reports: Sales analytics

### For Staff Users

1. **Login** with staff credentials
2. **Access**:
   - View dashboard with quick stats
   - Billing operations (Phase 4)
   - View products and customers
   - Invoice history

---

## 📸 Screenshots

### Login Page
- Modern gradient design
- Email & password authentication
- Demo credentials display
- Registration link

### Admin Dashboard
- **Statistics Cards**: Total Sales, Products, Low Stock, Customers
- **Sales Chart**: Bar chart showing 6-month sales trend
- **Low Stock Alert**: Real-time inventory warnings
- **Recent Invoices**: Latest transactions table

### Sidebar Navigation
- **Admin Menu**: Dashboard, Users, Products, Customers, Invoices, Reports, Inventory
- **Staff Menu**: Dashboard, New Billing, Billing History, Products, Customers
- **Active State**: Current page highlighted
- **Responsive**: Collapsible on mobile

---

## 🚀 Development Phases

### ✅ Phase 1: Database & Configuration (Complete)
- Database schema with 5 tables
- Sample data insertion
- Database connection setup

### ✅ Phase 2: Authentication System (Complete)
- Login with password verification
- User registration
- Role-based access control
- Session management

### ✅ Phase 3: Admin Dashboard & UI (Complete)
- Dashboard with statistics
- Chart.js integration
- Responsive layout
- Reusable components (header, sidebar, footer)

### 📦 Phase 4: Staff Module (Pending)
- Staff dashboard
- Quick billing access
- Today's statistics

### 📦 Phase 5: Billing Module (Pending)
- Create new invoice
- AJAX-based calculations
- Auto bill number generation
- Print invoice

### 📦 Phase 6: Reports Module (Pending)
- Daily/Monthly sales reports
- Product-wise analysis
- Excel export
- Print functionality

### 📦 Phase 7: Inventory Management (Pending)
- Auto stock reduction
- Low stock alerts
- Manual stock updates

### 📦 Phase 8: Full CRUD Operations (Pending)
- Product management
- Customer management
- User management (Admin only)

---

## 🔧 Troubleshooting

### Issue: "Connection failed" error

**Solution**:
- Verify XAMPP MySQL is running
- Check database credentials in `config/db.php`
- Ensure database `smart_billing` exists

### Issue: White screen / PHP errors

**Solution**:
- Enable error display in `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```
- Check Apache error logs in `C:\xampp\apache\logs\error.log`

### Issue: Login not working

**Solution**:
- Clear browser cookies/cache
- Verify database import was successful
- Check user table has sample data
- Ensure session is enabled in PHP

### Issue: Charts not displaying

**Solution**:
- Check internet connection (Chart.js loads from CDN)
- Verify JavaScript console for errors
- Ensure Chart.js script is loaded in header

---

## 🤝 Contributing

This is an internship project. Contributions, suggestions, and feedback are welcome!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-source and available under the **MIT License**.

---

## 👨‍💻 Author

**Internship Project**  
Smart Billing & Inventory Management System

---

## 📞 Support

For issues, questions, or suggestions:
- Open an issue in the repository
- Contact: [Your Email]

---

## 🙏 Acknowledgments

- **Bootstrap** for the UI framework
- **Chart.js** for data visualization
- **Font Awesome** for icons
- **XAMPP** for local development environment

---

**Note**: This is a Phase 1-3 release. More features will be added in upcoming phases. Stay tuned!

---

*Last Updated: January 2026*
