# Ameziane for Savings and Investments

A realistic, production-grade "Mini Banking System" web application built to demonstrate advanced Full-Stack PHP development, secure database architecture, professional MVC-like routing, and an ultra-premium institutional UI/UX design.

This project was built without heavy frameworks to highlight core competencies in Object-Oriented PHP, strictly secured relational databases (MySQL), robust transaction handling, and clean separated-concerns architecture.

---

## 🌟 Key Features

### 1. Enterprise Routing Structure (MVC-Style)
- **Separated Concerns:** The file system strictly separates the public-facing landing page, frontend user views (`admin/`, `client/`), backend controllers (`actions/`), database configurations (`config/`), and session middleware (`includes/`).
- **Dual Gateway Security:** Authentication is completely split. Clients use the public portal (`actions/login.php`), while authorized bank employees use a hidden, highly secure internal gateway (`actions/admin_login.php`). Cross-contamination of roles is explicitly blocked at the gateway level.

### 2. Advanced MySQL Architecture & Native JSON
- **Dual Data Handling:** Utilizes strict relational tables for absolute financial truth (Users, Accounts, Balances) alongside MySQL's native `JSON` columns to store complex NoSQL-style metadata (Detailed Receipts, Asset Acquisitions).
- **Two-Phase Commits:** All financial movements (Wire Transfers, Purchases) are wrapped in strict `PDO::beginTransaction()` blocks. If the balance deduction, addition, or the receipt generation fails at *any* point, a `PDO::rollBack()` reverts the entire financial operation to guarantee data consistency.

### 3. Bulletproof Security
- **Prepared Statements Everywhere:** Every single database query strictly utilizes PDO `bindParam()` to eliminate SQL Injection vulnerabilities.
- **Argon2 Password Hashing:** Uses PHP's modern native `password_hash()` with the robust `PASSWORD_ARGON2` algorithm.
- **Strict Error Handling:** The PDO connection catches and securely logs failures without exposing system details to the frontend.
- **Global Audit Trail:** Every sensitive action (logins, client creation, transfers, unauthorized access attempts) is logged securely in the database.

### 4. Ultra-Premium Institutional UI
- Built with **Bootstrap 5** and custom CSS, featuring a pristine, high-end "Wall Street" banking aesthetic. 
- Utilizes aristocratic typography (`Playfair Display` serif) and a regal color palette (Deep Navy and Wealth Gold) to exude trust and generational wealth management.
- Includes dynamic widgets for market index tracking and portfolio allocation visualization.

---

## 🚀 Application Workflows

### Client Portal
- **Dashboard:** Instantly view real-time portfolio capital, internal asset allocation, and a chronologically sorted feed of recent account activity parsed directly from MySQL JSON metadata.
- **Wire Transfers:** Move money to other account numbers conditionally based on sufficient funds, triggering complex dual-commit logic.
- **Corporate Services (Shop):** Acquire equity funds and treasury bonds. The system deducts the balance and generates a highly detailed JSON receipt.

### Administrative Terminal
- **Assets Under Management:** A secure dashboard summarizing the total system liquidity ($1 Billion baseline) and listing all registered clients in a professional matrix.
- **Corporate Compliance Ledger:** A live, chronological feed tracking every major action taken by any user across the entire platform.
- **Client Onboarding:** Easily register new clients. The system securely hashes their credentials, seeds their initial capital, and generates a structured Account Number (e.g., `MA-100200300`).

---

## 🛠️ Tech Stack
- **Frontend:** HTML5, CSS3, Bootstrap 5 (CDN), Google Fonts
- **Backend:** Pure PHP (OOP PDO, MVC-Style Routing)
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
   - Import the provided `database/schema.sql` file. 
   - *Note: The schema automatically seeds a default Master Admin account during creation.*

3. **Launch the App:**
   - Place the project folder into your local web root.
   - Navigate to the project directory in your browser (e.g., `http://localhost/mini-bank-system/index.php`).

4. **Default Internal Gateway Credentials (Admin):**
   - **URL:** Access via the subtle footer link on the home page or go directly to `actions/admin_login.php`.
   - **Email:** `admin@amezianetours.com`
   - **Password:** `admin123`

---

*Built for demonstration of Senior Full-Stack Architecture patterns and premium UI design.*
