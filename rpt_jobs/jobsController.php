<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'jobsModel.php';
date_default_timezone_set('Indian/Mahe');

$model = new JobsModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $fdate = isset($_POST['fdate']) ? $_POST['fdate'] : date('Y-m-d');
        $tdate = isset($_POST['tdate']) ? $_POST['tdate'] : date('Y-m-d');
        
        $output = '';
        $data = $model->readAllJobs();
        if ($model->totalJobsRowCount() > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
                    <tr>
                        <th>JobID</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Contact No</th>
                        <th>Net Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>QID</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $nv = $row['netvalue'] !== null ? number_format($row['netvalue'], 2) : '0.00';
                $pv = $row['paid_amount'] !== null ? number_format($row['paid_amount'], 2) : '0.00';
                $bv = $row['balance'] !== null ? number_format($row['balance'], 2) : '0.00';
                $url="../print_quotation/index.php?Rep=".$row['qid'];
                $statusClass = 'status-draft';
                if ($row['order_status'] == 'Delivered' || $row['order_status'] == 'Completed') $statusClass = 'status-delivered';
                if ($row['order_status'] == 'Cancelled') $statusClass = 'status-overdue';
                if ($row['order_status'] == 'In Progress' || $row['order_status'] == 'Pending') $statusClass = 'status-progress';

                $output .= '<tr>
                    <td class="fw-semibold">' .  $row['jobno'] . '</td>
                    <td class="text-muted">' . date('d M Y', strtotime($row['orderdate'])) . '</td>
                    <td>' . $row['company_name'] . '</td>
                    <td>' . $row['contact_person'] . '</td>
                    <td class="text-muted">' . $row['contact_no'] . '</td>
                    <td class="num">₹' . $nv . '</td>
                    <td class="num text-success">₹' . $pv . '</td>
                    <td class="num text-danger">₹' . $bv . '</td>
                    <td><span class="status-pill ' . $statusClass . '">' . $row['order_status'] . '</span></td>
                    <td>' . ($row['qid'] > 0 ? '<a href="'.$url.'" target="_blank" class="text-decoration-none">QT-' . $row['qid'] . '</a>' : $row['qid']) . '</td>
                     <td align="center">
                        <button class="btn btn-sm btn-bd-outline viewJobBtn" data-id="' . $row['jobid'] . '" title="View Job">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h3 class="text-center text-secondary mt-5">No Jobs found!</h3>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">
                Error: Unable to fetch users from the database.
              </div>';
    }
}

?>
