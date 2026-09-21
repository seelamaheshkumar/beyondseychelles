<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'jobsModel.php';
date_default_timezone_set('Indian/Mahe');

$model = new JobsModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        
        $output = '';
        $data = $model->readJobsByYear($year);
        if ($model->totalJobsRowCountByYear($year) > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
                    <tr>
                        <th>SL No</th>
                        <th>Date</th>
                        <th>Cash</th>
                        <th>Card</th>
                        <th>Cheque</th>
                        <th>Bank Transfer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $cash = $row['total_cash'] !== null ? number_format($row['total_cash'], 2) : '0.00';
                $card = $row['total_card'] !== null ? number_format($row['total_card'], 2) : '0.00';
                $cheque = $row['total_cheque'] !== null ? number_format($row['total_cheque'], 2) : '0.00';
                $wallet = $row['total_wallet'] !== null ? number_format($row['total_wallet'], 2) : '0.00';
                $total = $row['total'] !== null ? number_format($row['total'], 2) : '0.00';
                $output .= '<tr>
                    <td>' .  $sl++ . '</td>
                    <td>' . $row['formatted_paydate'] . '</td>
                    <td>' . $cash . '</td>
                    <td>' . $card . '</td>
                    <td>' . $cheque . '</td>
                    <td>' . $wallet . '</td>
                    <td>' . $total . '</td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h3 class="text-center text-secondary mt-5">No Jobs found!</h3>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">
                Error: Unable to fetch Cash Flow from the database. ' . $e->getMessage() . '
              </div>';
    }
}
?>

