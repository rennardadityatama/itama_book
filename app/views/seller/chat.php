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

      <!-- LEFT SIDEBAR -->
      <div class="col-xl-3 col-lg-4">
        <div class="card h-100">
          <div class="card-body chat-body">

            <!-- User Header -->
            <div class="d-flex mb-3">
              <img class="rounded-circle user-image"
                src="<?= BASE_URL ?>public/assets/images/user/12.png">
              <div class="flex-grow-1 ms-2">
                <div class="name f-w-600">Seller</div>
                <div class="status text-muted">Online</div>
              </div>
            </div>

            <!-- Chat List -->
            <div class="people-list">
              <div class="search mb-2">
                <input class="form-control" type="text" placeholder="Search chat">
              </div>

              <ul class="list custom-scrollbar" id="chat-list">
                <?php foreach ($chatList as $chat): ?>
                  <li class="clearfix chat-user"
                    data-room="<?= $chat['room_id'] ?>"
                    data-name="<?= htmlspecialchars($chat['name']) ?>"
                    data-avatar="<?= $chat['avatar'] ?? BASE_URL . 'public/assets/images/user/default.png' ?>">
                    <div class="d-flex align-items-center">
                      <img class="rounded-circle user-image"
                        src="<?= $chat['avatar'] ?? BASE_URL . 'public/assets/images/user/default.png' ?>">
                      <div class="flex-grow-1 ms-2">
                        <div class="name"><?= $chat['name'] ?></div>
                        <div class="status text-muted small">
                          <?= $chat['last_message'] ?? 'No message yet' ?>
                        </div>
                      </div>
                    </div>
                  </li>
                <?php endforeach ?>
              </ul>
            </div>

          </div>
        </div>
      </div>

      <!-- CHAT BODY -->
      <div class="col-xl-9 col-lg-8">
        <div class="card h-100">
          <div class="card-body d-flex flex-column p-0">

            <!-- Chat Header -->
            <div class="chat-header d-flex align-items-center p-3 border-bottom">
              <img class="rounded-circle" id="chat-avatar"
                src="<?= BASE_URL ?>public/assets/images/user/default.png">
              <div class="flex-grow-1 ms-2">
                <div class="name f-w-600" id="chat-name">Select chat</div>
                <div class="status text-muted" id="chat-status">---</div>
              </div>
            </div>

            <!-- Chat Messages -->
            <div class="chat-history flex-grow-1 p-3 custom-scrollbar">
              <input type="hidden" id="room_id">
              <ul id="chat-messages" class="list-unstyled mb-0"></ul>
            </div>

            <!-- Chat Input -->
            <div class="chat-message p-3 border-top">
              <div class="input-group">
                <input class="form-control"
                  id="message-to-send"
                  type="text"
                  placeholder="Type a message...">
                <button class="btn btn-primary" id="send-btn">Send</button>
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
  const chatList = document.getElementById('chat-list');
  const messagesBox = document.getElementById('chat-messages');
  const roomInput = document.getElementById('room_id');

  chatList.addEventListener('click', function(e) {
    const item = e.target.closest('.chat-user');
    if (!item) return;

    const roomId = item.dataset.room;
    roomInput.value = roomId;

    // Update header
    document.getElementById('chat-name').innerText = item.dataset.name;
    document.getElementById('chat-avatar').src = item.dataset.avatar;

    loadMessages(roomId);
  });

  function loadMessages(roomId) {
    fetch(`index.php?c=sellerChat&m=fetchMessages&room_id=${roomId}`)
      .then(res => res.json())
      .then(data => {
        messagesBox.innerHTML = '';
        data.forEach(msg => {
          messagesBox.innerHTML += `
          <li class="${msg.sender_role === 'seller' ? 'text-end' : ''}">
            <div class="badge bg-${msg.sender_role === 'seller' ? 'primary' : 'light text-dark'}">
              ${msg.message}
            </div>
          </li>
        `;
        });
      });
  }

  // SEND MESSAGE
  document.getElementById('send-btn').addEventListener('click', function() {
    const message = document.getElementById('message-to-send').value;
    const roomId = roomInput.value;

    if (!message || !roomId) return;

    fetch('index.php?c=sellerChat&m=sendMessage', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `room_id=${roomId}&message=${encodeURIComponent(message)}`
      })
      .then(() => {
        document.getElementById('message-to-send').value = '';
        loadMessages(roomId);
      });
  });
</script>