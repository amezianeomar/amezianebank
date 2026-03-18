<?php
session_start();

// If user is already logged in, redirect them to the portal
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/admin_dashboard.php');
    } else {
        header('Location: client/client_dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ameziane For Savings and Investments</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop') center/cover;
            color: var(--crisp-white);
        }
        
        .hero-content {
            z-index: 2;
        }

        .gold-line {
            width: 60px;
            height: 3px;
            background: var(--wealth-gold);
            margin: 2rem 0;
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.2);
        }

        .nav-transparent {
            background: transparent !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 10;
        }

        .cta-btn {
            background: var(--wealth-gold);
            color: var(--primary-navy);
            padding: 1rem 2.5rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: none;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            background: #e6c855;
            color: var(--primary-navy);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }

        .btn-outline-gold {
            border: 1px solid var(--wealth-gold);
            color: var(--wealth-gold);
            padding: 1rem 2.5rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--wealth-gold);
        }

        .stats-item h2 {
            font-family: 'Playfair Display', serif;
            color: var(--wealth-gold);
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .stats-item p {
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
        }
    </style>
</head>
<body class="bg-dark">

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

    <div class="hero-section">
        <div class="container hero-content pt-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="font-heading display-3 fw-bold mb-4" style="line-height: 1.1;">
                        Generational Wealth,<br>
                        <span style="color: var(--wealth-gold);">Institutionally Managed.</span>
                    </h1>
                    
                    <div class="gold-line"></div>
                    
                    <p class="font-body fs-5 mb-5" style="color: rgba(255,255,255,0.8); max-width: 600px; line-height: 1.8;">
                        Ameziane for Savings and Investments provides ultra-high-net-worth individuals and corporate entities with unparalleled capital preservation and tactical asset allocation strategies.
                    </p>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="actions/login.php" class="btn cta-btn">Access Private Portal</a>
                        <a href="#about" class="btn btn-outline-gold">Our Legacy</a>
                    </div>
                </div>
                
                <div class="col-lg-5 mt-5 mt-lg-0 text-center text-lg-end">
                    <img src="assets/ameziane_logo.png" alt="Ameziane Crest" style="width: 350px; opacity: 0.85;" class="drop-shadow-sm">
                </div>
            </div>

            <div class="row mt-5 pt-5 border-top border-secondary border-opacity-25" id="about">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="stats-item pe-md-4">
                        <h2>$1B+</h2>
                        <p>Assets Under Management</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0 border-start border-secondary border-opacity-25">
                    <div class="stats-item px-md-4">
                        <h2>1st</h2>
                        <p>Tier Institutional Status</p>
                    </div>
                </div>
                <div class="col-md-4 border-start border-secondary border-opacity-25">
                    <div class="stats-item ps-md-4">
                        <h2>24/7</h2>
                        <p>Dedicated Advisory</p>
                    </div>
                </div>
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
