<?php
/**
 * Admin Dashboard
 * Shows statistics and admin controls
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

requireAdmin();

$stats = getStats();
$recent_posts = getAllPosts(5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eBlog - Admin Dashboard</title>
    <link rel="icon" type="image/png" href="/IST-PHP-PROJECTS/PHP-BLOG-APP/assets/blog-logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    
    <div class="container">
        <div class="admin-dashboard">
            <div class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Manage your blog platform</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3><?php echo $stats['users']; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-content">
                        <h3><?php echo $stats['posts']; ?></h3>
                        <p>Published Posts</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-content">
                        <h3><?php echo $stats['comments']; ?></h3>
                        <p>Total Comments</p>
                    </div>
                </div>
            </div>
            
            <div class="admin-content">
                <div class="admin-section">
                    <h2>Quick Actions</h2>
                    <div class="action-grid">
                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="action-card">
                            <div class="action-icon">📝</div>
                            <h3>Manage Posts</h3>
                            <p>Create, edit, and delete blog posts</p>
                        </a>
                        
                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-users.php" class="action-card">
                            <div class="action-icon">👥</div>
                            <h3>Manage Users</h3>
                            <p>View and manage user accounts</p>
                        </a>
                    </div>
                </div>
                
                <div class="admin-section">
                    <h2>Recent Posts</h2>
                    <?php if (empty($recent_posts)): ?>
                        <div class="empty-state">
                            <p>No posts available.</p>
                            <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="btn btn-primary">Create First Post</a>
                        </div>
                    <?php else: ?>
                        <div class="recent-posts">
                            <?php foreach ($recent_posts as $post): ?>
                                <div class="post-item">
                                    <h3><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                                    <div class="post-meta">
                                        <span class="author">By <?php echo htmlspecialchars($post['username']); ?></span>
                                        <span class="date"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                    <div class="post-actions">
                                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'partials/footer.php'; ?>
</body>
</html>
