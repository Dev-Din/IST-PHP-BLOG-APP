<?php
/**
 * Security Helper Functions
 * CSRF protection and input validation
 */

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    return strlen($password) >= 6;
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function escapeOutput($output) {
    return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
}

function isSecureRequest() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
           $_SERVER['SERVER_PORT'] == 443;
}

function redirectToHTTPS() {
    if (!isSecureRequest() && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirectURL");
        exit();
    }
}

function rateLimitCheck($action, $limit = 5, $timeWindow = 300) {
    $key = $action . '_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    
    $now = time();
    $attempts = $_SESSION['rate_limit'][$key] ?? [];
    
    // Remove old attempts outside time window
    $attempts = array_filter($attempts, function($timestamp) use ($now, $timeWindow) {
        return ($now - $timestamp) < $timeWindow;
    });
    
    if (count($attempts) >= $limit) {
        return false; // Rate limit exceeded
    }
    
    $attempts[] = $now;
    $_SESSION['rate_limit'][$key] = $attempts;
    
    return true;
}

function logSecurityEvent($event, $details = '') {
    $logEntry = date('Y-m-d H:i:s') . " - $event - " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . " - $details\n";
    error_log($logEntry, 3, 'security.log');
}
?>
