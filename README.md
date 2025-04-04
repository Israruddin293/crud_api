# 🔐 Role-Based CRUD Web Application

A full-featured role-based CRUD (Create, Read, Update, Delete) web application built using **PHP (Backend)**, **JavaScript (Frontend)**, and **MySQL (Database)**. The application supports **user authentication**, **role-based access control**, and a **responsive interface** for managing user data.

---

## 📌 Features

- 🔐 **User Authentication** – Signup, Login, and Logout functionalities with secure password hashing.
- 🧑‍⚖️ **Role-Based Access** – Admin users can perform full CRUD operations; regular users have limited access.
- 🧾 **CRUD Operations** – Add, View, Update, and Delete users via RESTful API.
- 🛠️ **RESTful PHP API** – All backend operations are handled through clean API endpoints.
- 💡 **Real-Time Interactions** – Frontend uses JavaScript to dynamically update the UI.
- 🧰 **MySQL Database** – Stores user information securely.
- 🎨 **Responsive UI** – Clean and modern design using custom CSS.

---

## ⚙️ Installation & Setup

### 📦 Requirements

- PHP 7.x or above
- MySQL or MariaDB
- Apache Server (XAMPP/LAMP recommended)

### 🚀 Steps to Run Locally

1. **Clone the Repository**
   git clone https://github.com/Israruddin293/crud-app.git
   
   cd crud-app
   
Start Local Server

Start Apache and MySQL using XAMPP or any local server.

Place the project folder inside the Apache htdocs directory.

Create Database

Open phpMyAdmin.

Create a database named crud_app.

Import or allow the app to auto-create the users table on first run.

Run in Browser
http://localhost/crud-app/index.php
🧠 Usage
👤 Sign Up / Login
New users can register via the signup page.

Default role: user.

Admins can be manually created in the DB or via admin panel.

🛠️ Admin Access
View all users.

Add new users.

Edit or delete existing users.

👥 Regular User Access
Can view their own data or the public table (based on permissions).

Cannot modify or delete other users.

🔐 Security Notes
Passwords are stored using password_hash() for strong encryption.

User input is sanitized to prevent SQL injection.

Role checks are performed before allowing any critical operation.


