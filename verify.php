<?php
/**
 * Installation Verification Script
 * Tests database connection and basic functionality
 */

require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

echo "<h1>PHP Blog Application - Installation Verification</h1>";

// Test 1: Database Connection
echo "<h2>1. Database Connection Test</h2>";
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    echo "✅ Database connection successful<br>";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit();
}

// Test 2: Required Tables
echo "<h2>2. Database Tables Test</h2>";
$required_tables = ['users', 'posts', 'comments'];
foreach ($required_tables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table '$table' exists<br>";
    } else {
        echo "❌ Table '$table' missing<br>";
    }
}

// Test 3: Default Admin User
echo "<h2>3. Default Admin User Test</h2>";
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
$stmt->execute();
$admin_users = $stmt->fetchAll();
if (count($admin_users) > 0) {
    echo "✅ Admin user exists<br>";
    echo "Admin email: " . $admin_users[0]['email'] . "<br>";
} else {
    echo "❌ No admin user found<br>";
}

// Test 4: Sample Posts
echo "<h2>4. Sample Data Test</h2>";
$stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
$post_count = $stmt->fetch()['count'];
if ($post_count > 0) {
    echo "✅ Sample posts found ($post_count posts)<br>";
} else {
    echo "⚠️ No sample posts found<br>";
}

// Test 5: File Permissions
echo "<h2>5. File Permissions Test</h2>";
$required_files = [
    'index.php',
    'admin.php',
    'auth/login.php',
    'auth/register.php',
    'includes/db.php',
    'includes/auth.php',
    'includes/security.php',
    'assets/css/style.css',
    'partials/navbar.php',
    'partials/footer.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ File '$file' exists<br>";
    } else {
        echo "❌ File '$file' missing<br>";
    }
}

// Test 6: PHP Extensions
echo "<h2>6. PHP Extensions Test</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'json'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extension '$ext' loaded<br>";
    } else {
        echo "❌ Extension '$ext' missing<br>";
    }
}

// Test 7: Security Functions
echo "<h2>7. Security Functions Test</h2>";
session_start();
if (function_exists('generateCSRFToken')) {
    $token = generateCSRFToken();
    if (validateCSRFToken($token)) {
        echo "✅ CSRF protection working<br>";
    } else {
        echo "❌ CSRF protection failed<br>";
    }
} else {
    echo "❌ Security functions not loaded<br>";
}

// Test 8: Helper Functions
echo "<h2>8. Helper Functions Test</h2>";
if (function_exists('getAllPosts')) {
    $posts = getAllPosts(1);
    echo "✅ Database helper functions working<br>";
} else {
    echo "❌ Database helper functions missing<br>";
}

echo "<h2>Installation Summary</h2>";
echo "<p>If all tests show ✅, your PHP Blog Application is ready to use!</p>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>Access the application: <a href='index.php'>User Interface</a></li>";
echo "<li>Admin login: <a href='admin.php'>Admin Interface</a></li>";
echo "<li>Default admin credentials: admin@blog.com / admin123</li>";
echo "</ul>";

echo "<p><em>Delete this file (verify.php) after successful installation for security.</em></p>";
?>
