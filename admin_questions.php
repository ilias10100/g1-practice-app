<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

if (!isAdmin()) {
    echo "<div class='alert alert-danger'>Access denied. Admins only.</div>";
    require_once "includes/footer.php";
    exit;
}

// Fetch all questions
$stmt = $pdo->prepare("SELECT * FROM questions ORDER BY id ASC");
$stmt->execute();
$questions = $stmt->fetchAll();
?>

<h2>Manage Questions</h2>
<a href="question_create.php" class="btn btn-success mb-3">Add New Question</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Correct</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($questions as $q): ?>
            <tr>
                <td><?= $q['id'] ?></td>
                <td><?= htmlspecialchars(substr($q['question_text'], 0, 60)) ?>...</td>
                <td><?= $q['correct_option'] ?></td>
                <td><?= $q['category'] ?></td>
                <td>
                    <a href="question_edit.php?id=<?= $q['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="question_delete.php?id=<?= $q['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this question?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once "includes/footer.php"; ?>
