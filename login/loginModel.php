<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
require_once '../config/database.php';
class LoginModel{
    public function __construct(){
        //echo "Model - Start - ";
    }
    public function loginApp($email, $password) {
        //echo "Login to Model";
        $password = md5($password);
        try {
            // Create a new Database instance
            $db = new Database();
            $conn = $db->getConnection();
            $query = "SELECT * FROM tblusers WHERE username  = :email AND pass = :password";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                //echo "pass"; // This will be displayed if the login is successful
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_fname'] = $user['fullname'];
                $_SESSION['Username']=$user['username'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            } else {
               // echo "fail"; // This will be displayed if the login fails
                return false;
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}