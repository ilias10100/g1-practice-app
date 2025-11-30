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

// Calculate Best Score Percentage 
$total_questions = 40; 
$best_score_display = $best_score !== null ? $best_score : 0;
// Calculate the percentage, defaulting to 0 if no attempts have been made
$best_score_percentage = ($best_score_display > 0) ? round(($best_score_display / $total_questions) * 100) : 0;
// --------------------------------------------------------

?>

<h1>Welcome back!</h1>

<div class="row mt-4">

    <div class="col-md-6">
        <div class="card-custom h-100 d-flex flex-column justify-content-between">
            
            <div> <h4>Your Progress</h4>

                <p><strong>Total Attempts:</strong> <?= $total_attempts ?></p>
                
                <p><strong>Best Score:</strong> <?= $best_score !== null ? $best_score : "No attempts yet" ?>/40</p>
                
                <p class="mb-2"><strong>Best Score Percentage:</strong></p>
                <div class="score-percentage-display mb-4" data-progress="<?= $best_score_percentage ?>">
                    <span class="display-4"><?= $best_score_percentage ?>%</span> 
                </div>
                <p><strong>Latest Attempt:</strong><br>
                    <?php if ($latest): ?>
                        Score: <?= $latest['score'] ?>/40<br>
                        Date: <?= $latest['finished_at'] ?>
                    <?php else: ?>
                        You haven't taken any exams yet.
                    <?php endif; ?>
                </p>
            </div>
            
            <a href="my_attempts.php" class="btn btn-outline-light mt-4">View All Attempts</a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-custom h-100 d-flex flex-column justify-content-between">
            
            <div> <h4>Start a New Practice Exam</h4>
                <p>You will receive 40 randomized G1 questions.</p>
                
                <div class="text-center mb-5 mt-3">
                    <img src="images/dashimg.jpg" class="img-fluid card-image-sm" alt="Road Signs and Symbols">
                </div>
                </div>

            <a href="start_exam.php" class="btn btn-success mt-3">Start Exam</a>
        </div>
    </div>

</div>

<?php require_once "includes/footer.php"; ?>