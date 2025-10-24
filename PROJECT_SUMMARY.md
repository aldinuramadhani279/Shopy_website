# Shopy - Laravel E-Commerce System

## Project Overview
This is a complete e-commerce system built with Laravel that includes both frontend and backend (admin) interfaces. The system allows customers to browse products, add them to cart, checkout, and manage their orders. Admin users can manage products, categories, orders, customers, reviews, and coupons.

## Features Implemented

### 1. User Authentication
- Customer registration with strong password requirements
- Login/logout functionality
- Role-based access control (admin/customer)

### 2. Admin Panel
- Dashboard with statistics
- Product management (CRUD operations)
- Category management (CRUD operations)
- Order management with status updates
- Customer management with export functionality
- Review management (approve/reject)
- Coupon management (CRUD operations)
- Reports section with analytics
- Settings management

### 2. Admin Panel
- Dashboard with statistics
- Product management (CRUD operations)
- Category management (CRUD operations)
- Order management with status updates
- Customer management with export functionality
- Review management (approve/reject)
- Coupon management (CRUD operations)

### 3. Frontend Store
- Homepage with featured products
- Product catalog with search, filter, and sorting
- Product details page with images and reviews
- Shopping cart functionality
- Checkout process with multiple payment options
- User account dashboard with order history

### 4. Core Functionality
- Product management with categories
- Shopping cart with session storage
- Order processing workflow
- Review system with admin approval
- Coupon system with validation
- Image processing with security measures
- Reports dashboard with analytics
- Settings management

### 5. Security Features
- CSRF protection on all forms
- Input validation and sanitization
- File upload validation and processing
- Password hashing with bcrypt
- SQL injection protection with Eloquent ORM
- XSS protection with Blade templating
- Role-based access control
- Strong password requirements

## Error Fixes and Improvements
### Route Resolution Issues
- Fixed undefined route errors for all admin panel sections (products, categories, orders, customers, reviews, coupons)
- Corrected route naming inconsistencies throughout the application
- Updated view templates to use proper route references
- **Resolved Route Name Collision**: Fixed a critical issue where admin and frontend product/category routes conflicted by uniquely naming admin routes (e.g., `admin.products.index`).
- **Enhanced Frontend Navigation**: Renamed 'Home' to 'Product' and 'Products' to 'Category' in the navbar.
- **Dedicated Category Gallery Page**: Implemented a new, visually appealing `/categories` page displaying only category cards, linking to filtered product lists.
- **Language Consistency**: Ensured all frontend text is consistently in English.

### Database Schema Fixes
- Updated reviews table schema to use appropriate column names
- Fixed foreign key constraint issues
- Resolved migration order problems

### Controller Enhancements
- Improved error handling in all admin controllers
- Enhanced bulk operation functionality
- Fixed validation and redirect issues

### View Improvements
- Corrected all broken links in admin panel
- Fixed form action URLs
- Updated navigation menu references

### Laravel Features Used
- Eloquent ORM for database interactions
- Blade templating engine for views
- Middleware for authentication and authorization
- Request validation for form inputs
- Pagination for large datasets
- File storage and image processing
- Session management for cart functionality
- Route model binding for cleaner controllers

### Frontend Technologies
- Bootstrap 5 for responsive design
- jQuery for interactivity
- DataTables for admin tables
- Chart.js for dashboard charts
- SweetAlert2 for notifications

## File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── OrderController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── CouponController.php
│   │   │   ├── ReviewController.php
│   │   │   └── ReportController.php
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── AccountController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   └── ReviewController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   ├── EnsureAdmin.php
│   │   └── EnsureCustomer.php
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Review.php
│   ├── Coupon.php
│   ├── Cart.php
│   ├── Setting.php
│   └── User.php
├── Helpers/
│   └── ImageHelper.php
├── Rules/
│   └── StrongPassword.php
└── Providers/
    └── ImageHelperServiceProvider.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_categories_table.php
│   ├── create_products_table.php
│   ├── create_carts_table.php
│   ├── create_orders_table.php
│   ├── create_order_items_table.php
│   ├── create_reviews_table.php
│   ├── create_coupons_table.php
│   ├── create_settings_table.php
│   └── update_reviews_table_add_status.php

resources/
├── views/
│   ├── admin/
│   │   ├── layouts/
│   │   ├── dashboard/
│   │   ├── products/
│   │   ├── categories/
│   │   ├── orders/
│   │   ├── customers/
│   │   ├── coupons/
│   │   ├── reviews/
│   │   ├── reports/
│   │   └── settings/
│   ├── auth/
│   ├── frontend/
│   │   ├── layouts/
│   │   ├── home.blade.php
│   │   ├── products/
│   │   ├── cart/
│   │   ├── checkout/
│   │   ├── account/
│   │   └── reviews/
│   └── welcome.blade.php

routes/
├── web.php
└── api.php
```

## Security Measures Implemented
1. **Input Validation**: All user inputs are validated using Laravel's validation system
2. **CSRF Protection**: All forms include CSRF tokens
3. **Password Security**: Passwords are hashed using bcrypt with strong password requirements
4. **File Upload Security**: Image uploads are validated for type, size, and processed securely
5. **SQL Injection Protection**: Eloquent ORM is used for all database queries
6. **XSS Protection**: Blade templating engine automatically escapes output
7. **Role-Based Access Control**: Middleware ensures users can only access appropriate areas
8. **Session Management**: Proper session handling and regeneration

## Error Fixes and Improvements
1. **Route Naming Issues**: Fixed inconsistent route naming across all admin views
2. **Database Schema**: Updated reviews table schema to use proper column names
3. **Controller Methods**: Enhanced controller methods for better error handling
4. **View References**: Corrected all view references to use proper route names
5. **Bulk Operations**: Implemented proper bulk delete and update functionality

## How to Run the Application
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure database settings
4. Run `php artisan key:generate`
5. Create database `shopy` in your MySQL server
6. Run `php artisan migrate`
7. Run `php artisan db:seed` to seed the admin user
8. Run `php artisan serve`
9. Access the application at `http://localhost:8000`

## Admin Access
- Navigate to `http://localhost:8000/admin`
- Login with:
  - Email: admin@gmail.com
  - Password: password

## Admin Credentials
- Email: admin@gmail.com
- Password: password

## Customer Registration
Customers can register freely through the registration form.