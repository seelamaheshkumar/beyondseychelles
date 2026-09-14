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
        $data = $model->readJobsByDateRange($fdate, $tdate);
        if ($model->totalJobsRowCountByDateRange($fdate, $tdate) > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
                    <tr>
                        <th>Job#</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Remarks</th>
                        <th>Net Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Credit</th>
                        <th>Status</th>
                        <th align="center">Action</th>
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $nv = $row['netvalue'] !== null ? number_format($row['netvalue'], 2) : '0.00';
                $pv = $row['paid_amount'] !== null ? number_format($row['paid_amount'], 2) : '0.00';
                $bv = $row['balance'] !== null ? number_format($row['balance'], 2) : '0.00';
                
                // Determine status pill class based on order_status
                $statusClass = 'status-draft';
                if ($row['order_status'] == 'Pending') $statusClass = 'status-progress';
                if ($row['order_status'] == 'Ready') $statusClass = 'status-delivered';
                if ($row['order_status'] == 'Delivered') $statusClass = 'status-delivered';

                $output .= '<tr>
                    <td class="fw-semibold">JB-' . $row['jobid'] . '</td>
                    <td class="text-muted">' . date('d M Y', strtotime($row['orderdate'])) . '</td>
                    <td>' . $row['company_name'] . '</td>
                    <td>' . $row['contact_person'] . '</td>
                    <td>' . $row['address'] . '</td>
                    <td class="num">₹' . $nv . '</td>
                    <td class="num text-success">₹' . $pv . '</td>
                    <td class="num text-danger">₹' . $bv . '</td>
                    <td>' . $row['credit_bill'] . '</td>
                    <td><span class="status-pill ' . $statusClass . '">' . $row['order_status'] . '</span></td>
                    <td align="center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-bd-outline dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear"></i> Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="../viewjob/?id=' . $row['jobid'] . '"><i class="bi bi-eye text-primary"></i> View Job</a></li>
                                <li><a class="dropdown-item" href="../updatejob/?id=' . $row['jobid'] . '"><i class="bi bi-pencil-square text-primary"></i> Update Job</a></li>
                                <li><a class="dropdown-item" href="../printjob/?id=' . $row['jobid'] . '" target="_blank"><i class="bi bi-printer text-secondary"></i> Print Invoice</a></li>
                                <li><a class="dropdown-item addMoneyBtn" href="#" data-bs-toggle="modal" data-bs-target="#addPayModal" data-id="' . $row['jobid'] . '"><i class="bi bi-currency-dollar text-success"></i> Update Payment</a></li>
                                <li><a class="dropdown-item editBtn" href="#" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['jobid'] . '"><i class="bi bi-pencil text-warning"></i> Update Status</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger delBtn" href="#" data-id="' . $row['jobid'] . '"><i class="bi bi-trash text-danger"></i> Delete Job</a></li>
                            </ul>
                        </div>
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
if (isset($_POST['action']) && $_POST['action'] == 'getJobDetails') {
    $jobId = isset($_POST['jobId']) ? $_POST['jobId'] : null;
    if ($jobId !== null) {
        $jobDetails = $model->getJobDetailsFromDatabase($jobId);
        if ($jobDetails !== null) {
            echo json_encode($jobDetails);
        } else {
            // Handle case where job details are not found
            echo json_encode(array('error' => 'Job details not found'));
        }
    } else {
        // Handle case where jobId parameter is missing or invalid
        echo json_encode(array('error' => 'Invalid jobId parameter'));
    }
}
if (isset($_POST['action']) && $_POST['action'] == "updatepay") {
    try {
        // Log the received data
       // error_log("Received data: " . print_r($_POST, true));

        $result = $model->updatePay($_POST);
        if ($result) {
            echo "Payment Updated successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to Update the Payment";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to update the payment";
    }
}
if (isset($_POST['action']) && $_POST['action'] == "updatestatus") {
    try {
        // Log the received data
       // error_log("Received data: " . print_r($_POST, true));
        $result = $model->updateStatus($_POST);
        if ($result) {
            echo "Status Updated successfully";
        } else {
            error_log("Failed to update status: updateStatus() returned false.");
            echo "Failed to update the status";
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo "Failed to update the status";
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        echo "Failed to update the status";
    }
}
if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
    try {
        // Delete user from the model
        $result = $model->deleteJob($_POST['id']);
        //error_log($result);
        if ($result) {
            // Return success response
            echo "success";
        } else {
            // Return error response
            echo "error";
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "error";
    }
}

?>
