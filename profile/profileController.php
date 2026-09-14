<?php
require_once '../auth.php';
require_once '../config/database.php';

// Ensure user is logged in
checkLogin();

if (isset($_POST['action']) && $_POST['action'] == "change_password") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    $user_id = $_SESSION['user_id'];
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Check current password
        $stmt = $conn->prepare("SELECT pass FROM tblusers WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['pass'] === md5($current_password)) {
            // Update password
            $updateStmt = $conn->prepare("UPDATE tblusers SET pass = :new_pass WHERE id = :id");
            $new_pass_hashed = md5($new_password);
            $updateStmt->bindParam(':new_pass', $new_pass_hashed);
            $updateStmt->bindParam(':id', $user_id);
            
            if ($updateStmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Your password has been changed successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error during update.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}
?>
