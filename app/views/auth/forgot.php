<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="tivo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords" content="admin template, Tivo admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon/logo.png">
  <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/images/favicon/logo.png" type="image/x-icon">
  <title>Forgot | iTama Book</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/font-awesome.css'">
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

  <style>
    .loader-wrapper {
      display: none !important;
    }

    /* ===== LOADING OVERLAY ===== */
    .loader-overlay {
      position: fixed;
      inset: 0;
      background: rgba(255, 255, 255, 0.75);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    /* ===== DUAL COLOR SPINNER ===== */
    .dual-spinner {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: 6px solid transparent;
      border-top-color: #6f42c1;
      /* UNGU */
      border-right-color: #ffc107;
      /* KUNING */
      animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
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
              <form class="theme-form" id="forgotForm" method="POST" action="<?= BASE_URL ?>index.php?c=auth&m=forgot">
                <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                <h4 class="text-center">Reset Your Password</h4>
                <p class="text-center">Enter your email</p>
                <div class="form-group">
                  <label class="col-form-label">Email Address</label>
                  <input class="form-control" name="email" type="email" required="" placeholder="Test@gmail.com">
                </div>
                <div class="form-group mb-0">
                  <div class="text-end mt-3">
                    <button class="btn btn-primary btn-block w-100" type="submit">Send </button>
                  </div>
                </div>
                <p class="mt-4 mb-0 text-center">Already have an password?<a class="ms-2" href="#" onclick="goLogin(event)">Sign In</a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Global Loading Spinner -->
    <div id="globalLoader" class="loader-overlay d-none">
      <div class="dual-spinner"></div>
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
      function showLoader() {
        document.getElementById('globalLoader').classList.remove('d-none');
      }

      function hideLoader() {
        document.getElementById('globalLoader').classList.add('d-none');
      }

      function showToast(message, type = 'success') {
        const toastEl = document.getElementById('appToast');
        const toastMsg = document.getElementById('toastMessage');

        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');

        toastMsg.textContent = message;

        new bootstrap.Toast(toastEl, {
          delay: 3000
        }).show();
      }

      function goLogin(e) {
        e.preventDefault();
        showLoader();

        setTimeout(() => {
          window.location.href = "<?= BASE_URL ?>index.php?c=auth&m=login";
        }, 500); // delay dikit biar spinner keliatan
      }

      document.getElementById('forgotForm').addEventListener('submit', async e => {
        e.preventDefault();

        showLoader();

        try {
          const res = await fetch('?c=auth&m=forgot', {
            method: 'POST',
            body: new FormData(e.target)
          });

          const json = await res.json();

          hideLoader();
          showToast(json.message, json.status ? 'success' : 'danger');

          if (json.status) {
            e.target.reset();

            // ⏳ Delay 2.5 detik lalu redirect
            setTimeout(() => {
              window.location.href = "<?= BASE_URL ?>index.php?c=auth&m=login";
            }, 2500);
          }

        } catch (err) {
          hideLoader();
          showToast('Terjadi kesalahan server', 'danger');
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