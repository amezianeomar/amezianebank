<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Legacy | Ameziane For Savings and Investments</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .legacy-section {
            padding-top: 150px;
            padding-bottom: 80px;
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
            color: var(--crisp-white);
        }

        .gold-line {
            width: 60px;
            height: 3px;
            background: var(--wealth-gold);
            margin: 2rem 0;
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.2);
        }

        .nav-transparent {
            background: rgba(10, 25, 47, 0.95) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 10;
        }

        .stats-item h2 {
            font-family: var(--font-heading);
            color: var(--wealth-gold);
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .stats-item p {
            font-family: var(--font-body);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
        }
    </style>
</head>
<body class="bg-dark text-white">

    <nav class="navbar navbar-expand-lg nav-transparent py-4">
        <div class="container">
            <a class="navbar-brand text-uppercase fs-4 d-flex align-items-center text-white" style="letter-spacing: 1px;" href="index.php">
                <img src="assets/ameziane_logo.png" height="35" class="me-3" alt="Ameziane Logo">
                Ameziane
            </a>
            
            <div class="ms-auto d-flex gap-3">
                <a href="actions/login.php" class="btn text-white font-body text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;">Client Login</a>
            </div>
        </div>
    </nav>

    <div class="legacy-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="font-heading display-4 fw-bold mb-4" style="color: white;">Our Legacy</h1>
                    <div class="gold-line mx-auto"></div>
                    
                    <p class="font-body fs-5 mb-5" style="color: rgba(255,255,255,0.8); line-height: 1.8;">
                        Founded on principles of trust, integrity, and unparalleled financial acumen, Ameziane for Savings and Investments has spent decades cultivating a tradition of excellence. We are dedicated to the preservation and growth of generational wealth, providing our esteemed clients with bespoke investment strategies and an unwavering commitment to their financial legacies.
                    </p>
                    <p class="font-body fs-5 mb-5" style="color: rgba(255,255,255,0.8); line-height: 1.8;">
                        Our institution stands as a fortress of stability in an ever-changing economic landscape. We leverage deep market insights, rigorous risk management frameworks, and a network of global partnerships to deliver consistent, top-tier performance.
                    </p>
                </div>
            </div>

            <div class="row mt-5 pt-5 border-top border-secondary border-opacity-25" id="stats">
                <div class="col-md-4 mb-4 mb-md-0 text-center">
                    <div class="stats-item">
                        <h2>$1B+</h2>
                        <p>Assets Under Management</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0 text-center border-start border-secondary border-opacity-25">
                    <div class="stats-item">
                        <h2>1st</h2>
                        <p>Tier Institutional Status</p>
                    </div>
                </div>
                <div class="col-md-4 text-center border-start border-secondary border-opacity-25">
                    <div class="stats-item">
                        <h2>24/7</h2>
                        <p>Dedicated Advisory</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-outline-light px-4 py-2" style="font-family: var(--font-body); letter-spacing: 1px; text-transform: uppercase;">Return to Home</a>
            </div>
        </div>
    </div>

    <div class="container pb-3 pt-5 text-center" style="opacity: 0.5;">
        <a href="actions/admin_login.php" class="text-white text-decoration-none font-body" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;">
            Administrative Access Terminal
        </a>
    </div>

</body>
</html>
