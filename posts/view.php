<?php
/**
 * View Single Post
 * Shows post content and comments
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

$post_id = $_GET['id'] ?? null;

if (!$post_id || !is_numeric($post_id)) {
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    exit();
}

$post = getPostById($post_id);
if (!$post) {
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    exit();
}

$comments = getCommentsByPostId($post_id);
$current_user = getCurrentUser();

// Handle success/error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - PHP Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="post-view">
            <div class="post-content">
                <div class="post-header">
                    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="post-meta">
                        <span class="author">By <?php echo htmlspecialchars($post['username']); ?></span>
                        <span class="date"><?php echo date('F j, Y \a\t g:i A', strtotime($post['created_at'])); ?></span>
                    </div>
                </div>
                
                <div class="post-body">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
            </div>
            
            <div class="comments-section">
                <h2>Comments (<?php echo count($comments); ?>)</h2>
                
                <?php if (empty($comments)): ?>
                    <div class="no-comments">
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                <?php else: ?>
                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="comment-date">
                                        <?php 
                                        $created_time = date('M j, Y \a\t g:i A', strtotime($comment['created_at']));
                                        $updated_time = date('M j, Y \a\t g:i A', strtotime($comment['updated_at']));
                                        
                                        if ($comment['updated_at'] > $comment['created_at']) {
                                            echo $updated_time . ' <span class="edited-label">(edited)</span>';
                                        } else {
                                            echo $created_time;
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </div>
                                <?php if (isLoggedIn() && $current_user['id'] == $comment['user_id']): ?>
                                    <div class="comment-actions">
                                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/comments/edit.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/comments/delete.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isLoggedIn()): ?>
                    <div class="add-comment">
                        <h3>Add a Comment</h3>
                        <form method="POST" action="/IST-PHP-PROJECTS/PHP-BLOG-APP/comments/add.php">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <div class="form-group">
                                <textarea name="content" rows="4" placeholder="Write your comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="add-comment">
                        <h3>Add a Comment</h3>
                        <p>Please <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/login.php">login</a> or <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/auth/register.php">register</a> to add comments.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
