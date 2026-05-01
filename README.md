# Computer TK Store - Laravel E-Commerce Website

A full-featured e-commerce web application built with Laravel for selling computers, laptops, and tech accessories.

## Features

### Customer Features
- **Homepage** with hero banner, category showcase, featured products, and latest arrivals
- **Shop Page** with product grid, filters (category, brand, sort), and pagination
- **Product Detail Page** with images, descriptions, pricing, and add-to-cart
- **Shopping Cart** with quantity management and real-time totals
- **Checkout** with order placement
- **Order History** for logged-in users
- **User Registration & Login**

### Admin Features
- **Dashboard** with stats: products, categories, orders, customers, revenue
- **Category Management** (CRUD)
- **Product Management** (CRUD with images, pricing, stock, featured flag)
- **Order Management** with status updates
- **Customer Management**

## Tech Stack
- **Backend**: Laravel 10, PHP 8.1
- **Frontend**: Bootstrap 5, Blade templates
- **Database**: SQLite
- **Auth**: Laravel Breeze

## Admin Credentials
- **Email**: admin@computertkstore.com
- **Password**: admin123

## Sample Data
- **8 Categories**: Laptops, Desktops, Monitors, Keyboards, Mice, Accessories, Components, Headsets
- **23 Products** with real images from Unsplash

## Running the Project

```bash
cd computer-tk-store
php8.1 artisan serve --port=8000
```

Visit: http://localhost:8000

## Key URLs
| Page | URL |
|------|-----|
| Homepage | / |
| Shop | /shop |
| Cart | /cart |
| Login | /login |
| Register | /register |
| Admin Panel | /admin |
| Admin Products | /admin/products |
| Admin Categories | /admin/categories |
| Admin Orders | /admin/orders |
