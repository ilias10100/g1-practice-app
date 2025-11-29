<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

$attempt_id = $_GET['attempt_id'];

// Fetch attempt summary
$stmt = $pdo->prepare("
    SELECT * FROM exam_attempts WHERE id = ? AND user_id = ?
");
$stmt->execute([$attempt_id, $_SESSION['user_id']]);
$attempt = $stmt->fetch();

if (!$attempt) {
    echo "<div class='alert alert-danger'>Invalid attempt.</div>";
    require_once "includes/footer.php";
    exit;
}

// Fetch answers
$stmt = $pdo->prepare("
    SELECT q.*, a.selected_option, a.is_correct
    FROM exam_answers a
    JOIN questions q ON a.question_id = q.id
    WHERE a.attempt_id = ?
");
$stmt->execute([$attempt_id]);
$answers = $stmt->fetchAll();
?>

<h2>Your Results</h2>
<h4>Score: <?= $attempt['score'] ?>/<?= $attempt['total_questions'] ?></h4>

<?php foreach ($answers as $index => $q): ?>
    <div class="card p-3 my-3 <?= $q['is_correct'] ? 'border-success' : 'border-danger' ?>">
        <h5>Question <?= $index + 1 ?></h5>
        <p><?= htmlspecialchars($q['question_text']) ?></p>

        <p><strong>Your Answer:</strong> <?= $q['selected_option'] ?></p>
        <p><strong>Correct Answer:</strong> <?= $q['correct_option'] ?></p>
    </div>
<?php endforeach; ?>

<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>

<?php require_once "includes/footer.php"; ?>
