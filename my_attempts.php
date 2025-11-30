<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

$user_id = $_SESSION['user_id'];

// Fetch attempts
$stmt = $pdo->prepare("
    SELECT * FROM exam_attempts
    WHERE user_id = ?
    ORDER BY finished_at DESC
");
$stmt->execute([$user_id]);
$attempts = $stmt->fetchAll();
?>

<h2>My Exam Attempts</h2>

<?php if (count($attempts) === 0): ?>
    <div class="alert alert-info mt-3">You haven't taken any exams yet.</div>
<?php else: ?>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Date / Time</th>
                <th>Score</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $index => $a): 
                
                $score = $a['score'];
                $total_questions = $a['total_questions'] ?: 40; // Default to 40
                $percentage = round(($score / $total_questions) * 100);
                
                // Determine the score-badge class based on percentage (80% passing)
                if ($percentage >= 80) { 
                    $badge_class = 'score-pass';
                    $status_text = 'PASS';
                } elseif ($percentage >= 70) {
                    $badge_class = 'score-warning';
                    $status_text = 'Review';
                } else {
                    $badge_class = 'score-fail';
                    $status_text = 'FAIL';
                }
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    
                    <td>
                        <?php 
                        // Check if the date is set AND it's not the zero date string
                        if ($a['finished_at'] && $a['finished_at'] !== '0000-00-00 00:00:00') {
                            echo date('M j, Y H:i', strtotime($a['finished_at']));
                        } else {
                            // If the date is missing or zero, show a clean message
                            echo '<span class="text-warning-50">In Progress / Pending</span>'; 
                        }
                        ?>
                    </td>
                    
                    <td>
                        <span class="score-badge <?= $badge_class ?>">
                            <?= $score ?>/<?= $total_questions ?>
                        </span>
                    </td>
                    
                    <td>
                        <strong class="<?= $badge_class ?>"><?= $status_text ?></strong>
                    </td>
                    
                    <td><a class="btn btn-sm btn-primary" href="view_attempt.php?attempt_id=<?= $a['id'] ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="dashboard.php" class="btn btn-outline-light mt-3">Back to Dashboard</a>

<?php require_once "includes/footer.php"; ?>