-- =============================================
-- Smart Billing System - Complete Database Export
-- Includes: Users, Items (with Images), Customers
-- =============================================

CREATE DATABASE IF NOT EXISTS smart_billing;
USE smart_billing;

-- 1. Users Table
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users Data (Password: password123)
INSERT INTO users (name, email, password, role) VALUES
('System Admin', 'admin@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'admin'),
('Staff One', 'staff@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'staff'),
('Staff Two', 'staff2@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'staff');

-- 2. Items Table
DROP TABLE IF EXISTS items;
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_number INT NOT NULL UNIQUE,
    item_name VARCHAR(150) NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Items Data
INSERT INTO items (item_number, item_name, unit_price, quantity, image) VALUES
(1, 'First Bag', 1600.00, 50, 'bag1.png'),
(2, 'Leather Bag', 2341.00, 30, 'bag2.png'),
(3, 'Premium Wallet', 890.00, 100, 'wallet1.png'),
(4, 'Travel Bag', 1234.00, 25, 'bag3.png'),
(5, 'Office Bag', 35.00, 200, 'bag4.png');

-- 3. Customers Table
DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customers Data
INSERT INTO customers (customer_name, phone, email, address) VALUES
('ABC Corporation', '555-1001', 'abc@corp.com', '789 Business Blvd'),
('XYZ Retailers', '555-1002', 'xyz@retail.com', '321 Commerce St'),
('Global Traders', '555-1003', 'info@globaltraders.com', '654 Trade Center');

-- 4. Vendors Table (Structure Only - Not in Phase 1-3)
DROP TABLE IF EXISTS vendors;
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO vendors (id, vendor_name, contact_person, phone, email, address) VALUES
(3, 'Johnson and Johnsons Co.', 'John Smith', '555-0101', 'john@jj.com', '123 Main St'),
(4, 'Louise Vitton Bag', 'Louise Chen', '555-0202', 'louise@lv.com', '456 Fashion Ave');

-- 5. Purchases Table (Structure Only)
DROP TABLE IF EXISTS purchases;
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id VARCHAR(50) NOT NULL UNIQUE,
    item_number INT NOT NULL,
    purchase_date DATE NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    vendor_id INT NOT NULL,
    vendor_name VARCHAR(150) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
