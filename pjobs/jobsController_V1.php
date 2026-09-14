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
            $output .= '<table id="usersTable" class="table datatable">
                <thead class="thead-light">
                    <tr>
                        <th>Job#</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Contact No</th>
                        <th>Net AMount</th>
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
                $output .= '<tr>
                    <td>' . $row['jobid'] . '</td>
                    <td>' . date('d-m-Y', strtotime($row['orderdate'])) . '</td>
                    <td>' . $row['company_name'] . '</td>
                    <td>' . $row['contact_person'] . '</td>
                    <td>' . $row['contact_no'] . '</td>
                    <td>' . $nv . '</td>
                    <td>' . $pv . '</td>
                    <td>' . $bv . '</td>
                    <td>' . $row['credit_bill'] . '</td>
                    <td>' . $row['order_status'] . '</td>
                    <td align="center">
                        <a href="#" title="View Job" class="text-info viewJobBtn" data-id="' . $row['jobid'] . '">
                        <i class=" ri-eye-fill"></i>
                        </a>&nbsp;
                        <a href="#" title="Print Job Invoice" class="text-warning printBtn" data-id="' . $row['jobid'] . '">
                        <i class="ri-printer-fill"></i>
                        </a>&nbsp;
                        <a href="#" title="Update Payment" class="text-success addMoneyBtn" data-bs-toggle="modal" data-bs-target="#addPayModal" data-id="' . $row['jobid'] . '">
                            <i class=" ri-money-dollar-box-line"></i>
                        </a>&nbsp;
                        <a href="#" title="Update Status" class="text-primary editBtn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['jobid'] . '">
                            <i class=" ri-edit-box-fill"></i>
                        </a>&nbsp;
                        <a href="#" title="Delete Job" class="text-danger delBtn" data-id="' . $row['jobid'] . '">
                            <i class=" ri-delete-bin-2-fill"></i>
                        </a>
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
