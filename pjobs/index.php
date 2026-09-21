<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pending Jobs · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
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
      <div class="eyebrow">Workflow</div>
      <h1>Pending jobs</h1>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-bd-outline btn-sm"><i class="bi bi-download me-1"></i>Export</button>
      <a href="../newjob" class="btn btn-bd-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Job</a>
    </div>
  </div>

  <div class="bd-card">
    <div class="bd-card-head flex-wrap gap-2">
      <div class="filter-bar">
        <select id="yearFilter" class="form-select form-select-sm" style="width:150px;">
          <option value="">All Years</option>
          <option value="2026" selected>2026</option>
          <option value="2025">2025</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>
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
        <form class="row g-3" name="frmupay" id="frmupay" method="post">
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Company Name</label>
            <input type="text" id="company_name" name="company_name" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Contact Name</label>
            <input type="text" id="contact_person" name="contact_person" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Net Amount</label>
            <input type="text" id="netvalue" name="netvalue" class="form-control form-control-sm align-right fw-bold" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Balance Amount</label>
            <input type="text" id="balance" class="form-control form-control-sm align-right text-danger fw-bold" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Cash</label>
            <input type="number" step="0.01" id="cash" name="cash" class="form-control form-control-sm align-right">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Card</label>
            <input type="number" step="0.01" id="card" name="card" class="form-control form-control-sm align-right">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Cheque</label>
            <input type="number" step="0.01" id="cheque" name="cheque" class="form-control form-control-sm align-right">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Bank Transfer</label>
            <input type="number" step="0.01" id="wallet" name="wallet" class="form-control form-control-sm align-right">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Paid Amount</label>
            <input type="text" id="paidAmount" name="paidAmount" class="form-control form-control-sm align-right text-success fw-bold" readonly required>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">New Balance</label>
            <input type="text" id="newbalance" name="newbalance" class="form-control form-control-sm align-right fw-bold" readonly required>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Payment Remarks</label>
            <input type="text" id="remarks" name="remarks" class="form-control form-control-sm">
          </div>
          <input type="hidden" id="jobId" name="jobId" value="" />
          <input type="hidden" id="disc" name="disc" value="0" />
          <div class="col-12 mt-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-bd-primary btn-sm">Update Payment</button>
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
            <label class="form-label small fw-semibold">Company Name</label>
            <input type="text" id="company_name1" name="company_name1" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Contact Name</label>
            <input type="text" id="contact_person1" name="contact_person1" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Status</label>
            <select name="order_status" id="order_status" class="form-select form-select-sm" required>
              <option value="Pending">Pending</option>
              <option value="Ready">Ready</option>
              <option value="Delivered">Delivered</option>
            </select>
          </div>
          <input type="hidden" id="jbid" name="jbid" value="" />
          <input type="hidden" id="balance2" name="balance2" />
          <input type="hidden" id="cbill2" name="cbill2" />
          <div class="col-12 mt-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-bd-primary btn-sm">Update Status</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("pending", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=3"></script>
</body>
</html>
