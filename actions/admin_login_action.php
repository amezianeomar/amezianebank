<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_login.php');
    exit;
}

try {
    require_once '../config/database.php';
} catch (Exception $e) {
    $_SESSION['error_message'] = "System Error: Unable to connect to the internal network.";
    header('Location: admin_login.php');
    exit;
}

$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error_message'] = "Please provide your corporate credentials.";
    header('Location: admin_login.php');
    exit;
}

try {
    // 1. Authenticate user via Prepared Statement
    $stmt = $mysql_pdo->prepare("SELECT id, email, password_hash, role, first_name, last_name FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        
        // Block clients from logging into the Internal Gateway
        if ($user['role'] !== 'admin') {
            $_SESSION['error_message'] = "Access Denied: You do not possess corporate administrative clearance.";
            
            // Log this unauthorized access attempt
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $action_msg = "UNAUTHORIZED_ADMIN_ACCESS_ATTEMPT (Client: " . $user['id'] . ")";
            
            try {
                $stmtLog = $mysql_pdo->prepare("INSERT INTO audit_logs (action, ip_address, device_info) VALUES (:action, :ip, :device)");
                $stmtLog->bindParam(':action', $action_msg, PDO::PARAM_STR);
                $stmtLog->bindParam(':ip', $ip_address, PDO::PARAM_STR);
                $stmtLog->bindParam(':device', $device_info, PDO::PARAM_STR);
                $stmtLog->execute();
            } catch (Exception $e) {}
            
            header('Location: admin_login.php');
            exit;
        }

        // Admin Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        
        // Log login success to MySQL Audit Logs
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        try {
            $stmtLog = $mysql_pdo->prepare("INSERT INTO audit_logs (user_id, action, ip_address, device_info) VALUES (:uid, 'ADMIN_LOGIN_SUCCESS', :ip, :device)");
            $stmtLog->bindParam(':uid', $user['id'], PDO::PARAM_INT);
            $stmtLog->bindParam(':ip', $ip_address, PDO::PARAM_STR);
            $stmtLog->bindParam(':device', $device_info, PDO::PARAM_STR);
            $stmtLog->execute();
        } catch (Exception $dbEx) {
            error_log("Failed to insert audit log: " . $dbEx->getMessage());
        }

        header('Location: ../admin/admin_dashboard.php');
        exit;
    } else {
        // Login failed
        $_SESSION['error_message'] = "Invalid credentials or unauthorized terminal access.";
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $action_msg = "ADMIN_LOGIN_FAILED (Attempt for: " . substr($email, 0, 50) . ")";
        
        try {
            $stmtLog = $mysql_pdo->prepare("INSERT INTO audit_logs (action, ip_address, device_info) VALUES (:action, :ip, :device)");
            $stmtLog->bindParam(':action', $action_msg, PDO::PARAM_STR);
            $stmtLog->bindParam(':ip', $ip_address, PDO::PARAM_STR);
            $stmtLog->bindParam(':device', $device_info, PDO::PARAM_STR);
            $stmtLog->execute();
        } catch (Exception $e) {}
        
        header('Location: admin_login.php');
        exit;
    }

} catch (PDOException $e) {
    error_log("Admin Login DB Error: " . $e->getMessage());
    $_SESSION['error_message'] = "A server error occurred during authentication. Please try again later.";
    header('Location: admin_login.php');
    exit;
}
