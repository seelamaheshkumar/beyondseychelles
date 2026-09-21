<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

class JobsModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function readAllJobs() {
        $sql = "SELECT * FROM tbljobs where order_status<>'Cancelled' ANDcredit_bill='Yes' order by jobid, orderdate desc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function totalJobsRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tbljobs where order_status<>'Cancelled' AND credit_bill='Yes'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function readJobsByDateRange($fdate, $tdate) {
        //$sql = "SELECT * FROM tbljobs where balance<>0 AND credit_bill='Yes' AND orderdate BETWEEN :fdate AND :tdate order by jobid desc";
        $sql = "SELECT * FROM tbljobs where order_status<>'Cancelled' AND balance<>0 AND credit_bill='Yes' order by jobid desc";
        $stmt = $this->conn->prepare($sql);
        //$stmt->bindParam(':fdate', $fdate);
        //$stmt->bindParam(':tdate', $tdate);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalJobsRowCountByDateRange($fdate, $tdate) {
        //$sql = "SELECT COUNT(*) as count FROM tbljobs where  balance<>0 AND credit_bill='Yes' AND orderdate BETWEEN :fdate AND :tdate";
        $sql = "SELECT COUNT(*) as count FROM tbljobs where order_status<>'Cancelled' AND  balance<>0 AND credit_bill='Yes'";
        $stmt = $this->conn->prepare($sql);
        //$stmt->bindParam(':fdate', $fdate);
        //$stmt->bindParam(':tdate', $tdate);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    // Function to fetch job details from the database
    public function getJobDetailsFromDatabase($jobId) {
        try {
            $sql = "SELECT * FROM tbljobs WHERE jobid = :jobId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':jobId', $jobId);
            $stmt->execute();
            $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            return $jobDetails;
        } catch (PDOException $e) {
            // Handle database error
            error_log("Database Error: " . $e->getMessage());
            return null;
        }
    }
    
