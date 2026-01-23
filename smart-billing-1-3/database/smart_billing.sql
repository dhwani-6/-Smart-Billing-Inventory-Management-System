-- =============================================
-- Smart Billing System - Complete Database
-- With Inventory Management Tables
-- =============================================

CREATE DATABASE IF NOT EXISTS smart_billing;
USE smart_billing;

-- =============================================
-- Table: users
-- Handles login for both Admin and Staff
-- =============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Table: items (Products/Inventory)
-- =============================================
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_number INT NOT NULL UNIQUE,
    item_name VARCHAR(150) NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Table: vendors
-- =============================================
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Table: customers
-- =============================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Table: purchases
-- =============================================
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

-- =============================================
-- Table: sales (Invoices)
-- =============================================
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    sale_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Table: sale_items (Invoice Line Items)
-- =============================================
CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    item_number INT NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Demo Data
-- Password for all: password123
-- Hash: $2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai
-- =============================================

-- 1. Users
INSERT INTO users (name, email, password, role) VALUES
('System Admin', 'admin@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'admin'),
('Staff One', 'staff@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'staff'),
('Staff Two', 'staff2@billing.com', '$2y$10$qhqtjibUq1v4UGpebTrlMOib4Gqugfg9ksjMbfNZAbTb.BkaDMHai', 'staff');

-- 2. Items
INSERT INTO items (item_number, item_name, unit_price, quantity) VALUES
(1, 'First Bag', 1600, 50),
(2, 'Leather Bag', 2341, 30),
(3, 'Premium Wallet', 890, 100),
(4, 'Travel Bag', 1234, 25),
(5, 'Office Bag', 35, 200);

-- 3. Vendors
INSERT INTO vendors (id, vendor_name, contact_person, phone, email, address) VALUES
(3, 'Johnson and Johnsons Co.', 'John Smith', '555-0101', 'john@jj.com', '123 Main St'),
(4, 'Louise Vitton Bag', 'Louise Chen', '555-0202', 'louise@lv.com', '456 Fashion Ave');

-- 4. Customers
INSERT INTO customers (customer_name, phone, email, address) VALUES
('ABC Corporation', '555-1001', 'abc@corp.com', '789 Business Blvd'),
('XYZ Retailers', '555-1002', 'xyz@retail.com', '321 Commerce St'),
('Global Traders', '555-1003', 'info@globaltraders.com', '654 Trade Center');

-- 5. Purchases (matching screenshot data)
INSERT INTO purchases (purchase_id, item_number, purchase_date, item_name, unit_price, quantity, vendor_id, vendor_name, total_price) VALUES
('39', 1, '2018-05-24', 'First Bag', 1600, 10, 3, 'Johnson and Johnsons Co.', 16000),
('40', 2, '2018-05-18', 'First Bag', 2341, 2, 4, 'Louise Vitton Bag', 4682),
('41', 4, '2018-05-07', 'Leather Bag', 1234, 3, 3, 'Johnson and Johnsons Co.', 3702),
('42', 1, '2018-05-24', 'First Bag', 345, 12, 4, 'Louise Vitton Bag', 4140),
('43', 5, '2018-05-03', 'Travel Bag', 35, 3, 3, 'Johnson and Johnsons Co.', 105);

-- 6. Sales
INSERT INTO sales (invoice_number, customer_id, customer_name, sale_date, total_amount) VALUES
('INV-001', 1, 'ABC Corporation', '2024-01-15', 8000),
('INV-002', 2, 'XYZ Retailers', '2024-01-16', 4682),
('INV-003', 3, 'Global Traders', '2024-01-17', 2670);

-- 7. Sale Items
INSERT INTO sale_items (sale_id, item_number, item_name, unit_price, quantity, total_price) VALUES
(1, 1, 'First Bag', 1600, 5, 8000),
(2, 2, 'Leather Bag', 2341, 2, 4682),
(3, 4, 'Travel Bag', 1234, 2, 2468),
(3, 3, 'Premium Wallet', 890, 1, 890);
