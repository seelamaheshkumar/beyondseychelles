<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New Quotation · BEYOND design</title>
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
</style>
</head>
<body>

<div id="bdMain" class="bd-main">
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow">Workflow / Quotations</div>
      <h1>New Quotation</h1>
    </div>
    <a href="../quotations" class="btn btn-bd-outline btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Back to Quotations
    </a>
  </div>

  <form id="frmquote" action="" method="POST">
    <div class="bd-card mb-4">
      <div class="bd-card-head border-bottom">
        <h2 style="font-size:15px; margin:0;">Client Details</h2>
      </div>
      <div class="p-3">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Company Name</label>
            <input type="text" name="company_name" class="form-control form-control-sm" required />
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Contact Person</label>
            <input type="text" name="contact_person" class="form-control form-control-sm" required />
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Contact No</label>
            <input type="text" name="contact_no" class="form-control form-control-sm" required />
          </div>
          <div class="col-md-12">
            <label class="form-label small fw-semibold">Remarks</label>
            <textarea name="address" rows="2" class="form-control form-control-sm">-</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="bd-card">
      <div class="bd-card-head border-bottom">
        <h2 style="font-size:15px; margin:0;">Line Items</h2>
        <button type="button" class="btn btn-bd-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
          <i class="bi bi-plus-lg me-1"></i>Add Product
        </button>
      </div>
      <div class="table-responsive p-3" id="showUsers">
          <h3 class="text-center text-success my-5">Loading...</h3>
      </div>
      
      <div class="p-3 border-top bg-light">
        <div class="row justify-content-end">
          <div class="col-md-5">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="fw-semibold small">Total Quantity</span>
              <input type="text" name="tqty" id="tqty" class="form-control form-control-sm w-50 text-end" required readonly />
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="fw-semibold small">Total Value (₹)</span>
              <input type="text" name="qvalue" id="qvalue" class="form-control form-control-sm w-50 text-end fw-bold text-success" required readonly />
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" id="btnSaveQuotation" class="btn btn-bd-primary"><i class="bi bi-check-circle me-1"></i>Save Quotation</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

</main>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="add-user-form">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Product Name</label>
            <input type="text" class="form-control form-control-sm" id="product_name" name="product_name" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Quantity</label>
            <input type="number" class="form-control form-control-sm" id="qty" name="qty" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Unit Cost</label>
            <input type="number" step="0.01" class="form-control form-control-sm" id="unitcost" name="unitcost" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Amount</label>
            <input type="number" step="0.01" class="form-control form-control-sm" id="total" name="total" required readonly>
          </div>
        </div>
        <input type="hidden" value="0" id="qid" name="qid" required>
        <div class="modal-footer">
          <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bd-primary btn-sm">Add Product</button>
        </div>
      </form>
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
