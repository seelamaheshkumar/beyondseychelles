<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/database.php';

class JobsModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
    public function getJobDetailsById($jobId) {
        $sql = "SELECT * FROM tbljobs WHERE jobid = :jobid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':jobid', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($jobDetails) {
            $jobDetails['orderdate'] = date('d-m-Y', strtotime($jobDetails['orderdate']));
        }
        return $jobDetails;
    }
     public function showJobListRowCount($jobId) {
        $sql = "SELECT COUNT(*) as count FROM tbloproducts where jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function showJobList($jobId) {
        $sql = "SELECT * FROM tbloproducts WHERE jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function showPayListRowCount($jobId) {
        $sql = "SELECT COUNT(*) as count FROM tblcashflow where jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function showPayList($jobId) {
        $sql = "SELECT * FROM tblcashflow WHERE jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getJobSums($jobId) {
        $sql = "SELECT SUM(qty) as total_qty, SUM(total) as total_sum FROM tbloproducts WHERE jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getJobvat($jobId) {
        $sql = "SELECT vat FROM tbljobs WHERE jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && isset($result['vat'])) {
            return $result['vat'];
        } else {
            return 0; // Return 0 if the jobId does not exist or if no vat value is found
        }
    }
    public function getJobSt($jobId) {
        $sql = "SELECT order_status FROM tbljobs WHERE jobid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && isset($result['order_status'])) {
            return $result['order_status'];
        } else {
            return false; // Return 0 if the jobId does not exist or if no vat value is found
        }
    }
    public function addOProduct_bak($productData) {
        try {
            if (!isset($productData['jobid1'], $productData['product_name'], $productData['qty'], $productData['unitcost'], $productData['total'])) {
                throw new InvalidArgumentException('Missing required product data.');
            }
            $sql = "INSERT INTO tbloproducts (jobid, product_name, qty, unitcost, total) 
                    VALUES (:jobid, :product_name, :qty, :unitcost, :total)";
            $stmt = $this->conn->prepare($sql);
            $total_1=ceil($productData['total']);
            $stmt->bindParam(':jobid', $productData['jobid1'], PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $productData['product_name'], PDO::PARAM_STR);
            $stmt->bindParam(':qty', $productData['qty'], PDO::PARAM_INT);
            $stmt->bindParam(':unitcost', $productData['unitcost'], PDO::PARAM_STR);
            $stmt->bindParam(':total',$total_1, PDO::PARAM_STR);
            $stmt->execute();
            $jobSums = $this->getJobSums($productData['jobid1']);
            $vatvalue=$productData['vat1'];
            $tq=$jobSums['total_qty'];
            $tv=$jobSums['total_sum'];
            $vat=0;
            if($vatvalue>0){
                $vat = ($productData['total']/100)*15;
            }
           $sqlUpdateQuotation = "UPDATE tbljobs 
                               SET oqty = oqty = :tqty,
                                   ovalue = ovalue + :total, 
                                   vat = vat + :vat, 
                                   netvalue = netvalue + :netvalue, 
                                   balance = balance + :nbalance
                               WHERE jobid = :jid";
                                                     
        $stmt = $this->conn->prepare($sqlUpdateQuotation);

        // Bind the parameters
        $stmt->bindParam(':tqty', $productData['qty']);
        $stmt->bindParam(':total', $total_1);
        $stmt->bindParam(':vat', $vat);
        $stmt->bindParam(':netvalue', $total_1);
        $stmt->bindParam(':nbalance', $total_1);
        $stmt->bindParam(':jid', $productData['jobid1'], PDO::PARAM_INT);

        // Execute the statement
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
    public function addOProduct($productData) {
        try {
            if (!isset($productData['jobid1'], $productData['product_name'], $productData['qty'], $productData['unitcost'], $productData['total'])) {
                throw new InvalidArgumentException('Missing required product data.');
            }
            $sql = "INSERT INTO tbloproducts (jobid, product_name, qty, unitcost, total) 
                    VALUES (:jobid, :product_name, :qty, :unitcost, :total)";
            $stmt = $this->conn->prepare($sql);
            $total_1=ceil($productData['total']);
            $stmt->bindParam(':jobid', $productData['jobid1'], PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $productData['product_name'], PDO::PARAM_STR);
            $stmt->bindParam(':qty', $productData['qty'], PDO::PARAM_INT);
            $stmt->bindParam(':unitcost', $productData['unitcost'], PDO::PARAM_STR);
            $stmt->bindParam(':total',$total_1, PDO::PARAM_STR);

            $stmt->execute();
             $jobSums = $this->getJobSums($productData['jobid1']);
            $vatvalue=$productData['vat1'];
            $tq=$jobSums['total_qty'];
            $tv=$jobSums['total_sum'];
            $vat=0;
            if($vatvalue>0){
                $vat = ($productData['total']/100)*15;
            }
            $sqlUpdateQuotation = "UPDATE tbljobs 
                               SET oqty =  :tqty,
                                   ovalue = ovalue + :total, 
                                   vat = vat + :vat, 
                                   netvalue = netvalue + :netvalue, 
                                   balance = balance + :nbalance
                               WHERE jobid = :jid";
        $stmt = $this->conn->prepare($sqlUpdateQuotation);

        // Bind the parameters
        $stmt->bindParam(':tqty', $tq);
        $stmt->bindParam(':total', $total_1);
        $stmt->bindParam(':vat', $vat);
        $stmt->bindParam(':netvalue', $total_1);
        $stmt->bindParam(':nbalance', $total_1);
        $stmt->bindParam(':jid', $productData['jobid1'], PDO::PARAM_INT);


    // Print the query with parameters
    /*$queryWithParams = str_replace(
        [':tqty', ':total', ':vat', ':netvalue', ':nbalance', ':jid'],
        [$tq, $total_1, $vat, $total_1, $total_1, $jid],
        $sqlUpdateQuotation
    );
    
    error_log($queryWithParams);*/

        // Execute the statement
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
    
    public function updateJProduct($productData) {
    try {
        // Check if required product data is present
        if (!isset($productData['jobid2'], $productData['pid2'], $productData['pname'], $productData['pqty'], $productData['ucost'], $productData['tot1'], $productData['vat2'])) {
            throw new InvalidArgumentException('Missing required product data.');
        }

        // Prepare SQL update query for the product
        $sql = "UPDATE tbloproducts 
                SET product_name = :product_name, qty = :qty, unitcost = :unitcost, total = :total 
                WHERE jobid = :jobid AND id = :pid";
        $stmt = $this->conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':jobid', $productData['jobid2'], PDO::PARAM_INT);
        $stmt->bindParam(':pid', $productData['pid2'], PDO::PARAM_INT);
        $stmt->bindParam(':product_name', $productData['pname'], PDO::PARAM_STR);
        $stmt->bindParam(':qty', $productData['pqty'], PDO::PARAM_INT);
        $stmt->bindParam(':unitcost', $productData['ucost'], PDO::PARAM_STR);
        $stmt->bindParam(':total', $productData['tot1'], PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            // Call calc_job_orders to update job totals
            $this->calc_job_orders($productData['jobid2'], $productData['vat2'], $productData['tot1']);
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
 public function updateJobData($productData) {
    try {
        // Check if required product data is present
        if (!isset($productData['jobid3'], $productData['cmpname'],  $productData['cmprep'], $productData['cmpcontact'], $productData['cmpaddress'], $productData['opcreditbill'])) {
            throw new InvalidArgumentException('Missing required Job Data.');
        }

        // Prepare SQL update query for the product
        $sql = "UPDATE tbljobs 
                SET company_name = :company_name, contact_person = :contact_person, contact_no = :contact_no, address = :address, credit_bill = :credit_bill 
                WHERE jobid = :jobid";
        $stmt = $this->conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':jobid', $productData['jobid3'], PDO::PARAM_INT);
        $stmt->bindParam(':company_name', $productData['cmpname'], PDO::PARAM_STR);
        $stmt->bindParam(':contact_person', $productData['cmprep'], PDO::PARAM_STR);
        $stmt->bindParam(':contact_no', $productData['cmpcontact'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $productData['cmpaddress'], PDO::PARAM_STR);
        $stmt->bindParam(':credit_bill', $productData['opcreditbill'], PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
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
public function calc_job_orders($jobid, $vat1, $tot){
    try {
        // Fetch job sums
        $jobSums = $this->getJobSums($jobid);

        // Ensure jobSums contains expected keys
        if (!isset($jobSums['total_qty']) || !isset($jobSums['total_sum'])) {
            throw new Exception('Job sums data is incomplete');
        }

        $vatvalue = $vat1;
        $totalQty = $jobSums['total_qty'];
        $totalValue = $jobSums['total_sum'];
        
        // Calculate VAT if applicable
        $vat = 0;
        if ($vatvalue > 0) {
            $vat = ($tot / 100) * 15;
        }

        // Prepare SQL update query for the job
        $sqlUpdateJob = "
            UPDATE tbljobs 
            SET oqty = :tqty,
                ovalue = :tvalue, 
                vat = vat + :vat, 
                netvalue = (ovalue - discount) + (vat + :vat), 
                balance = (netvalue - paid_amount)
            WHERE jobid = :jid";
        
        $stmt = $this->conn->prepare($sqlUpdateJob);

        // Bind the parameters
        $stmt->bindParam(':tqty', $totalQty, PDO::PARAM_INT);
        $stmt->bindParam(':tvalue', $totalValue);
        $stmt->bindParam(':vat', $vat);
        $stmt->bindParam(':jid', $jobid, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
        //error_log("Update query executed: " . $sqlUpdateJob);
    } catch (Exception $e) {
        error_log("Error in calc_job_orders: " . $e->getMessage());
    }
}
        public function deleteOProduct($id, $jobid) {
        try {
            $sql = "DELETE FROM tbloproducts WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                 $this->calc_job_orders($jobid,$this->getJobvat($jobid),0);
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
        public function getPaymentDetailsFromDatabase($pid) {
            try {
                $sql = "SELECT * FROM tblcashflow WHERE id = :pid";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':pid', $pid);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return null;
            }
        }

        public function recalculateJobPayments($jobid) {
            try {
                $sql = "SELECT SUM(cash) as t_cash, SUM(card) as t_card, SUM(cheque) as t_cheque, SUM(wallet) as t_wallet 
                        FROM tblcashflow WHERE jobid = :jobid";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':jobid', $jobid);
                $stmt->execute();
                $sums = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $t_cash = $sums['t_cash'] ? $sums['t_cash'] : 0;
                $t_card = $sums['t_card'] ? $sums['t_card'] : 0;
                $t_cheque = $sums['t_cheque'] ? $sums['t_cheque'] : 0;
                $t_wallet = $sums['t_wallet'] ? $sums['t_wallet'] : 0;
                $paid_amount = $t_cash + $t_card + $t_cheque + $t_wallet;
                
                $sql2 = "UPDATE tbljobs SET cash = :cash, card = :card, cheque = :cheque, wallet = :wallet, 
                         paid_amount = :paid_amount, balance = netvalue - :paid_amount2 
                         WHERE jobid = :jobid";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->bindParam(':cash', $t_cash);
                $stmt2->bindParam(':card', $t_card);
                $stmt2->bindParam(':cheque', $t_cheque);
                $stmt2->bindParam(':wallet', $t_wallet);
                $stmt2->bindParam(':paid_amount', $paid_amount);
                $stmt2->bindParam(':paid_amount2', $paid_amount);
                $stmt2->bindParam(':jobid', $jobid);
                $stmt2->execute();
            } catch (PDOException $e) {
                error_log("Error recalculating job payments: " . $e->getMessage());
            }
        }

        public function updatePaymentData($payData) {
            try {
                $sql = "UPDATE tblcashflow SET cash = :cash, card = :card, cheque = :cheque, wallet = :wallet, remarks = :remarks WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':cash', $payData['pcash']);
                $stmt->bindParam(':card', $payData['pcard']);
                $stmt->bindParam(':cheque', $payData['pcheque']);
                $stmt->bindParam(':wallet', $payData['pwallet']);
                $stmt->bindParam(':remarks', $payData['premarks']);
                $stmt->bindParam(':id', $payData['payid']);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $this->recalculateJobPayments($payData['jobid4']);
                    return true;
                }
                return false; // Or it might just be the same data, but return true if we just recalculate anyway
            } catch (PDOException $e) {
                return false;
            }
        }

        public function deletePayment($id, $jobid) {
            try {
                $sql = "DELETE FROM tblcashflow WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $this->recalculateJobPayments($jobid);
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                return false;
            }
        }
}
?>
