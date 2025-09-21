# PHP Blog Application - Setup Guide

## 🚀 Complete Professional PHP Blog Application

A modern, secure blog platform with role-based authentication, admin management, and responsive design.

## 📋 Features

- **User Authentication**: Secure registration and login system
- **Role-Based Access**: Separate interfaces for users and admins
- **Blog Management**: Create, edit, and delete posts
- **Comment System**: User comments with admin moderation
- **Responsive Design**: Modern UI with dark blue theme
- **Security Features**: CSRF protection, rate limiting, input validation
- **Admin Dashboard**: Statistics and management tools

## 🛠 Installation Instructions

### Prerequisites

- XAMPP (Apache + MySQL)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Step 1: Setup Project Directory

1. Place the project in: `C:\xampp\htdocs\IST-PHP-PROJECTS\PHP-BLOG-APP\`
2. Ensure all files are in the correct directory structure

### Step 2: Database Setup

1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `php_blog_app`
4. Import the `database.sql` file:
   - Click "Import" tab
   - Choose file: `database.sql`
   - Click "Go"

### Step 3: Database Configuration

1. Open `includes/db.php`
2. Update database credentials if needed:
   ```php
   private $host = 'localhost';
   private $db_name = 'php_blog_app';
   private $username = 'root';
   private $password = ''; // Your MySQL password
   ```

### Step 4: Access the Application

1. Start XAMPP services (Apache + MySQL)
2. Open your browser and navigate to:
   - **User Interface**: `http://localhost/IST-PHP-PROJECTS/PHP-BLOG-APP/index.php`
   - **Admin Interface**: `http://localhost/IST-PHP-PROJECTS/PHP-BLOG-APP/admin.php`

## 🔐 Default Login Credentials

### Admin Account

- **Email**: admin@blog.com
- **Password**: admin123

### User Account

- Register a new account using the registration form

## 📁 Project Structure

```
PHP-BLOG-APP/
├── assets/
│   └── css/
│       └── style.css          # Main stylesheet
├── auth/
│   ├── login.php              # User login page
│   ├── register.php           # User registration page
│   └── logout.php             # Logout handler
├── includes/
│   ├── db.php                 # Database connection & helpers
│   ├── auth.php               # Authentication helpers
│   └── security.php           # Security functions
├── partials/
│   ├── navbar.php             # Navigation component
│   └── footer.php             # Footer component
├── users/
│   └── dashboard.php          # User dashboard
├── admins/
│   ├── manage-posts.php       # Post management
│   ├── manage-users.php       # User management
│   └── manage-comments.php    # Comment moderation
├── posts/
│   └── view.php               # Single post view
├── comments/
│   └── add.php                # Comment submission
├── index.php                  # Main homepage
├── admin.php                  # Admin dashboard
└── database.sql               # Database schema
```

## 🎨 Design Features

- **Color Scheme**: Dark blue (#16234d), Orange accent (#ff9800)
- **Responsive Layout**: Mobile-friendly design
- **Modern UI**: Clean cards, shadows, and animations
- **Typography**: Professional font stack
- **Grid System**: Flexible layout system

## 🔒 Security Features

- **Password Hashing**: Secure password storage
- **CSRF Protection**: Cross-site request forgery prevention
- **Rate Limiting**: Login and registration attempt limits
- **Input Validation**: Comprehensive data validation
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Output escaping
- **Session Security**: Secure session management

## 👥 User Roles

### Regular User

- View blog posts
- Add comments (pending approval)
- Access personal dashboard
- View own posts and comments

### Admin User

- All user permissions
- Create, edit, delete posts
- Manage user accounts
- Moderate comments
- View admin dashboard with statistics

## 🚀 Usage Guide

### For Users

1. **Register**: Create an account with username, email, and password
2. **Login**: Access your dashboard and view posts
3. **Comment**: Add comments on posts (requires admin approval)
4. **Dashboard**: View your posts and comment history

### For Admins

1. **Login**: Use admin credentials to access admin panel
2. **Create Posts**: Write and publish blog posts
3. **Manage Users**: View, promote, or delete user accounts
4. **Moderate Comments**: Approve or reject user comments
5. **View Statistics**: Monitor platform activity

## 🛠 Customization

### Adding New Features

1. Create new PHP files in appropriate directories
2. Update database schema if needed
3. Add navigation links in `partials/navbar.php`
4. Style new components in `assets/css/style.css`

### Modifying Design

1. Edit `assets/css/style.css` for styling changes
2. Update color variables in CSS root
3. Modify components in `partials/` directory

### Database Changes

1. Update `database.sql` for schema changes
2. Modify helper functions in `includes/db.php`
3. Update related PHP files

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**

   - Check XAMPP MySQL is running
   - Verify database credentials in `includes/db.php`
   - Ensure database `php_blog_app` exists

2. **Page Not Found (404)**

   - Check file paths are correct
   - Ensure Apache is running
   - Verify project is in correct XAMPP directory

3. **Login Issues**

   - Check if user exists in database
   - Verify password hashing is working
   - Check session configuration

4. **Permission Errors**
   - Ensure proper file permissions
   - Check directory structure
   - Verify includes paths

### Debug Mode

Enable error reporting by adding to PHP files:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📝 Development Notes

- All database queries use prepared statements
- Passwords are hashed using `password_hash()`
- CSRF tokens are generated for all forms
- Rate limiting prevents abuse
- Security events are logged

## 🔄 Updates and Maintenance

### Regular Tasks

- Monitor security logs
- Update dependencies
- Backup database regularly
- Review user activity

### Security Updates

- Keep PHP and MySQL updated
- Review security logs
- Update password requirements
- Monitor for vulnerabilities

## 📞 Support

For technical support or questions:

1. Check this setup guide
2. Review error logs
3. Verify all requirements are met
4. Test with default credentials

---

**Built with ❤️ using PHP, MySQL, and modern web technologies**
