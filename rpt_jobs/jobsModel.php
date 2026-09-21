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

    public function readAllJobs($year = null) {
        if ($year) {
            $sql = "SELECT * FROM tbljobs WHERE YEAR(orderdate) = :year ORDER BY jobid, orderdate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':year', $year);
        } else {
            $sql = "SELECT * FROM tbljobs ORDER BY jobid, orderdate DESC";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function totalJobsRowCount($year = null) {
        if ($year) {
            $sql = "SELECT COUNT(*) as count FROM tbljobs WHERE YEAR(orderdate) = :year";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':year', $year);
        } else {
            $sql = "SELECT COUNT(*) as count FROM tbljobs";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function readJobsByDateRange($fdate, $tdate) {
        $sql = "SELECT * FROM tbljobs WHERE orderdate BETWEEN :fdate AND :tdate order by jobid, orderdate desc";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fdate', $fdate);
        $stmt->bindParam(':tdate', $tdate);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalJobsRowCountByDateRange($fdate, $tdate) {
        $sql = "SELECT COUNT(*) as count FROM tbljobs WHERE orderdate BETWEEN :fdate AND :tdate";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fdate', $fdate);
        $stmt->bindParam(':tdate', $tdate);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
   
}
?>
