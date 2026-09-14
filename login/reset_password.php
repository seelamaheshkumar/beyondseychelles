<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
<style>
  body { background-color: var(--bd-bg-subtle); display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
  .reset-box { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 420px; }
</style>
</head>
<body>

<div class="reset-box">
  <div class="text-center mb-4">
    <img src="../assets/img/logo.png" alt="BEYOND design" style="height:34px;">
  </div>
  <h2 class="h4 fw-bold mb-3 text-center">Set New Password</h2>
  
  <?php
    require_once '../config/database.php';
    $token = $_GET['token'] ?? '';
    $validToken = false;
    
    if (!empty($token)) {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id FROM tblusers WHERE reset_token = :token AND reset_token_expires > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        if ($stmt->fetch()) {
            $validToken = true;
        }
    }
  ?>

  <?php if ($validToken): ?>
  <form id="resetPasswordForm">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="action" value="reset_password">
    
    <div class="mb-3">
      <label class="form-label fw-semibold" style="font-size:13px;">New Password</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold" style="font-size:13px;">Confirm New Password</label>
      <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-bd-primary w-100" id="btnReset">Reset Password</button>
  </form>
  <?php else: ?>
    <div class="alert alert-danger text-center">
      The password reset link is invalid or has expired.
    </div>
    <div class="text-center">
      <a href="index.php" class="btn btn-bd-outline">Return to Sign In</a>
    </div>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
<script>
$(document).ready(function() {
    $('#resetPasswordForm').submit(function(e) {
        e.preventDefault();
        var pass = $('#password').val();
        var confirm = $('#confirm_password').val();
        
        if (pass !== confirm) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match!' });
            return;
        }
        
        $('#btnReset').prop('disabled', true).text('Updating...');
        
        $.ajax({
            type: 'POST',
            url: 'resetPasswordController.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success', title: 'Success!', text: res.message
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    $('#btnReset').prop('disabled', false).text('Reset Password');
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function() {
                $('#btnReset').prop('disabled', false).text('Reset Password');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            }
        });
    });
});
</script>
</body>
</html>
