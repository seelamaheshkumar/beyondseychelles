<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

class JobsModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
    public function __destruct() {
        $this->conn = null;
    }
    public function readAllJobs() {
        $sql = "SELECT * FROM vw_cashflow_summary order by formatted_paydate desc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

public function totalJobsRowCount() {
    try {
        $sql = "SELECT COUNT(*) as count FROM vw_cashflow_summary";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    } catch (PDOException $e) {
        // Log the error message or handle it as needed
        echo "Error: " . $e->getMessage();
        return 0; // Return 0 or handle the error as appropriate
    }
}

public function readJobsByYear($year = null) {
    if ($year) {
        $sql = "SELECT * FROM vw_cashflow_summary WHERE YEAR(formatted_paydate) = :year ORDER BY formatted_paydate DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':year', $year);
    } else {
        $sql = "SELECT * FROM vw_cashflow_summary ORDER BY formatted_paydate DESC";
        $stmt = $this->conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

public function totalJobsRowCountByYear($year = null) {
    if ($year) {
        $sql = "SELECT COUNT(*) as count FROM vw_cashflow_summary WHERE YEAR(formatted_paydate) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':year', $year);
    } else {
        $sql = "SELECT COUNT(*) as count FROM vw_cashflow_summary";
        $stmt = $this->conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

   
}
?>
