# Shopy - Laravel E-Commerce System

A complete e-commerce solution built with Laravel featuring both frontend customer interface and backend admin panel.

## Features

### Frontend (Customer)
- Product browsing with search and filtering
- Shopping cart functionality
- User registration and authentication
- Order placement and tracking
- Product reviews
- Coupon code application

### Backend (Admin)
- Dashboard with statistics
- Product management (CRUD)
- Category management
- Order processing
- Customer management
- Review moderation
- Coupon management
- PDF invoice generation
- Excel customer export

## Technical Stack
- Laravel 10.x
- PHP 8.1+
- MySQL
- Bootstrap 5
- jQuery
- Intervention Image
- DomPDF
- Maatwebsite Excel
- Spatie Laravel Permission

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `php artisan serve`
7. Visit `http://localhost:8000`

## Admin Access
- Email: admin@gmail.com
- Password: password

## Security Features
- CSRF protection
- Input validation
- Password hashing
- Role-based access control
- SQL injection prevention
- XSS protection

## License
MIT License