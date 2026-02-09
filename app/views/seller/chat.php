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
          <h3>Chat App</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Chat</li>
            <li class="breadcrumb-item active"> Chat App</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <div class="col call-chat-sidebar">
        <div class="card">
          <div class="card-body chat-body">
            <div class="chat-box">
              <!-- Chat left side Start-->
              <div class="chat-left-aside">
                <div class="people-list" id="people-list">
                  <div class="search">
                    <form class="theme-form">
                      <div class="form-group">
                        <div class="input-group">
                          <input class="form-control" type="text" placeholder="Search"><span class="input-group-text"> <i class="fa fa-search"></i></span>
                        </div>
                      </div>
                    </form>
                  </div>
                  <ul class="list custom-scrollbar">
                    <?php foreach ($chatList as $chat): ?>
                      <li class="clearfix">
                        <div class="d-flex align-items-center chat-room" data-room-id="<?= $chat['room_id'] ?>">
                          <img class="rounded-circle user-image" src="<?= $chat['avatar'] ?: '../../../public/assets/images/user/default.png' ?>" alt="">
                          <div class="flex-grow-1">
                            <div class="about">
                              <div class="name"><?= htmlspecialchars($chat['name']) ?></div>
                              <div class="status"><?= htmlspecialchars($chat['status'] ?? 'Offline') ?></div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
              <!-- Chat left side Ends-->
            </div>
          </div>
        </div>
      </div>
      <div class="col call-chat-body">
        <div class="card">
          <div class="card-body p-0">
            <div class="row chat-box">
              <!-- Chat right side start-->
              <div class="col chat-right-aside">
                <!-- chat start-->
                <div class="chat">
                  <!-- chat-header start-->
                  <div class="d-flex chat-header clearfix align-items-start"><img class="rounded-circle" src="../../../public/assets/images/user/8.jpg" alt="">
                    <div class="flex-grow-1">
                      <div class="about">
                        <div class="name"><a href="user-profile.html">Kori Thomas  </a></div>
                        <div class="status digits">Online</div>
                      </div>
                    </div>
                  </div>
                  <!-- chat-header end-->
                  <div class="chat-history chat-msg-box custom-scrollbar">
                    <ul>
                      <li>
                        <div class="message my-message"><img class="rounded-circle float-start chat-user-img img-30" src="../../../public/assets/images/user/3.png" alt="">
                          <div class="message-data text-end"><span class="message-data-time">10:12 am</span></div>Are we meeting today? Project has been already finished and I have results to show you.
                        </div>
                      </li>
                      <li class="clearfix">
                        <div class="message other-message pull-right"><img class="rounded-circle float-end chat-user-img img-30" src="../../../public/assets/images/user/12.png" alt="">
                          <div class="message-data"><span class="message-data-time">10:14 am</span></div>Well I am not sure. The rest of the team
                        </div>
                      </li>
                    </ul>
                  </div>
                  <!-- end chat-history-->
                  <div class="chat-message clearfix">
                    <div class="row">
                      <div class="col-xl-12 d-flex">
                        <div class="input-group text-box">
                          <input class="form-control input-txt-bx" id="message-to-send" type="text" name="message-to-send" placeholder="Type a message......">
                          <button class="btn btn-primary input-group-text" type="button">SEND</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>