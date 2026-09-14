<?php
session_start(); // Start the session

function checkLogin() {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect to the login page
        header('Location: ../index.php');
        exit(); // Stop further execution of the script
    }
}
?>
