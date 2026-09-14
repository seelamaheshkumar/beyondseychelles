<?php
require_once '../config/database.php';
date_default_timezone_set('Indian/Mahe');

class DashboardModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
    /*
    public function getDashboardData($startDate, $endDate) {
        $data = [];

        // New jobs between given dates
        $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE  DATE_FORMAT(orderdate, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['newJobs'] = $result['count'];
        $data['newJobsValue'] = number_format($result['value'],2);

        // Pending jobs
        $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count,SUM(netvalue) as value  FROM tbljobs WHERE order_status = 'Pending'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['pendingJobs'] = $result['count'];
        $data['pendingvalue'] = number_format($result['value'],2);


        // Ready jobs
        $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value  FROM tbljobs WHERE order_status = 'Ready'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['readyJobs'] = $result['count'];
        $data['readyvalue'] = number_format($result['value'],2);

        // Delivered jobs between given dates
        $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value  FROM tbljobs WHERE order_status = 'Delivered' AND  DATE_FORMAT(delivery_date, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['deliveredJobs'] = $result['count'];
        $data['delvvalue'] = number_format($result['value'],2);

        // Cash collected between given dates
        $stmt = $this->conn->prepare("SELECT SUM(cash) as cash, SUM(card) as card, SUM(cheque) as cheque, SUM(wallet) as wallet FROM tblcashflow WHERE DATE_FORMAT(paydate, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['cash'] = number_format($result['cash'],2);
        $data['card'] = number_format($result['card'],2);
        $data['cheque'] = number_format($result['cheque'],2);
        $data['wallet'] = number_format($result['wallet'],2);

        // Credit jobs where balance > 0 and credit_bill = 'Yes'
        $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE balance > 0 AND credit_bill = 'Yes'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['creditJobs'] = $result['count'];
        $data['creditJobsValue'] = number_format($result['value'],2);

        return $data;
    }*/
    public function getDashboardData($startDate, $endDate) {
    $data = [];

    // New jobs between given dates
    $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE  order_status<>'Cancelled' AND  DATE_FORMAT(orderdate, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
    $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['newJobs'] = $result['count'];
    $data['newJobsValue'] = $result['value'] !== null ? number_format($result['value'], 2) : '0.00';

    // Pending jobs
    $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE order_status = 'Pending'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['pendingJobs'] = $result['count'];
    $data['pendingvalue'] = $result['value'] !== null ? number_format($result['value'], 2) : '0.00';

    // Ready jobs
    $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE order_status = 'Ready'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['readyJobs'] = $result['count'];
    $data['readyvalue'] = $result['value'] !== null ? number_format($result['value'], 2) : '0.00';

    // Delivered jobs between given dates
    $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE order_status = 'Delivered' AND DATE_FORMAT(delivery_date, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
    $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['deliveredJobs'] = $result['count'];
    $data['delvvalue'] = $result['value'] !== null ? number_format($result['value'], 2) : '0.00';

    // Cash collected between given dates
    $stmt = $this->conn->prepare("SELECT SUM(cash) as cash, SUM(card) as card, SUM(cheque) as cheque, SUM(wallet) as wallet FROM tblcashflow WHERE DATE_FORMAT(paydate, '%Y-%m-%d') BETWEEN :startDate AND :endDate");
    $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['cash'] = $result['cash'] !== null ? number_format($result['cash'], 2) : '0.00';
    $data['card'] = $result['card'] !== null ? number_format($result['card'], 2) : '0.00';
    $data['cheque'] = $result['cheque'] !== null ? number_format($result['cheque'], 2) : '0.00';
    $data['wallet'] = $result['wallet'] !== null ? number_format($result['wallet'], 2) : '0.00';

    // Credit jobs where balance > 0 and credit_bill = 'Yes'
    $stmt = $this->conn->prepare("SELECT COUNT(jobid) as count, SUM(netvalue) as value FROM tbljobs WHERE  order_status<>'Cancelled' AND balance > 0 AND credit_bill = 'Yes'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['creditJobs'] = $result['count'];
    $data['creditJobsValue'] = $result['value'] !== null ? number_format($result['value'], 2) : '0.00';

    // Build Chart Data
    $chartData = [
        'statusDistribution' => [
            'labels' => ['Pending', 'Ready', 'Delivered'],
            'data' => [(int)$data['pendingJobs'], (int)$data['readyJobs'], (int)$data['deliveredJobs']]
        ],
        'salesTrend' => [
            'labels' => [],
            'data' => []
        ]
    ];

    // Fetch Sales Trend (Last 7 Days up to endDate)
    $stmt = $this->conn->prepare("
        SELECT DATE_FORMAT(paydate, '%b %d') as date_label, SUM(cash + card + cheque + wallet) as total_revenue 
        FROM tblcashflow 
        WHERE paydate >= DATE_SUB(:endDate, INTERVAL 6 DAY) AND paydate <= :endDate
        GROUP BY DATE_FORMAT(paydate, '%Y-%m-%d')
        ORDER BY paydate ASC
    ");
    $stmt->execute([':endDate' => $endDate]);
    $trendResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize the last 7 days with 0
    $trendMap = [];
    for ($i = 6; $i >= 0; $i--) {
        $dateLabel = date('M d', strtotime("$endDate -$i days"));
        $trendMap[$dateLabel] = 0;
    }
    
    foreach ($trendResults as $row) {
        $trendMap[$row['date_label']] = (float)$row['total_revenue'];
    }

    foreach ($trendMap as $label => $val) {
        $chartData['salesTrend']['labels'][] = $label;
        $chartData['salesTrend']['data'][] = $val;
    }

    $data['chartData'] = $chartData;

    return $data;
}

}
?>

