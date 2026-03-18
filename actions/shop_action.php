<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] === 'admin') {
    header('Location: ../admin/admin_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../shop.php');
    exit;
}

require_once '../config/database.php';

$user_id = $_SESSION['user_id'];
$type = $_POST['type'] ?? '';
$asset_name = sanitizeInput($_POST['asset_name'] ?? '');
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$price_per_unit = filter_input(INPUT_POST, 'price_per_unit', FILTER_VALIDATE_FLOAT);

if (!$type || !$asset_name || $quantity === false || $quantity <= 0 || $price_per_unit === false || $price_per_unit <= 0) {
    $_SESSION['error_message'] = "Invalid purchase parameters.";
    header('Location: ../shop.php');
    exit;
}

$total_cost = $quantity * $price_per_unit;

try {
    // 1. Getting buyer account
    $stmtAcc = $mysql_pdo->prepare("SELECT account_number, balance FROM accounts WHERE user_id = :uid LIMIT 1");
    $stmtAcc->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtAcc->execute();
    $buyer = $stmtAcc->fetch();
    
    if (!$buyer) {
         $_SESSION['error_message'] = "Your account was not found.";
         header('Location: ../shop.php');
         exit;
    }
    
    $buyer_account = $buyer['account_number'];
    
    if ($buyer['balance'] < $total_cost) {
         $_SESSION['error_message'] = "Insufficient funds. You need $" . number_format($total_cost, 2) . " to complete this purchase.";
         header('Location: ../shop.php');
         exit;
    }

    // --- ALL MYSQL PDO TRANSACTION ---
    $mysql_pdo->beginTransaction(); 
    
    // 1. Deduct from buyer
    $stmtDeduct = $mysql_pdo->prepare("UPDATE accounts SET balance = balance - :cost WHERE account_number = :acc");
    $stmtDeduct->bindParam(':cost', $total_cost, PDO::PARAM_STR);
    $stmtDeduct->bindParam(':acc', $buyer_account, PDO::PARAM_STR);
    $stmtDeduct->execute();

    // 2. Insert Audit Log
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $actionMsg = "PURCHASED $quantity x $asset_name";
    $stmtAudit = $mysql_pdo->prepare("INSERT INTO audit_logs (user_id, action, ip_address) VALUES (:uid, :action, :ip)");
    $stmtAudit->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtAudit->bindParam(':action', $actionMsg, PDO::PARAM_STR);
    $stmtAudit->bindParam(':ip', $ip_address, PDO::PARAM_STR);
    $stmtAudit->execute();

    // 3. Generate JSON Receipt and Insert natively
    $referenceId = "REC-" . strtoupper(bin2hex(random_bytes(6)));
    $receiptMetadata = json_encode([
        'item' => [
            'name' => $asset_name,
            'quantity' => $quantity,
            'price_per_unit' => $price_per_unit,
            'total' => $total_cost
        ],
        'vendor' => 'Ameziane Internal Systems',
        'ip_address' => $ip_address
    ]);

    $status = 'completed';

    $stmtReceipt = $mysql_pdo->prepare("INSERT INTO transaction_receipts (reference_id, type, account_number, amount_deducted, status, metadata) VALUES (:ref, :type, :acc, :amt, :status, :meta)");
    $stmtReceipt->bindParam(':ref', $referenceId, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':acc', $buyer_account, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':amt', $total_cost, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':meta', $receiptMetadata, PDO::PARAM_STR);
    $stmtReceipt->execute();

    // Commit Transaction!
    $mysql_pdo->commit();
    
    $_SESSION['success_message'] = "Successfully purchased $quantity x $asset_name. Receipt Ref: $referenceId";
    header('Location: ../client/client_dashboard.php');
    exit;

} catch (PDOException $e) {
    if ($mysql_pdo->inTransaction()) {
        $mysql_pdo->rollBack();
    }
    error_log("Shop DB Error: " . $e->getMessage());
    $_SESSION['error_message'] = "A database error occurred during checkout. Course of transaction rolled back.";
    header('Location: ../shop.php');
    exit;
}
