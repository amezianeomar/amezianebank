-- Create the Database
CREATE DATABASE IF NOT EXISTS mini_bank_db;
USE mini_bank_db;

-- Users Table (Handles both Clients and Admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Accounts Table (Strict financials tied to users)
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_number VARCHAR(20) NOT NULL UNIQUE,
    balance DECIMAL(15, 2) DEFAULT 0.00,
    status ENUM('active', 'frozen', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Audit Logs Table (Logging global user actions)
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    device_info VARCHAR(255) DEFAULT 'Unknown',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- user_id can be null (e.g., failed login for unknown email)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Transaction Receipts Table (Utilizes MySQL native JSON for NoSQL-like metadata storage)
CREATE TABLE IF NOT EXISTS transaction_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id VARCHAR(50) NOT NULL UNIQUE,
    type VARCHAR(50) NOT NULL,
    account_number VARCHAR(20) NOT NULL,
    amount_deducted DECIMAL(15, 2) NOT NULL,
    status ENUM('completed', 'failed', 'pending') DEFAULT 'completed',
    metadata JSON NOT NULL, -- The crucial JSON column
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Admin Seed (Password: admin123 hashed natively via script)
-- Note: Replace password hash generation on a live system, using standard cost parameters.
INSERT IGNORE INTO users (id, first_name, last_name, email, password_hash, role) 
VALUES (1, 'Super', 'Admin', 'admin@amezianetours.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'admin');

-- Master Admin Portfolio Baseline (The $1B request)
INSERT IGNORE INTO accounts (user_id, account_number, balance, status) 
VALUES (1, 'MA-100000001', 1000000000.00, 'active');