# Mini Banking System

A realistic, production-grade "Mini Banking System" web application built to demonstrate advanced Full-Stack PHP development, secure database architecture, and professional UI/UX design.

This project was built without heavy frameworks to highlight core competencies in Object-Oriented PHP, strictly secured relational databases (MySQL), and robust transaction handling.

---

## 🌟 Key Features

### 1. Advanced MySQL Architecture & Native JSON
- **Dual Data Handling:** Utilizes strict relational tables for absolute financial truth (Users, Accounts, Balances) alongside MySQL's native `JSON` columns to store complex NoSQL-style metadata (Detailed Receipts, E-commerce Items).
- **Two-Phase Commits:** All financial movements (Transfers, Purchases) are wrapped in strict `PDO::beginTransaction()` blocks. If the balance deduction, addition, or the receipt generation fails at *any* point, a `PDO::rollBack()` reverts the entire financial operation to guarantee data consistency.

### 2. Bulletproof Security
- **Prepared Statements Everywhere:** Every single database query strictly utilizes PDO `bindParam()` to eliminate SQL Injection vulnerabilities.
- **Argon2 Password Hashing:** Uses PHP's modern native `password_hash()` with the robust `PASSWORD_ARGON2ID` algorithm.
- **Strict Error Handling:** The PDO connection is configured with `PDO::ERRMODE_EXCEPTION` to catch and securely log failures without exposing system details to the frontend.
- **Role-Based Routing Guards:** Clean PHP session management separates and protects the Dashboard, Transfer operations, and the Master Admin Panel.

### 3. Professional, Trustworthy UI
- Built with **Bootstrap 5**, featuring a clean, responsive layout consisting of trustworthy blues, crisp whites, and gentle shadows.
- Distinct and intuitive interfaces for both Clients (managing funds) and Admins (system monitoring).

---

## 🚀 Application Workflows

### Client Panel
- **Dashboard:** Instantly view real-time account balances and a chronologically sorted feed of recent account activity parsed directly from MySQL JSON metadata.
- **Secure Transfers:** Move money to other account numbers conditionally based on sufficient funds, triggering the complex Dual-Commit logic.
- **Mock Shop & Investments:** Simulate buying assets (like Tech Index Funds). The system deducts the balance and generates a heavily detailed JSON receipt stored natively in the database.

### Admin Panel
- **Master Overview:** A secure dashboard summarizing the total system balance and listing all registered users alongside their active banking status.
- **Global Audit Trail:** A live, chronological feed tracking every major action (Logins, Client Creation, Purchases) taken by any user across the entire platform.
- **Client Management:** Easily register new clients. The system securely hashes their credentials, seeds their initial deposit, and generates a unique, structured Account Number (e.g., `MA-100200300`).

---

## 🛠️ Tech Stack
- **Frontend:** HTML5, CSS3, Bootstrap 5 (CDN)
- **Backend:** Pure PHP (OOP PDO)
- **Database:** MySQL / MariaDB (Relational + JSON Data Types)
- **Server:** Apache / Nginx (via XAMPP/MAMP for local dev)

---

## ⚙️ How to Setup & Run Locally

1. **Prerequisites:**
   - A local server environment like XAMPP, MAMP, or a native LAMP stack.
   - PHP 8.0+ recommended.
   - MySQL / MariaDB.
   - Ensure the `pdo_mysql` extension is enabled in your `php.ini`.

2. **Database Setup:**
   - Create a new MySQL database named `mini_bank_db`.
   - Import the provided `./database/schema.sql` file. 
   - *Note: The schema automatically seeds a default Master Admin account during creation.*

3. **Launch the App:**
   - Place the project folder into your local web root (e.g., `/htdocs` or `/www`).
   - Navigate to the project directory in your browser (e.g., `http://localhost/mini-bank-system`).

4. **Default Login Credentials:**
   - **Email:** `admin@amezianetours.com`
   - **Password:** `admin123`

---

*Built for demonstration of Senior Full-Stack Architecture patterns.*
