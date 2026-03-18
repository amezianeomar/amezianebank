<?php
require_once '../includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'client_dashboard.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ameziane for Savings and Investments</title>
    <!-- Bootstrap 5 Grid System -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Institutional CSS -->
    <link href="../assets/style.css" rel="stylesheet">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: url('https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover no-repeat fixed;
            position: relative;
        }
        
        /* Dark overlay for the background image to make the white panel pop */
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: rgba(10, 25, 47, 0.85); /* Deep Navy over the image */
            z-index: 0;
        }

        .login-panel {
            width: 100%;
            max-width: 480px;
            z-index: 10;
            padding: 4rem 3rem;
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--primary-navy);
            line-height: 1.2;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="glass-panel login-panel">
            
            <div class="text-center mb-5">
                <div class="brand-logo text-uppercase d-flex align-items-center justify-content-center">
                    <img src="../assets/ameziane_logo.png" height="45" class="me-3 drop-shadow-sm" alt="Ameziane Logo">
                    Ameziane
                </div>
                <p class="font-body text-uppercase text-muted-glass mb-1" style="font-size: 0.85rem; letter-spacing: 2px;">Savings & Investments</p>
                <div style="width: 40px; height: 3px; background: var(--wealth-gold); margin: 15px auto;"></div>
                <p class="font-heading mt-3 text-secondary" style="font-style: italic;">Private Client Portal</p>
            </div>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger font-body mb-4" style="border-radius: 2px; border: 1px solid var(--danger-red); background-color: #fef2f2; color: var(--danger-red);">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="login_action.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="form-label">Client ID / Email</label>
                    <input type="email" class="form-control form-control-glass" id="email" name="email" required autocomplete="email" placeholder="Enter your registered email">
                </div>
                
                <div class="mb-5">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-glass" id="password" name="password" required placeholder="Enter your secure password">
                </div>
                
                <div class="d-grid mb-4">
                    <button type="submit" class="btn-gold w-100 fs-6">Secure Sign In</button>
                </div>
            </form>

            <div class="text-center mt-4 border-top border-glass-bottom pt-4">
                <p class="text-secondary small font-body" style="font-size: 0.75rem;">
                    Wealth Management & Corporate Advisory<br>
                    &copy; 2026 Ameziane Group. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
