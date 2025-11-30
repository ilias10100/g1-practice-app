# G1 Practice Exam App

This project is a simple web app to practice for the Ontario G1 written test.  
Users can create an account, log in, take a 40-question practice exam, see their score, and review their previous attempts.

---

## Features

- **User Registration & Login**  
  Each user has their own account and exam history.

- **40-Question Practice Exam**  
  The app loads 40 G1-style multiple choice questions from a MySQL database.

- **Automatic Grading**  
  After submitting the exam, the app calculates the score and shows how many questions were correct.

- **Attempt History**  
  Users can see a list of all their previous exams and open any attempt to review the questions and their answers.

---

## Requirements

- PHP 8+  
- MySQL server (local)  
- Apache (XAMPP/WAMP) or PHP built-in server  
- A web browser

---

## Setup

### 1. Put the project in your server folder

For XAMPP:

```text
C:\xampp\htdocs\G1-app\
Then the main files look like:

index.php – home page

login.php, register.php, logout.php

dashboard.php – user stats + start exam

start_exam.php, take_exam.php, submit_exam.php

my_attempts.php, view_attempt.php

includes/ – db_connect.php, auth.php, header.php, footer.php

css/style.css

2. Create the database and tables
In MySQL (phpMyAdmin, VS Code, etc.), run:

sql
Copy code
CREATE DATABASE g1_practice;
USE g1_practice;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password_hash VARCHAR(255),
    role ENUM('user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option ENUM('A','B','C','D'),
    category VARCHAR(50),
    is_active TINYINT(1) DEFAULT 1
);

CREATE TABLE exam_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    finished_at DATETIME,
    score INT,
    total_questions INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE exam_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT,
    question_id INT,
    selected_option ENUM('A','B','C','D'),
    is_correct TINYINT(1),
    FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id),
    FOREIGN KEY (question_id) REFERENCES questions(id)
);
Then insert the 40 G1 questions using the SQL script (the same one used in my setup).

3. Configure database connection
Edit includes/db_connect.php and put your own MySQL info:

php
Copy code
<?php
$dsn = "mysql:host=127.0.0.1;dbname=g1_practice;charset=utf8mb4";
$username = "root";                 // or your MySQL username
$password = "YOUR_MYSQL_PASSWORD";  // your MySQL password

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
Usage
Start your server (Apache + MySQL if using XAMPP).

Open the app in your browser:

text
Copy code
http://localhost/G1-app/
Register a new user.

Log in and click Start Exam on the dashboard.

Answer all 40 questions and submit the exam.

Check your score and use My Attempts to review older exams.

Notes
This project is for learning/practice only.

It is not an official G1 exam, just a practice tool based on G1-style questions.

pgsql
Copy code

You can just change the folder name or DB name if you and your friend use something slightly different.
