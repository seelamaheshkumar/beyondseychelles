<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require_once 'loginModel.php';
    $model = new LoginModel();
    if(isset($_POST['action']) && $_POST['action'] == "Verify"){
        $email = $_POST['email'];
        $password = $_POST['password'];
        try{
            $response = $model->loginApp($email, $password);
        } catch (PDOException $e) {
            die("Model failed to fetch: " . $e->getMessage());
        }
        if ($response==1){
            echo "success";
        }
        else{
            echo "Failed";
        }
    }
?>