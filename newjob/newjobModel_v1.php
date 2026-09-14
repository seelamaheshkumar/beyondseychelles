<?php
require_once '../config/database.php';
date_default_timezone_set('Indian/Mahe');

class NewJobModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function readAllOProducts() {
        $sql = "SELECT * FROM tbloproducts WHERE jobid=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalOProductsRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tbloproducts WHERE jobid=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function totalQtyRowSum() {
        $sql = "SELECT SUM(tqty) as tq as count FROM tbloproducts WHERE jobid=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['tq'];
    }
    public function totalValueRowSum() {
        $sql = "SELECT SUM(qvalue) as tq as count FROM tbloproducts WHERE jobid=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['tq'];
    }
    
    public function addOProduct($productData) {
        try {
            if (!isset($productData['qid'], $productData['product_name'], $productData['qty'], $productData['unitcost'], $productData['total'])) {
                throw new InvalidArgumentException('Missing required product data.');
            }

            $sql = "INSERT INTO tbloproducts (jobid, product_name, qty, unitcost, total) 
                    VALUES (:qid, :product_name, :qty, :unitcost, :total)";
            $stmt = $this->conn->prepare($sql);
            $total_1=ceil($productData['total']);
            $stmt->bindParam(':qid', $productData['qid'], PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $productData['product_name'], PDO::PARAM_STR);
            $stmt->bindParam(':qty', $productData['qty'], PDO::PARAM_INT);
            $stmt->bindParam(':unitcost', $productData['unitcost'], PDO::PARAM_STR);
            $stmt->bindParam(':total',$total_1, PDO::PARAM_STR);

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
       public function updateOProduct($productData) {
    try {
        if (!isset($productData['jid2'], $productData['pid1'], $productData['pname'], $productData['pqty'], $productData['ucost'], $productData['tot1'])) {
            throw new InvalidArgumentException('Missing required product data.');
        }

        $sql = "UPDATE tbloproducts 
                SET product_name = :product_name, qty = :qty, unitcost = :unitcost, total = :total 
                WHERE jobid = :jobid AND id = :pid";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':jobid', $productData['jid2'], PDO::PARAM_INT);
        $stmt->bindParam(':pid', $productData['pid1'], PDO::PARAM_INT);
        $stmt->bindParam(':product_name', $productData['pname'], PDO::PARAM_STR);
        $stmt->bindParam(':qty', $productData['pqty'], PDO::PARAM_INT);
        $stmt->bindParam(':unitcost', $productData['ucost'], PDO::PARAM_STR);
        $stmt->bindParam(':total', $productData['tot1'], PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            error_log("Update failed: No rows affected.");
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
    public function addJob($jobData) {
    try {
        if (!isset($jobData['company_name'], $jobData['contact_person'], $jobData['contact_no'], $jobData['address'], $jobData['tqty'], $jobData['qvalue'], $jobData['discount'], $jobData['vat'], $jobData['netvalue'], $jobData['credit_bill'], $jobData['cash'], $jobData['card'], $jobData['cheque'], $jobData['wallet'], $jobData['paid_amount'], $jobData['balance'])) {
            throw new InvalidArgumentException('Missing required job data.');
        }

        // Insert into tbljobs and get the new jobid
        $sqlInsertJob = "INSERT INTO tbljobs (qid, orderdate, company_name, contact_person, contact_no, address, oqty, ovalue, discount, vat, netvalue, credit_bill, cash, card, cheque, wallet, paid_amount, balance, createdby) 
                               VALUES (:qid, :orderdate, :company_name, :contact_person, :contact_no, :address, :tqty, :qvalue, :discount, :vat, :netvalue, :credit_bill, :cash, :card, :cheque, :wallet, :paid_amount, :balance, :createdby)";
        $stmtInsertJob = $this->conn->prepare($sqlInsertJob);
        
        $orderdate = date('Y-m-d'); // Assuming you want to use the current date for orderdate
        $createdby = 'admin'; // Assuming you want to set the createdby field to 'admin', you can change this as needed
        $stmtInsertJob->bindParam(':qid', $jobData['qid'], PDO::PARAM_INT);
        $stmtInsertJob->bindParam(':orderdate', $orderdate);
        $stmtInsertJob->bindParam(':company_name', $jobData['company_name']);
        $stmtInsertJob->bindParam(':contact_person', $jobData['contact_person']);
        $stmtInsertJob->bindParam(':contact_no', $jobData['contact_no']);
        $stmtInsertJob->bindParam(':address', $jobData['address']);
        $stmtInsertJob->bindParam(':tqty', $jobData['tqty']);
        $stmtInsertJob->bindParam(':qvalue', $jobData['qvalue']);
        $stmtInsertJob->bindParam(':discount', $jobData['discount']);
        $stmtInsertJob->bindParam(':vat', $jobData['vat']);
        $stmtInsertJob->bindParam(':netvalue', $jobData['netvalue']);
        $stmtInsertJob->bindParam(':credit_bill', $jobData['credit_bill']);
        $stmtInsertJob->bindParam(':cash', $jobData['cash']);
        $stmtInsertJob->bindParam(':card', $jobData['card']);
        $stmtInsertJob->bindParam(':cheque', $jobData['cheque']);
        $stmtInsertJob->bindParam(':wallet', $jobData['wallet']);
        $stmtInsertJob->bindParam(':paid_amount', $jobData['paid_amount']);
        $stmtInsertJob->bindParam(':balance', $jobData['balance']);
        $stmtInsertJob->bindParam(':createdby', $createdby);
        
        $stmtInsertJob->execute();

        if ($stmtInsertJob->rowCount() > 0) {
            // Get the new jobid
            $newJobid = $this->conn->lastInsertId();
            // Update tbloproducts where jobid = 0 to the new jobid
            $sqlUpdateProducts = "UPDATE tbloproducts SET jobid = :newJobid WHERE jobid = 0";
            $stmtUpdateProducts = $this->conn->prepare($sqlUpdateProducts);
            $stmtUpdateProducts->bindParam(':newJobid', $newJobid, PDO::PARAM_INT);
            $stmtUpdateProducts->execute();
            
            // Insert into tblcashflow
            $sqlInsertCashFlow = "INSERT INTO tblcashflow (jobid, paydate, cash, card, cheque, wallet, remarks) 
                                  VALUES (:jobid, :paydate, :cash, :card, :cheque, :wallet, :remarks)";
            $stmtInsertCashFlow = $this->conn->prepare($sqlInsertCashFlow);
            $paydate = date('Y-m-d'); // Assuming you want to set paydate to current date
            $stmtInsertCashFlow->bindParam(':jobid', $newJobid);
            $stmtInsertCashFlow->bindParam(':paydate', $paydate);
            $stmtInsertCashFlow->bindParam(':cash', $jobData['cash']);
            $stmtInsertCashFlow->bindParam(':card', $jobData['card']);
            $stmtInsertCashFlow->bindParam(':cheque', $jobData['cheque']);
            $stmtInsertCashFlow->bindParam(':wallet', $jobData['wallet']);
            //$remarks = "Payment for job " . $newJobid; // You can customize the remarks as needed
            $stmtInsertCashFlow->bindParam(':remarks', $jobData['remarks']);
            $stmtInsertCashFlow->execute();
            
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

    public function addJob_bak($jobData) {
    try {
        if (!isset($jobData['company_name'], $jobData['contact_person'], $jobData['tqty'], $jobData['qvalue'])) {
            throw new InvalidArgumentException('Missing required quotation data.');
        }

        // Insert into tblquotations and get the new qid
        $sqlInsertJob = "INSERT INTO tbljobs (qdate, company_name, contact_person, contact_no, address, tqty, qvalue) 
                               VALUES (:qdate, :company_name, :contact_person, :contact_no, :address, :tqty, :qvalue)";
        $stmtInsertJob = $this->conn->prepare($sqlInsertJob);
        $orderdate = date('Y-m-d');
        $createdon = date('Y-m-d');
        $stmtInsertJob->bindParam(':qdate', $qdate);
        $stmtInsertJob->bindParam(':company_name', $jobData['company_name']);
        $stmtInsertJob->bindParam(':contact_person', $jobData['contact_person']);
        $stmtInsertJob->bindParam(':contact_no', $jobData['contact_no']);
        $stmtInsertJob->bindParam(':address', $jobData['address']);
        $stmtInsertJob->bindParam(':tqty', $jobData['tqty']);
        $stmtInsertJob->bindParam(':qvalue', $jobData['qvalue']);
        $stmtInsertJob->execute();

        if ($stmtInsertJob->rowCount() > 0) {
            // Get the new qid
            $newJobid = $this->conn->lastInsertId();
            // Update tblqproducts where qid = 0 to the new qid
            $sqlUpdateProducts = "UPDATE tbloproducts SET qid = :newJid WHERE jobid = 0";
            $stmtUpdateProducts = $this->conn->prepare($sqlUpdateProducts);
            $stmtUpdateProducts->bindParam(':newJid', $newJobid, PDO::PARAM_INT);
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

        public function deleteOProduct($id) {
        try {
            $sql = "DELETE FROM tbloproducts WHERE id = :id";
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
        public function getProductDetailsFromDatabase($pid) {
        try {
            $sql = "SELECT * FROM tbloproducts WHERE id = :pid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':pid', $pid);
            $stmt->execute();
            $PDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            return $PDetails;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return null;
        }
    }
    
}
?>
