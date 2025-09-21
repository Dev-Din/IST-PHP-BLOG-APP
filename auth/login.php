<?php
/**
 * User Login Page
 * Handles authentication and role-based redirection
 */

session_start();
require_once '../includes/db.php';
require_once '../includes/security.php';
require_once '../includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php');
    } else {
        header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $error = 'Email and password are required.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                logSecurityEvent('USER_LOGIN', "Email: $email, Role: {$user['role']}");
                if ($user['role'] === 'admin') {
                    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php');
                } else {
                    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
                }
                exit();
            } else {
                $error = 'Invalid email or password.';
                logSecurityEvent('LOGIN_FAILED', "Email: $email");
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'Login failed. Please try again.';
            logSecurityEvent('LOGIN_ERROR', $e->getMessage());
        }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eBlog - Login</title>
    <link rel="icon" type="image/png" href="/IST-PHP-PROJECTS/PHP-BLOG-APP/assets/blog-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
            <div class="auth-form">
                <h2>Login</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                
                <p class="auth-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </div>
    </div>
</body>
</html>
