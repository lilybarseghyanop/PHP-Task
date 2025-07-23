# PHP Authentication System

A simple user authentication system built with **PHP** and **MySQL**, allowing users to register, log in, and access a protected dashboard. Includes profile photo upload, password validation, and password reset features.

---

## 🚀 Features

- ✅ Sign-Up page with:
  - Name
  - Email
  - Password
  - Confirm Password
  - Gender (radio)
  - Hobbies (checkboxes)
  - Profile photo upload
- ✅ Sign-In page with:
  - Email and password login
  - Validation and error messages
- ✅ Home page:
  - Displays user info from the database
  - Only accessible when logged in
- ✅ Password reset page
- ✅ CSS styling for all pages

---

## 📁 Project Structure

auth_app/
├── config.php # Database connection file
├── signup.php # User registration
├── signin.php # User login
├── home.php # Dashboard page
├── logout.php # Log out user
├── reset_password.php # Password reset form
├── uploads/ # Stores uploaded profile photos
├── assets/
│ └── style.css # CSS styles
└── README.md # Project documentation


---

## 🛠️ How to Use

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/auth_app.git
cd auth_app
```


✅ 2. Make Sure PHP & MySQL Are Installed
To verify:
```
php -v
mysql --version

```

If missing:

macOS with Homebrew:
```
brew install php
brew install mysql
brew services start mysql
(or brew install phpmyadmin)
```
Ubuntu/Linux:
```
sudo apt update
sudo apt install php mysql-server
sudo service mysql start
```



### Use phpMyAdmin or run this SQL manually:
In terminal:
```
mysql -u root

CREATE DATABASE auth_app;

USE auth_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  gender VARCHAR(10),
  hobbies TEXT,
  profile_photo VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```


If you're using the terminal:

```
php -S localhost:8000
```
Then open your browser and go to:
```
http://localhost:8000/signup.php
```
