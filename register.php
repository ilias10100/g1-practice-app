<?php
require_once "includes/db_connect.php";
require_once "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password_hash]);
        echo "<div class='alert alert-success'>Account created! You can now <a href='login.php'>login</a>.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Email already exists.</div>";
    }
}
?>

<h2>Register</h2>

<form method="POST" class="w-50">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>

    <label>Email</label>
    <input type="email" name="email" class="form-control" required>

    <label>Password</label>
    <input type="password" name="password" class="form-control" required>

    <button class="btn btn-primary mt-3">Register</button>
</form>

<?php require_once "includes/footer.php"; ?>
