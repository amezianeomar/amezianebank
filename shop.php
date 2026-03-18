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
    <title>Nexus Marketplace - Mini Banking System</title>
    <!-- Bootstrap 5 Grid -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fintech 2026 CSS -->
    <link href="assets/style.css" rel="stylesheet">
    <style>
        .shop-orb {
            position: fixed;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 60%);
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
            z-index: 0;
        }
        .asset-card {
            border: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.2);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .asset-card:hover {
            border-color: var(--neon-purple);
            background: rgba(79, 172, 254, 0.05);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass mb-4 py-3 shadow-sm bg-white border-bottom border-subtle">
    <div class="container">
        <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center" style="letter-spacing: 1px;" href="client/client_dashboard.php">
            <img src="assets/ameziane_logo.png" height="30" class="me-2" alt="Ameziane Logo">
            Ameziane<span class="text-secondary ms-2 fs-6 fw-normal" style="letter-spacing: 2px;">Corporate Services</span>
        </a>
        
        <div class="ms-auto">
            <a href="client/client_dashboard.php" class="btn-glass px-4 py-2 text-decoration-none small">Return to Portfolio</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger font-body border-danger border-opacity-50 text-danger mb-4 bg-white" style="border-radius: 4px;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="text-center mb-5">
        <h2 class="font-heading fw-bold d-inline-block text-dark" style="color: var(--primary-navy) !important;">Wealth Asset Management</h2>
        <p class="text-secondary mt-2">Acquire verified institutional assets. Transparently managed via Ameziane ledgers.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Stock Purchase Simulation -->
        <div class="col-md-5">
            <div class="glass-panel p-4 h-100 position-relative overflow-hidden bg-white shadow-sm">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--wealth-gold)" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 11.5a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2zM9.5 8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v5.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V8zm-4.5-3a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-8.5z"/>
                    </svg>
                </div>
                
                <h4 class="font-heading text-dark fw-bold mb-1" style="color: var(--primary-navy) !important;">Ameziane Global Equity Fund</h4>
                <p class="text-secondary small font-monospace mb-4 fw-bold">ASSET CLASS: EQUITIES_INDEX</p>
                
                <form action="actions/shop_action.php" method="POST">
                    <input type="hidden" name="type" value="stock_purchase">
                    <input type="hidden" name="asset_name" value="Ameziane Global Equity Fund">
                    <input type="hidden" name="price_per_unit" value="150.00">
                    
                    <div class="asset-card border-subtle bg-light mb-4">
                        <div class="text-secondary mb-2 text-uppercase small letter-spacing-1 fw-bold">Net Asset Value (NAV)</div>
                        <div class="fs-2 text-dark fw-bold">$150.00 <span class="text-secondary fs-6 fw-normal">/ share</span></div>
                        <hr class="border-subtle mt-3 mb-3">
                        <p class="text-secondary small font-body mb-0">A diversified institutional-grade equity portfolio providing exposure to premier global markets.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-dark fw-bold">TARGET SHARE ACQUISITION</label>
                        <input type="number" class="form-control form-control-glass bg-white text-center fs-5 text-dark" name="quantity" min="1" value="1" required>
                    </div>
                    
                    <button type="submit" class="btn-gold w-100 fw-bold letter-spacing-1 text-uppercase">Authorize Trade</button>
                </form>
            </div>
        </div>

        <!-- Wealth Bonds Simulation -->
        <div class="col-md-5">
            <div class="glass-panel p-4 h-100 position-relative overflow-hidden bg-white shadow-sm">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--primary-navy)" viewBox="0 0 16 16">
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </div>
                
                <h4 class="font-heading text-dark fw-bold mb-1" style="color: var(--primary-navy) !important;">Ameziane Treasury Bonds</h4>
                <p class="text-secondary small font-monospace mb-4 fw-bold">ASSET CLASS: FIXED_INCOME</p>
                
                <form action="actions/shop_action.php" method="POST">
                    <input type="hidden" name="type" value="ecommerce_purchase">
                    <input type="hidden" name="asset_name" value="Ameziane 10-Year Treasury Bonds">
                    <input type="hidden" name="price_per_unit" value="500.00">
                    
                    <div class="asset-card border-subtle bg-light mb-4">
                        <div class="text-secondary mb-2 text-uppercase small letter-spacing-1 fw-bold">Bond Face Value</div>
                        <div class="fs-2 text-dark fw-bold">$500.00 <span class="text-secondary fs-6 fw-normal">/ bond</span></div>
                        <hr class="border-subtle mt-3 mb-3">
                        <p class="text-secondary small font-body mb-0">High-yield fixed income securities backed directly by the Ameziane Financial Group.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-dark fw-bold">ISSUE ALLOCATION</label>
                        <input type="number" class="form-control form-control-glass bg-white text-center fs-5 text-dark" name="quantity" min="1" value="1" required>
                    </div>
                    
                    <button type="submit" class="btn-neon w-100 fw-bold letter-spacing-1 text-uppercase">Authorize Trade</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
