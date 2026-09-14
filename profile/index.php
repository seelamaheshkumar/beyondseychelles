<?php
require_once '../auth.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

<div id="bdMain" class="bd-main">
<main class="bd-content">

  <div class="page-head">
    <div>
      <div class="eyebrow">Settings</div>
      <h1>My Profile</h1>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-6 col-lg-5">
      <div class="bd-card h-100">
        <div class="bd-card-head">
          <h2>Profile Details</h2>
        </div>
        <div class="bd-card-body">
            <div class="d-flex align-items-center mb-4 gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-4" style="width: 72px; height: 72px; background: var(--bd-ink);">
                    <?= strtoupper(substr($_SESSION['user_fname'], 0, 2)) ?>
                </div>
                <div>
                    <h3 class="h5 mb-1 fw-bold"><?= htmlspecialchars($_SESSION['user_fname']) ?></h3>
                    <div class="text-muted"><?= htmlspecialchars($_SESSION['user_role']) ?></div>
                </div>
            </div>
            
            <table class="table bd-table mb-0">
                <tbody>
                    <tr>
                        <th class="text-muted" style="width: 140px; border-bottom: none;">Username / Email</th>
                        <td class="fw-semibold" style="border-bottom: none;"><?= htmlspecialchars($_SESSION['Username']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="border-bottom: none;">Role</th>
                        <td class="fw-semibold" style="border-bottom: none;"><span class="badge bg-light text-dark border"><?= htmlspecialchars($_SESSION['user_role']) ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-7">
      <div class="bd-card h-100">
        <div class="bd-card-head">
          <h2>Change Password</h2>
        </div>
        <div class="bd-card-body">
            <form id="change-password-form">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-bd-primary">Update Password</button>
            </form>
        </div>
      </div>
    </div>
  </div>

</main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
<script src="../assets/js/shell.js?v=2"></script>
<script>
  BD.mount("users", { user: { name: "<?php echo isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : 'User'; ?>", role: "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Admin'; ?>" } });

  $(document).ready(function() {
      $('#change-password-form').submit(function(e) {
          e.preventDefault();
          var formData = $(this).serialize();
          
          if($('input[name="new_password"]').val() !== $('input[name="confirm_password"]').val()) {
              Swal.fire({ icon: 'error', title: 'Error', text: 'New passwords do not match!' });
              return;
          }
          
          $.ajax({
              url: 'profileController.php',
              type: 'POST',
              data: formData + '&action=change_password',
              dataType: 'json',
              success: function(response) {
                  if (response.status === 'success') {
                      Swal.fire({
                          icon: 'success',
                          title: 'Password Updated',
                          text: response.message
                      });
                      $('#change-password-form')[0].reset();
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: response.message
                      });
                  }
              },
              error: function() {
                  Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'An unexpected error occurred. Please try again.'
                  });
              }
          });
      });
  });
</script>
</body>
</html>
