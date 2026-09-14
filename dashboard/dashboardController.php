<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Indian/Mahe');
require_once 'dashboardModel.php';

$model = new DashboardModel();

if (isset($_POST['action']) && $_POST['action'] == "fetchDashboardData") {
    // Get the start and end dates from the POST request
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

    try {
        $data = $model->getDashboardData($startDate, $endDate);
        echo json_encode($data);
    } catch (Exception $e) {
        // Handle exception
        echo json_encode(["error" => "Failed to fetch dashboard data"]);
    }
} else {
    echo json_encode(["error" => "Invalid action"]);
}
?>

