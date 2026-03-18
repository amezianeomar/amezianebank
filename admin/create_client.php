<?php
require_once '../includes/auth.php';
requireLogin();

if ($_SESSION['role'] !== 'admin') {
    header('Location: client_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initialize Node - Nexus Admin</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fintech 2026 CSS -->
    <link href="../assets/style.css" rel="stylesheet">
    <style>
        .creator-orb {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass mb-4 py-3 shadow-sm bg-white">
    <div class="container">
        <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center" style="letter-spacing: 1px;" href="admin_dashboard.php">
            <img src="../assets/ameziane_logo.png" height="30" class="me-2" alt="Ameziane Logo">
            Ameziane<span class="text-secondary ms-2 fs-6 fw-normal" style="letter-spacing: 2px;">Wealth Admin</span>
        </a>
        
        <div class="ms-auto">
            <a href="admin_dashboard.php" class="btn-glass px-4 py-2 text-decoration-none small">Return to Matrix</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger font-body border-danger border-opacity-50 text-danger mb-4 bg-white" style="border-radius: 4px;">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success font-body border-success border-opacity-50 text-success mb-4 bg-white" style="border-radius: 4px;">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="glass-panel p-5 bg-white shadow-sm">
                <div class="mb-5 text-center">
                    <h3 class="font-heading fw-bold mb-2 text-dark" style="color: var(--primary-navy) !important;">Client Onboarding</h3>
                    <p class="text-secondary mb-0 font-body">Provision secure credentials and allocate initial capital.</p>
                </div>

                <form action="../actions/create_client_action.php" method="POST">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Client Given Name</label>
                            <input type="text" class="form-control form-control-glass bg-light" id="first_name" name="first_name" required autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Client Surname</label>
                            <input type="text" class="form-control form-control-glass bg-light" id="last_name" name="last_name" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Primary Email Address</label>
                        <input type="email" class="form-control form-control-glass bg-light" id="email" name="email" required autocomplete="email">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Initial Password</label>
                        <input type="password" class="form-control form-control-glass bg-light" id="password" name="password" required>
                        <div class="form-text text-secondary mt-2" style="font-size: 0.75rem;">Encrypted natively using Bcrypt standard.</div>
                    </div>

                    <div class="mb-5 border-top pt-4 mt-4 border-subtle">
                        <label for="initial_deposit" class="form-label text-dark">Initial Capital Allocation (USD)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-subtle text-secondary">$</span>
                            <input type="number" class="form-control form-control-glass bg-light" id="initial_deposit" name="initial_deposit" step="0.01" min="0" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn-gold py-3 text-uppercase letter-spacing-1">Provision Client Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
