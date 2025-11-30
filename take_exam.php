<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

$user_id = $_SESSION['user_id']; // For future use, if you want to log exam start time etc.

// Fetch 40 random questions that are active
$stmt = $pdo->query("SELECT * FROM questions WHERE is_active = 1 ORDER BY RAND() LIMIT 40");
$questions = $stmt->fetchAll();

if (empty($questions)) {
    echo "<div class='alert alert-warning'>No active questions available for the exam. Please contact support.</div>";
    require_once "includes/footer.php";
    exit;
}

?>

<h1>G1 Practice Exam</h1>
<p class="lead text-white-50">Answer all questions to complete the exam.</p>

<form action="submit_exam.php" method="POST">
    <?php foreach ($questions as $index => $q): ?>
        
        <div class="card-custom p-4 my-3 question-block">
            
            <h5 class="mb-3">Question <?= $index + 1 ?></h5>
            
            <?php if (!empty($q['image_path'])): ?>
                <div class="text-center mb-4 p-3" style="background: #1e293b; border-radius: 8px;">
                    <img src="<?= htmlspecialchars($q['image_path']) ?>" class="img-fluid question-image" alt="Question Image" style="max-height: 180px;">
                </div>
            <?php endif; ?>
            
            <p class="mb-4 lead"><?= htmlspecialchars($q['question_text']) ?></p>
            
            <input type="hidden" name="question_ids[]" value="<?= $q['id'] ?>">
            
            <div class="options-group">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="answer_<?= $q['id'] ?>" id="option_a_<?= $q['id'] ?>" value="A" required>
                    <label class="form-check-label" for="option_a_<?= $q['id'] ?>">A. <?= htmlspecialchars($q['option_a']) ?></label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="answer_<?= $q['id'] ?>" id="option_b_<?= $q['id'] ?>" value="B">
                    <label class="form-check-label" for="option_b_<?= $q['id'] ?>">B. <?= htmlspecialchars($q['option_b']) ?></label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="answer_<?= $q['id'] ?>" id="option_c_<?= $q['id'] ?>" value="C">
                    <label class="form-check-label" for="option_c_<?= $q['id'] ?>">C. <?= htmlspecialchars($q['option_c']) ?></label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="answer_<?= $q['id'] ?>" id="option_d_<?= $q['id'] ?>" value="D">
                    <label class="form-check-label" for="option_d_<?= $q['id'] ?>">D. <?= htmlspecialchars($q['option_d']) ?></label>
                </div>
            </div>
            
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary btn-lg mt-4">Submit Exam</button>
</form>

<?php require_once "includes/footer.php"; ?>