<?php
require_once '../config/database.php';

class UsersModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function readAllUsers() {
        $sql = "SELECT * FROM tblusers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function totalUsersRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tblusers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

 public function addUser($userData) {
    try {
        // Prepare SQL statement to insert user into tblusers table
        $sql = "INSERT INTO tblusers (username, pass, fullname, role, user_status, email) 
                VALUES (:username, :pass, :fullname, :role, :user_status, :email)";
        $stmt = $this->conn->prepare($sql);
        // Bind parameters
        $pass=md5($userData['password']);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':fullname', $userData['fullname']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':user_status', $userData['user_status']);
        $stmt->bindParam(':email', $userData['email']);
        // Execute the statement
        $stmt->execute();
        // Check if user was inserted successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        //echo "Database Error: " . $e->getMessage();
        return false;
    }
}


public function getUserById($userId) {
    try {
        // Prepare SQL statement to select user by ID
        $sql = "SELECT * FROM tblusers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameter
        $stmt->bindParam(':id', $userId);
        // Execute the statement
        $stmt->execute();
        // Fetch user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return user details
        return $user;
    } catch (PDOException $e) {
        // Handle database error
        return null;
    }
}


public function editUser($userData) {
    try {
        // Prepare SQL statement to update user in tblusers table
        $sql = "UPDATE tblusers SET username = :username, pass = :pass, fullname = :fullname, role = :role, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':id', $userData['id']);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':pass', $userData['password']);
        $stmt->bindParam(':fullname', $userData['fullname']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':email', $userData['email']);
        // Execute the statement
        $stmt->execute();
        // Check if user was updated successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        return false;
    }
}


public function deleteUser($userId) {
    try {
        // Prepare SQL statement to delete user
        $sql = "DELETE FROM tblusers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameter
        $stmt->bindParam(':id', $userId);
        // Execute the statement
        $stmt->execute();
        // Check if user was deleted successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        return false;
    }
}


    // Add insert, update, and delete methods here
}
?>
