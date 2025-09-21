<?php
/**
 * User Dashboard
 * Shows user profile and their posts/comments
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

$current_user = getCurrentUser();

// Get user's posts
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$current_user['id']]);
$user_posts = $stmt->fetchAll();

// Get user's comments
$stmt = $pdo->prepare("SELECT c.*, p.title as post_title FROM comments c 
                      JOIN posts p ON c.post_id = p.id 
                      WHERE c.user_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$current_user['id']]);
$user_comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="dashboard">
            <div class="dashboard-header">
                <h1>My Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($current_user['username']); ?>!</p>
            </div>
            
            <div class="dashboard-content">
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📝</div>
                        <div class="stat-content">
                            <h3><?php echo count($user_posts); ?></h3>
                            <p>My Posts</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">💬</div>
                        <div class="stat-content">
                            <h3><?php echo count($user_comments); ?></h3>
                            <p>My Comments</p>
                        </div>
                    </div>
                    
                    <?php if (isAdmin()): ?>
                        <?php
                        // Get admin statistics
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total_posts FROM posts");
                        $stmt->execute();
                        $total_posts = $stmt->fetch()['total_posts'];
                        
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total_comments FROM comments");
                        $stmt->execute();
                        $total_comments = $stmt->fetch()['total_comments'];
                        
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total_users FROM users");
                        $stmt->execute();
                        $total_users = $stmt->fetch()['total_users'];
                        ?>
                        
                        <div class="stat-card">
                            <div class="stat-icon">📊</div>
                            <div class="stat-content">
                                <h3><?php echo $total_posts; ?></h3>
                                <p>Total Posts</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">👥</div>
                            <div class="stat-content">
                                <h3><?php echo $total_users; ?></h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Admin Quick Actions -->
                <?php if (isAdmin()): ?>
                    <div class="dashboard-section">
                        <h2>Admin Quick Actions</h2>
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
                            
                            <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php" class="action-card">
                                <div class="action-icon">⚙️</div>
                                <h3>Admin Dashboard</h3>
                                <p>Access full admin panel</p>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="dashboard-section">
                    <h2>Profile Information</h2>
                    <div class="profile-card">
                        <div class="profile-info">
                            <h3><?php echo htmlspecialchars($current_user['username']); ?></h3>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($current_user['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo ucfirst($current_user['role']); ?></p>
                            <p><strong>Member since:</strong> <?php echo date('F Y', strtotime($current_user['created_at'] ?? '2024-01-01')); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>My Posts (<?php echo count($user_posts); ?>)</h2>
                    <?php if (empty($user_posts)): ?>
                        <div class="empty-state">
                            <p>You haven't created any posts yet.</p>
                            <?php if (isAdmin()): ?>
                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="btn btn-primary">Create Post</a>
                            <?php else: ?>
                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="btn btn-primary">Create Post</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="posts-list">
                            <?php foreach ($user_posts as $post): ?>
                                <div class="post-item">
                                    <h3><a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                                    <div class="post-meta">
                                        <span class="date"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                    <?php if (isAdmin()): ?>
                                        <div class="post-actions">
                                            <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="section-actions">
                            <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="btn btn-outline">Manage All Posts</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="dashboard-section">
                    <h2>My Comments (<?php echo count($user_comments); ?>)</h2>
                    <?php if (empty($user_comments)): ?>
                        <div class="empty-state">
                            <p>You haven't commented on any posts yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($user_comments as $comment): ?>
                                <div class="comment-item">
                                    <div class="comment-content">
                                        <p><?php echo htmlspecialchars(substr($comment['content'], 0, 100)) . (strlen($comment['content']) > 100 ? '...' : ''); ?></p>
                                    </div>
                                    <div class="comment-meta">
                                        <span class="post-title">On: <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $comment['post_id']; ?>"><?php echo htmlspecialchars($comment['post_title']); ?></a></span>
                                        <span class="date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
