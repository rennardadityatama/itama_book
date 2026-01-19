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
  <title>Register | iTama Book</title>
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
  <div class="container-fluid p-0">
    <div class="row m-0">
      <div class="col-xl-7 p-0"><img class="bg-img-cover bg-center" src="<?= BASE_URL ?>/assets/img/bg.jpg" alt="looginpage"></div>
      <div class="col-xl-5 p-0">
        <div class="login-card" style="background: none !important; background-color: #f5f5f5 !important;">
          <div>
            <div><a class="logo text-center" href="login.php"><img class="img-fluid for-light" src="<?= BASE_URL ?>/assets/img/logo.png" alt="looginpage"></a></div>
            <div class="login-main">
              <form class="theme-form">
                <h4>Create your account</h4>
                <p>Enter your personal details to create account</p>
                <div class="form-group">
                  <label class="col-form-label">Your Name</label>
                  <input class="form-control" type="text" required="" placeholder="Your Name">
                </div>
                <div class="form-group">
                  <label class="col-form-label">Email Address</label>
                  <input class="form-control" type="email" required="" placeholder="Test@gmail.com">
                </div>
                <div class="form-group">
                  <label class="col-form-label">Password</label>
                  <div class="form-input position-relative">
                    <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"></span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-form-label"> Confirm Password</label>
                  <div class="form-input position-relative">
                    <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                    <div class="show-hide"><span class="show"></span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-form-label">Your Address</label>
                  <input class="form-control" type="text" required="" placeholder="Your Address">
                </div>
                <div class="form-group">
                  <label class="form-label" for="validationCustom04">Role</label>
                  <select class="form-select" id="validationCustom04" required="">
                    <option selected="" disabled="" value="">Choose Your Role...</option>
                    <option>...</option>
                  </select>
                  <div class="invalid-feedback">Please select a valid state.</div>
                </div>
                <div class="form-group mb-0">
                  <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Create</button>
                </div>
                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="<?= BASE_URL ?>index.php?c=auth&m=login">Sign in</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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