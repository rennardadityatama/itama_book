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
    <title><?= $title ?? 'Seller | iTama Book' ?></title>
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
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/date-picker.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/owlcarousel.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/prism.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/style.css">
    <link id="color" rel="stylesheet" href="<?= BASE_URL ?>/assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>

<body>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <?php require_once BASE_PATH . '/app/views/customer/layouts/header.php' ?>

        <div class="page-body-wrapper">
            <?php require_once BASE_PATH . '/app/views/customer/layouts/sidebar.php' ?>

            <?php require_once $content ?>

            <?php require_once BASE_PATH . '/app/views/customer/layouts/footer.php' ?>
        </div>

    </div>


    <!-- latest jquery-->
    <script src="<?= BASE_URL ?>/assets/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap js-->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="<?= BASE_URL ?>/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
    <?php foreach ($js as $script): ?>
        <script src="<?= BASE_URL ?>/assets/js/<?= $script ?>"></script>
    <?php endforeach; ?>
    <!-- Template js-->
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/theme-customizer/customizer.js"> </script>
    <!-- scrollbar js-->
    <script src="<?= BASE_URL ?>/assets/js/scrollbar/simplebar.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="<?= BASE_URL ?>/assets/js/config.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/sidebar-menu.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/chart/chartist/chartist.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/chart/chartist/chartist-plugin-tooltip.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/chart/apex-chart/stock-prices.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/prism/prism.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/clipboard/clipboard.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/custom-card/custom-card.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-au-mill.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-in-mill.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vector-map/map/jquery-jvectormap-asia-mill.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard/default.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/notify/index.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/typeahead/handlebars.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/typeahead/typeahead.custom.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/typeahead-search/handlebars.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/typeahead-search/typeahead-custom.js"></script>
</body>

</html>