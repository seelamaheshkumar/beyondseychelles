<?php
require_once '../auth.php';
checkLogin();
date_default_timezone_set('Indian/Mahe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Job · BEYOND design</title>
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
    <h1>View Job</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Job Details</li>
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
                                    <td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                </tr>                                
                            </table>
                            <table class="table">
                                <tr>
                                    <td><h4>Job Details</h4></td>
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

