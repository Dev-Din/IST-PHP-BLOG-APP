<?php
/**
 * Navigation Bar Component
 * Responsive navigation with user authentication
 */

$current_user = getCurrentUser();
?>

<nav class="navbar">
    <div class="container">
        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/index.php" class="navbar-brand">eBlog</a>
        
        <ul class="navbar-nav">
            <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/index.php">Home</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/users/dashboard.php">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/login.php">Login</a></li>
                <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/register-user.php">Register</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="navbar-user">
            <?php if (isLoggedIn()): ?>
                <span>Welcome, <?php echo htmlspecialchars($current_user['username']); ?></span>
                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/logout.php" class="btn">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
