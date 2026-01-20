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
    <title>Dashboard | iTama Book</title><link rel="preconnect" href="https://fonts.googleapis.com">
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
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/chartist.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/prism.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/vector-map.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/style.css">
    <link id="color" rel="stylesheet" href="<?= BASE_URL ?>/assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/css/responsive.css">
  </head>
  <body onload="startTime()">
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"> </div>
      <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          <div class="header-logo-wrapper col-auto p-0">
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            <div class="logo-header-main"><a href="index.html"><img class="img-fluid for-light img-100" src="<?= BASE_URL ?>/assets/images/logo/logo2.png" alt=""><img class="img-fluid for-dark" src="<?= BASE_URL ?>/assets/images/logo/logo.png" alt=""></a></div>
          </div>
          <div class="left-header col horizontal-wrapper ps-0">
            <div class="left-menu-header">
              <ul class="app-list">
                <li class="onhover-dropdown">
                  <div class="app-menu"> <i data-feather="folder-plus"></i></div>
                  <ul class="onhover-show-div left-dropdown">
                    <li> <a href="file-manager.html">File Manager</a></li>
                    <li> <a href="kanban.html"> Kanban board</a></li>
                    <li> <a href="social-app.html"> Social App</a></li>
                    <li> <a href="bookmark.html"> Bookmark</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <div class="nav-right col-6 pull-right right-header p-0">
            <ul class="nav-menus">
              <li> 
                <div class="right-header ps-0">
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text mobile-search"><i class="fa fa-search"></i></span></div>
                    <input class="form-control" type="text" placeholder="Search Here........">
                  </div>
                </div>
              </li>
              <li class="serchinput">
                <div class="serchbox"><i data-feather="search"></i></div>
                <div class="form-group search-form">
                  <input type="text" placeholder="Search here...">
                </div>
              </li>
              <li>
                <div class="mode"><i class="fa fa-moon-o"></i></div>
              </li>
              <li class="onhover-dropdown">
                <div class="message"><i data-feather="message-square"></i></div>
                <ul class="message-dropdown onhover-show-div">
                  <li><i data-feather="message-square">            </i>
                    <h6 class="f-18 mb-0">Messages</h6>
                  </li>
                  <li>
                    <div class="d-flex align-items-start">
                      <div class="message-img bg-light-primary"><img src="<?= BASE_URL ?>/assets/images/user/10.jpg" alt=""></div>
                      <div class="flex-grow-1">
                        <h5 class="mb-1"><a href="email_inbox.html">Sarah Loren</a></h5>
                        <p>What`s the project report update?</p>
                      </div>
                      <div class="notification-right"><i data-feather="x"></i></div>
                    </div>
                  </li>
                  <li><a class="btn btn-primary" href="email_inbox.html">Check Messages</a></li>
                </ul>
              </li>
              <li class="maximize"><a href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize-2"></i></a></li>
              <li class="profile-nav onhover-dropdown">
                <div class="account-user"><i data-feather="user"></i></div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="user-profile.html"><i data-feather="user"></i><span>Account</span></a></li>
                  <li><a href="login.html"><i data-feather="log-in"> </i><span>Log out</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{name}}</div>
            </div>
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
        </div>
      </div>
      <!-- Page Header Ends-->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper">
          <div>
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="<?= BASE_URL ?>/assets/img/logo.png" alt=""></a>
              <div class="back-btn"><i data-feather="grid"></i></div>
              <div class="toggle-sidebar icon-box-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="index.html">
                <div class="icon-box-sidebar"><i data-feather="grid"></i></div></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="pin-title sidebar-list">
                    <h6>Pinned</h6>
                  </li>
                  <hr>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="home"> </i><span>Dashboard</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="user"> </i><span>Admin</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="users"> </i><span>Seller</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="shopping-bag"> </i><span>Customer</span></a></li>
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6">
                  <h3>Dashboard</h3>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid dashboard-default">
            <div class="row">
              <div class="col-xxl-6 col-xl-5 col-lg-6 dash-45 box-col-40">
                <div class="card profile-greeting">               
                  <div class="card-body">
                    <div class="d-sm-flex d-block justify-content-between">
                      <div class="flex-grow-1"> 
                        <div class="weather d-flex">
                          <h2 class="f-w-400"> <span>28<sup><i class="fa fa-circle-o f-10"></i></sup>C </span></h2>
                          <div class="span sun-bg"><i class="icofont icofont-sun font-primary"></i></div>
                        </div><span class="font-primary f-w-700">Sunny Day</span>
                        <p>Beautiful Sunny Day Walk</p>
                      </div>
                      <div class="badge-group">
                        <div class="badge badge-light-primary f-12">                         <i class="fa fa-clock-o"></i><span id="txt"></span></div>
                      </div>
                    </div>
                    <div class="greeting-user"> 
                      <div class="profile-vector">
                        <ul class="dots-images">
                          <li class="dot-small bg-info dot-1"></li>
                          <li class="dot-medium bg-primary dot-2"></li>
                          <li class="dot-medium bg-info dot-3"></li>
                          <li class="semi-medium bg-primary dot-4"></li>
                          <li class="dot-small bg-info dot-5"></li>
                          <li class="dot-big bg-info dot-6"></li>
                          <li class="dot-small bg-primary dot-7"></li>
                          <li class="semi-medium bg-primary dot-8"></li>
                          <li class="dot-big bg-info dot-9"></li>
                        </ul><img class="img-fluid" src="<?= BASE_URL ?>/assets/images/dashboard/default/profile.png" alt="">
                        <ul class="vector-image"> 
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon1.png" alt=""></li>
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon3.png" alt=""></li>
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon4.png" alt=""></li>
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon5.png" alt=""></li>
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon6.png" alt=""></li>
                          <li> <img src="<?= BASE_URL ?>/assets/images/dashboard/default/ribbon7.png" alt=""></li>
                        </ul>
                      </div>
                      <h4><a href="user-profile.html"><span>Welcome Back</span> John  </a><span class="right-circle"><i class="fa fa-check-circle font-primary f-14 middle"></i></span></h4>
                      <div><span class="badge badge-primary">Your 5</span><span class="font-primary f-12 middle f-w-500 ms-2"> Task Is Pending</span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-12 box-col-12">
                <div class="card total-growth">
                  <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                      <div class="flex-grow-1"> 
                        <p class="square-after f-w-600 header-text-primary">Our Total Growth<i class="fa fa-circle"> </i></p>
                        <h4>96.564%</h4>
                      </div>
                      <div class="setting-list">
                        <ul class="list-unstyled setting-option">
                          <li>
                            <div class="setting-light"><i class="icon-layout-grid2"></i></div>
                          </li>
                          <li><i class="view-html fa fa-code font-white"></i></li>
                          <li><i class="icofont icofont-maximize full-card font-white"></i></li>
                          <li><i class="icofont icofont-minus minimize-card font-white"></i></li>
                          <li><i class="icofont icofont-refresh reload-card font-white"></i></li>
                          <li><i class="icofont icofont-error close-card font-white"> </i></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="growth-chart"> 
                      <div id="growth-chart"></div>
                    </div>
                    <div class="code-box-copy">
                      <button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#growth"><i class="icofont icofont-copy-alt"></i></button>
                      <pre><code class="language-html" id="growth">&lt;div class="card total-growth"&gt;
  &lt;div class="card-header pb-0"&gt;
    &lt;div class="d-flex justify-content-between"&gt;
      &lt;div class="flex-grow-1"&gt;
        &lt;p class="square-after f-w-600 header-text-primary"&gt; Our Total Growth
          &lt;i class="fa fa-circle"&gt;&lt;/i&gt;
        &lt;/p&gt;
        &lt;h4&gt; 96.564%&lt;/h4&gt;
      &lt;/div&gt;
      &lt;div class="setting-list"&gt;
        &lt;ul class="list-unstyled setting-option"&gt;
          &lt;li&gt;&lt;div class="setting-light"&gt;&lt;i class="icon-layout-grid2"&gt;&lt;/i&gt;&lt;/div&gt;&lt;/li&gt;
          &lt;li&gt;&lt;i class="view-html fa fa-code font-white"&gt;&lt;/i&gt;&lt;/li&gt;
          &lt;li&gt;&lt;i class="icofont icofont-maximize full-card font-white"&gt;&lt;/i&gt;&lt;/li&gt;
          &lt;li&gt;&lt;i class="icofont icofont-minus minimize-card font-white"&gt;&lt;/i&gt;&lt;/li&gt;
          &lt;li&gt;&lt;i class="icofont icofont-refresh reload-card font-white"&gt;&lt;/i&gt;&lt;/li&gt;
          &lt;li&gt;&lt;i class="icofont icofont-error close-card font-white"&gt; &lt;/i&gt;&lt;/li&gt;
        &lt;/ul&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;div class="card-body p-0"&gt;
    &lt;div class="growth-chart"&gt;
      &lt;div id="growth-chart"&gt;&lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/div&gt;</code></pre>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 p-0 footer-left">
                <p class="mb-0">Copyright © 2023 Tivo. All rights reserved.</p>
              </div>
              <div class="col-md-6 p-0 footer-right">
                <p class="mb-0">Hand-crafted & made with <i class="fa fa-heart font-danger"></i></p>
              </div>
            </div>
          </div>
        </footer>
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
    <!-- Template js-->
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/theme-customizer/customizer.js">  </script>
    <!-- login js-->
  </body>
</html>