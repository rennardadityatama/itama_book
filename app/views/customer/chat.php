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
                  <div class="d-flex chat-header clearfix align-items-start">
                    <?php if ($target): ?>
                      <img class="rounded-circle" src="<?= $target['avatar'] ?: '../../../public/assets/images/user/default.png' ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                      <div class="flex-grow-1">
                        <div class="about">
                          <div class="name">
                            <a href="javascript:void(0)"><?= htmlspecialchars($target['name']) ?></a>
                          </div>
                          <div class="status">
                            <?php
                            // Logika sederhana: Jika aktivitas kurang dari 5 menit yang lalu, anggap online
                            $isOnline = (strtotime($target['last_activity']) > strtotime('-5 minutes'));
                            ?>
                            <span class="badge rounded-pill badge-<?= $isOnline ? 'success' : 'secondary' ?>">
                              <?= $isOnline ? 'Online' : 'Offline' ?>
                            </span>
                            <small class="text-muted">Last seen: <?= date('d M, H:i', strtotime($target['last_activity'])) ?></small>
                          </div>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="text-muted">Pilih chat untuk memulai</div>
                    <?php endif; ?>
                  </div>
                  <!-- chat-header end-->
                  <div class="chat-history chat-msg-box custom-scrollbar" id="chat-content">
                    <ul id="message-list">
                      <?php foreach ($messages as $msg): ?>
                        <li class="<?= $msg['sender_id'] == $_SESSION['user']['id'] ? '' : 'clearfix' ?>">
                          <div class="message <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'my-message' : 'other-message pull-right' ?>">
                            <div class="message-data <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'text-end' : '' ?>">
                              <span class="message-data-time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                            </div>
                            <?= htmlspecialchars($msg['message']) ?>
                          </div>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                  <!-- end chat-history-->
                  <div class="chat-message clearfix">
                    <div class="row">
                      <div class="col-xl-12 d-flex">
                        <div class="input-group text-box">
                          <input type="hidden" id="active-room-id" value="<?= $activeRoom ?>">
                          <input class="form-control input-txt-bx" id="message-to-send" type="text" placeholder="Type a message......">
                          <button class="btn btn-primary input-group-text" id="btn-send" type="button">SEND</button>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btnSend = document.getElementById('btn-send');
    const inputMsg = document.getElementById('message-to-send');
    const chatContent = document.getElementById('chat-content');
    const messageList = document.getElementById('message-list');

    btnSend?.addEventListener('click', function() {
      const roomId = document.getElementById('active-room-id').value;
      const message = inputMsg.value.trim();
      if (!message) return;

      fetch('<?= BASE_URL ?>index.php?c=customerChat&m=send', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `room_id=${roomId}&message=${encodeURIComponent(message)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            const now = new Date();
            const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const li = document.createElement('li');
            li.innerHTML = `<div class="message my-message">
                                    <div class="message-data text-end">
                                        <span class="message-data-time">${time}</span>
                                    </div>
                                    ${message}
                                </div>`;
            messageList.appendChild(li);
            inputMsg.value = '';
            chatContent.scrollTop = chatContent.scrollHeight;
          }
        });
    });
  });
</script>