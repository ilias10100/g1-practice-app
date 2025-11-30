1. Project Description and Features
The G1 Practice Exam Application is a dynamic, user-friendly web application designed to help users prepare for their G1 driver's license knowledge test. Built on a robust PHP backend and MySQL database, the application provides a realistic exam environment, tracks user progress, and offers a comprehensive review system.

Key Features:
- User Authentication: Secure login and registration for users (and administrators).
- Realistic Exam: Fetches 40 random, active questions from the database for a timed practice test.
- Progress Tracking: Users can view their total attempts, best score, and latest score on the dashboard.
- Detailed Review System: (NEW) The My Attempts page and View Attempt page are fully styled with a professional dark theme, using green/red    indicators and highlighting to clearly show the correct answer versus the user's selected answer.
- Visual Questions: Supports embedding images (e.g., traffic signs) directly into questions for enhanced learning.
- Administrator Panel: Functionality to create, edit, and delete exam questions.

2. Installation Instructions (XAMPP/Local Setup)
This project is built using the LAMP/XAMPP stack (Linux/Windows/macOS, Apache, MySQL, PHP).
- Install XAMPP: Download and install XAMPP (or MAMP/WAMP) on your local machine.
- Clone the Repository: Clone this project into your XAMPP web root directory, typically: C:\xampp\htdocs\g1-practice-app
- Start Services: Open the XAMPP Control Panel and start the Apache and MySQL services.
- Database Setup: Proceed to the Database Setup Instructions section below.
- Access App: Once the database is set up, open your web browser and navigate to: http://localhost/g1-practice-app/

3. Database Setup Instructions
The application requires a MySQL database named g1_practice.
- Create the Database:
    - Open phpMyAdmin in your browser: http://localhost/phpmyadmin/
    - Click the New link in the left sidebar.
    - Enter g1_practice as the database name and click Create.
- Create Tables
    - Run the following SQL code in the SQL tab of your new g1_practice database to create the required tables (users, questions, exam_attempts, exam_answers).

    -- 1. USERS Table
    CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- 2. QUESTIONS Table
    CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option CHAR(1) NOT NULL, -- 'A', 'B', 'C', or 'D'
    category VARCHAR(50) DEFAULT 'General',
    image_path VARCHAR(255) DEFAULT NULL, -- NEW: For sign-based questions
    is_active BOOLEAN DEFAULT TRUE
    );

    -- 3. EXAM_ATTEMPTS Table
    CREATE TABLE exam_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    started_at DATETIME NOT NULL,
    finished_at DATETIME,
    total_questions INT DEFAULT 40,
    score INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    -- 4. EXAM_ANSWERS Table
    CREATE TABLE exam_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_option CHAR(1),
    is_correct BOOLEAN NOT NULL,
    FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    );

4. User Guide
- Registration & Login:
    - New users must register an account (register.php). They can then log in using their credentials (login.php).
- Dashboard:
    - After logging in, users are presented with a dashboard showing their latest stats and a prominent button to start a new exam.
- Taking the Exam:
    - The exam presents 40 random questions. Questions related to road signs will display the relevant image above the question text. 
- Reviewing Attempts:
    - The "My Attempts" page lists all past exams with a clear, color-coded status (PASS/REVIEW/FAIL). Clicking "View" takes the user to the review page.
- Review Screen Features:
    - Each question block is bordered Green if correct and Red if incorrect.

5. Group Member Contributions

Ilias Taabich
Backend Core & Admin Management: Designed and implemented the initial Database Schema. Developed Core Authentication Logic (login.php, register.php). Implemented the full CRUD (Create, Read, Update, Delete) functionality for the Question Management pages.

Wiam Salam
Aesthetic & System Integrity: Implemented the full, custom Dark Theme by writing and managing style.css. Resolved critical Database Date Depreciation Issues (my_attempts.php). Implemented the Image Path Support logic in both take_exam.php and view_attempt.php.

Aya Sahnoune
Frontend Structure & Documentation: Established the Base HTML Structure for all application files (including integration of header.php and footer.php). Applied foundational Bootstrap Layout to core pages (e.g., Login/Register forms, Admin table). Gathered and compiled all project member ideas and wrote the content for the topic proposal file.