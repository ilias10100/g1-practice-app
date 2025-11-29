<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

if (!isset($_GET['attempt_id'])) {
    echo "<div class='alert alert-danger'>Invalid attempt.</div>";
    require_once "includes/footer.php";
    exit;
}

$attempt_id = $_GET['attempt_id'];

// Fetch 40 random questions
$stmt = $pdo->query("SELECT * FROM questions WHERE is_active = 1 ORDER BY RAND() LIMIT 40");
$questions = $stmt->fetchAll();
?>

<h2>G1 Practice Exam</h2>
<form method="POST" action="submit_exam.php">

    <input type="hidden" name="attempt_id" value="<?= $attempt_id ?>">

    <?php foreach ($questions as $index => $q): ?>
        <div class="card p-3 my-3">
            <h5>Question <?= $index + 1 ?></h5>
            <p><?= htmlspecialchars($q['question_text']) ?></p>

            <input type="hidden" name="question_ids[]" value="<?= $q['id'] ?>">

            <div>
                <label><input type="radio" name="answer_<?= $q['id'] ?>" value="A" required> A. <?= $q['option_a'] ?></label><br>
                <label><input type="radio" name="answer_<?= $q['id'] ?>" value="B"> B. <?= $q['option_b'] ?></label><br>
                <label><input type="radio" name="answer_<?= $q['id'] ?>" value="C"> C. <?= $q['option_c'] ?></label><br>
                <label><input type="radio" name="answer_<?= $q['id'] ?>" value="D"> D. <?= $q['option_d'] ?></label>
            </div>
        </div>
    <?php endforeach; ?>

    <button class="btn btn-primary btn-lg">Submit Exam</button>
</form>

<?php require_once "includes/footer.php"; ?>
