<?php
/**
 * Landing Page / Login Page
 * 
 * This is the main entry point of the application.
 * Redirects logged-in users to their respective dashboards.
 */

session_start();
require_once 'config/db.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: staff/dashboard.php');
    }
    exit();
}

// Redirect to login page
header('Location: auth/login.php');
exit();
?>