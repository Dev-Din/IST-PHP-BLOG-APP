<?php
/**
 * Admin Registration Page
 * Handles admin registration with validation
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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $role = 'admin'; // Fixed role for admin registration
    
        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = 'All fields are required.';
        } elseif (!validateUsername($username)) {
            $error = 'Username must be 3-20 characters and contain only letters, numbers, and underscores.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } elseif (!validatePassword($password)) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $role]);
                
                // Get the newly created user ID
                $user_id = $pdo->lastInsertId();
                
                // Automatically log in the user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                
                logSecurityEvent('ADMIN_REGISTERED', "Username: $username, Email: $email, Role: $role");
                
                // Redirect to admin dashboard
                header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $error = 'Registration failed. Please try again.';
            logSecurityEvent('REGISTRATION_ERROR', $e->getMessage());
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
    <title>eBlog - Register as Admin</title>
    <link rel="icon" type="image/png" href="/IST-PHP-PROJECTS/PHP-BLOG-APP/assets/blog-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
            <div class="auth-form">
                <h2>Create Admin Account</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Register as Admin</button>
                </form>
                
                <p class="auth-link">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
                
                <p class="auth-link">
                    Want to register as user? <a href="register-user.php">User Registration</a>
                </p>
            </div>
    </div>
</body>
</html>
