<?php
/**
 * Delete Comment
 * Handles comment deletion
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

$current_user = getCurrentUser();
$comment_id = $_GET['id'] ?? null;

if (!$comment_id || !is_numeric($comment_id)) {
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    exit();
}

// Get the comment to verify ownership
$stmt = $pdo->prepare("SELECT c.*, p.title as post_title FROM comments c 
                      JOIN posts p ON c.post_id = p.id 
                      WHERE c.id = ? AND c.user_id = ?");
$stmt->execute([$comment_id, $current_user['id']]);
$comment = $stmt->fetch();

if (!$comment) {
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    exit();
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $current_user['id']]);
        
        $_SESSION['success'] = 'Comment deleted successfully!';
        header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=' . $comment['post_id']);
        exit();
    } catch (PDOException $e) {
        error_log("Comment delete error: " . $e->getMessage());
        $_SESSION['error'] = 'Failed to delete comment. Please try again.';
        header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=' . $comment['post_id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Comment - PHP Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="delete-comment-page">
            <div class="page-header">
                <h1>Delete Comment</h1>
                <p>Are you sure you want to delete this comment?</p>
            </div>
            
            <div class="comment-preview">
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo htmlspecialchars($current_user['username']); ?></span>
                        <span class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                    </div>
                    <div class="comment-content">
                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                    </div>
                </div>
            </div>
            
            <div class="delete-form">
                <form method="POST" action="">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">Yes, Delete Comment</button>
                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $comment['post_id']; ?>" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
