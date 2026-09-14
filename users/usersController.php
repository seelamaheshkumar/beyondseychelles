<?php
require_once 'usersModel.php';

$model = new UsersModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $output = '';
        $data = $model->readAllUsers();
        if ($model->totalUsersRowCount() > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($data as $row) {
                $statusClass = $row['user_status'] == 'Active' ? 'status-delivered' : 'status-progress';
                $output .= '<tr>
                    <td class="fw-semibold">' . $row['username'] . '</td>
                    <td>' . $row['fullname'] . '</td>
                    <td class="text-muted">' . $row['email'] . '</td>
                    <td>' . $row['role'] . '</td>
                    <td><span class="status-pill ' . $statusClass . '">' . $row['user_status'] . '</span></td>
                    <td>
                        <button class="btn btn-sm btn-bd-outline editBtn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-bd-outline text-danger delBtn" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h3 class="text-center text-secondary mt-5">No users found!</h3>';
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-user-form">
                <div class="modal-body">
                    <input type="hidden" id="edit-user-id" name="edit-user-id" value="' . $user['id'] . '">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label small fw-semibold">Username</label>
                        <input type="text" class="form-control form-control-sm" id="edit-username" name="edit-username" value="' . $user['username'] . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label small fw-semibold">Full Name</label>
                        <input type="text" class="form-control form-control-sm" id="edit-fullname" name="edit-fullname" value="' . $user['fullname'] . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role" class="form-label small fw-semibold">Role</label>
                        <select class="form-select form-select-sm" id="edit-role" name="edit-role" required>
                            <option value="Billing" ' . ($user['role'] == "Billing" ? 'selected' : '') . '>Billing</option>
                            <option value="Administrator" ' . ($user['role'] == "Administrator" ? 'selected' : '') . '>Administrator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label small fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-sm" id="edit-email" name="edit-email" value="' . $user['email'] . '" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-bd-primary btn-sm">Save Changes</button>
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


// Handle delete_user action
if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
    try {
        // Delete user from the model
        $result = $model->deleteUser($_POST['id']);
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
