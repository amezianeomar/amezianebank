<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../client/client_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/create_client.php');
    exit;
}

require_once '../config/database.php';

$first_name = sanitizeInput($_POST['first_name'] ?? '');
$last_name = sanitizeInput($_POST['last_name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$initial_deposit = filter_input(INPUT_POST, 'initial_deposit', FILTER_VALIDATE_FLOAT);

if (!$first_name || !$last_name || !$email || !$password || $initial_deposit === false || $initial_deposit < 0) {
    $_SESSION['error_message'] = "Please provide valid information for all fields.";
    header('Location: ../admin/create_client.php');
    exit;
}

$account_number = 'MA-' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $mysql_pdo->beginTransaction();

    // 1. Insert User
    $stmtUser = $mysql_pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (:fn, :ln, :email, :pw, 'client')");
    $stmtUser->bindParam(':fn', $first_name, PDO::PARAM_STR);
    $stmtUser->bindParam(':ln', $last_name, PDO::PARAM_STR);
    $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtUser->bindParam(':pw', $password_hash, PDO::PARAM_STR);
    $stmtUser->execute();
    
    $user_id = $mysql_pdo->lastInsertId();

    // 2. Insert Account
    $stmtAcc = $mysql_pdo->prepare("INSERT INTO accounts (user_id, account_number, balance) VALUES (:uid, :acc_no, :bal)");
    $stmtAcc->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtAcc->bindParam(':acc_no', $account_number, PDO::PARAM_STR);
    $stmtAcc->bindParam(':bal', $initial_deposit, PDO::PARAM_STR); 
    $stmtAcc->execute();

    // 3. Insert Audit Log
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $actionMsg = "CREATED CLIENT: $email | ACCT: $account_number | DEP: \$$initial_deposit";
    $stmtAudit = $mysql_pdo->prepare("INSERT INTO audit_logs (user_id, action, ip_address) VALUES (:uid, :action, :ip)");
    $admin_id = $_SESSION['user_id'];
    $stmtAudit->bindParam(':uid', $admin_id, PDO::PARAM_INT);
    $stmtAudit->bindParam(':action', $actionMsg, PDO::PARAM_STR);
    $stmtAudit->bindParam(':ip', $ip_address, PDO::PARAM_STR);
    $stmtAudit->execute();

    // Commit
    $mysql_pdo->commit();
    $_SESSION['success_message'] = "Client $first_name $last_name created successfully with Account Number: $account_number";


} catch (PDOException $e) {
    if ($mysql_pdo->inTransaction()) {
        $mysql_pdo->rollBack();
    }
    
    if ($e->getCode() == 23000) { 
        $_SESSION['error_message'] = "A user with this email already exists.";
    } else {
        error_log("DB Error creating client: " . $e->getMessage());
        $_SESSION['error_message'] = "A database error occurred. Please check logs.";
    }
}

header('Location: ../create_client.php');
exit;
