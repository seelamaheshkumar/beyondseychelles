<?php
require_once '../auth.php';
checkLogin();
date_default_timezone_set('Indian/Mahe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Job · BEYOND design</title>
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
<div class="pagetitle row">
    <div class="col-md-9">
    <h1>Update Job</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Update Job</li>
        </ol>
    </nav>
</div>
<div class="col-md-3" style="text-align:right">
    <a href='../newjob' class='btn btn-info'>
        <i class="ri-user-add-fill"></i> Back to Jobs
    </a>
</div>
</div>
    <section class="section dashboard">
 
<div class="row">
    <div class="col-lg-12">
          <div class="card">
          <!--  <button type="button" class="btn ripple btn-warning ms-auto" data-bs-target="#addModal" data-bs-toggle="modal" style="float:right;">
                            <span><i class="fa fa-plus"></i></span> New User </button>-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <br>
                            <table class="table">
                                <tr>
                                    <td class="align-middle">Job ID</td>
                                    <td class="align-middle"><input type="text" name="jobid" id="jobid" class="form-control" readonly /></td>
                                    <td class="align-middle">Job Date</td>
                                    <td class="align-middle"><input type="text" name="orderdate" id="orderdate" class="form-control" readonly /></td>
                                    <td class="align-middle">Job Status</td>
                                    <td class="align-middle"><input type="text" name="order_status" id="order_status" class="form-control" readonly /></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Company Name</td>
                                    <td class="align-middle"><input type="text" name="company_name" id="company_name" class="form-control" readonly /></td>
                                    <td class="align-middle">Contact Person</td>
                                    <td class="align-middle"><input type="text" name="contact_person" id="contact_person" class="form-control" readonly /></td>
                                    <td class="align-middle">Contact No</td>
                                    <td class="align-middle"><input type="text" name="contact_no" id="contact_no" class="form-control" readonly /></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Address</td>
                                    <td class="align-middle"><input type="text" name="address" id="address" class="form-control" readonly></td>
                                    <td class="align-middle">Total Qty</td>
                                    <td class="align-middle"><input type="text" name="oqty" id="oqty" class="form-control text-end" readonly /></td>
                                    <td class="align-middle">Order Value</td>
                                    <td class="align-middle"><input type="text" name="ovalue" id="ovalue" class="form-control text-end" readonly /></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Discount</td>
                                    <td class="align-middle"><input type="text" name="discount" id="discount" class="form-control text-end" readonly></td>
                                    <td class="align-middle">VAT</td>
                                    <td class="align-middle"><input type="text" name="vat" id="vat" class="form-control text-end" readonly /></td>
                                    <td class="align-middle">Net Value</td>
                                    <td class="align-middle"><input type="text" name="netvalue" id="netvalue" class="form-control text-end" readonly /></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Credit Bill</td>
                                    <td class="align-middle"><input type="text" name="credit_bill" id="credit_bill" class="form-control text-end" readonly></td>
                                    <td class="align-middle">Paid Amount</td>
                                    <td class="align-middle"><input type="text" name="paid_amount" id="paid_amount" class="form-control text-end" readonly /></td>
                                    <td class="align-middle">Balance</td>
                                    <td class="align-middle"><input type="text" name="balance" id="balance" class="form-control text-end" readonly /></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Quotation No</td>
                                    <td class="align-middle"><input type="text" name="qno" id="qno" class="form-control text-end" readonly></td>
                                    <td class="align-middle">PO No</td>
                                    <td class="align-middle"><input type="text" name="po_no" id="po_no" class="form-control text-end" readonly /></td>
                                    <td class="align-middle"><button type="button" id="updateJobBtn" class="btn ripple btn-success ms-auto" data-bs-target="#editJobModal" data-bs-toggle="modal" style="float:right;" >
                            <span><i class="fa fa-plus"></i></span> Update Job </button></td>
                                    <td class="align-middle"></td>
                                </tr>                                
                            </table>
                            <table class="table">
                                <tr>
                                    <td><h4>Job Details</h4></td>
                                    <td style="text-align:right"><button type="button" id="newProductButton" class="btn ripple btn-warning ms-auto" data-bs-target="#addModal" data-bs-toggle="modal" style="float:right;" >
                            <span><i class="fa fa-plus"></i></span> New Product </button></td>
                                </tr>
                            </table>
                            
                            <div class="table-responsive" id="joblist">
                                <h3 class="text-center text-success" style="margin-top: 150px">Loading...</h3>
                            </div>
                            <br>
                            <h4>Payment Details</h4>
                            <div class="table-responsive" id="paylist">
                                <h3 class="text-center text-success" style="margin-top: 150px">Loading...</h3>
                            </div>
                        </div>
                    </div>
          </div>
      </div>
    </section>
  </main>
</div>

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
                    <input type="text" class="form-control" id="total" name="total" readonly required>
                </div>

            </div>
            <input type="hidden" value="0" id="jobid1" name="jobid1" required>
            <input type="hidden" value="0" id="vat1" name="vat1" required>
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
                                <input type="hidden" id="pid2" name="pid2" value="" />
                                <input type="hidden" id="jobid2" name="jobid2" value="" />
                                <input type="hidden" value="0" id="vat2" name="vat2" required>
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
              

 <!-- Update Header Modal -->
   <div class="modal fade" id="editJobModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Job</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3" name="frmujob" id="frmujob" method="post">
                            <div class="col-md-12">
                                <label>Company Name</label>
                              <input type="text" id="cmpname" name="cmpname" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label>Contact Person</label>
                              <input type="text" id="cmprep" name="cmprep" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label>Contact No	</label>
                              <input type="text" id="cmpcontact" name="cmpcontact" class="form-control" required />
                            </div>
                            <div class="col-md-12">
                                <label>Address	</label>
                              <input type="text" id="cmpaddress" name="cmpaddress" class="form-control" required />
                            </div>                            
                            <div class="col-md-12">
                                <label>Credit Bill</label>
                              <select id="opcreditbill" name="opcreditbill" class="form-control" required>
                                  <option value="No">No</option>
                                  <option value="Yes">Yes</option>
                              </select>
                            </div>                            
                             <div class="col-md-6" style="text-align:right">
                                <input type="hidden" id="jobid3" name="jobid3" value="" />
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div> 
                            <div class="col-md-6" style="text-align:right">
                                <button type="submit" class="btn btn-success">Update Job</button>
                            </div>
                        </form>
                    </div>

                  </div>
                </div>
              </div>                   

 <!-- Update Payment Modal -->
   <div class="modal fade" id="editPayModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Payment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3" name="frmUpdatePay" id="frmUpdatePay" method="post">
                            <div class="col-md-6">
                                <label>Cash</label>
                              <input type="number" step="0.01" id="pcash" name="pcash" class="form-control pay-input" required />
                            </div>
                            <div class="col-md-6">
                                <label>Card</label>
                              <input type="number" step="0.01" id="pcard" name="pcard" class="form-control pay-input" required />
                            </div>
                            <div class="col-md-6">
                                <label>Cheque</label>
                              <input type="number" step="0.01" id="pcheque" name="pcheque" class="form-control pay-input" required />
                            </div>
                            <div class="col-md-6">
                                <label>Wallet</label>
                              <input type="number" step="0.01" id="pwallet" name="pwallet" class="form-control pay-input" required />
                            </div>
                            <div class="col-md-12">
                                <label>Remarks</label>
                              <input type="text" id="premarks" name="premarks" class="form-control" />
                            </div>
                            <div class="col-md-12">
                                <label>Total Amount</label>
                                <input type="text" id="ptotal" class="form-control text-end font-weight-bold" readonly />
                            </div>
                             <div class="col-md-6" style="text-align:right">
                                <input type="hidden" id="payid" name="payid" value="" />
                                <input type="hidden" id="jobid4" name="jobid4" value="" />
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div> 
                            <div class="col-md-6" style="text-align:right">
                                <button type="submit" class="btn btn-success">Update Payment</button>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("jobs", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=4"></script>
</body>
</html>

