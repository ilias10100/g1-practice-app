<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

$attempt_id = $_GET['attempt_id'] ?? null;

if (!$attempt_id) {
    echo "<div class='alert alert-danger'>Attempt ID missing.</div>";
    require_once "includes/footer.php";
    exit;
}

// Fetch attempt summary
$stmt = $pdo->prepare("SELECT * FROM exam_attempts WHERE id = ? AND user_id = ?");
$stmt->execute([$attempt_id, $_SESSION['user_id']]);
$attempt = $stmt->fetch();

if (!$attempt) {
    echo "<div class='alert alert-danger'>Invalid attempt or access denied.</div>";
    require_once "includes/footer.php";
    exit;
}

// Fetch answers, joining with questions to get text, correct_option, and the new image_path
// NOTE: We rely on the 'questions' table having the 'image_path' column now.
$stmt = $pdo->prepare("
    SELECT q.*, a.selected_option, a.is_correct
    FROM exam_answers a
    JOIN questions q ON a.question_id = q.id
    WHERE a.attempt_id = ?
    ORDER BY a.id ASC
");
$stmt->execute([$attempt_id]);
$answers = $stmt->fetchAll();

// Calculate percentage and status for the header
$total_q = $attempt['total_questions'];
$score_perc = round(($attempt['score'] / $total_q) * 100);
$score_color_class = $score_perc >= 80 ? 'score-pass' : 'score-fail'; 
?>

<h2 class="mb-4">Review Exam Attempt: #<?= $attempt_id ?></h2>
<h4 class="mb-5">Final Score: 
    <span class="score-badge <?= $score_color_class ?>">
        <?= $attempt['score'] ?>/<?= $total_q ?>
    </span>
</h4>

<?php foreach ($answers as $index => $q): 
    // Determine CSS class for the entire question block's left border
    $card_class = $q['is_correct'] ? 'review-correct' : 'review-incorrect';
    $status_text = $q['is_correct'] ? 'CORRECT' : 'INCORRECT';
?>
    
    <div class="card-custom question-review-block <?= $card_class ?> my-4">
        
        <h5 class="mb-3">
            Question <?= $index + 1 ?>: 
            <span class="status-label"><?= $status_text ?></span>
        </h5>
        
        <?php if (!empty($q['image_path'])): ?>
            <div class="text-center mb-4 p-3 review-image-container">
                <img src="<?= htmlspecialchars($q['image_path']) ?>" class="img-fluid question-image" alt="Question Image">
            </div>
        <?php endif; ?>
        
        <p class="mb-4 lead"><?= htmlspecialchars($q['question_text']) ?></p>
        
        <div class="options-group">
            <?php 
            $options = ['A', 'B', 'C', 'D'];
            foreach ($options as $option_key):
                $option_text = $q['option_' . strtolower($option_key)];
                
                // Determine styling for this specific option
                $is_selected = ($q['selected_option'] === $option_key);
                $is_correct_answer = ($q['correct_option'] === $option_key);
                
                $option_class = '';
                $icon = '';
                
                if ($is_correct_answer) {
                    $option_class = 'option-correct';
                    $icon = '<i class="fas fa-check-circle ms-auto" title="Correct Answer"></i>'; // Green Check
                } elseif ($is_selected) {
                    $option_class = 'option-wrong';
                    $icon = '<i class="fas fa-times-circle ms-auto" title="Your Selection"></i>'; // Red X
                }
            ?>
                <div class="form-check p-3 my-2 <?= $option_class ?>">
                    <label class="form-check-label d-flex align-items-center" for="option_<?= $option_key ?>_<?= $q['id'] ?>">
                        <span class="badge option-key me-2"><?= $option_key ?></span>
                        <?= htmlspecialchars($option_text) ?>
                        <?= $icon ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
<?php endforeach; ?>

<a href="my_attempts.php" class="btn btn-outline-light mt-4">Back to Attempts</a>

<?php require_once "includes/footer.php"; ?>