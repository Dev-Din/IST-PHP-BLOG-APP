<?php
/**
 * Add Comment
 * Handles comment submission
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

$current_user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $content = trim($_POST['content'] ?? '');
    
    if (!$post_id || !is_numeric($post_id) || empty($content)) {
        $_SESSION['error'] = 'Invalid comment data.';
    } else {
        try {
            // Verify post exists
            $stmt = $pdo->prepare("SELECT id FROM posts WHERE id = ? AND status = 'published'");
            $stmt->execute([$post_id]);
            
            if ($stmt->fetch()) {
                // Insert comment with approved status
                $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, status) VALUES (?, ?, ?, 'approved')");
                $stmt->execute([$post_id, $current_user['id'], $content]);
                
                $_SESSION['success'] = 'Comment posted successfully!';
            } else {
                $_SESSION['error'] = 'Post not found.';
            }
        } catch (PDOException $e) {
            error_log("Comment error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to submit comment. Please try again.';
        }
    }
    
    // Redirect back to post
    header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=' . $post_id);
    exit();
}

// If not POST request, redirect to home
header('Location: /IST-PHP-PROJECTS/PHP-BLOG-APP/index.php');
exit();
?>
