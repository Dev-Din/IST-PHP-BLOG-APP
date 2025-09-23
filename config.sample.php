<?php
/**
 * Configuration File Template
 * 
 * Copy this file to config.php and update the values for your environment
 * 
 * IMPORTANT: Never commit config.php to version control!
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_blog_app');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Settings
define('APP_NAME', 'PHP Blog');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/IST-PHP-PROJECTS/PHP-BLOG-APP');

// Security Settings
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour
define('SESSION_LIFETIME', 7200); // 2 hours
define('RATE_LIMIT_LOGIN', 5); // 5 attempts
define('RATE_LIMIT_REGISTER', 3); // 3 attempts
define('RATE_LIMIT_WINDOW', 900); // 15 minutes

// Pagination Settings
define('POSTS_PER_PAGE', 10);
define('COMMENTS_PER_PAGE', 20);
define('USERS_PER_PAGE', 15);

// File Upload Settings
define('MAX_FILE_SIZE', 2097152); // 2MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Email Settings (for future use)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('FROM_EMAIL', 'noreply@blog.com');
define('FROM_NAME', 'PHP Blog');

// Debug Settings
define('DEBUG_MODE', false);
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Timezone
date_default_timezone_set('UTC');

// Error Reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
