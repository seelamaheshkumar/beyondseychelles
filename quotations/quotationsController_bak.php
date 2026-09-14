<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'quotationsModel.php';

$model = new UsersModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $output = '';
        $data = $model->readAllQuotations();
        if ($model->totalQuotationsRowCount() > 0) {
            $output .= '<table id="usersTable" class="table datatable">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Contact No</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th align="center">Action</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $url="../print_quotation/index.php?Rep=".$row['qid'];
                $qv = $row['qvalue'] !== null ? number_format($row['qvalue'], 2) : '0.00';
                $output .= '<tr>
                    <td>' .  $row['qid'] . '</td>
                    <td>' . date('d-m-Y',strtotime($row['qdate'])) . '</td>
                    <td>' . $row['company_name'] . '</td>
                    <td>' . $row['contact_person'] . '</td>
                    <td>' . $row['contact_no'] . '</td>
                    <td>' . $qv . '</td>
                    <td>' . $row['qstatus'] . '</td>
                    <td align="center">
                        <a href="'.$url.'" title="Print" class="text-warning printBtn1" data-id="' . $row['qid'] . '" target="_blank">
                        <i class="ri-printer-fill"></i>
                        </a>&nbsp;&nbsp;
                        <a href="#" title="Update Quotation" class="text-primary editBtn" data-id="' . $row['qid'] . '">
                            <i class=" ri-edit-box-fill"></i>
                        </a>&nbsp;
                        <a href="#" title="Convert To Job" class="text-success convertBtn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['qid'] . '">
                            <i class="ri-add-box-line"></i>
                        </a>&nbsp;
                        <a href="#" title="Delete" class="text-danger delBtn" data-id="' . $row['qid'] . '">
                            <i class="ri-delete-bin-2-fill"></i>
                        </a>
                        
                        
                            <div class="btn-group">
                              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                              </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item"  href="'.$url.'" target="_blank">Print</a>
                                    <a class="dropdown-item" href="#">Update Quotation</a>
                                    <a class="dropdown-item" href="#">Convert To Job</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Delete Quotation</a>
                                  </div>
                              </div>
                              
                              
                              <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="'.$url.'" target="_blank">Print</a></li>
                                <li><a class="dropdown-item editBtn" href="#"  data-id="' . $row['qid'] . '">Update Quotation</a></li>
                                <li><a class="dropdown-item convertBtn" href="#"  data-id="' . $row['qid'] . '">Convert To Job</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item delBtn" href="#"  data-id="' . $row['qid'] . '">Delete Quotation</a></li>
                            </ul>
                        </div>
                              
                              
                    </td>
                    <!-- Add more cells as needed -->
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

if (isset($_POST['action']) && $_POST['action'] == "add") {
    // Check if action is received correctly
    //echo "Action: Add";
    // Create a new instance of the UsersModel class
    $model = new UsersModel();
    // Call the addUser method to insert the user into the database
    $result = $model->addUser($_POST);
    // Check if user was added successfully
    if ($result) {
        // Return success response
        //echo "User added successfully";
    } else {
        // Return error response
        //echo "Failed to add user";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "edit") {
        echo "Edit action reached";
    try {
        // Check if all required fields are set
        if (isset($_POST['edit-user-id'], $_POST['edit-username'], $_POST['edit-fullname'], $_POST['edit-role'], $_POST['edit-email'])) {
            // Create an array to hold user data
            $userData = array(
                'id' => $_POST['edit-user-id'],
                'username' => $_POST['edit-username'],
                'fullname' => $_POST['edit-fullname'],
                'role' => $_POST['edit-role'],
                'email' => $_POST['edit-email']
            );
            // Call the editUser method to update the user in the database
            $result = $model->editUser($userData);
            // Check if user was edited successfully
            if ($result) {
                // Return success response
                echo "success";
            } else {
                // Return error response
                echo "error";
            }
        } else {
            // Return error response if any required field is missing
            echo "error";
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "error";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "updatestatus") {
    try {
        // Check if all required fields are set
        if (isset($_POST['qid'], $_POST['po_no'])) {
            // Create an array to hold user data
            $userData = array(
                'qid' => $_POST['qid'],
                'po_no' => $_POST['po_no'],
                'credit_bill' => $_POST['credit_bill']
            );
            // Call the editUser method to update the user in the database
            $result = $model->ConvertToJob($userData);
            // Check if user was edited successfully
            if ($result) {
                // Return success response
                echo "Status Updated successfully";
            } else {
                // Return error response
                echo "error-1";
            }
        } else {
            // Return error response if any required field is missing
            echo "error-2";
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "error";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "get_user") {
    try {
        // Fetch user details from the model based on user ID
        $user = $model->getUserById($_POST['id']);
        // Check if user exists
        if ($user) {
            // Prepare HTML markup for the edit form with user details
            $output = '
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-user-form">
                <div class="modal-body">
                    <input type="hidden" id="edit-user-id" name="edit-user-id" value="' . $user['id'] . '">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="edit-username" value="' . $user['username'] . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit-fullname" name="edit-fullname" value="' . $user['fullname'] . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role" class="form-label">Role</label>
                        <select class="form-select" id="edit-role" name="edit-role" required>
                            <option value="Billing" ' . ($user['role'] == "Billing" ? 'selected' : '') . '>Billing</option>
                            <option value="Administrator" ' . ($user['role'] == "Administrator" ? 'selected' : '') . '>Administrator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="edit-email" value="' . $user['email'] . '" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>';
            // Return HTML markup for the edit form
            echo $output;
        } else {
            // Return error response if user does not exist
            echo "error";
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "error";
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'getQuoteDetails') {
    $qid = isset($_POST['qid']) ? $_POST['qid'] : null;
    if ($qid !== null) {
        $jobDetails = $model->getQuotationByID($qid);
        if ($jobDetails !== null) {
            echo json_encode($jobDetails);
        } else {
            // Handle case where job details are not found
            echo json_encode(array('error' => 'Quotation details not found'));
        }
    } else {
        // Handle case where jobId parameter is missing or invalid
        echo json_encode(array('error' => 'Invalid jobId parameter'));
    }
}


// Handle delete_user action
if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
    try {
        // Delete user from the model
        $result = $model->deleteQuotation($_POST['id']);
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
// Add insert, update, and delete functionality here
?>
