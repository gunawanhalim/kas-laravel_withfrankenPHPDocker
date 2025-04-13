
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/kas.jpg" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="../../assets/images/kas.jpg">
                </div>
                <h6 class="font-weight-light">Login.</h6>
                @if(session('seconds'))
                <div id="seconds" class="alert alert-danger">
                    Coba lagi dalam waktu <span id="countdown"></span>
                </div>
                @endif
                <form class="pt-3" action="{{ route('login?pengguna') }}" method="post" id="loginForm">
                    @csrf
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="login" name="login" placeholder="Username/Email" autocomplete="off">
                    @error('login')
                    <span style="color: red">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
                    <span class="password-toggle-btn" onclick="togglePasswordVisibility(this)"><i id="eyeIcon" class="far fa-eye"></i></span>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input">Ingat saya </label>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    
    <!-- endinject -->
    <script>
       // Get the countdown element
    function updateThrottle() {
        // Kirim permintaan ke server untuk memperbarui waktu jeda
        axios.post('/updateThrottle')
            .then(function (response) {
                // Jika berhasil, mulai perhitungan mundur dengan waktu jeda yang baru
                startCountdown(response.data.seconds);
            })
            .catch(function (error) {
                console.error(error);
            });
    }
    //togglepasswordvisibility

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var eyeIcon = document.getElementById("eyeIcon");
    
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("far", "fa-eye");
            eyeIcon.classList.add("far", "fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("far", "fa-eye-slash");
            eyeIcon.classList.add("far", "fa-eye");
        }
    }
    </script>
  </body>
</html>