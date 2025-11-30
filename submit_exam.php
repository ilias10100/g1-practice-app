<?php
require_once "includes/auth.php";
require_once "includes/db_connect.php";
require_once "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['question_ids']) && is_array($_POST['question_ids'])) {
    
    $user_id = $_SESSION['user_id'];
    $question_ids = $_POST['question_ids']; 
    $total_questions = count($question_ids);
    $correct_count = 0;
    
    // --- STEP 1: Insert new attempt and get the ID ---
    // Note: 'started_at' and 'total_questions' are required for the new record.
    $stmt = $pdo->prepare("
        INSERT INTO exam_attempts (user_id, started_at, total_questions) 
        VALUES (?, NOW(), ?)
    ");
    $stmt->execute([$user_id, $total_questions]);
    
    // **CRITICAL LINE:** Get the ID of the new attempt for recording answers
    $attempt_id = $pdo->lastInsertId(); 

    // Check if a valid ID was created (if not, something is wrong with the table structure)
    if (!$attempt_id) {
        // Fallback error, though shouldn't happen if table is correct
        header("Location: dashboard.php"); 
        exit;
    }

    // --- STEP 2: Fetch correct answers for comparison ---
    $placeholders = implode(',', array_fill(0, count($question_ids), '?'));
    
    $stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE id IN ($placeholders)");
    $stmt->execute($question_ids);
    $correct_answers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // --- STEP 3: Loop through submitted answers, grade, and record each answer ---
    foreach ($question_ids as $q_id) {
        $selected_option = $_POST["answer_" . $q_id] ?? NULL; // NULL if unanswered
        $correct_option = $correct_answers[$q_id] ?? NULL;
        $is_correct = ($selected_option === $correct_option && $selected_option !== NULL);

        if ($is_correct) {
            $correct_count++;
        }
        
        // Record the individual answer in the exam_answers table
        $stmt = $pdo->prepare("
            INSERT INTO exam_answers (attempt_id, question_id, selected_option, is_correct)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$attempt_id, $q_id, $selected_option, $is_correct]);
    }
    
    // --- STEP 4: Finalize the exam attempt record with the final score and time ---
    $stmt = $pdo->prepare("
        UPDATE exam_attempts
        SET score = ?, finished_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$correct_count, $attempt_id]);

    // --- STEP 5: Redirect to the results page ---
    // The attempt_id is now guaranteed to be valid and recorded.
    header("Location: view_attempt.php?attempt_id=" . $attempt_id);
    exit;

} else {
    // Redirect if the form was not submitted correctly (e.g., accessed directly)
    header("Location: take_exam.php"); 
    exit;
}
?>