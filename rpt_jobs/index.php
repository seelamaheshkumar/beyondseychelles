<?php
require_once '../auth.php';
checkLogin();
date_default_timezone_set('Indian/Mahe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Jobs Report · BEYOND design</title>
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
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow">Reports</div>
      <h1>Jobs Report</h1>
    </div>
  </div>

  <div class="bd-card">
    <div class="bd-card-head">
      <div class="filter-bar w-100 d-flex gap-3 align-items-center">
        <div class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 small fw-semibold">Year</label>
            <select class="form-select form-select-sm" name="fyear" id="fyear" style="width: 120px;">
                <?php 
                $currentYear = date('Y');
                for($y = $currentYear; $y >= 2020; $y--) {
                    echo "<option value=\"$y\">$y</option>";
                }
                ?>
            </select>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 small fw-semibold">Status</label>
            <select class="form-select form-select-sm" id="statusFilter" style="width: 130px;">
                <option value="">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Ready">Ready</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
        <button type="button" id="btnSearch" class="btn btn-bd-primary btn-sm"><i class="bi bi-search me-1"></i>Search</button>
      </div>
    </div>
    <div class="table-responsive p-3" id="showUsers">
      <h3 class="text-center text-success my-5">Loading...</h3>
    </div>
  </div>

</main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- DataTables Export Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("reports", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=5"></script>
</body>
</html>
