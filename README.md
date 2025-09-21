# PHP Blog Application

A modern, full-featured PHP blog application with user authentication, role-based access control, and a clean, responsive design.

## Features

- **User Authentication**: Registration, login, and logout functionality
- **Role-Based Access**: Admin and regular user roles with different permissions
- **Blog Management**: Create, edit, and delete blog posts
- **Comment System**: Users can comment on posts with edit/delete capabilities
- **Responsive Design**: Modern, mobile-friendly interface
- **Security**: CSRF protection, password hashing, and input validation
- **Auto-Publishing**: Posts are automatically published (no draft system)

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Security**: PDO prepared statements, password hashing, CSRF tokens

## Installation

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd PHP-BLOG-APP
   ```

2. **Configuration Setup**

   - Copy `config.sample.php` to `config.php`
   - Update database credentials and other settings in `config.php`
   - **Important**: Never commit `config.php` to version control!

3. **Database Setup**

   - Create a MySQL database named `php_blog_app`
   - Import the `database.sql` file to create tables

4. **Web Server Configuration**
   - Place files in your web server directory (e.g., XAMPP htdocs)
   - Ensure PHP and MySQL are running
   - Access via `http://localhost/PHP-BLOG-APP/`

## Database Schema

The application uses three main tables:

- `users` - User accounts and authentication
- `posts` - Blog posts content
- `comments` - User comments on posts

## Default Admin Account

- **Email**: admin@blog.com
- **Password**: admin123

## File Structure

```
PHP-BLOG-APP/
├── admins/           # Admin management pages
├── assets/           # CSS, JS, and static files
├── auth/             # Authentication pages
├── comments/         # Comment management
├── includes/         # Core PHP includes
├── partials/         # Reusable components
├── posts/            # Post viewing pages
├── users/            # User dashboard
├── admin.php         # Admin dashboard
├── index.php         # Homepage
└── database.sql      # Database schema
```

## Security Features

- Password hashing using PHP's `password_hash()`
- CSRF token protection on forms
- SQL injection prevention with PDO prepared statements
- Input validation and sanitization
- Session-based authentication

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support or questions, please open an issue in the repository.
