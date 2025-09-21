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

// Application Configuration
define('APP_NAME', 'PHP Blog');
define('APP_URL', 'http://localhost/PHP-BLOG-APP');
define('APP_ENV', 'development'); // development, staging, production

// Security Configuration
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Email Configuration (if using email features)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('FROM_EMAIL', 'noreply@yourblog.com');
define('FROM_NAME', 'PHP Blog');

// File Upload Configuration
define('UPLOAD_MAX_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Debug Configuration
define('DEBUG_MODE', true); // Set to false in production
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', 'logs/error.log');

// Timezone
date_default_timezone_set('UTC');
