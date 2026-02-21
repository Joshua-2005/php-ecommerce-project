# 🛒 PHP E-Commerce Web Application

A full-stack E-Commerce website built using PHP and MySQL.

This project demonstrates user authentication, role-based access control, product management, cart system, and checkout functionality.

---

## 🚀 Live Demo

If deployed online:

(https://ecommerse.infinityfree.me/?i=1)

---

## 📌 Features

### 👤 User Features
- User Registration
- Secure Login System (Password Hashing)
- Role-Based Redirection (Admin/User)
- Add to Cart
- Update Cart Quantity
- Remove Items from Cart
- Checkout System
- Order Confirmation Page

### 🛠 Admin Features
- Admin Login
- Protected Admin Dashboard
- Add New Products (With Image Upload)
- Edit Products
- Delete Products
- Manage All Products

---

## 🔐 Security Features

- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Session-based authentication
- Role-based access control
- Protected admin pages

---

## 🧰 Technologies Used

- PHP
- MySQL
- PDO
- HTML
- CSS
- XAMPP (Local Development)
- InfinityFree (Hosting)

---

## 🗄 Database Setup Instructions

1. Install XAMPP
2. Start Apache and MySQL
3. Open phpMyAdmin
4. Create a new database
5. Import the provided `.sql` file
6. Update database credentials in:

includes/db.php

Example:

$host = "your_host";
$dbname = "your_database";
$user = "your_username";
$password = "your_password";

---

## 📂 Project Structure

/admin  
    dashboard.php  
    add_product.php  
    manage_products.php  
    edit_product.php  
    delete_product.php  

/pages  
    login.php  
    register.php  
    cart.php  
    checkout.php  

/includes  
    db.php  

/images  

index.php  

---

## 🎯 Learning Outcomes

This project helped in understanding:

- Full-stack web development
- Database design
- CRUD operations
- Session handling
- Authentication & Authorization
- Cart and order management logic
- File upload handling

---


## 👨‍💻 Author

Joshua  
B.Tech Student  
Aspiring Full Stack Developer  

---

⭐ If you like this project, feel free to star the repository!
