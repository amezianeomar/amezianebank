<?php
require_once 'includes/auth.php';
requireLogin();

if ($_SESSION['role'] === 'admin') {
    header('Location: admin/admin_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Execute Transfer - Nexus Client</title>
    <!-- Bootstrap 5 Grid -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fintech 2026 CSS -->
    <link href="assets/style.css" rel="stylesheet">
    <style>
        .transfer-orb {
            position: fixed;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(79, 172, 254, 0.1) 0%, transparent 60%);
            bottom: -200px;
            left: -200px;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass mb-4 py-3 shadow-sm bg-white">
    <div class="container">
        <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center" style="letter-spacing: 1px;" href="client/client_dashboard.php">
            <img src="assets/ameziane_logo.png" height="30" class="me-2" alt="Ameziane Logo">
            Ameziane<span class="text-secondary ms-2 fs-6 fw-normal" style="letter-spacing: 2px;">Corporate Services</span>
        </a>
        
        <div class="ms-auto">
            <a href="client/client_dashboard.php" class="btn-glass px-4 py-2 text-decoration-none small">Cancel Wire Transfer</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger font-body border-danger border-opacity-50 text-danger mb-4 bg-white" style="border-radius: 4px;">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="glass-panel p-5 bg-white shadow-sm">
                <div class="mb-5 text-center border-bottom pb-4 border-subtle">
                    <h3 class="font-heading fw-bold letter-spacing-1 text-dark" style="color: var(--primary-navy) !important;">Initiate Wire Transfer</h3>
                    <p class="text-secondary mb-0 small font-body">Domestic & International Routing</p>
                </div>

                <form action="actions/transfer_action.php" method="POST">
                    
                    <div class="mb-4">
                        <label for="receiver_account" class="form-label text-dark">Destination Routing Code</label>
                        <input type="text" class="form-control form-control-glass font-monospace fs-5 bg-light" id="receiver_account" name="receiver_account" required placeholder="e.g. C-10000" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label text-dark">Capital Allocation (USD)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-subtle text-dark fs-4 fw-bold">$</span>
                            <input type="number" class="form-control form-control-glass fs-4 fw-bold text-dark bg-light" id="amount" name="amount" step="0.01" min="0.01" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="note" class="form-label text-dark">Transfer Memorandum (Optional)</label>
                        <input type="text" class="form-control form-control-glass font-body bg-light" id="note" name="note" placeholder="Enter secure note..." autocomplete="off">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-gold py-3 fs-6 text-uppercase letter-spacing-1">Execute Transaction</button>
                    </div>
                </form>
                
                <div class="text-center mt-4 pt-3 border-top border-subtle">
                    <p class="text-secondary mb-0 font-body" style="font-size: 0.75rem;">Secured by global 256-bit encryption standards and immutable ledgers.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
