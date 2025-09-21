<?php
/**
 * Session Management Helper
 * Provides functions for authentication and authorization
 */

function requireLogin() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
        exit();
    }
}

function isLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role']
    ];
}

function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if (isAdmin()) {
            header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php');
        } else {
            header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
        }
        exit();
    }
}
?>
