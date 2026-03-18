<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] !== 'admin') {
    header('Location: client_dashboard.php');
    exit;
}

require_once '../config/database.php';

// Prepare data for the dashboard
$users_list = [];
$total_system_balance = 0.00;
$audit_trail_logs = [];

try {
    // 1. Fetch Users & Balances via JOIN
    $stmt = $mysql_pdo->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email, u.role, 
               IFNULL(a.account_number, 'N/A') as account_number, 
               IFNULL(a.balance, 0.00) as balance, 
               IFNULL(a.status, 'N/A') as status
        FROM users u
        LEFT JOIN accounts a ON u.id = a.user_id
        ORDER BY u.created_at DESC
    ");
    $stmt->execute();
    $users_list = $stmt->fetchAll();

    foreach ($users_list as $u) {
        $total_system_balance += (float)$u['balance'];
    }

    // 2. Fetch Global Audit Trail
    $stmtAudit = $mysql_pdo->prepare("
        SELECT a.id, a.action, a.ip_address, a.device_info, a.created_at, 
               u.email as user_email
        FROM audit_logs a
        LEFT JOIN users u ON a.user_id = u.id
        ORDER BY a.created_at DESC
        LIMIT 50
    ");
    $stmtAudit->execute();
    $audit_trail_logs = $stmtAudit->fetchAll();

} catch (Exception $e) {
    error_log("Admin Dashboard Error: " . $e->getMessage());
    $error_msg = "An error occurred retrieving dashboard data.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Admin Core - Mini Banking System</title>
    <!-- Bootstrap 5 Grid CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fintech 2026 CSS -->
    <link href="../assets/style.css" rel="stylesheet">
    <style>
        .admin-stat {
            font-size: 2.5rem;
            font-family: var(--font-heading);
            font-weight: 800;
            background: linear-gradient(to right, #10b981, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-glow {
            box-shadow: 0 4px 30px rgba(0, 242, 254, 0.1);
        }
        
        .table-responsive {
            border-radius: 16px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass mb-4 py-3">
    <div class="container">
        <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center" style="letter-spacing: 1px;" href="admin_dashboard.php">
            <img src="../assets/ameziane_logo.png" height="30" class="me-2" alt="Ameziane Logo">
            Ameziane<span class="text-white-50 ms-2 fs-6 fw-normal" style="letter-spacing: 2px;">Wealth Admin</span>
        </a>
        <div class="d-flex gx-4 align-items-center">
            <span class="me-4 text-white-50 font-body" style="letter-spacing: 1px; font-size: 0.85rem;">ADVISOR: <span class="text-white fw-bold"><?= strtoupper(htmlspecialchars($_SESSION['first_name'])); ?></span></span>
            <a href="../actions/logout.php" class="btn-glass px-3 py-1 text-decoration-none" style="font-size: 0.85rem; border-color: rgba(255,255,255,0.2);">SECURE LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger font-body mb-4" style="border-radius: 10px;">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- System Stats -->
        <div class="col-xl-3 col-lg-4">
            <div class="glass-card h-100 p-4 d-flex flex-column justify-content-center text-center bg-white">
                <h6 class="text-muted-glass text-uppercase letter-spacing-1 mb-3 font-body fw-bold" style="font-size: 0.8rem;">Assets Under Management (AUM)</h6>
                <div class="mb-4" style="font-size: 2.2rem; font-family: var(--font-heading); font-weight: 700; color: var(--primary-navy);">$<?= number_format($total_system_balance, 2) ?></div>
                <div class="mt-auto">
                    <a href="create_client.php" class="btn-gold w-100 d-block text-decoration-none py-2 text-uppercase fs-6">Initialize Portfolio</a>
                </div>
            </div>
        </div>

        <!-- Master User List -->
        <div class="col-xl-9 col-lg-8">
            <div class="glass-panel h-100 overflow-hidden">
                <div class="d-flex justify-content-between align-items-center p-4 border-glass-bottom bg-white">
                    <h5 class="mb-0 font-heading fw-bold" style="color: var(--primary-navy);">Client Portfolio Matrix</h5>
                    <span class="badge border bg-light text-secondary font-body"><?= count($users_list) ?> ACTIVE ACCOUNTS</span>
                </div>
                <div class="p-0 table-responsive border-0 m-0 no-scrollbar bg-white" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-glass table-hover mb-0">
                        <thead style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th class="ps-4">Entity ID</th>
                                <th>Primary Entity</th>
                                <th>Classification</th>
                                <th>Routing #</th>
                                <th>Capital Ledger</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_list as $u): ?>
                            <tr>
                                <td class="ps-4 text-muted-glass font-monospace" style="font-size:0.85rem;">C-<?= str_pad($u['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div class="fw-bold text-dark mb-1" style="font-family: var(--font-heading);"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></div>
                                    <div class="text-muted-glass" style="font-size: 0.8rem;"><?= htmlspecialchars($u['email']) ?></div>
                                </td>
                                <td>
                                    <?php if($u['role'] === 'admin'): ?>
                                        <span class="badge bg-dark text-white rounded-1 px-2 py-1 font-body fw-normal" style="font-size: 0.7rem;">ADVISOR</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border rounded-1 px-2 py-1 font-body fw-normal" style="font-size: 0.7rem;">CLIENT</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="font-monospace text-muted-glass px-2 py-1 rounded" style="font-size:0.85rem; background: #f8f9fa; border: 1px solid #e2e8f0;"><?= htmlspecialchars($u['account_number']) ?></span></td>
                                <td class="fw-bold" style="color: var(--primary-navy); font-family: var(--font-body); font-size: 1.1rem;">$<?= number_format((float)$u['balance'], 2) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($u['status'] === 'active'): ?>
                                            <div class="rounded-circle me-2" style="width: 6px; height: 6px; background-color: var(--success-green);"></div>
                                            <span class="text-secondary small fw-bold" style="font-size:0.75rem;">ACTIVE</span>
                                        <?php else: ?>
                                            <div class="rounded-circle me-2" style="width: 6px; height: 6px; background-color: var(--danger-red);"></div>
                                            <span class="text-secondary small fw-bold" style="font-size:0.75rem;">CLOSED</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($users_list)): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted-glass">Matrix Empty.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Audit Trail -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="glass-panel overflow-hidden">
                <div class="d-flex align-items-center p-4 border-glass-bottom" style="background: rgba(0, 242, 254, 0.03);">
                    <div class="me-3">
                        <svg xmlns="http://www.w3.org/-w3.org/2000/svg" width="24" height="24" fill="var(--electric-blue)" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5zm8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                        </svg>
                    </div>
                    <h5 class="mb-0 text-white font-heading fw-semibold">Global Transaction Immutable Ledger</h5>
                </div>
                
                <div class="table-responsive m-0 no-scrollbar" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-glass table-sm mb-0">
                        <thead style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th class="ps-4">UTC TIMESTAMP</th>
                                <th>EVENT LOG</th>
                                <th>ACTOR IDENTITY</th>
                                <th>IP VECTOR</th>
                                <th>DEVICE SIGNATURE</th>
                            </tr>
                        </thead>
                        <tbody class="font-body" style="font-size: 0.85rem;">
                            <?php foreach ($audit_trail_logs as $log): ?>
                            <tr>
                                <td class="ps-4 text-muted-glass font-monospace"><?= htmlspecialchars($log['created_at']) ?></td>
                                <td><span class="text-info fw-semibold"><?= htmlspecialchars($log['action']) ?></span></td>
                                <td class="text-white opacity-75"><?= htmlspecialchars($log['user_email'] ?? 'System/Unknown') ?></td>
                                <td class="text-muted-glass font-monospace"><?= htmlspecialchars($log['ip_address']) ?></td>
                                <td class="text-muted-glass" style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?= htmlspecialchars($log['device_info']) ?>">
                                    <?= htmlspecialchars($log['device_info']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($audit_trail_logs)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted-glass">Ledger Empty.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
