<?php
require_once '../config/database.php';

class NewQuotationModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function readAllQProducts() {
        $sql = "SELECT * FROM tblqproducts WHERE pstatus='Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalQProductsRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tblqproducts WHERE pstatus='Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function totalQtyRowSum() {
        $sql = "SELECT SUM(tqty) as tq as count FROM tblqproducts WHERE pstatus='Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['tq'];
    }
    public function totalValueRowSum() {
        $sql = "SELECT SUM(qvalue) as tq as count FROM tblqproducts WHERE pstatus='Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['tq'];
    }
    
    public function addQProduct($productData) {
        try {
            if (!isset($productData['qid'], $productData['product_name'], $productData['qty'], $productData['unitcost'], $productData['total'])) {
                throw new InvalidArgumentException('Missing required product data.');
            }

            $sql = "INSERT INTO tblqproducts (qid, product_name, qty, unitcost, total) 
                    VALUES (:qid, :product_name, :qty, :unitcost, :total)";
            $stmt = $this->conn->prepare($sql);
            $total_1 = ceil($productData['total']);
            $stmt->bindParam(':qid', $productData['qid'], PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $productData['product_name'], PDO::PARAM_STR);
            $stmt->bindParam(':qty', $productData['qty'], PDO::PARAM_INT);
            $stmt->bindParam(':unitcost', $productData['unitcost'], PDO::PARAM_STR);
            $stmt->bindParam(':total', $total_1, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("Insert failed: No rows affected.");
                return false;
            }
        } catch (InvalidArgumentException $e) {
            error_log("Validation Error: " . $e->getMessage());
            return false;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function addQuotation($quotationData) {
    try {
        if (!isset($quotationData['company_name'], $quotationData['contact_person'], $quotationData['tqty'], $quotationData['qvalue'])) {
            throw new InvalidArgumentException('Missing required quotation data.');
        }

        // Insert into tblquotations and get the new qid
        $sqlInsertQuotation = "INSERT INTO tblquotations (qdate, company_name, contact_person, contact_no, address, tqty, qvalue) 
                               VALUES (:qdate, :company_name, :contact_person, :contact_no, :address, :tqty, :qvalue)";
        $stmtInsertQuotation = $this->conn->prepare($sqlInsertQuotation);
        $qdate = date('Y-m-d');
        $stmtInsertQuotation->bindParam(':qdate', $qdate);
        $stmtInsertQuotation->bindParam(':company_name', $quotationData['company_name']);
        $stmtInsertQuotation->bindParam(':contact_person', $quotationData['contact_person']);
        $stmtInsertQuotation->bindParam(':contact_no', $quotationData['contact_no']);
        $stmtInsertQuotation->bindParam(':address', $quotationData['address']);
        $stmtInsertQuotation->bindParam(':tqty', $quotationData['tqty']);
        $stmtInsertQuotation->bindParam(':qvalue', $quotationData['qvalue']);
        $stmtInsertQuotation->execute();

        if ($stmtInsertQuotation->rowCount() > 0) {
            // Get the new qid
            $newQid = $this->conn->lastInsertId();
            $newPs="Completed";
            // Update tblqproducts where qid = 0 to the new qid
            $sqlUpdateProducts = "UPDATE tblqproducts SET qid = :newQid, pstatus = :ps WHERE qid = 0";
            $stmtUpdateProducts = $this->conn->prepare($sqlUpdateProducts);
            $stmtUpdateProducts->bindParam(':newQid', $newQid, PDO::PARAM_INT);
            $stmtUpdateProducts->bindParam(':ps',$newPs, PDO::PARAM_STR);
            $stmtUpdateProducts->execute();

            return true;
        } else {
            error_log("Insert failed: No rows affected.");
            return false;
        }
    } catch (InvalidArgumentException $e) {
        error_log("Validation Error: " . $e->getMessage());
        return false;
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

        public function addQuotation_bak($productData) {
        try {
            if (!isset($productData['company_name'], $productData['contact_person'], $productData['tqty'], $productData['qvalue'])) {
                throw new InvalidArgumentException('Missing required quotation data.');
            }

            $sql = "INSERT INTO tblquotations (qdate, company_name, contact_person, contact_no, address, tqty, qvalue) 
                    VALUES (:qdate, :company_name, :contact_person, :contact_no, :address, :tqty, :qvalue)";
            $stmt = $this->conn->prepare($sql);
            $qdate=date('Y-m-d');
            $stmt->bindParam(':qdate', $qdate);
            $stmt->bindParam(':company_name', $productData['company_name']);
            $stmt->bindParam(':contact_person', $productData['contact_person']);
            $stmt->bindParam(':contact_no', $productData['contact_no']);
            $stmt->bindParam(':address', $productData['address']);
            $stmt->bindParam(':tqty', $productData['tqty']);
            $stmt->bindParam(':qvalue', $productData['qvalue']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("Insert failed: No rows affected.");
                return false;
            }
        } catch (InvalidArgumentException $e) {
            error_log("Validation Error: " . $e->getMessage());
            return false;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
    
        public function deleteQProduct($id) {
        try {
            $sql = "DELETE FROM tblqproducts WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
}
?>