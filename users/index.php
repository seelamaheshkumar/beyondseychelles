<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Users · BEYOND design</title>
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
      <div class="eyebrow">Admin</div>
      <h1>Users</h1>
    </div>
    <button class="btn btn-bd-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-person-plus me-1"></i>Add User
    </button>
  </div>

  <div class="bd-card">
    <div class="bd-card-head">
      <div class="filter-bar">
      </div>
    </div>
    <div class="table-responsive p-3" id="showUsers">
      <h3 class="text-center text-success my-5">Loading...</h3>
    </div>
  </div>

</main>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" style="font-size:17px;">Add user</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="add-user-form">
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label small fw-semibold">Username</label>
            <input type="text" class="form-control form-control-sm" name="username" required>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Password</label>
            <input type="password" class="form-control form-control-sm" name="password" required>
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold">Full name</label>
            <input type="text" class="form-control form-control-sm" name="fullname" required>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" class="form-control form-control-sm" name="email" required>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold">Role</label>
            <select class="form-select form-select-sm" name="role" required>
              <option value="Administrator">Administrator</option>
              <option value="Billing">Billing</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-bd-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-bd-primary btn-sm">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" id="editModalContent">
      <!-- Edit modal content will be populated dynamically via AJAX -->
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
<script src="../assets/js/shell.js"></script>
<script>
  BD.mount("users", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });
</script>
<script src="scripts.js?v=2"></script>
</body>
</html>
