<?php
require_once "includes/auth.php";      // Protect page
require_once "includes/db_connect.php";
require_once "includes/header.php";

// Fetch user stats
$user_id = $_SESSION['user_id'];

// Total attempts
$stmt = $pdo->prepare("SELECT COUNT(*) FROM exam_attempts WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_attempts = $stmt->fetchColumn();

// Best score
$stmt = $pdo->prepare("SELECT MAX(score) FROM exam_attempts WHERE user_id = ?");
$stmt->execute([$user_id]);
$best_score = $stmt->fetchColumn();

// Latest attempt
$stmt = $pdo->prepare("SELECT score, finished_at FROM exam_attempts WHERE user_id = ? ORDER BY finished_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$latest = $stmt->fetch();
?>

<h1>Welcome back!</h1>

<div class="row mt-4">

    <!-- Stats card -->
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h4>Your Progress</h4>

            <p><strong>Total Attempts:</strong> <?= $total_attempts ?></p>
            <p><strong>Best Score:</strong> <?= $best_score !== null ? $best_score : "No attempts yet" ?>/40</p>

            <p><strong>Latest Attempt:</strong><br>
                <?php if ($latest): ?>
                    Score: <?= $latest['score'] ?>/40<br>
                    Date: <?= $latest['finished_at'] ?>
                <?php else: ?>
                    You haven't taken any exams yet.
                <?php endif; ?>
            </p>

            <a href="my_attempts.php" class="btn btn-outline-primary mt-2">View All Attempts</a>
        </div>
    </div>

    <!-- Start Exam card -->
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h4>Start a New Practice Exam</h4>
            <p>You will receive 40 randomized G1 questions.</p>

            <a href="start_exam.php" class="btn btn-success btn-lg mt-3">Start Exam</a>
        </div>
    </div>

</div>

<?php require_once "includes/footer.php"; ?>
