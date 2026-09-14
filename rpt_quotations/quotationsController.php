<?php
require_once 'quotationsModel.php';

$model = new UsersModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $output = '';
        $data = $model->readAllQuotations();
        if ($model->totalQuotationsRowCount() > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
                    <tr>
                        <th>Quote #</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Contact No</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Job ID</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $qv = $row['qvalue'] !== null ? number_format($row['qvalue'], 2) : '0.00';
                $url="../print_quotation/index.php?Rep=".$row['qid'];
                $url1="../viewjob/index.php?JobID=".$row['JobID'];
                $statusClass = 'status-draft';
                if ($row['qstatus'] == 'Approved') $statusClass = 'status-delivered';
                if ($row['qstatus'] == 'Rejected') $statusClass = 'status-overdue';
                if ($row['qstatus'] == 'Pending') $statusClass = 'status-progress';

               $output .= '<tr>
                <td class="fw-semibold">QT-' . $row['qno'] . '</td>
                <td class="text-muted">' . date('d M Y', strtotime($row['qdate'])) . '</td>
                <td>' . $row['company_name'] . '</td>
                <td>' . $row['contact_person'] . '</td>
                <td class="text-muted">' . $row['contact_no'] . '</td>
                <td class="num">₹' . $qv . '</td>
                <td><span class="status-pill ' . $statusClass . '">' . $row['qstatus'] . '</span></td>
                <td>' . ($row['JobID'] > 0 ? '<a href="'.$url1.'" class="text-decoration-none">' . $row['JobID'] . '</a>' : $row['JobID']) . '</td>
                <td align="center">
                    <a href="'.$url.'" title="View Quotation" class="btn btn-sm btn-bd-outline viewJobBtn1" target="_blank" data-id="' . $row['qid'] . '">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>';

            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h3 class="text-center text-secondary mt-5">No Quotations found!</h3>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">
                Error: Unable to fetch users from the database.
              </div>';
    }
}
?>
