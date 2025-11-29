<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

if (!isAdmin()) {
    echo "<div class='alert alert-danger'>Access denied.</div>";
    require_once "includes/footer.php";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("
        INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option, category)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['question_text'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $_POST['correct_option'],
        $_POST['category']
    ]);

    echo "<div class='alert alert-success'>Question added successfully.</div>";
}
?>

<h2>Add New Question</h2>

<form method="POST" class="mt-3">

    <label>Question Text</label>
    <textarea name="question_text" class="form-control" required></textarea>

    <label class="mt-2">Option A</label>
    <input type="text" name="option_a" class="form-control" required>

    <label class="mt-2">Option B</label>
    <input type="text" name="option_b" class="form-control" required>

    <label class="mt-2">Option C</label>
    <input type="text" name="option_c" class="form-control" required>

    <label class="mt-2">Option D</label>
    <input type="text" name="option_d" class="form-control" required>

    <label class="mt-2">Correct Option</label>
    <select name="correct_option" class="form-control" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select>

    <label class="mt-2">Category</label>
    <input type="text" name="category" class="form-control" value="Rules">

    <button class="btn btn-primary mt-3">Add Question</button>
</form>

<?php require_once "includes/footer.php"; ?>
