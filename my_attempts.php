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
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $a): ?>
                <tr>
                    <td><?= $a['finished_at'] ?></td>
                    <td><?= $a['score'] ?>/<?= $a['total_questions'] ?></td>
                    <td><a class="btn btn-sm btn-primary" href="view_attempt.php?attempt_id=<?= $a['id'] ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

<?php require_once "includes/footer.php"; ?>
