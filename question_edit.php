<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

if (!isAdmin()) {
    echo "<div class='alert alert-danger'>Access denied.</div>";
    require_once "includes/footer.php";
    exit;
}

$id = $_GET['id'];

// Fetch question
$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$q = $stmt->fetch();

if (!$q) {
    echo "<div class='alert alert-danger'>Question not found.</div>";
    require_once "includes/footer.php";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("
        UPDATE questions
        SET question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, category=?
        WHERE id=?
    ");
    $stmt->execute([
        $_POST['question_text'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $_POST['correct_option'],
        $_POST['category'],
        $id
    ]);

    echo "<div class='alert alert-success'>Updated successfully.</div>";
}
?>

<h2>Edit Question</h2>

<form method="POST">

    <label>Question Text</label>
    <textarea name="question_text" class="form-control" required><?= $q['question_text'] ?></textarea>

    <label class="mt-2">Option A</label>
    <input type="text" class="form-control" name="option_a" value="<?= $q['option_a'] ?>" required>

    <label class="mt-2">Option B</label>
    <input type="text" class="form-control" name="option_b" value="<?= $q['option_b'] ?>" required>

    <label class="mt-2">Option C</label>
    <input type="text" class="form-control" name="option_c" value="<?= $q['option_c'] ?>" required>

    <label class="mt-2">Option D</label>
    <input type="text" class="form-control" name="option_d" value="<?= $q['option_d'] ?>" required>

    <label class="mt-2">Correct Option</label>
    <select name="correct_option" class="form-control">
        <option <?= $q['correct_option']=='A'?'selected':'' ?>>A</option>
        <option <?= $q['correct_option']=='B'?'selected':'' ?>>B</option>
        <option <?= $q['correct_option']=='C'?'selected':'' ?>>C</option>
        <option <?= $q['correct_option']=='D'?'selected':'' ?>>D</option>
    </select>

    <label class="mt-2">Category</label>
    <input type="text" class="form-control" name="category" value="<?= $q['category'] ?>">

    <button class="btn btn-primary mt-3">Save Changes</button>
</form>

<?php require_once "includes/footer.php"; ?>