   public function updatePay($jobData) {
        try {
            if (!isset($jobData['jobId'], $jobData['newbalance'], $jobData['paidAmount'], $jobData['cash'], $jobData['card'], $jobData['cheque'], $jobData['wallet'], $jobData['disc'])) {
                throw new InvalidArgumentException('Missing required job data.');
            }
    
            $query = "UPDATE tbljobs SET netvalue=netvalue-:newnet, discount=discount+:discount, cash=cash+:ucash, card=card+:ucard, cheque=cheque+:ucheque, wallet=wallet+:uwallet, paid_amount=paid_amount+:upaid_amount, balance=:ubalance";
            
            if (floatval($jobData['newbalance']) <= 0) {
                $query .= ", order_status = CASE WHEN order_status = 'Pending' THEN 'Ready' ELSE order_status END";
            }

            $query .= " WHERE jobid=:jid";
            $stmtInsertJob = $this->conn->prepare($query);
            $stmtInsertJob->bindParam(':jid', $jobData['jobId'], PDO::PARAM_INT);
            $stmtInsertJob->bindParam(':newnet', $jobData['disc']);
            $stmtInsertJob->bindParam(':discount', $jobData['disc']);
            $stmtInsertJob->bindParam(':ucash', $jobData['cash']);
            $stmtInsertJob->bindParam(':ucard', $jobData['card']);
            $stmtInsertJob->bindParam(':ucheque', $jobData['cheque']);
            $stmtInsertJob->bindParam(':uwallet', $jobData['wallet']);
            $stmtInsertJob->bindParam(':upaid_amount', $jobData['paidAmount']);
            $stmtInsertJob->bindParam(':ubalance', $jobData['newbalance']);
            $stmtInsertJob->execute();
    
            if ($stmtInsertJob->rowCount() > 0) {
                // Get the new jobid
                $newJobid = $jobData['jobId'];
                $paydate=date('Y-m-d');
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
        public function updatePay_bak($jobData) {
        try {
            if (!isset($jobData['jobId'], $jobData['newbalance'], $jobData['paidAmount'], $jobData['cash'], $jobData['card'], $jobData['cheque'], $jobData['wallet'])) {
                throw new InvalidArgumentException('Missing required job data.');
            }
    
            $query = "UPDATE tbljobs SET cash=cash+:ucash, card=card+:ucard, cheque=cheque+:ucheque, wallet=wallet+:uwallet, paid_amount=paid_amount+:upaid_amount, balance=:ubalance WHERE jobid=:jid";
            $stmtInsertJob = $this->conn->prepare($query);
            $stmtInsertJob->bindParam(':jid', $jobData['jobId'], PDO::PARAM_INT);
            $stmtInsertJob->bindParam(':ucash', $jobData['cash']);
            $stmtInsertJob->bindParam(':ucard', $jobData['card']);
            $stmtInsertJob->bindParam(':ucheque', $jobData['cheque']);
            $stmtInsertJob->bindParam(':uwallet', $jobData['wallet']);
            $stmtInsertJob->bindParam(':upaid_amount', $jobData['paidAmount']);
            $stmtInsertJob->bindParam(':ubalance', $jobData['newbalance']);
            $stmtInsertJob->execute();
    
            if ($stmtInsertJob->rowCount() > 0) {
                // Get the new jobid
                $newJobid = $jobData['jobId'];
                $paydate=date('Y-m-d');
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

        public function updateStatus($jobData) {
            try {
                // Check if the required job data is set
                if (!isset($jobData['jbid'], $jobData['order_status'])) {
                    throw new InvalidArgumentException('Missing required job data.');
                }
        
                // Prepare the SQL query
                $query = "UPDATE tbljobs SET order_status=:uorder_status WHERE jobid=:jid";
                $stmtUpdateJob = $this->conn->prepare($query);
            
                // Bind the parameters
                $stmtUpdateJob->bindParam(':jid', $jobData['jbid'], PDO::PARAM_INT);
                $stmtUpdateJob->bindParam(':uorder_status', $jobData['order_status']);
        
                // Substitute bound values into the query for logging
                $loggedQuery = $query;
                $loggedQuery = str_replace(':jid', $this->conn->quote($jobData['jbid'], PDO::PARAM_INT), $loggedQuery);
                $loggedQuery = str_replace(':uorder_status', $this->conn->quote($jobData['order_status']), $loggedQuery);
        
                // Log the query
                //error_log("Executing query: " . $loggedQuery);
        
                // Execute the statement
                $stmtUpdateJob->execute();
            
                // Check if any rows were affected
                if ($stmtUpdateJob->rowCount() > 0) {
                    return true;
                } else {
                    error_log("Update failed: No rows affected for job ID " . $jobData['jbid']);
                    return false;
                }
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                return false;
            } catch (InvalidArgumentException $e) {
                error_log("Validation Error: " . $e->getMessage());
                return false;
            } catch (Exception $e) {
                error_log("General Error: " . $e->getMessage());
                return false;
            }
        }

public function deleteJob($QId) {
    try {
        // Start a transaction
        $this->conn->beginTransaction();

        // Prepare SQL statement to update the order status in tbljobs
        $sqlDeleteQuotation = "UPDATE tbljobs SET order_status = :uorder_status WHERE jobid = :id";
        $stmtDeleteQuotation = $this->conn->prepare($sqlDeleteQuotation);

        // Bind parameters
        $newstatus = 'Cancelled';
        $stmtDeleteQuotation->bindParam(':id', $QId, PDO::PARAM_INT);
        $stmtDeleteQuotation->bindParam(':uorder_status', $newstatus, PDO::PARAM_STR);

        // Execute the statement
        $stmtDeleteQuotation->execute();

        // Check if the update was successful
        if ($stmtDeleteQuotation->rowCount() > 0) {
            // Commit the transaction
            $this->conn->commit();
            return true;
        } else {
            // Roll back the transaction if the update failed
            $this->conn->rollBack();
            return false;
        }
    } catch (PDOException $e) {
        // Roll back the transaction in case of error
        $this->conn->rollBack();
        // Handle database error
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

public function deleteJob_BAK($QId) {
    try {
        // Start a transaction
        $this->conn->beginTransaction();

        // Prepare SQL statement to delete from tblqproducts
        /*$sqlDeleteProducts = "DELETE FROM tbloproducts WHERE jobid = :id";
        $stmtDeleteProducts = $this->conn->prepare($sqlDeleteProducts);
        // Bind parameter
        $stmtDeleteProducts->bindParam(':id', $QId);
        // Execute the statement
        $stmtDeleteProducts->execute();*/

        
        // Prepare SQL statement to delete from tblquotations
        //$sqlDeleteQuotation = "DELETE FROM tbljobs WHERE jobid = :id";
        $sqlDeleteQuotation = "UPDATE tbljobs SET order_status=:uorder_status WHERE jobid = :id";
        $stmtDeleteQuotation = $this->conn->prepare($sqlDeleteQuotation);
        // Bind parameter
        $newstatus='Cancelled';
        $stmtDeleteQuotation->bindParam(':id', $QId);
        $stmtUpdateJob->bindParam(':uorder_status', $newstatus);
        // Execute the statement
        $stmtDeleteQuotation->execute();
        

        // Check if both deletions were successful
        //if ($stmtDeleteProducts->rowCount() > 0 && $stmtDeleteQuotation->rowCount() > 0) {
        if ($stmtDeleteQuotation->rowCount() > 0 ) {
            // Commit the transaction
            $this->conn->commit();
            return true;
        } else {
            // Roll back the transaction if any deletion failed
            $this->conn->rollBack();
            return false;
        }
    } catch (PDOException $e) {
        // Roll back the transaction in case of error
        $this->conn->rollBack();
        // Handle database error
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}


 /*public function addQuotation($userData) {
    try {
        // Prepare SQL statement to insert user into tblusers table
        $sql = "INSERT INTO tblquotations (qdate, company_name, contact_person, contact_no, address, tqty, qvalue,	qstatus) 
                VALUES (:username, :pass, :fullname, :role, :user_status, :email)";
        $stmt = $this->conn->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':pass', $userData['password']);
        $stmt->bindParam(':fullname', $userData['fullname']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':user_status', $userData['user_status']);
        $stmt->bindParam(':email', $userData['email']);
        // Execute the statement
        $stmt->execute();
        // Check if user was inserted successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        //echo "Database Error: " . $e->getMessage();
        return false;
    }
}


public function getUserById($userId) {
    try {
        // Prepare SQL statement to select user by ID
        $sql = "SELECT * FROM tblusers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameter
        $stmt->bindParam(':id', $userId);
        // Execute the statement
        $stmt->execute();
        // Fetch user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return user details
        return $user;
    } catch (PDOException $e) {
        // Handle database error
        return null;
    }
}


public function editUser($userData) {
    try {
        // Prepare SQL statement to update user in tblusers table
        $sql = "UPDATE tblusers SET username = :username, pass = :pass, fullname = :fullname, role = :role, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':id', $userData['id']);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':pass', $userData['password']);
        $stmt->bindParam(':fullname', $userData['fullname']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':email', $userData['email']);
        // Execute the statement
        $stmt->execute();
        // Check if user was updated successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        return false;
    }
}

public function deleteQuotation($QId) {
    try {
        // Start a transaction
        $this->conn->beginTransaction();

        // Prepare SQL statement to delete from tblqproducts
        $sqlDeleteProducts = "DELETE FROM tblqproducts WHERE qid = :id";
        $stmtDeleteProducts = $this->conn->prepare($sqlDeleteProducts);
        // Bind parameter
        $stmtDeleteProducts->bindParam(':id', $QId);
        // Execute the statement
        $stmtDeleteProducts->execute();

        // Prepare SQL statement to delete from tblquotations
        $sqlDeleteQuotation = "DELETE FROM tblquotations WHERE qid = :id";
        $stmtDeleteQuotation = $this->conn->prepare($sqlDeleteQuotation);
        // Bind parameter
        $stmtDeleteQuotation->bindParam(':id', $QId);
        // Execute the statement
        $stmtDeleteQuotation->execute();

        // Check if both deletions were successful
        if ($stmtDeleteProducts->rowCount() > 0 && $stmtDeleteQuotation->rowCount() > 0) {
            // Commit the transaction
            $this->conn->commit();
            return true;
        } else {
            // Roll back the transaction if any deletion failed
            $this->conn->rollBack();
            return false;
        }
    } catch (PDOException $e) {
        // Roll back the transaction in case of error
        $this->conn->rollBack();
        // Handle database error
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

public function deleteQuotation_bak($QId) {
    try {
        // Prepare SQL statement to delete user
        $sql = "DELETE FROM tblquotations WHERE qid = :id";
        $stmt = $this->conn->prepare($sql);
        // Bind parameter
        $stmt->bindParam(':id', $QId);
        // Execute the statement
        $stmt->execute();
        // Check if user was deleted successfully
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Handle database error
        return false;
    }
}
*/

    // Add insert, update, and delete methods here
}
?>
