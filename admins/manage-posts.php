<?php
/**
 * Manage Posts
 * Admin page for creating, editing, and deleting posts
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireAdmin();

$current_user = getCurrentUser();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $post_id = $_POST['post_id'] ?? null;
    
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        try {
            if ($post_id && is_numeric($post_id)) {
                // Update existing post
                $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, status = 'published', updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
                $stmt->execute([$title, $content, $post_id, $current_user['id']]);
                $success = 'Post updated successfully!';
            } else {
                // Create new post
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, status) VALUES (?, ?, ?, 'published')");
                $stmt->execute([$current_user['id'], $title, $content]);
                $success = 'Post created successfully!';
            }
        } catch (PDOException $e) {
            error_log("Post error: " . $e->getMessage());
            $error = 'Failed to save post. Please try again.';
        }
    }
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$_GET['delete'], $current_user['id']]);
        $success = 'Post deleted successfully!';
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        $error = 'Failed to delete post.';
    }
}

// Get post for editing
$edit_post = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['edit'], $current_user['id']]);
    $edit_post = $stmt->fetch();
}

// Get all posts by current user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$current_user['id']]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="admin-page">
            <div class="admin-header">
                <h1>Manage Posts</h1>
                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="admin-content">
                <div class="admin-section">
                    <h2><?php echo $edit_post ? 'Edit Post' : 'Create New Post'; ?></h2>
                    <form method="POST" action="">
                        <?php if ($edit_post): ?>
                            <input type="hidden" name="post_id" value="<?php echo $edit_post['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required 
                                   value="<?php echo htmlspecialchars($edit_post['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" name="content" rows="10" required 
                                      placeholder="Write your post content here..."><?php echo htmlspecialchars($edit_post['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $edit_post ? 'Update Post' : 'Create Post'; ?>
                            </button>
                            <?php if ($edit_post): ?>
                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php" class="btn btn-outline">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <div class="admin-section">
                    <h2>Your Posts (<?php echo count($posts); ?>)</h2>
                    <?php if (empty($posts)): ?>
                        <div class="empty-state">
                            <p>You haven't created any posts yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="posts-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td>
                                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $post['id']; ?>">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                            <td>
                                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admins/manage-posts.php?delete=<?php echo $post['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
