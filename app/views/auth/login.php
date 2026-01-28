<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="tivo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords" content="admin template, Tivo admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon/logo.png" type="image/x-icon">
  <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/images/favicon/logo.png" type="image/x-icon">
  <title>Login | iTama Book</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/font-awesome.css">
  <!-- ico-font-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/icofont.css">
  <!-- Themify icon-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/themify.css">
  <!-- Flag icon-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/flag-icon.css">
  <!-- Feather icon-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/feather-icon.css">
  <!-- Bootstrap css-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/bootstrap.css">
  <!-- App css-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/style.css">
  <link id="color" rel="stylesheet" href="<?= BASE_URL ?>/assets/css/color-1.css" media="screen">
  <!-- Responsive css-->
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>

<body>
  <!-- Loader starts-->
  <div class="loader-wrapper">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"> </div>
    <div class="dot"></div>
  </div>
  <!-- Loader ends-->
  <!-- login page start-->
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-7"><img class="bg-img-cover bg-center" src="<?= BASE_URL ?>/assets/img/bg.jpg" alt="looginpage"></div>
      <div class="col-xl-5 p-0">
        <div class="login-card" style="background: none !important; background-color: #f5f5f5 !important;">
          <div>
            <div><a class="logo" href="login.php"><img class="img-fluid for-light" src="<?= BASE_URL ?>/assets/img/logo.png" alt="looginpage"></a></div>
            <div class="login-main">
              <form class="theme-form" method="POST" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= Csrf::token(); ?>">
                <h4 class="text-center">Sign in to account</h4>
                <p class="text-center">Enter your email & password to login</p>
                <div class="form-group">
                  <label class="col-form-label">Email Address</label>
                  <input class="form-control" type="email" name="email" required="" placeholder="Test@gmail.com">
                </div>
                <div class="form-group">
                  <label class="col-form-label">Password</label>
                  <div class="form-input position-relative">
                    <input class="form-control" type="password" name="password" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"> </span></div>
                  </div>
                </div>
                <div class="form-group mb-0">
                  <div class="checkbox p-0">
                    <input id="checkbox1" type="checkbox">
                    <a class="link" href="<?= BASE_URL ?>index.php?c=auth&m=forgot">Forgot password?</a>
                  </div>
                  <div class="text-end mt-3">
                    <button class="btn btn-primary btn-block w-100" type="submit" id="loginBtn">
                      <span class="btn-text">Sign in</span>
                      <span class="spinner-border spinner-border-sm d-none" id="loginSpinner"></span>
                    </button>
                  </div>
                </div>
                <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="<?= BASE_URL ?>index.php?c=auth&m=register">Create Account</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
      <div id="appToast" class="toast align-items-center text-white border-0" role="alert">
        <div class="d-flex">
          <div class="toast-body d-flex align-items-center gap-2">
            <i class="fa fa-check-circle"></i>
            <span id="toastMessage"></span>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    </div>

    <script>
      function showToast(message, type = 'success') {
        const toastEl = document.getElementById('appToast');
        const toastMsg = document.getElementById('toastMessage');
        const icon = toastEl.querySelector('i');

        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');
        icon.className = 'fa';

        if (type === 'success') {
          toastEl.classList.add('bg-success');
          icon.classList.add('fa-check-circle');
        } else {
          toastEl.classList.add('bg-danger');
          icon.classList.add('fa-times-circle');
        }

        toastMsg.textContent = message;

        const toast = new bootstrap.Toast(toastEl, {
          delay: 3000
        });

        toast.show();
      }

      document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.show-hide');
        if (!toggle) return;

        const input = toggle.previousElementSibling;
        if (!input || input.tagName !== 'INPUT') return;

        if (input.type === 'password') {
          input.type = 'text';
          toggle.classList.add('show');
        } else {
          input.type = 'password';
          toggle.classList.remove('show');
        }
      });

      document.getElementById('loginForm').addEventListener('submit', async e => {
        e.preventDefault();

        const btn = document.getElementById('loginBtn');
        const spinner = document.getElementById('loginSpinner');
        const btnText = btn.querySelector('.btn-text');

        btn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        try {
          const res = await fetch('<?= BASE_URL ?>index.php?c=auth&m=loginProcess', {
            method: 'POST',
            body: new FormData(e.currentTarget),
            credentials: 'same-origin'
          });

          const json = await res.json();

          // toast (opsional, tapi tetap bagus)
          showToast(json.message, json.status ? 'success' : 'danger');

          if (json.status && json.data.redirect) {
            // langsung redirect (tanpa delay panjang)
            setTimeout(() => {
              window.location.href = json.data.redirect;
            }, 600);
          } else {
            // gagal → balikin tombol
            btn.disabled = false;
            spinner.classList.add('d-none');
            btnText.classList.remove('d-none');
          }

        } catch (err) {
          console.error(err);
          showToast('Login gagal, coba lagi', 'danger');
          btn.disabled = false;
          spinner.classList.add('d-none');
          btnText.classList.remove('d-none');
        }
      });
    </script>

    <!-- latest jquery-->
    <script src="<?= BASE_URL ?>/assets/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap js-->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="<?= BASE_URL ?>/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <!-- Sidebar jquery-->
    <script src="<?= BASE_URL ?>/assets/js/config.js"></script>
    <!-- Template js-->
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <!-- login js-->
  </div>
</body>

</html>