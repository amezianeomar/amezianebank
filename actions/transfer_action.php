<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] === 'admin') {
    header('Location: ../admin/admin_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../transfer.php');
    exit;
}

require_once '../config/database.php';

$user_id = $_SESSION['user_id'];
$receiver_account = sanitizeInput($_POST['receiver_account'] ?? '');
$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
$note = sanitizeInput($_POST['note'] ?? '');

if (!$receiver_account || $amount === false || $amount <= 0) {
    $_SESSION['error_message'] = "Invalid account number or amount.";
    header('Location: ../transfer.php');
    exit;
}

try {
    // 1. Getting sender account number and balance
    $stmtSender = $mysql_pdo->prepare("SELECT id, account_number, balance FROM accounts WHERE user_id = :uid LIMIT 1");
    $stmtSender->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtSender->execute();
    $sender = $stmtSender->fetch();
    
    if (!$sender) {
         $_SESSION['error_message'] = "Your account was not found.";
         header('Location: ../transfer.php');
         exit;
    }
    
    $sender_account = $sender['account_number'];
    
    if ($sender['balance'] < $amount) {
         $_SESSION['error_message'] = "Insufficient funds for this transfer.";
         header('Location: ../transfer.php');
         exit;
    }
    
    if ($sender_account === $receiver_account) {
         $_SESSION['error_message'] = "Cannot transfer money to yourself.";
         header('Location: ../transfer.php');
         exit;
    }

    // Checking if receiver exists
    $stmtReceiver = $mysql_pdo->prepare("SELECT id FROM accounts WHERE account_number = :acc LIMIT 1");
    $stmtReceiver->bindParam(':acc', $receiver_account, PDO::PARAM_STR);
    $stmtReceiver->execute();
    $receiver = $stmtReceiver->fetch();
    
    if (!$receiver) {
         $_SESSION['error_message'] = "Recipient account not found.";
         header('Location: ../transfer.php');
         exit;
    }

    // --- ALL MYSQL PDO TRANSACTION ---
    $mysql_pdo->beginTransaction(); 
    
    // 1. Deduct from sender
    $stmtDeduct = $mysql_pdo->prepare("UPDATE accounts SET balance = balance - :amount WHERE account_number = :acc");
    $stmtDeduct->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmtDeduct->bindParam(':acc', $sender_account, PDO::PARAM_STR);
    $stmtDeduct->execute();
    
    // 2. Add to receiver
    $stmtAdd = $mysql_pdo->prepare("UPDATE accounts SET balance = balance + :amount WHERE account_number = :acc");
    $stmtAdd->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmtAdd->bindParam(':acc', $receiver_account, PDO::PARAM_STR);
    $stmtAdd->execute();

    // 3. Insert Audit Log
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $actionMsg = "TRANSFERRED \$$amount TO $receiver_account";
    $stmtAudit = $mysql_pdo->prepare("INSERT INTO audit_logs (user_id, action, ip_address) VALUES (:uid, :action, :ip)");
    $stmtAudit->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtAudit->bindParam(':action', $actionMsg, PDO::PARAM_STR);
    $stmtAudit->bindParam(':ip', $ip_address, PDO::PARAM_STR);
    $stmtAudit->execute();

    // 4. Generate JSON Receipt and Insert
    $referenceId = "TXN-" . strtoupper(bin2hex(random_bytes(6)));
    $receiptMetadata = json_encode([
        'note' => $note,
        'fee_charged' => 0.00,
        'sender_account' => $sender_account,
        'receiver_account' => $receiver_account,
        'ip_address' => $ip_address
    ]);

    $type = 'money_transfer';
    $status = 'completed';

    $stmtReceipt = $mysql_pdo->prepare("INSERT INTO transaction_receipts (reference_id, type, account_number, amount_deducted, status, metadata) VALUES (:ref, :type, :acc, :amt, :status, :meta)");
    $stmtReceipt->bindParam(':ref', $referenceId, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':acc', $sender_account, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':amt', $amount, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmtReceipt->bindParam(':meta', $receiptMetadata, PDO::PARAM_STR); // Inserted natively as JSON string
    $stmtReceipt->execute();

    // Everything Succeeded! Commit transaction.
    $mysql_pdo->commit();
    
    $_SESSION['success_message'] = "Successfully transferred $" . number_format($amount, 2) . " to $receiver_account. Ref: $referenceId";
    header('Location: ../client/client_dashboard.php');
    exit;

} catch (PDOException $e) {
    if ($mysql_pdo->inTransaction()) {
        $mysql_pdo->rollBack(); // Catch-all abort ensures completely atomic transfers
    }
    error_log("Transfer DB Error: " . $e->getMessage());
    $_SESSION['error_message'] = "System Error: The transaction could not be permanently audited. The transfer has been completely rolled back.";
    header('Location: ../transfer.php');
    exit;
}
