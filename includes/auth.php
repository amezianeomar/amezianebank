<?php
// auth.php
session_start();

// Utility function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Ensure the user is logged in, redirect otherwise
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../actions/login.php');
        exit;
    }
}

// Redirect logged-in users away from the login page based on role
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../admin/admin_dashboard.php');
        } else {
            header('Location: ../client/client_dashboard.php');
        }
        exit;
    }
}

// Optional utility for sanitizing inputs as a secondary defense to prepared statements
function sanitizeInput($data) {
    if ($data === null) return '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
