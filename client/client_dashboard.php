<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

require_once '../config/database.php';

$user_id = $_SESSION['user_id'];
$account_info = null;
$recent_activity = [];

try {
    // 1. Fetch Account Balance
    $stmt = $mysql_pdo->prepare("SELECT account_number, balance, status FROM accounts WHERE user_id = :uid LIMIT 1");
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $account_info = $stmt->fetch();

    if ($account_info) {
        $acc_number = $account_info['account_number'];
        
        // 2. Fetch Detailed Transaction Logs & Receipts from MySQL native JSON
        $stmtActivity = $mysql_pdo->prepare("
            SELECT reference_id, type, account_number, amount_deducted, status, metadata, created_at 
            FROM transaction_receipts 
            WHERE account_number = :acc 
               OR JSON_EXTRACT(metadata, '$.sender_account') = :acc 
               OR JSON_EXTRACT(metadata, '$.receiver_account') = :acc
            ORDER BY created_at DESC 
            LIMIT 20
        ");
        $stmtActivity->bindParam(':acc', $acc_number, PDO::PARAM_STR);
        $stmtActivity->execute();
        
        while ($row = $stmtActivity->fetch()) {
            $row['metadata'] = json_decode($row['metadata'], true);
            $recent_activity[] = $row;
        }
    }

} catch (Exception $e) {
    error_log("Client Dashboard Error: " . $e->getMessage());
    $error_msg = "An error occurred fetching your account details.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ameziane Client Portal</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Institutional CSS -->
    <link href="../assets/style.css" rel="stylesheet">
    <style>
        body { background-color: var(--bg-page); }
        .balance-value { font-size: 3rem; font-family: var(--font-heading); font-weight: 700; color: var(--primary-navy); letter-spacing: -1px;}
        .activity-row {
            transition: all 0.2s ease;
            cursor: default;
        }
        .activity-row:hover {
            background-color: #f8f9fa !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass mb-4 shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center" style="letter-spacing: 1px;" href="client_dashboard.php">
            <img src="../assets/ameziane_logo.png" height="30" class="me-2" alt="Ameziane Logo">
            Ameziane<span class="text-white-50 ms-2 fs-6 fw-normal" style="letter-spacing: 2px;">Corporate Services</span>
        </a>
        
        <div class="ms-auto d-flex align-items-center">
            <span class="me-4 text-white-50 font-body" style="letter-spacing: 1px; font-size: 0.85rem;">CLIENT: <span class="text-white fw-bold"><?= strtoupper(htmlspecialchars($_SESSION['first_name'])); ?></span></span>
            <a href="../actions/logout.php" class="btn-glass text-white px-3 py-1 text-decoration-none" style="font-size: 0.85rem; border-color: rgba(255,255,255,0.2);">SECURE LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success bg-white border-success border-opacity-50 text-success font-body mb-4" style="border-radius: 4px;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger bg-white border-danger border-opacity-50 text-danger font-body mb-4" style="border-radius: 4px;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Account Overview -->
        <div class="col-lg-4">
            <div class="glass-card mb-4 bg-white">
                <div class="card-body p-5">
                    <h6 class="text-secondary text-uppercase mb-2 font-body fw-bold" style="letter-spacing: 1px; font-size:0.8rem;">Available Portfolio Capital</h6>
                    <?php if ($account_info): ?>
                        <div class="balance-value mb-4">$<?= number_format((float)$account_info['balance'], 2) ?></div>
                        
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 border-subtle">
                            <div>
                                <span class="text-secondary d-block small mb-1 fw-bold">ROUTING NUMBER</span>
                                <strong class="text-dark font-monospace" style="font-size:0.9rem;"><?= htmlspecialchars($account_info['account_number']) ?></strong>
                            </div>
                            <span class="badge border <?= $account_info['status'] === 'active' ? 'bg-success text-white' : 'bg-danger text-white' ?> px-2 py-1 fw-normal" style="font-size:0.75rem;">
                                <?= strtoupper(htmlspecialchars($account_info['status'])) ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="balance-value text-muted mb-4">$0.00</div>
                        <p class="mt-3 text-danger font-body fw-bold">ERROR: PORTFOLIO UNLINKED.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="d-grid gap-3">
                <a href="transfer.php" class="btn-gold text-center text-decoration-none py-3 letter-spacing-1 text-uppercase">Initiate Wire Transfer</a>
                <a href="shop.php" class="btn-glass bg-white text-center text-decoration-none py-3 letter-spacing-1 text-uppercase">Asset Acquisition</a>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-lg-8">
            <div class="glass-panel h-100 overflow-hidden bg-white">
                <div class="p-4 border-glass-bottom d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0 font-heading fw-bold" style="color: var(--primary-navy);">Transaction Ledger</h5>
                    <span class="badge bg-white border text-secondary font-monospace" style="font-size: 0.7rem;">UP TO DATE</span>
                </div>
                <div class="p-0 table-responsive border-0 m-0 no-scrollbar bg-white" style="max-height: 550px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($recent_activity)): ?>
                            <li class="list-group-item text-center py-5 border-0 text-secondary font-body bg-white">No recent portfolio allocations.</li>
                        <?php else: ?>
                            <?php foreach ($recent_activity as $act): 
                                $timeStr = date('M j, Y - H:i', strtotime($act['created_at'])) . ' EST';
                                
                                $isPurchase = ($act['type'] ?? '') === 'stock_purchase' || ($act['type'] ?? '') === 'ecommerce_purchase';
                                $isTransfer = ($act['type'] ?? '') === 'money_transfer';
                                
                                $title = htmlspecialchars($act['type'] ?? 'Transaction');
                                $amountStr = "";
                                
                                if ($isPurchase) {
                                    $amountStr = "-$" . number_format((float)$act['amount_deducted'], 2);
                                } elseif ($isTransfer) {
                                    $meta = $act['metadata'];
                                    if(isset($meta['sender_account']) && $account_info && $meta['sender_account'] === $account_info['account_number']) {
                                        $amountStr = "-$" . number_format((float)$act['amount_deducted'], 2);
                                    } else {
                                        $amountStr = "+$" . number_format((float)$act['amount_deducted'], 2);
                                    }
                                }
                                
                                $status = htmlspecialchars($act['status'] ?? 'completed');
                            ?>
                            <li class="list-group-item border-glass-bottom px-4 py-4 activity-row bg-white">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="w-75">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="text-dark fw-bold font-heading fs-5 me-3" style="color: var(--primary-navy) !important;"><?= strtoupper(str_replace('_', ' ', $title)) ?></span>
                                            <span class="badge border <?= $status === 'completed' ? 'text-success bg-light' : 'text-warning bg-light' ?>" style="font-size:0.65rem;"><?= strtoupper($status) ?></span>
                                        </div>
                                        <div class="text-secondary font-monospace small mb-3" style="font-size:0.8rem;">
                                            <span class="text-dark fw-bold">DATE:</span> <?= $timeStr ?> &nbsp;&nbsp;|&nbsp;&nbsp; 
                                            <span class="text-dark fw-bold">REF:</span> <?= htmlspecialchars($act['reference_id'] ?? 'N/A') ?>
                                        </div>
                                        
                                        <?php if ($isTransfer && isset($act['metadata'])): ?>
                                            <div class="font-body small border rounded p-2 bg-light d-inline-block">
                                                <span class="text-secondary fw-bold">ORIGIN:</span> <span class="text-dark mx-2 font-monospace"><?= htmlspecialchars($act['metadata']['sender_account'] ?? 'N/A') ?></span>
                                                <span class="text-secondary fw-bold ms-2">DEST:</span> <span class="text-dark ms-2 font-monospace"><?= htmlspecialchars($act['metadata']['receiver_account'] ?? 'N/A') ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($isPurchase && isset($act['metadata'])): ?>
                                            <div class="font-body small border rounded p-3 mt-2 bg-light" style="max-width: 100%; overflow-x: auto;">
                                                <span class="text-dark fw-bold d-block mb-2 font-monospace" style="font-size:0.75rem;">> ASSET_METADATA</span>
                                                <pre class="mb-0 text-secondary" style="font-size:0.75rem;"><?= htmlspecialchars(json_encode($act['metadata'], JSON_PRETTY_PRINT)) ?></pre>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fs-4 font-heading fw-bold <?= strpos($amountStr, '+') !== false ? 'text-success' : 'text-danger' ?>">
                                        <?= $amountStr ?>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Market Performance -->
        <div class="col-lg-4">
            <div class="glass-panel p-4 bg-white shadow-sm h-100">
                <h6 class="text-secondary text-uppercase mb-3 font-body fw-bold" style="letter-spacing: 1px; font-size:0.8rem;">Market Index Overview</h6>
                <div class="d-flex justify-content-between align-items-center border-bottom border-subtle pb-2 mb-2">
                    <span class="text-dark fw-bold font-monospace">S&P 500</span>
                    <span class="text-success fw-bold">+1.24%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom border-subtle pb-2 mb-2">
                    <span class="text-dark fw-bold font-monospace">NASDAQ</span>
                    <span class="text-success fw-bold">+1.58%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark fw-bold font-monospace">DOW JONES</span>
                    <span class="text-danger fw-bold">-0.32%</span>
                </div>
            </div>
        </div>
        <!-- Asset Allocation -->
        <div class="col-lg-4">
            <div class="glass-panel p-4 bg-white shadow-sm h-100">
                <h6 class="text-secondary text-uppercase mb-3 font-body fw-bold" style="letter-spacing: 1px; font-size:0.8rem;">Portfolio Allocation</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small font-body mb-1">
                        <span class="text-dark fw-bold">Equities</span>
                        <span class="text-secondary">65%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small font-body mb-1">
                        <span class="text-dark fw-bold">Fixed Income</span>
                        <span class="text-secondary">25%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%; background-color: var(--primary-navy);"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between small font-body mb-1">
                        <span class="text-dark fw-bold">Cash Reserves</span>
                        <span class="text-secondary">10%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 10%; background-color: var(--wealth-gold);"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wealth Advisor -->
        <div class="col-lg-4">
            <div class="glass-panel p-4 bg-white shadow-sm h-100">
                <h6 class="text-secondary text-uppercase mb-3 font-body fw-bold" style="letter-spacing: 1px; font-size:0.8rem;">Dedicated Wealth Advisor</h6>
                <div class="d-flex align-items-center mb-3 mt-4">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark fw-bold me-3 border border-subtle" style="width: 48px; height: 48px; font-size: 1.2rem; color: var(--primary-navy) !important;">
                        AR
                    </div>
                    <div>
                        <h6 class="mb-0 font-heading fw-bold text-dark">Alexander Rothschild</h6>
                        <span class="text-secondary small font-body">Managing Director</span>
                    </div>
                </div>
                <div class="d-grid mt-4">
                    <a href="#" class="btn btn-outline-secondary btn-sm font-body text-uppercase fw-bold letter-spacing-1 py-2">Schedule Consultation</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
