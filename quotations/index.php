<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Quotations · BEYOND design</title>
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
</style>
</head>
<body>

<div id="bdMain" class="bd-main">
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow">Workflow</div>
      <h1>Quotations</h1>
    </div>
    <a href="../newquotation/index.php" class="btn btn-bd-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>New Quotation
    </a>
  </div>

  <!-- KPI strip -->
  <div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
      <div class="bd-card kpi-card"><div class="kpi-label">Total quotes</div><div class="kpi-value">86</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bd-card kpi-card"><div class="kpi-label">Awaiting approval</div><div class="kpi-value" style="color:var(--bd-warning);">14</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bd-card kpi-card"><div class="kpi-label">Approved this month</div><div class="kpi-value" style="color:var(--bd-success);">31</div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bd-card kpi-card"><div class="kpi-label">Conversion rate</div><div class="kpi-value">72%</div></div>
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
      <button class="btn btn-bd-outline btn-sm"><i class="bi bi-download me-1"></i>Export CSV</button>
    </div>
    <div class="table-responsive p-3" id="showUsers">
        <h3 class="text-center text-success my-5">Loading...</h3>
    </div>
  </div>

</main>
</div>

<!-- Convert Quotation to Job Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Convert Quotation to Job</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" name="frmustatus" id="frmustatus" method="post">
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Quotation ID</label>
            <input type="text" id="qid" name="qid" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Company Name</label>
            <input type="text" id="company_name" name="company_name" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Contact Name</label>
            <input type="text" id="contact_person" name="contact_person" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Qty</label>
            <input type="text" id="tqty" name="tqty" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Value</label>
            <input type="text" id="qvalue" name="qvalue" class="form-control form-control-sm fw-bold" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Credit Bill</label>
            <select name="credit_bill" id="credit_bill" class="form-select form-select-sm" required>
              <option value="No" selected>No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">PO No</label>
            <input type="text" id="po_no" name="po_no" class="form-control form-control-sm" required>
          </div>
          <div class="col-12 mt-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-bd-primary btn-sm">Convert To Job</button>
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
  BD.mount("quotations", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js"></script>
</body>
</html>
