<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['action']) && $_POST['action'] === 'request_reset') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide an email address.']);
        exit;
    }
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, fullname FROM tblusers WHERE username = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
            
            // Save token
            $updateStmt = $conn->prepare("UPDATE tblusers SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");
            $updateStmt->bindParam(':token', $token);
            $updateStmt->bindParam(':expires', $expires);
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();
            
            // Create reset link (adjust localhost path if deployed online)
            $resetLink = "http://localhost/beyondseychelles.in/login/reset_password.php?token=" . $token;
            
            // Send Email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'mail.beyondseychelles.in'; // SMTP HOST
                $mail->SMTPAuth   = true;
                $mail->Username   = 'alerts@beyondseychelles.in'; // SMTP USERNAME
                $mail->Password   = 'Alerts@2026'; // SMTP PASSWORD
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Port 465 typically uses implicit SSL
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('alerts@beyondseychelles.in', 'BEYOND design');
                $mail->addAddress($email, $user['fullname']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "<p>Hi {$user['fullname']},</p>
                                  <p>You requested a password reset. Click the link below to set a new password:</p>
                                  <p><a href='{$resetLink}'>Reset Password</a></p>
                                  <p>This link will expire in 1 hour.</p>
                                  <p>If you didn't request this, you can safely ignore this email.</p>";

                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'A password reset link has been sent to your email.']);
            } catch (Exception $e) {
                // Mail failed to send
                echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            // Do not reveal that the email doesn't exist for security, just show success
            echo json_encode(['status' => 'success', 'message' => 'If the email is registered, you will receive a reset link shortly.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
