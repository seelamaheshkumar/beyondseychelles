<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div id="bdMain" class="bd-main">
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow"><?= date('l, d F') ?></div>
      <h1>Business Overview</h1>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-bd-outline btn-sm"><i class="bi bi-download me-1"></i>Export</button>
    </div>
  </div>

  <div class="bd-card mb-4">
    <div class="bd-card-head flex-wrap gap-2 p-3">
      <form name="frmsearch" id="frmsearch" method="post" class="d-flex align-items-center gap-3 m-0">
        <div class="d-flex align-items-center gap-2">
          <span class="text-muted fw-medium small">From</span>
          <input type="date" class="form-control form-control-sm" name="fdate" id="fdate" required value="<?php echo date('Y-m-d'); ?>" style="width: 140px;">
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="text-muted fw-medium small">To</span>
          <input type="date" class="form-control form-control-sm" name="tdate" id="tdate" required value="<?php echo date('Y-m-d'); ?>" style="width: 140px;">
        </div>
        <button type="submit" class="btn btn-bd-primary btn-sm px-3"><i class="bi bi-funnel"></i> Filter</button>
      </form>
    </div>
  </div>

  <!-- First Row: Jobs KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">New Jobs</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #f1f3ff; color: #3E6FD9;">
            <i class="bi bi-cart3 fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold" id="newjobscount" style="font-size: 1.75rem; color: #1B2436;">0</div>
            <div class="fw-semibold" style="font-size: 0.8rem; color: #1E9E6B;" id="newjobvalue">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Pending Jobs</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e7f7f0; color: #1E9E6B;">
            <i class="bi bi-house-door fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold" id="pendingcount" style="font-size: 1.75rem; color: #1B2436;">0</div>
            <div class="fw-semibold" style="font-size: 0.8rem; color: #1E9E6B;" id="pendingvalue">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Ready Jobs</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fff1e2; color: #F58220;">
            <i class="bi bi-check-square fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="readycount" style="font-size: 1.75rem; color: #1B2436;">0</div>
            <div class="fw-semibold" style="font-size: 0.8rem; color: #D64545;" id="readyvalue">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Delivered Jobs</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fff3dc; color: #1E9E6B;">
            <i class="bi bi-check-circle-fill fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="delvcount" style="font-size: 1.75rem; color: #1B2436;">0</div>
            <div class="fw-semibold" style="font-size: 0.8rem; color: #D64545;" id="delvvalue">0.00</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Second Row: Payment Methods -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Cash</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #f8f6ff; color: #5b4fe5;">
            <i class="bi bi-currency-dollar fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="cash" style="font-size: 1.5rem; color: #1B2436;">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Card</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e7f7f0; color: #1E9E6B;">
            <i class="bi bi-credit-card fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="card" style="font-size: 1.5rem; color: #1B2436;">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Cheque</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fff1e2; color: #F58220;">
            <i class="bi bi-bank fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="cheque" style="font-size: 1.5rem; color: #1B2436;">0.00</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm">
        <div class="kpi-label text-dark fw-semibold mb-3">Bank Transfer</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fdf2eb; color: #1E9E6B;">
            <i class="bi bi-building fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="wallet" style="font-size: 1.5rem; color: #1B2436;">0.00</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Third Row: Credit Jobs & Charts -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-3">
      <div class="bd-card h-100 p-3 shadow-sm" style="max-height: 150px;">
        <div class="kpi-label text-dark fw-semibold mb-3">Credit Jobs</div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #f1f3ff; color: #3E6FD9;">
            <i class="bi bi-cart3 fs-4"></i>
          </div>
          <div class="text-end">
            <div class="fw-bold num" id="creditcount" style="font-size: 1.75rem; color: #1B2436;">0</div>
            <div class="fw-semibold" style="font-size: 0.8rem; color: #1E9E6B;" id="creditvalue">0.00</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Charts -->
    <div class="col-12 col-xl-5">
      <div class="bd-card h-100 shadow-sm">
        <div class="bd-card-head py-3 px-4 border-bottom">
          <h2 class="m-0 fs-6 fw-bold">Sales Trend (Last 7 Days)</h2>
        </div>
        <div class="bd-card-body p-4">
          <canvas id="salesTrendChart" height="200"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-12 col-xl-4">
      <div class="bd-card h-100 shadow-sm">
        <div class="bd-card-head py-3 px-4 border-bottom">
          <h2 class="m-0 fs-6 fw-bold">Job Status Distribution</h2>
        </div>
        <div class="bd-card-body p-4 d-flex justify-content-center">
          <canvas id="statusChart" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

</main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("dashboard", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js"></script>
</body>
</html>
