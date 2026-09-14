<?php
include 'editquotationModel.php';

$model = new EditQuotationModel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'addProduct':
            try {
                if (!isset($_POST['qid']) || empty($_POST['qid'])) {
                    throw new Exception("Quotation ID is missing.");
                }
        
                $qid = $_POST['qid'];
                $productName = $_POST['product_name'];
                $qty = $_POST['qty'];
                $unitCost = $_POST['unitcost'];
                $total = $_POST['amount'];
        
                $result = $model->addProduct($qid, $productName, $qty, $unitCost, $total);
                echo $result ? "Product added successfully" : "Failed to add product";
            } catch (Exception $e) {
                error_log("Error adding product: " . $e->getMessage());
                echo "Error adding product: " . $e->getMessage();
            }
            break;

        case 'deleteProduct':
            $id = $_POST['id'];
            $result = $model->deleteProduct($id);
            echo $result ? "Product deleted successfully" : "Failed to delete product";
            break;

        case 'updateProduct':
            $id = $_POST['pid1'];
            $productName = $_POST['pname'];
            $qty = $_POST['pqty'];
            $unitCost = $_POST['ucost'];
            $total = $qty * $unitCost;

            $result = $model->updateProduct($id, $productName, $qty, $unitCost, $total);
            echo $result ? "Product updated successfully" : "Failed to update product";
            break;

        case 'getQuotationDetails':
            $qid = $_POST['qid'];
            $quotation = $model->getQuotationDetails($qid);
            echo $quotation ? json_encode($quotation) : json_encode(["error" => "No data found for qid: $qid"]);
            break;

        case 'viewQuotationProducts':
            $qid = $_POST['qid'];
            $products = $model->getProductsByQuotation($qid);
            if ($products) {
                echo '
                    <table id="usersTable" class="table bd-table mb-0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
            
                foreach ($products as $product) {
                    echo "<tr>
                            <td>{$product['product_name']}</td>
                            <td>{$product['qty']}</td>
                            <td>{$product['unitcost']}</td>
                            <td>{$product['total']}</td>
                            <td>
                                <button class='btn btn-sm btn-bd-outline editBtn' data-id='{$product['id']}'><i class='bi bi-pencil'></i></button>
                                <button class='btn btn-sm btn-bd-outline text-danger delBtn' data-id='{$product['id']}'><i class='bi bi-trash'></i></button>
                            </td>
                        </tr>";
                }

                echo '</tbody></table>';
            } else {
                echo "
                    <table id='usersTable' class='table bd-table mb-0'>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan='5'>No products found</td></tr>
                        </tbody>
                    </table>";
            }
            break;
        case 'updateQuotationHeader':
            try {
                if (!isset($_POST['qid']) || empty($_POST['qid'])) {
                    throw new Exception("Quotation ID is missing.");
                }
                
                $qid = $_POST['qid'];
                $companyName = $_POST['company_name'];
                $contactPerson = $_POST['contact_person'];
                $contactNo = $_POST['contact_no'];
                $address = $_POST['address'];
        
                $result = $model->updateQuotationHeader($qid, $companyName, $contactPerson, $contactNo, $address);
                echo $result ? "Quotation updated successfully" : "Failed to update quotation";
            } catch (Exception $e) {
                error_log("Error updating quotation: " . $e->getMessage());
                echo "Error updating quotation: " . $e->getMessage();
            }
            break;
        case 'getProductDetails':
            try {
                $id = $_POST['id'];
                if (empty($id)) {
                    throw new Exception("Product ID is missing");
                }
                
                $product = $model->getProductById($id);
                if ($product) {
                    echo json_encode($product);
                } else {
                    echo json_encode(["error" => "Product not found"]);
                }
            } catch (Exception $e) {
                error_log("Error fetching product details: " . $e->getMessage());
                echo json_encode(["error" => "Error fetching product details: " . $e->getMessage()]);
            }
            break;
    }
}
