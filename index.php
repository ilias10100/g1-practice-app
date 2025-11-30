<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Start session if not already started (needed for header.php)
if (session_status() === PHP_SESSION_NONE) session_start(); 

require_once "includes/header.php";

// Redirect logged-in users to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<main class="text-center pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <div class="card-custom p-5">
                
                <h1 class="display-4 mb-4" style="color: #3b82f6;">Master Your G1 Test</h1>
                
                <p class="lead mb-5 text-white-50">
                    Your complete resource for G1 road signs and rule practice tests. Get ready for your license with realistic simulations!
                </p>
                
                <div class="mb-5">
                    <img src="images/index logo.png" class="img-fluid rounded shadow" alt="Collection of Canadian Road Signs" style="max-height: 250px; object-fit: contain;">
                </div>
                
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a href="register.php" class="btn btn-success btn-lg px-4 me-sm-3">Start Practicing Now!</a>
                    <a href="login.php" class="btn btn-outline-light btn-lg px-4">Already a Member? Login</a>
                </div>
                
            </div>
            
        </div>
    </div>
</main>

<?php require_once "includes/footer.php"; ?>