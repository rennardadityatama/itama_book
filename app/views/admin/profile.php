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
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>User Profile</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active">User Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="user-profile">
      <div class="row">
        <!-- user profile header start-->
        <div class="col-sm-12">
          <div class="card profile-header"><img class="img-fluid bg-img-cover"
              src="<?= BASE_URL ?>/assets/images/user-profile/bg-profile.jpg" alt="">
            <div class="profile-img-wrrap"><img class="img-fluid bg-img-cover"
                src="<?= BASE_URL ?>/assets/images/user-profile/bg-profile.jpg" alt=""></div>
            <div class="userpro-box">
              <div class="img-wrraper">
                <div class="avatar"><img class="img-fluid" alt="" src="<?= BASE_URL ?>/assets/images/user/7.jpg">
                </div><a class="icon-wrapper" href="edit-profile.html"><i class="icofont icofont-pencil-alt-5"></i></a>
              </div>
              <div class="user-designation">
                <div class="title"><a target="_blank" href="">
                    <h4><?= htmlspecialchars($_SESSION['user']['name'] ?? '-') ?></h4>
                    <h6 class="f-w-500"><?= htmlspecialchars($_SESSION['user']['email'] ?? '-') ?></h6>
                  </a></div>
                <div class="follow">
                  <ul class="follow-list">
                    <li>
                      <div class="follow-num counter">325</div><span>Follower</span>
                    </li>
                    <li>
                      <div class="follow-num counter">450</div><span>Following</span>
                    </li>
                    <li>
                      <div class="follow-num counter">500</div><span>Likes</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- user profile header end-->
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>