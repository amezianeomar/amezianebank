<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

try {
    require_once '../config/database.php';
} catch (Exception $e) {
    $_SESSION['error_message'] = "System Error: Unable to connect to the database.";
    header('Location: login.php');
    exit;
}

$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error_message'] = "Please provide both email and password.";
    header('Location: login.php');
    exit;
}

try {
    // 1. Authenticate user via Prepared Statement
    $stmt = $mysql_pdo->prepare("SELECT id, email, password_hash, role, first_name, last_name FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        
        // 2. Log login success to MySQL Audit Logs
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        try {
            $stmtLog = $mysql_pdo->prepare("INSERT INTO audit_logs (user_id, action, ip_address, device_info) VALUES (:uid, 'LOGIN_SUCCESS', :ip, :device)");
            $stmtLog->bindParam(':uid', $user['id'], PDO::PARAM_INT);
            $stmtLog->bindParam(':ip', $ip_address, PDO::PARAM_STR);
            $stmtLog->bindParam(':device', $device_info, PDO::PARAM_STR);
            $stmtLog->execute();
        } catch (Exception $dbEx) {
            error_log("Failed to insert audit log: " . $dbEx->getMessage());
        }

        // Restrict this portal to clients only
        if ($user['role'] === 'admin') {
            $_SESSION['error_message'] = "Administrative accounts cannot log in via the Client Portal. Please use the Administrative Terminal.";
            header('Location: login.php');
            exit;
        }

        // Login successful (client)
        header('Location: ../client/client_dashboard.php');
        exit;
    } else {
        // Login failed
        $_SESSION['error_message'] = "Invalid email or password.";
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $action_msg = "LOGIN_FAILED (Attempt for: " . substr($email, 0, 50) . ")";
        
        try {
            $stmtLog = $mysql_pdo->prepare("INSERT INTO audit_logs (action, ip_address, device_info) VALUES (:action, :ip, :device)");
            $stmtLog->bindParam(':action', $action_msg, PDO::PARAM_STR);
            $stmtLog->bindParam(':ip', $ip_address, PDO::PARAM_STR);
            $stmtLog->bindParam(':device', $device_info, PDO::PARAM_STR);
            $stmtLog->execute();
        } catch (Exception $e) {}
        
        header('Location: login.php');
        exit;
    }

} catch (PDOException $e) {
    error_log("Login DB Error: " . $e->getMessage());
    $_SESSION['error_message'] = "A server error occurred during login. Please try again later.";
    header('Location: login.php');
    exit;
}
