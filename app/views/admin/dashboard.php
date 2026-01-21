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
  <!-- Page Body Start-->
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
                    <div class="badge badge-light-primary f-12"> <i class="fa fa-clock-o"></i><span id="txt"></span></div>
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
                  <h4><a href="user-profile.html"><span>Welcome Back</span> John </a><span class="right-circle"><i class="fa fa-check-circle font-primary f-14 middle"></i></span></h4>
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