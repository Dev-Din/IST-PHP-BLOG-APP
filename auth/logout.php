<?php
/**
 * Logout Page
 * Destroys session and redirects to login
 */

session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/auth/login.php');
exit();
?>
