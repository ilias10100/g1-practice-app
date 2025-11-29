<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

$attempt_id = $_POST['attempt_id'];
$question_ids = $_POST['question_ids'];
$score = 0;

foreach ($question_ids as $qid) {
    $selected = $_POST["answer_$qid"];

    // Fetch correct answer
    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE id = ?");
    $stmt->execute([$qid]);
    $correct = $stmt->fetchColumn();

    $is_correct = ($selected === $correct) ? 1 : 0;
    if ($is_correct) $score++;

    // Store each answer
    $stmt = $pdo->prepare("
        INSERT INTO exam_answers (attempt_id, question_id, selected_option, is_correct)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$attempt_id, $qid, $selected, $is_correct]);
}

// Update final score
$stmt = $pdo->prepare("
    UPDATE exam_attempts
    SET score = ?, finished_at = NOW()
    WHERE id = ?
");
$stmt->execute([$score, $attempt_id]);

// Redirect to results
header("Location: view_attempt.php?attempt_id=" . $attempt_id);
exit;
