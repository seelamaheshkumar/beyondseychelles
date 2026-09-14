<?php
require_once '../config/database.php';

class EditQuotationModel {
    private $db;
    private $conn;
    private $current_user;  // New property to store the current user

    public function __construct() {
        // Initialize the database connection using the getConnection method
        $this->db = new Database();
        $this->conn = $this->db->getConnection(); // Assuming getConnection() returns a PDO instance
        if (!$this->conn) {
            die("Database connection failed");
        }

        // Set the current application-level user for audit purposes
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->current_user = $_SESSION['Username'] ?? 'unknown_user';
        $this->conn->exec("SET @current_user = '{$this->current_user}'");
    }

    /**
     * Get details of a specific quotation.
     */
    public function getQuotationDetails($qid) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tblquotations WHERE qid = :qid");
            $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching quotation details: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get products related to a specific quotation.
     */
    public function getProductsByQuotation($qid) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tblqproducts WHERE qid = :qid");
            $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching products by quotation: " . $e->getMessage());
            return [];
        }
    }

  /**
 * Add a product to a quotation.
 * 
 * @param int $qid Quotation ID.
 * @param string $productName Name of the product.
 * @param int $qty Quantity of the product.
 * @param float $unitCost Cost per unit.
 * @param float $total Total cost.
 * @return bool True if product was added successfully, false otherwise.
 */
public function addProduct($qid, $productName, $qty, $unitCost, $total) {
    try {
        // Update the insert query to set pstatus to 'Completed'
        $stmt = $this->conn->prepare("
            INSERT INTO tblqproducts (qid, product_name, qty, unitcost, total, pstatus) 
            VALUES (:qid, :product_name, :qty, :unitcost, :total, :pstatus)
        ");
        $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
        $stmt->bindValue(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindValue(':unitcost', $unitCost, PDO::PARAM_STR);
        $stmt->bindValue(':total', $total, PDO::PARAM_STR);
        $stmt->bindValue(':pstatus', 'Completed', PDO::PARAM_STR); // Set pstatus to 'Completed'
        
        $result = $stmt->execute();

        if ($result) {
            $this->updateQuotationTotals($qid);
        }

        return $result;
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        return false;
    }
}


    /**
     * Delete a product by its ID.
     */
    public function deleteProduct($id) {
        try {
            // Get the quotation ID before deleting the product
            $qid = $this->getQuotationIdByProduct($id);
            error_log("Attempting to delete product ID: " . $id . " by user: " . $this->current_user);
            
            $stmt = $this->conn->prepare("DELETE FROM tblqproducts WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            // Update the quotation totals if the product was deleted
            if ($result && $qid) {
                $this->updateQuotationTotals($qid);
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing product.
     */
    public function updateProduct($id, $productName, $qty, $unitCost, $total) {
        try {
            $stmt = $this->conn->prepare("UPDATE tblqproducts SET product_name = :product_name, qty = :qty, unitcost = :unitcost, total = :total WHERE id = :id");
            $stmt->bindValue(':product_name', $productName, PDO::PARAM_STR);
            $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $stmt->bindValue(':unitcost', $unitCost, PDO::PARAM_STR);
            $stmt->bindValue(':total', $total, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            // Update quotation totals after updating the product
            if ($result) {
                $qid = $this->getQuotationIdByProduct($id);
                if ($qid) {
                    $this->updateQuotationTotals($qid);
                }
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Update quotation header details.
     */
    public function updateQuotationHeader($qid, $companyName, $contactPerson, $contactNo, $address) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE tblquotations 
                SET company_name = :company_name, 
                    contact_person = :contact_person, 
                    contact_no = :contact_no, 
                    address = :address 
                WHERE qid = :qid
            ");
            $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
            $stmt->bindValue(':contact_person', $contactPerson, PDO::PARAM_STR);
            $stmt->bindValue(':contact_no', $contactNo, PDO::PARAM_STR);
            $stmt->bindValue(':address', $address, PDO::PARAM_STR);
            $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            if ($result) {
                $this->updateQuotationTotals($qid);
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error updating quotation header: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Update the totals for a specific quotation.
     */
    private function updateQuotationTotals($qid) {
        try {
            $stmt = $this->conn->prepare("SELECT SUM(qty) AS tqty, SUM(total) AS qvalue FROM tblqproducts WHERE qid = :qid");
            $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
            $stmt->execute();
            $totals = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->conn->prepare("UPDATE tblquotations SET tqty = IFNULL(:tqty, 0), qvalue = IFNULL(:qvalue, 0) WHERE qid = :qid");
            $stmt->bindValue(':tqty', $totals['tqty'], PDO::PARAM_INT);
            $stmt->bindValue(':qvalue', $totals['qvalue'], PDO::PARAM_STR);
            $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating quotation totals: " . $e->getMessage());
        }
    }

    /**
     * Get product details by product ID.
     */
    public function getProductById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tblqproducts WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching product by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get quotation ID by product ID.
     */
    private function getQuotationIdByProduct($id) {
        try {
            $stmt = $this->conn->prepare("SELECT qid FROM tblqproducts WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error fetching quotation ID by product: " . $e->getMessage());
            return null;
        }
    }
}
?>
