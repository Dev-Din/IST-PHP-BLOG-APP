<?php
/**
 * Edit Comment
 * Handles comment editing with timestamp update
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

// Get the comment
$stmt = $pdo->prepare("SELECT c.*, p.title as post_title FROM comments c 
                      JOIN posts p ON c.post_id = p.id 
                      WHERE c.id = ? AND c.user_id = ?");
$stmt->execute([$comment_id, $current_user['id']]);
$comment = $stmt->fetch();

if (!$comment) {
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    
    if (empty($content)) {
        $error = 'Comment content is required.';
    } else {
        try {
            // Update comment with new timestamp
            $stmt = $pdo->prepare("UPDATE comments SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
            $stmt->execute([$content, $comment_id, $current_user['id']]);
            
            $_SESSION['success'] = 'Comment updated successfully!';
            header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=' . $comment['post_id']);
            exit();
        } catch (PDOException $e) {
            error_log("Comment edit error: " . $e->getMessage());
            $error = 'Failed to update comment. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eBlog - Edit Comment</title>
    <link rel="icon" type="image/png" href="/IST-PHP-PROJECTS/PHP-BLOG-APP/assets/blog-logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="edit-comment-page">
            <div class="page-header">
                <h1>Edit Comment</h1>
                <p>Editing comment on: <strong><?php echo htmlspecialchars($comment['post_title']); ?></strong></p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="edit-form">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="content">Comment Content</label>
                        <textarea id="content" name="content" rows="6" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Comment</button>
                        <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $comment['post_id']; ?>" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
