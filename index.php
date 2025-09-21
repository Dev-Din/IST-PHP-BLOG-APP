<?php
/**
 * Main Blog Homepage
 * Shows blog feed for all users
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

$posts = getAllPosts();
$current_user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    
    <div class="container">
        <div class="main-content">
            <div class="content-area">
                <div class="welcome-section">
                    <h1>Welcome to PHP Blog</h1>
                    <?php if (isLoggedIn()): ?>
                        <p>Welcome back, <?php echo htmlspecialchars($current_user['username']); ?>! Discover amazing articles and share your thoughts with the community.</p>
                    <?php else: ?>
                        <p>Discover amazing articles and share your thoughts with the community. <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/register.php">Join us today!</a></p>
                    <?php endif; ?>
                </div>
                
                <div class="posts-grid">
                    <?php if (empty($posts)): ?>
                        <div class="no-posts">
                            <h3>No posts available</h3>
                            <p>Be the first to create a post!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <div class="post-header">
                                    <h2><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                                    <div class="post-meta">
                                        <span class="author">By <?php echo htmlspecialchars($post['username']); ?></span>
                                        <span class="date"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                </div>
                                
                                <div class="post-content">
                                    <?php 
                                    $excerpt = substr(strip_tags($post['content']), 0, 200) . '...';
                                    echo htmlspecialchars($excerpt);
                                    ?>
                                </div>
                                
                                <div class="post-footer">
                                    <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>" class="btn btn-outline">Read More</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <aside class="sidebar">
                <?php if (isLoggedIn()): ?>
                    <div class="sidebar-widget">
                        <h3>Quick Actions</h3>
                        <ul class="action-list">
                            <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/users/dashboard.php" class="btn btn-primary">My Dashboard</a></li>
                            <?php if (isAdmin()): ?>
                                <li><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php" class="btn btn-secondary">Admin Panel</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="sidebar-widget">
                    <h3>Recent Posts</h3>
                    <?php 
                    $recent_posts = getAllPosts(5);
                    if ($recent_posts):
                    ?>
                        <ul class="recent-posts">
                            <?php foreach ($recent_posts as $post): ?>
                                <li>
                                    <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                    <small><?php echo date('M j', strtotime($post['created_at'])); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
    
    <?php include 'partials/footer.php'; ?>
</body>
</html>
