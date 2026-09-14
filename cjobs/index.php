<?php
require_once '../auth.php';
checkLogin();
date_default_timezone_set('Indian/Mahe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Credit Bills · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

<style>
  .bd-table.dataTable { border-collapse: collapse !important; }
  .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--bd-border); border-radius: 6px; padding: 4px 8px; }
  .align-right { text-align: right; }
</style>
</head>
<body>

<div id="bdMain" class="bd-main">
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow">Accounts</div>
      <h1>Credit Bills</h1>
    </div>
  </div>

  <div class="bd-card">
    <div class="bd-card-head">
      <div class="filter-bar w-100 d-flex gap-3 align-items-center">
        <div class="d-flex align-items-center gap-2">
          <label class="form-label mb-0 small fw-semibold">Year</label>
          <select id="yearFilter" class="form-select form-select-sm" style="width:120px;">
              <option value="">All Years</option>
              <option value="2026" selected>2026</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
              <option value="2023">2023</option>
          </select>
        </div>
      </div>
    </div>
    <div class="table-responsive p-3" id="showUsers">
      <h3 class="text-center text-success my-5">Loading...</h3>
    </div>
  </div>

</main>
</div>

<!-- Add Pay Modal -->
<div class="modal fade" id="addPayModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3"  name="frmupay" id="frmupay" method="post">
              <div class="col-md-12">
                  <label class="form-label mb-1">Company Name</label>
                <input type="text" id="company_name" name="company_name" class="form-control" readonly>
              </div>
              <div class="col-md-12">
                  <label class="form-label mb-1">Contact Name</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" readonly>
              </div>                        
              <div class="col-md-12">
                  <label class="form-label mb-1">Net Amount</label>
                <input type="text" id="netvalue" name="netvalue" class="form-control align-right" readonly>
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Discount</label>
                <input type="text" id="disc" name="disc" class="form-control align-right" value="0"  pattern="^\d+(\.\d{0,2})?$" oninput="validateDecimalInput(this)" />
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Balance Amount</label>
                <input type="text" id="balance" class="form-control align-right" readonly>
              </div>

              <div class="col-md-6">
                  <label class="form-label mb-1">Cash</label>
                 <input type="text" id="cash" name="cash" class="form-control align-right" pattern="^\d+(\.\d{0,2})?$" oninput="validateDecimalInput(this)">
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Card</label>
                  <input type="text" id="card" name="card" class="form-control align-right"pattern="^\d+(\.\d{0,2})?$" oninput="validateDecimalInput(this)">
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Cheque</label>
                  <input type="text" id="cheque" name="cheque" class="form-control align-right" pattern="^\d+(\.\d{0,2})?$" oninput="validateDecimalInput(this)">
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Bank Transfer</label>
                  <input type="text" id="wallet" name="wallet" class="form-control align-right" pattern="^\d+(\.\d{0,2})?$" oninput="validateDecimalInput(this)">
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">Paid Amount</label>
                <input type="text" id="paidAmount" name="paidAmount" class="form-control align-right" readonly required>
              </div>
              <div class="col-md-6">
                  <label class="form-label mb-1">New Balance</label>
                <input type="text" id="newbalance" name="newbalance" class="form-control align-right" readonly required>
              </div>
               <div class="col-md-12">
                  <label class="form-label mb-1">Payment Remarks</label>
                <input type="text" id="remarks" name="remarks" class="form-control">
              </div>
                <div class="col-md-6" style="text-align:right">
                 <input type="hidden" id="jobId" name="jobId" value="" />
                <button type="submit" class="btn btn-secondary canpaybtn btn-cancel" data-bs-dismiss="modal">Close</button>
              </div>
              <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Update Payment</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" name="frmustatus" id="frmustatus" method="post">
              <div class="col-md-12">
                  <label class="form-label mb-1">Company Name</label>
                <input type="text" id="company_name1" name="company_name1" class="form-control" readonly>
              </div>
              <div class="col-md-12">
                  <label class="form-label mb-1">Contact Name</label>
                <input type="text" id="contact_person1" name="contact_person1" class="form-control" readonly>
              </div>                        
              <div class="col-md-12">
                  <label class="form-label mb-1">Status</label>
                <select name="order_status" id="order_status" class="form-select" required>
                    <option value="Pending">Pending</option>
                    <option value="Delivered">Delivered</option>
                </select>
              </div>                        
               <div class="col-md-6 text-end">
                  <input type="hidden" id="jbid" name="jbid" value="" />
                  <input type="hidden" id="balance2" name="balance2" />
                  <input type="hidden" id="cbill2" name="cbill2" />
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div> 
              <div class="col-md-6 text-end">
                  <button type="submit" class="btn btn-success">Update Status</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("credit-bills", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=4"></script>
</body>
</html>

