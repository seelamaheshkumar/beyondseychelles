<?php
require_once '../auth.php';
checkLogin();
date_default_timezone_set('Indian/Mahe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New Job · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
  .bd-table.dataTable { border-collapse: collapse !important; }
</style>
</head>
<body>
<div id="bdMain" class="bd-main">
<main class="bd-content" id="bdContent">
<main id="main" class="main">
<div class="pagetitle row">
    <div class="col-md-9">
    <h1>Job</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">New Job</li>
        </ol>
    </nav>
</div>
<div class="col-md-3" style="text-align:right">
    <a href='../pjobs' class='btn btn-bd-outline btn-sm'>
        <i class="bi bi-arrow-left"></i> Back to Jobs
    </a>
</div>
</div>
    <section class="section dashboard">
 
<div class="row">
    <div class="col-lg-12">
          <div class="card">
          <!--  <button type="button" class="btn ripple btn-warning ms-auto" data-bs-target="#addModal" data-bs-toggle="modal" style="float:right;">
                            <span><i class="fa fa-plus"></i></span> New User </button>-->
                    <form id="frmjob" action="" method="POST">
                    <div class="card-body">
                        <div class="table-responsive">
                            <br>
                            <table class="table">
                                <tr>
                                    <td>Company Name </td>
                                    <td><input type="text" name="company_name" class="form-control" required /></td>
                                    <td>&nbsp;&nbsp; </td>
                                    <td>Contact Person </td>
                                    <td><input type="text" name="contact_person" class="form-control" required /></td>
                                </tr>
                                <tr>
                                    <td>Contact No </td>
                                    <td><input type="text" name="contact_no" class="form-control" required /></td>
                                    <td>&nbsp;&nbsp; </td>
                                    <td>Remarks </td>
                                    <td><textarea name="address" rows=3 cols=35 class="form-control">-</textarea></td>
                                </tr>
                            </table>
                            <table class="table">
                                <tr>
                                    <td>
                                       <h4>List of Products</h4>
                                    </td>
                                    <td align="right">
                                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus"></i> Add Product </button>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="table-responsive" id="showUsers">
                                <h3 class="text-center text-success" style="margin-top: 150px">Loading...</h3>
                                 
                       
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                     <td>VAT Required : <input type="checkbox" name="chkvat" id="chkvat" checked /> </td>
                                    <td>Total Qty </td>
                                    <td><input type="text" name="tqty" id="tqty" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                                    <td>&nbsp;&nbsp; </td>
                                    <td>Total Value </td>
                                    <td><input type="text" name="qvalue" id="qvalue" class="form-control" required  style="float:right; text-align:right;" readonly /></td>
                                </tr>                                
                            </table>
                        </div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <td>Discount</td><td><input type="text" name="discount" id="discount" value="0" class="form-control" required style="float:right; text-align:right;" /></td>
                            </tr>
                            <tr>
                                <td>Gross</td><td><input type="text" name="grossAmt" id="grossAmt" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                            </tr>                            
                            <tr>
                                <td>VAT @ 15%</td><td><input type="text" name="vat" id="vat" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                            </tr>  
                            <tr>
                                <td>Net Amount</td><td><input type="text" name="netvalue" id="netvalue" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                            </tr>                              
                        </table>
                   </div>
                   <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <td>Cash</td><td><input type="text" name="cash" id="cash" value="0"  class="form-control" required style="float:right; text-align:right;" /></td>
                            </tr>
                            <tr>
                                <td>Card</td><td><input type="text" name="card" id="card" value="0"  class="form-control" required style="float:right; text-align:right;" /></td>
                            </tr>                            
                            <tr>
                                <td>Cheque</td><td><input type="text" name="cheque" id="cheque" value="0"  class="form-control" required style="float:right; text-align:right;" /></td>
                            </tr>  
                            <tr>
                                <td>Bank Transfer</td><td><input type="text" name="wallet" id="wallet" value="0" class="form-control" required style="float:right; text-align:right;" /></td>
                            </tr>                              
                        </table>
                    </div>    
                <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <td>Paid Amount</td><td><input type="text" name="paid_amount" id="paid_amount" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                            </tr>
                            <tr>
                                <td>Balance</td><td><input type="text" name="balance" id="balance" class="form-control" required style="float:right; text-align:right;" readonly /></td>
                            </tr>                            
                            <tr>
                                <td>Credit Bill</td><td>
                                    <select name="credit_bill" class="form-control" required>
                                        <option value="No" selected>No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>                                        
                               Payment Remarks</td>
                               <td><textarea name="remarks" class="form-control" rows="3" cols="35">-</textarea></td>
                            </tr>  
                            <tr>
                                <td>                                        
                                <input type="hidden" name="createdby" value="admin" />
                                <input type="hidden" name="qid" id="qid" value="0" />
                                </td><td><button type="submit" id="btnSaveQuotation" class="btn btn-success"><i class="bi bi-save"></i> Save Job </button></td>
                            </tr>                            
                        </table>
                       
                            <br><br>
                   </div>                    
                   </div>
                </form>
          </div>
      </div>
    </section>
  </main>

<!-- Add modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <form id="add-user-form">
            <div class="modal-body">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="text" class="form-control" id="qty" name="qty" required>
                </div>
                                <div class="mb-3">
                    <label for="unitcost" class="form-label">Unit Cost</label>
                    <input type="text" class="form-control" id="unitcost" name="unitcost" required>
                </div>    
                <!-- Full Name -->
                <div class="mb-3">
                    <label for="fullname" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="total" name="total" required>
                </div>

            </div>
            <input type="hidden" value="0" id="qid" name="qid" required>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
</div>


 <!-- Update Product Modal -->
   <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3" name="frmustatus" id="frmustatus" method="post">
                            <div class="col-md-12">
                                <label>Product Name</label>
                              <input type="text" id="pname" name="pname" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label>Qty</label>
                              <input type="text" id="pqty" name="pqty" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label>Unit Cost</label>
                              <input type="text" id="ucost" name="ucost" class="form-control" required />
                            </div>
                            <div class="col-md-12">
                                <label>Total</label>
                              <input type="text" id="tot1" name="tot1" class="form-control" required readonly />
                            </div>                            
                             <div class="col-md-6" style="text-align:right">
                                <input type="hidden" id="pid1" name="pid1" value="" />
                                <input type="hidden" id="jid2" name="jid2" value="" />
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div> 
                            <div class="col-md-6" style="text-align:right">
                                <button type="submit" class="btn btn-success">Update Product</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                      
                    </div>
                  </div>
                </div>
              </div>     
              
</main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("jobs", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=2"></script>
</body>
</html>


