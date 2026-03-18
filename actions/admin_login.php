<?php
require_once '../includes/auth.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? '../admin/admin_dashboard.php' : '../client/client_dashboard.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ameziane Internal - Admin Gateway</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            background-color: var(--primary-navy);
            background-image: radial-gradient(circle at top right, rgba(212, 175, 55, 0.05), transparent 40%),
                              radial-gradient(circle at bottom left, rgba(212, 175, 55, 0.05), transparent 40%);
        }
        .admin-login-box {
            background: #ffffff;
            border-radius: 0;
            padding: 4rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            border-top: 4px solid var(--wealth-gold);
        }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center">

    <div class="admin-login-box m-3">
        
        <div class="text-center mb-5 border-bottom pb-4">
            <img src="../assets/ameziane_logo.png" alt="Ameziane Crest" height="60" class="mb-3">
            <h2 class="font-heading mb-1" style="color: var(--primary-navy);">Internal Gateway</h2>
            <p class="font-body text-uppercase mb-0" style="letter-spacing: 2px; font-size: 0.8rem; color: #6c757d;">Authorized Personnel Only</p>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger font-body text-center" style="font-size: 0.9rem;" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="admin_login_action.php" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label font-body text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 1px; color: var(--primary-navy);">Corporate Email</label>
                <input type="email" class="form-control rounded-0 p-3" id="email" name="email" required autocomplete="email">
            </div>
            <div class="mb-5">
                <label for="password" class="form-label font-body text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 1px; color: var(--primary-navy);">Secure Clearance Key</label>
                <input type="password" class="form-control rounded-0 p-3" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn w-100 rounded-0 p-3 font-body text-uppercase fw-bold text-white shadow-sm" style="background-color: var(--primary-navy); letter-spacing: 2px;">Authorize Access</button>
        </form>

        <div class="mt-4 text-center">
            <a href="../index.php" class="text-decoration-none font-body text-secondary" style="font-size: 0.85rem;">&larr; Return to Public Portal</a>
        </div>
    </div>

</body>
</html>
