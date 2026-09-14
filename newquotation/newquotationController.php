<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'newquotationModel.php';

$model = new NewQuotationModel();

if (isset($_POST['action']) && $_POST['action'] == "view") {
    try {
        $output = '';
        $data = $model->readAllQProducts();
        if ($model->totalQProductsRowCount() > 0) {
            $output .= '<table id="usersTable" class="table bd-table mb-0">
                <thead>
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
                        <a href="#" title="Delete" class="btn btn-sm btn-bd-outline text-danger delBtn" data-id="' . $row['id'] . '">
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
                Error: Unable to fetch users from the database.
              </div>';
    }
}

if (isset($_POST['action']) && $_POST['action'] == "add") {
    try {
        // Log the received data
        error_log("Received data: " . print_r($_POST, true));

        $result = $model->addQProduct($_POST);
        if ($result) {
            echo "User added successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to add user";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to add user";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "addquotation") {
    try {
        // Log the received data
        error_log("Received data: " . print_r($_POST, true));

        $result = $model->addQuotation($_POST);
        if ($result) {
            echo "Quotation saved successfully";
        } else {
            //error_log("Failed to add user: addQProduct() returned false.");
            echo "Failed to save Quotation";
        }
    } catch (PDOException $e) {
       // error_log("Database Error: " . $e->getMessage());
        echo "Failed to save Quotation";
    }
}

if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
    try {
        $result = $model->deleteQProduct($_POST['id']);
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
?>
