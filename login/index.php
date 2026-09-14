<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign in · BEYOND design</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../assets/img/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
</head>
<body>

<div class="login-shell">
  <!-- Brand panel -->
  <div class="login-brand-panel">
    <div class="grid-overlay"></div>
    <div class="corner-marks">
      <span style="top:28px; left:28px; border-right:none; border-bottom:none;"></span>
      <span style="top:28px; right:28px; border-left:none; border-bottom:none;"></span>
      <span style="bottom:28px; left:28px; border-right:none; border-top:none;"></span>
      <span style="bottom:28px; right:28px; border-left:none; border-top:none;"></span>
    </div>

    <div style="position:relative; z-index:2;">
      <img src="../assets/img/logo.png" alt="BEYOND design" style="height:34px; filter: brightness(0) invert(1);">
    </div>

    <div style="position:relative; z-index:2;">
      <span class="cmyk-dots mb-3">
        <span></span><span></span><span></span><span></span>
      </span>
      <h1 class="font-display text-white" style="font-size:32px; max-width:420px; line-height:1.25;">
        Every brochure, card and banner — tracked from quote to delivery.
      </h1>
      <p class="text-white-50" style="max-width:380px; font-size:14.5px;">
        The internal billing and job console for Beyond Design's studio floor: quotations, print jobs, dispatch and credit accounts, in one place.
      </p>
    </div>

    <div style="position:relative; z-index:2;" class="d-flex align-items-center gap-4 text-white-50" style="font-size:12.5px;">
      <span><i class="bi bi-file-earmark-text me-1"></i>Quotations</span>
      <span><i class="bi bi-briefcase me-1"></i>Jobs</span>
      <span><i class="bi bi-receipt me-1"></i>Credit Bills</span>
    </div>
  </div>

  <!-- Form panel -->
  <div class="login-form-panel">
    <form id="loginForm" novalidate>
      <div class="mb-4 d-lg-none text-center">
        <img src="../assets/img/logo.png" alt="BEYOND design" style="height:30px;">
      </div>
      <div class="eyebrow text-muted mb-1" style="font-size:12.5px;">Studio console</div>
      <h2 style="font-size:22px; font-weight:600;" class="mb-1">Sign in to your account</h2>
      <p class="text-muted mb-4" style="font-size:13.5px;">Use the username and password issued by your studio admin.</p>

      <div class="mb-3">
        <label class="form-label" style="font-size:13px; font-weight:500;">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
          <input type="text" id="username" class="form-control" placeholder="username" required>
        </div>
      </div>

      <div class="mb-2">
        <label class="form-label" style="font-size:13px; font-weight:500;">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
          <input type="password" id="password" class="form-control" placeholder="••••••••" required>
          <button class="btn btn-bd-outline" type="button" id="togglePw"><i class="bi bi-eye"></i></button>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-4" style="font-size:13px;">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" checked>
          <label class="form-check-label text-muted" for="remember">Keep me signed in</label>
        </div>
        <a href="#" class="text-orange fw-semibold" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-bd-primary w-100 py-2 mb-3">Sign in</button>
    </form>
  </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel">Reset your password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="forgotPasswordForm">
        <div class="modal-body">
          <p class="text-muted" style="font-size:14px;">Enter your email address and we'll send you a link to reset your password.</p>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px; font-weight:500;">Email Address</label>
            <input type="email" id="resetEmail" name="email" class="form-control" placeholder="name@company.com" required>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bd-primary" id="btnSendReset">Send Reset Link</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
<script>
  document.getElementById("togglePw").addEventListener("click", function () {
    const input = this.closest(".input-group").querySelector("input");
    const icon = this.querySelector("i");
    const isPw = input.type === "password";
    input.type = isPw ? "text" : "password";
    icon.className = isPw ? "bi bi-eye-slash" : "bi bi-eye";
  });

  $(document).ready(function () {
    $("#loginForm").on("submit", function (e) {
      e.preventDefault();
      var email = $("#username").val();
      var password = $("#password").val();
      $.ajax({
        type: "POST",
        url: "loginController.php",
        data: {
          email: email,
          password: password,
          action: "Verify",
        },
        success: function (response) {
          if (response.trim() === "success") {
            window.location.href = "../dashboard";
          } else {
            Swal.fire({
              icon: "error",
              title: "Login Failed",
              text: "Invalid username or password. Please try again.",
              confirmButtonColor: "#3085d6",
              confirmButtonText: "OK",
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "An error occurred. Please try again later.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK",
          });
        },
      });
    });

    // Forgot Password Form Submit
    $("#forgotPasswordForm").on("submit", function(e) {
        e.preventDefault();
        var email = $("#resetEmail").val();
        var btn = $("#btnSendReset");
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
        
        $.ajax({
            type: "POST",
            url: "forgotPasswordController.php",
            data: { email: email, action: 'request_reset' },
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).text('Send Reset Link');
                if (res.status === 'success') {
                    $('#forgotPasswordModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Check your email', text: res.message });
                    $('#forgotPasswordForm')[0].reset();
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function() {
                btn.prop('disabled', false).text('Send Reset Link');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Try again.' });
            }
        });
    });
  });
</script>
</body>
</html>
