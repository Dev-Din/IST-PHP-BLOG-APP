-- PHP Blog Application Database Schema
-- Database: php_blog_app

CREATE DATABASE IF NOT EXISTS php_blog_app;
USE php_blog_app;

-- Users table for authentication and role management
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Posts table for blog articles
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(500) DEFAULT NULL,
    status ENUM('draft', 'published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Comments table for post interactions
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user
-- Password: admin123 (hashed)
-- INSERT INTO users (username, email, password, role) VALUES 
-- ('admin', 'admin@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample posts
-- INSERT INTO posts (user_id, title, content, excerpt) VALUES 
-- (1, 'Welcome to Our Blog', 'This is the first post on our amazing blog platform. Here you can share your thoughts, ideas, and connect with other users through comments and discussions.', 'Welcome to our blog platform where you can share your thoughts and connect with others.'),
-- (1, 'Getting Started with PHP', 'PHP is a powerful server-side scripting language that is widely used for web development. In this post, we will explore the basics of PHP programming.', 'Learn the fundamentals of PHP programming and web development.'),
-- (1, 'Building Modern Web Applications', 'Modern web applications require careful planning, clean code, and user-friendly interfaces. Let us explore the best practices for building scalable web applications.', 'Discover the best practices for building modern, scalable web applications.');


