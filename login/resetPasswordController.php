<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/database.php';

if (isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Verify token again
        $stmt = $conn->prepare("SELECT id FROM tblusers WHERE reset_token = :token AND reset_token_expires > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Update password
            $hashed_password = md5($password);
            $updateStmt = $conn->prepare("UPDATE tblusers SET pass = :pass, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
            $updateStmt->bindParam(':pass', $hashed_password);
            $updateStmt->bindParam(':id', $user['id']);
            
            if ($updateStmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Your password has been successfully updated. You can now login.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update the password.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}
?>
