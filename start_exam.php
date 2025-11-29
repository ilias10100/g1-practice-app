<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";

// Create new exam attempt
$stmt = $pdo->prepare("
    INSERT INTO exam_attempts (user_id, score, total_questions)
    VALUES (?, 0, 40)
");
$stmt->execute([$_SESSION['user_id']]);

$attempt_id = $pdo->lastInsertId();

// Redirect to start answering questions
header("Location: take_exam.php?attempt_id=" . $attempt_id);
exit;
