<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

class UsersModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
public function readAllQuotations() {
            $sql = "SELECT * FROM tblquotations where qstatus='Pending' ORDER BY (YEAR(qdate) = YEAR(CURDATE())) DESC, qid desc";

//    $sql = "SELECT * FROM tblquotations WHERE qstatus='Pending' ORDER BY (YEAR(qdate) = YEAR(CURDATE())) DESC,  qdate DESC, order qid DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
    public function readAllQuotations1() {
        //$sql = "SELECT * FROM tblquotations where qstatus='Pending' order by qid desc";
        // $sql = "SELECT * FROM tblquotations where qstatus='Pending' order by qid,qdate desc";
        $sql = "SELECT * FROM tblquotations 
            WHERE qstatus='Pending' 
            ORDER BY 
                (YEAR(qdate) = YEAR(CURDATE())) DESC,               /* Prioritize current year records (current year first) */
                CAST(SUBSTRING_INDEX(qno, '/', 1) AS UNSIGNED) DESC, /* Sort by the numerical part of qno in descending order */
                qdate DESC,                                         /* Fallback sort by qdate in descending order */
                qid DESC;";                         
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function totalQuotationsRowCount() {
        $sql = "SELECT COUNT(*) as count FROM tblquotations where qstatus='Pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getKPIs($year = '') {
        $yearCondition = "";
        if (!empty($year)) {
            $yearCondition = " AND YEAR(qdate) = :year";
        }

        // Total Quotes
        $sqlTotal = "SELECT COUNT(*) as cnt FROM tblquotations WHERE 1=1" . $yearCondition;
        $stmtTotal = $this->conn->prepare($sqlTotal);
        if (!empty($year)) $stmtTotal->bindParam(':year', $year);
        $stmtTotal->execute();
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['cnt'];

        // Awaiting Approval (Pending)
        $sqlPending = "SELECT COUNT(*) as cnt FROM tblquotations WHERE qstatus='Pending'" . $yearCondition;
        $stmtPending = $this->conn->prepare($sqlPending);
        if (!empty($year)) $stmtPending->bindParam(':year', $year);
        $stmtPending->execute();
        $pending = $stmtPending->fetch(PDO::FETCH_ASSOC)['cnt'];

        // Approved this month (if year is specified, only look at that year's current month? Or just approved in general for that year. Let's do approved in the given year, or if no year, this month.)
        if (!empty($year)) {
            $sqlApproved = "SELECT COUNT(*) as cnt FROM tblquotations WHERE qstatus='Approved' OR qstatus='Converted'" . $yearCondition;
        } else {
            $sqlApproved = "SELECT COUNT(*) as cnt FROM tblquotations WHERE (qstatus='Approved' OR qstatus='Converted') AND MONTH(qdate) = MONTH(CURRENT_DATE) AND YEAR(qdate) = YEAR(CURRENT_DATE)";
        }
        $stmtApproved = $this->conn->prepare($sqlApproved);
        if (!empty($year)) $stmtApproved->bindParam(':year', $year);
        $stmtApproved->execute();
        $approved = $stmtApproved->fetch(PDO::FETCH_ASSOC)['cnt'];

        // Total Approved for Conversion Rate
        $sqlTotalApproved = "SELECT COUNT(*) as cnt FROM tblquotations WHERE (qstatus='Approved' OR qstatus='Converted')" . $yearCondition;
        $stmtTotalApproved = $this->conn->prepare($sqlTotalApproved);
        if (!empty($year)) $stmtTotalApproved->bindParam(':year', $year);
        $stmtTotalApproved->execute();
        $totalApproved = $stmtTotalApproved->fetch(PDO::FETCH_ASSOC)['cnt'];

        $conversionRate = $total > 0 ? round(($totalApproved / $total) * 100) : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'conversion' => $conversionRate . '%'
        ];
    }

    public function getQuotationByID($id) {
        $sql = "SELECT * FROM tblquotations WHERE qid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getQuotationProductsByID($id) {
        $sql = "SELECT * FROM tblqproducts WHERE qid = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
 public function addQuotation($userData) {
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


/* public function ConvertToJob($userData) {
     $qid=$userData['qid'];
     $po_no=$userData['po_no'];
     $qstatus="Converted";
    try {
            $query = "UPDATE tblquotations SET qstatus=:qstatus, po_no=:po_no WHERE qid=:qid";
            $stmtInsertJob = $this->conn->prepare($query);
            $stmtInsertJob->bindParam(':qid', $qid, PDO::PARAM_INT);
            $stmtInsertJob->bindParam(':qstatus', $qstatus);
            $stmtInsertJob->bindParam(':po_no', $po_no);
            // Execute the statement
            $stmtInsertJob->execute();
            // Check if user was inserted successfully
            if ($stmtInsertJob->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
    } catch(PDOException $e) {
        // Handle database error
        //echo "Database Error: " . $e->getMessage();
        return false;
    }
}*/

/*
public function ConvertToJob($userData) {
    $qid = $userData['qid'];
    $po_no = $userData['po_no'];
    $qstatus = "Converted";
    
    try {
        // Begin a transaction
        $this->conn->beginTransaction();

        // Step 1: Update the tblquotations table
        $updateQuery = "UPDATE tblquotations SET qstatus = :qstatus, po_no = :po_no WHERE qid = :qid";
        $stmtUpdate = $this->conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':qstatus', $qstatus);
        $stmtUpdate->bindParam(':po_no', $po_no);
        $stmtUpdate->execute();

        // Step 2: Insert into tbljobs table
        $insertQuery = "
            INSERT INTO tbljobs (
                qid,
                orderdate,
                company_name,
                contact_person,
                contact_no,
                address,
                oqty,
                ovalue,
                vat,
                netvalue,
                order_status,
                createdby,
                createdon,
                po_no
            )
            SELECT 
                qid,
                NOW(),
                company_name,
                contact_person,
                contact_no,
                address,
                tqty,
                qvalue,
                qvalue * 0.15,
                qvalue + (qvalue * 0.15),
                'Pending',
                'admin',
                NOW(),
                po_no
            FROM tblquotations
            WHERE qid = :qid";
        
        $stmtInsert = $this->conn->prepare($insertQuery);
        $stmtInsert->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmtInsert->execute();

        // Commit the transaction
        $this->conn->commit();

        // Check if the insert was successful
        if ($stmtInsert->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Rollback the transaction in case of an error
        $this->conn->rollBack();
        // Handle database error
        // echo "Database Error: " . $e->getMessage();
        return false;
    }
}
*/

public function ConvertToJob($userData) {
    $qid = $userData['qid'];
    $po_no = $userData['po_no'];
    $qstatus = "Converted";
    $cb = $userData['credit_bill'];
    
    try {
        // Begin a transaction
        $this->conn->beginTransaction();

        // Step 1: Update the tblquotations table
        $updateQuery = "UPDATE tblquotations SET qstatus = :qstatus, po_no = :po_no WHERE qid = :qid";
        $stmtUpdate = $this->conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':qstatus', $qstatus);
        $stmtUpdate->bindParam(':po_no', $po_no);
        $stmtUpdate->execute();

                // Generate the jobno
        $currentYear = date('y'); // Get the last two digits of the current year (e.g., '25' for 2025)

        // Get the latest jobno for the current year
        $sqlGetLatestJobno = "SELECT jobno FROM tbljobs WHERE jobno LIKE '%/$currentYear' ORDER BY jobid DESC LIMIT 1";
        $stmtGetLatestJobno = $this->conn->prepare($sqlGetLatestJobno);
        $stmtGetLatestJobno->execute();
        $latestJobno = $stmtGetLatestJobno->fetchColumn();

        // Generate the new jobno
        if ($latestJobno) {
            $latestSequence = (int)explode('/', $latestJobno)[0];
            $newSequence = str_pad($latestSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0001'; // Start with 0001 for the new year
        }
        $newJobno = $newSequence . '/' . $currentYear;

        // Step 2: Insert into tbljobs table
        $insertQuery = "
            INSERT INTO tbljobs (
                qid,
                orderdate,
                company_name,
                contact_person,
                contact_no,
                address,
                oqty,
                ovalue,
                vat,
                netvalue,
                balance,     
                credit_bill,
                order_status,
                createdby,
                createdon,
                po_no,
                jobno
            )
            SELECT 
                qid,
                NOW(),
                company_name,
                contact_person,
                contact_no,
                address,
                tqty,
                qvalue,
                qvalue * 0.15,
                qvalue + (qvalue * 0.15),
                qvalue + (qvalue * 0.15),
                :credit_bill,
                'Pending',
                'admin',
                NOW(),
                po_no,
                :jobno
            FROM tblquotations
            WHERE qid = :qid";
        
        $stmtInsert = $this->conn->prepare($insertQuery);
        $stmtInsert->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmtInsert->bindParam(':credit_bill', $cb);
        $stmtInsert->bindParam(':jobno', $newJobno);  // Bind jobno
        $stmtInsert->execute();

        // Step 3: Get the last inserted jobid
        $lastJobId = $this->conn->lastInsertId();

        // Step 4: Insert into tbloproducts table
        $insertProductsQuery = "
            INSERT INTO tbloproducts (jobid, product_name, qty, unitcost, total)
            SELECT 
                :jobid,
                product_name,
                qty,
                unitcost,
                total
            FROM tblqproducts
            WHERE qid = :qid";
        
        $stmtInsertProducts = $this->conn->prepare($insertProductsQuery);
        $stmtInsertProducts->bindParam(':jobid', $lastJobId, PDO::PARAM_INT);
        $stmtInsertProducts->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmtInsertProducts->execute();

        // Commit the transaction
        $this->conn->commit();

        // Check if the insert was successful
        if ($stmtInsert->rowCount() > 0 && $stmtInsertProducts->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        // Rollback the transaction in case of an error
        $this->conn->rollBack();
        // Handle database error
        // echo "Database Error: " . $e->getMessage();
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
        $qstatus='Cancelled';
        // Prepare SQL statement to delete from tblqproducts
        $sqlDeleteProducts = "UPDATE tblquotations SET qstatus = :qstatus WHERE qid = :id";
        $stmtDeleteProducts = $this->conn->prepare($sqlDeleteProducts);
        // Bind parameter
        $stmtDeleteProducts->bindParam(':qstatus', $qstatus);
        $stmtDeleteProducts->bindParam(':id', $QId);
        // Execute the statement
        $stmtDeleteProducts->execute();

        /*
        // Prepare SQL statement to delete from tblquotations
        $sqlDeleteQuotation = "DELETE FROM tblquotations WHERE qid = :id";
        $stmtDeleteQuotation = $this->conn->prepare($sqlDeleteQuotation);
        // Bind parameter
        $stmtDeleteQuotation->bindParam(':id', $QId);
        // Execute the statement
        $stmtDeleteQuotation->execute();
        */

        // Check if both deletions were successful
        //if ($stmtDeleteProducts->rowCount() > 0 && $stmtDeleteQuotation->rowCount() > 0) {
        if ($stmtDeleteProducts->rowCount() > 0) {
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
/*public function popdetails($qid){
      $query = "SELECT * FROM tblqproducts WHERE qid = :qid";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':qid', $qid, PDO::PARAM_INT);
    $stmt->execute();
    $quotation = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    $result="No Products Found";
    if ($quotation) {
        $result = "<table><tr><th>QID</th><th>Product Name</th><th>Qty</th><th>Total</th></tr>";
        foreach ($quotation as $row) {
        $result .= "<tr>";
        $result .= "<td>Quotation ID:</td><td> " . $row['qid'] . "</td>";
        $result .=  "<td>Product Name:</td><td>" . $row['product_name'] . "</td>";
        $result .= "<td>Qty:</td><td>" . $row['qty'] . "</td>";
        $result .= "<td>Total Amount:</td><td>$" . $row['total'] . "</td>";
        $result .= "</tr></table>";
        }
    }
    return $result;
}*/

    // Add insert, update, and delete methods here
}
?>
