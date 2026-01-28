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
                <li><i data-feather="message-square"> </i>
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
            <?php

            use Csrf; ?>
            <li class="profile-nav onhover-dropdown">
              <div class="account-user"><i data-feather="user"></i></div>
              <ul class="profile-dropdown onhover-show-div">
                <li><a href="<?= BASE_URL ?>/index.php?c=seller&m=profile"><i data-feather="user"></i><span>Account</span></a></li>
                <li>
                  <button type="button"
                    class="btn btn-link text-dark p-0 w-100 text-start"
                    style="text-decoration: none; border: none; background: none;"
                    data-bs-toggle="modal"
                    data-bs-target="#logoutModal">
                    <div class="d-flex align-items-center">
                      <i data-feather="log-out" class="me-2"></i>
                      <span>Log out</span>
                    </div>
                  </button>
                </li>
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

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body text-center">
            <i data-feather="log-out" class="txt-warning mb-2"></i>
            <p class="mb-0">Apakah kamu yakin ingin logout?</p>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cancel
            </button>

            <form action="<?= BASE_URL ?>index.php?c=auth&m=logout" method="POST">
              <input type="hidden" name="csrf_token" value="<?= Csrf::token(); ?>">
              <button type="submit" class="btn btn-danger">
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      feather.replace()
    </script>