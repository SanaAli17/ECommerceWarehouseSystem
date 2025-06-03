# SK Logistics Solutions – E-Commerce Warehouse System

A console and web-based E-Commerce Warehouse Management System developed using **C++** and enhanced into a full-stack **Laravel** web application. This project demonstrates efficient use of Data Structures & Algorithms to manage warehouse operations such as product handling, order processing, delivery prioritization, and customer interaction.

## 👩‍💻 Developed By
- Sana Ali 
- Kashaf Zahra 

## 🎯 Problem Statement
Modern e-commerce warehouses struggle with:
- Poor customer interaction and record maintenance
- Lack of prioritization for delivery orders
- Complex user interfaces for warehouse staff

**SK Logistics Solutions** provides a solution by:
- Automating warehouse tasks using optimized data structures
- Enabling priority-based delivery processing
- Offering a user-friendly and secure interface

## ✅ Project Objectives
- Improve customer satisfaction through feedback and complaint handling
- Prioritize delivery orders using a min-heap (based on distance)
- Secure and tailored user authentication
- Implement CRUD operations for product inventory
- Efficient order handling via Takeaway and Home Delivery modes

## 🧠 Key Data Structures Used
- **AVL Tree**: Manages Takeaway orders with fast search and insert/delete operations.
- **Priority Queue (Min Heap)**: Prioritizes Delivery Orders based on shortest distance.
- **Linked List**: Handles dynamic home delivery order management.
- **Queue**: Stores and manages customer complaints (FIFO).
- **Stack**: Stores recent orders (LIFO).

## 🧰 Libraries Used (C++)
- `iostream`, `iomanip`, `algorithm`, `queue`, `stack`, `vector`, `fstream`, `windows.h`, `string`

## 🧱 OOP Concepts Applied
- **Encapsulation**: Organizes code within modular classes
- **Inheritance**: Specialized user roles like DeliveryUser extend base User class
- **Abstraction**: Hides implementation from the interface for user-friendliness

---

## 🌐 Laravel Web Application

The console-based C++ backend logic was translated into a modern Laravel web app with full MVC structure.

### 🔧 Tech Stack
- **Laravel** (PHP MVC Framework)
- **MySQL** (via phpMyAdmin)
- **Blade Templates** for views
- **Bootstrap/CSS** for styling

### 📂 Laravel Modules
- **User Authentication**: Built-in Laravel Auth
- **Models**: User, Product, Order, Feedback, Complaint
- **Controllers**: Handle business logic for each model
- **Routes**: Define navigation and API endpoints

### 🧪 Features
- User Registration & Login
- Add, Edit, Delete Products
- Takeaway & Delivery Order Placement
- Feedback & Complaint Management
- Priority-based Delivery Processing

---

## 🖥️ How to Run

### Console-Based (C++)
1. Compile with any C++11+ compatible compiler
2. Run `main.cpp` file

### Web-Based (Laravel)
1. Install [XAMPP](https://www.apachefriends.org/index.html) and [Composer](https://getcomposer.org/)
2. Clone repo:  https://github.com/SanaAli17/ECommerceWarehouseSystem.git
