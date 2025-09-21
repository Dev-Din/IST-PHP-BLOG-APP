<?php
/**
 * Manage Comments
 * Admin page for approving and managing comments
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireAdmin();

$error = '';
$success = '';

// Handle comment actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $comment_id = $_POST['comment_id'] ?? null;
    
    if (!$comment_id || !is_numeric($comment_id)) {
        $error = 'Invalid comment ID.';
    } else {
        try {
            switch ($action) {
                case 'approve':
                    $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
                    $stmt->execute([$comment_id]);
                    $success = 'Comment approved successfully!';
                    break;
                    
                case 'reject':
                    $stmt = $pdo->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?");
                    $stmt->execute([$comment_id]);
                    $success = 'Comment rejected successfully!';
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
                    $stmt->execute([$comment_id]);
                    $success = 'Comment deleted successfully!';
                    break;
                    
                default:
                    $error = 'Invalid action.';
            }
        } catch (PDOException $e) {
            error_log("Comment management error: " . $e->getMessage());
            $error = 'Action failed. Please try again.';
        }
    }
}

// Get all comments with post and user info
$stmt = $pdo->query("SELECT c.*, u.username, p.title as post_title 
                    FROM comments c 
                    JOIN users u ON c.user_id = u.id 
                    JOIN posts p ON c.post_id = p.id 
                    ORDER BY c.created_at DESC");
$comments = $stmt->fetchAll();

// Group comments by status
$pending_comments = array_filter($comments, fn($c) => $c['status'] === 'pending');
$approved_comments = array_filter($comments, fn($c) => $c['status'] === 'approved');
$rejected_comments = array_filter($comments, fn($c) => $c['status'] === 'rejected');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="admin-page">
            <div class="admin-header">
                <h1>Manage Comments</h1>
                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="admin-content">
                <div class="comment-stats">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3><?php echo count($pending_comments); ?></h3>
                            <p>Pending</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3><?php echo count($approved_comments); ?></h3>
                            <p>Approved</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3><?php echo count($rejected_comments); ?></h3>
                            <p>Rejected</p>
                        </div>
                    </div>
                </div>
                
                <div class="admin-section">
                    <h2>Pending Comments (<?php echo count($pending_comments); ?>)</h2>
                    <?php if (empty($pending_comments)): ?>
                        <div class="empty-state">
                            <p>No pending comments to review.</p>
                        </div>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($pending_comments as $comment): ?>
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="comment-post">On: <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $comment['post_id']; ?>"><?php echo htmlspecialchars($comment['post_title']); ?></a></span>
                                        <span class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                    
                                    <div class="comment-content">
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                    </div>
                                    
                                    <div class="comment-actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-sm btn-primary">Approve</button>
                                            <button type="submit" name="action" value="reject" class="btn btn-sm btn-warning">Reject</button>
                                            <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="admin-section">
                    <h2>All Comments</h2>
                    <?php if (empty($comments)): ?>
                        <div class="empty-state">
                            <p>No comments found.</p>
                        </div>
                    <?php else: ?>
                        <div class="comments-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Post</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comments as $comment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($comment['username']); ?></td>
                                            <td>
                                                <a href="/IST-PHP-PROJECTS/PHP-BLOG-APP/posts/view.php?id=<?php echo $comment['post_id']; ?>">
                                                    <?php echo htmlspecialchars($comment['post_title']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($comment['content'], 0, 100)) . (strlen($comment['content']) > 100 ? '...' : ''); ?></td>
                                            <td>
                                                <span class="status status-<?php echo $comment['status']; ?>">
                                                    <?php echo ucfirst($comment['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                    
                                                    <?php if ($comment['status'] === 'pending'): ?>
                                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-primary">Approve</button>
                                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-warning">Reject</button>
                                                    <?php elseif ($comment['status'] === 'rejected'): ?>
                                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-primary">Approve</button>
                                                    <?php endif; ?>
                                                    
                                                    <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                                </form>
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
