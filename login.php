<?php
require_once "includes/db_connect.php";
require_once "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        if (password_verify($password, $user["password_hash"])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        }
    }

    echo "<div class='alert alert-danger'>Invalid login.</div>";
}
?>

<h2>Login</h2>

<form method="POST" class="w-50">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>

    <label>Password</label>
    <input type="password" name="password" class="form-control" required>

    <button class="btn btn-primary mt-3">Login</button>
</form>

<?php require_once "includes/footer.php"; ?>
