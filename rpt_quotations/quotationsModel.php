<?php
require_once '../config/database.php';

class UsersModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function readAllQuotations() {
        $sql = "SELECT tblquotations.*, COALESCE(tbljobs.jobid, 0) as JobID FROM tblquotations LEFT JOIN tbljobs ON tbljobs.qid = tblquotations.qid ORDER BY tblquotations.qid DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalQuotationsRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tblquotations";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>
