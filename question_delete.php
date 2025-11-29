<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";

if (!isAdmin()) {
    die("Access denied.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin_questions.php");
exit;
