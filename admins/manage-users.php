<?php
/**
 * Manage Users
 * Admin page for viewing and managing users
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireAdmin();

$error = '';
$success = '';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? null;
    
    if (!$user_id || !is_numeric($user_id)) {
        $error = 'Invalid user ID.';
    } else {
        try {
            switch ($action) {
                case 'delete':
                    // Don't allow deleting admin users
                    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();
                    
                    if ($user && $user['role'] === 'admin') {
                        $error = 'Cannot delete admin users.';
                    } else {
                        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        $success = 'User deleted successfully!';
                    }
                    break;
                    
                case 'toggle_role':
                    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();
                    
                    if ($user) {
                        $new_role = $user['role'] === 'admin' ? 'user' : 'admin';
                        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
                        $stmt->execute([$new_role, $user_id]);
                        $success = "User role updated to {$new_role}!";
                    }
                    break;
                    
                default:
                    $error = 'Invalid action.';
            }
        } catch (PDOException $e) {
            error_log("User management error: " . $e->getMessage());
            $error = 'Action failed. Please try again.';
        }
    }
}

$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="admin-page">
            <div class="admin-header">
                <h1>Manage Users</h1>
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
                    <h2>All Users (<?php echo count($users); ?>)</h2>
                    
                    <?php if (empty($users)): ?>
                        <div class="empty-state">
                            <p>No users found.</p>
                        </div>
                    <?php else: ?>
                        <div class="users-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <span class="role role-<?php echo $user['role']; ?>">
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    
                                                    <?php if ($user['role'] !== 'admin'): ?>
                                                        <button type="submit" name="action" value="toggle_role" 
                                                                class="btn btn-sm btn-outline"
                                                                onclick="return confirm('Change role to admin?')">
                                                            Make Admin
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" name="action" value="toggle_role" 
                                                                class="btn btn-sm btn-warning"
                                                                onclick="return confirm('Change role to user?')">
                                                            Make User
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($user['role'] !== 'admin'): ?>
                                                        <button type="submit" name="action" value="delete" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                            Delete
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="admin-section">
                    <h2>User Statistics</h2>
                    <div class="stats-grid">
                        <?php
                        $admin_count = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
                        $user_count = count(array_filter($users, fn($u) => $u['role'] === 'user'));
                        ?>
                        <div class="stat-card">
                            <div class="stat-content">
                                <h3><?php echo $admin_count; ?></h3>
                                <p>Admin Users</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-content">
                                <h3><?php echo $user_count; ?></h3>
                                <p>Regular Users</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-content">
                                <h3><?php echo count($users); ?></h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>
