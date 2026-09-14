<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Indian/Mahe');
require_once 'newjobModel.php';

$model = new NewJobModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $output = '';
        $data = $model->readAllOProducts();
        if ($model->totalOProductsRowCount() > 0) {
            $output .= '<table id="usersTable" class="table datatable">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Unit Cost</th>
                        <th>Total</th>
                        <th align="center">Action</th>
                    </tr>
                </thead>
                <tbody>';
                $sl=1;
            foreach ($data as $row) {
                $output .= '<tr>
                    <td>' . $sl++ . '</td>
                    <td>' . $row['product_name'] . '</td>
                    <td>' . $row['qty'] . '</td>
                    <td>' . $row['unitcost'] . '</td>
                    <td>' . $row['total'] . '</td>
                    <td align="center">
                         <a href="#" title="Update Product" class="text-primary editBtn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['id'] . '">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    &nbsp;
                    <a href="#" title="Delete" class="text-danger delBtn" data-id="' . $row['id'] . '">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h3 class="text-center text-secondary mt-5">No Products found!</h3>';
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo '<div class="alert alert-danger" role="alert">
                Error: Unable to fetch products from the database.
              </div>';
    }
}

if (isset($_POST['action']) && $_POST['action'] == "add") {
    try {
        // Log the received data
        error_log("Received data: " . print_r($_POST, true));

        $result = $model->addOProduct($_POST);
        if ($result) {
            echo "Product added successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to Save Job";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to add Job";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "addjob") {
    try {
        // Log the received data
       // error_log("Received data: " . print_r($_POST, true));

        $result = $model->addJob($_POST);
        if ($result) {
            echo "Job saved successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to save Job";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to save Job";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
    try {
        $result = $model->deleteOProduct($_POST['id']);
        if ($result) {
            echo "success";
        } else {
            //error_log("Failed to delete user: deleteQProduct() returned false.");
            echo "error";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "error";
    }
}
if (isset($_POST['action']) && $_POST['action'] == 'updateProduct') {
    $pid = isset($_POST['pid']) ? $_POST['pid'] : null;
    if ($pid !== null) {
        $PDetails = $model->getProductDetailsFromDatabase($pid);
        if ($PDetails !== null) {
            echo json_encode($PDetails);
        } else {
            echo json_encode(array('error' => 'Product not found'));
        }
    } else {
        echo json_encode(array('error' => 'Invalid Product ID parameter'));
    }
}
if (isset($_POST['action']) && $_POST['action'] == "UpdateJ1") {
    try {
        // Log the received data
       // error_log("Received data: " . print_r($_POST, true));

        $result = $model->updateOProduct($_POST);
        if ($result) {
            echo "Product Updated successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to update Product";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to add user";
    }
}
?>

