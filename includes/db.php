<?php
/**
 * Database Connection File
 * Secure PDO connection with error handling
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'php_blog_app';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    public function query($sql) {
        return $this->pdo->query($sql);
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

// Global database instance
$db = new Database();
$pdo = $db->getConnection();

// Helper functions for common database operations
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function getAllPosts($limit = null) {
    global $pdo;
    $sql = "SELECT p.*, u.username FROM posts p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getPostById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p 
                          JOIN users u ON p.user_id = u.id 
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getCommentsByPostId($post_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c 
                          JOIN users u ON c.user_id = u.id 
                          WHERE c.post_id = ? AND c.status = 'approved' 
                          ORDER BY c.created_at ASC");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll();
}

function getAllCommentsByPostId($post_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c 
                          JOIN users u ON c.user_id = u.id 
                          WHERE c.post_id = ? 
                          ORDER BY c.created_at ASC");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll();
}

function getPendingComments() {
    global $pdo;
    $stmt = $pdo->query("SELECT c.*, u.username, p.title as post_title 
                        FROM comments c 
                        JOIN users u ON c.user_id = u.id 
                        JOIN posts p ON c.post_id = p.id 
                        WHERE c.status = 'pending' 
                        ORDER BY c.created_at DESC");
    return $stmt->fetchAll();
}

function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function getStats() {
    global $pdo;
    $stats = [];
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $stats['users'] = $stmt->fetch()['count'];
    
    // Total posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
    $stats['posts'] = $stmt->fetch()['count'];
    
    // Total comments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM comments WHERE status = 'approved'");
    $stats['comments'] = $stmt->fetch()['count'];
    
    // Pending comments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM comments WHERE status = 'pending'");
    $stats['pending_comments'] = $stmt->fetch()['count'];
    
    return $stats;
}
?>
