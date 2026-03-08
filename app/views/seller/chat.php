<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- Loader starts-->
<div class="loader-wrapper">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>

<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>Chat</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item active">Chat</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <!-- SIDEBAR CHAT LIST -->
      <div class="col-xl-3 col-md-4 call-chat-sidebar">
        <div class="card">
          <div class="card-body chat-body">
            <div class="chat-box">
              <div class="chat-left-aside">
                <div class="people-list" id="people-list">

                  <ul class="list custom-scrollbar" id="chat-list">
                    <?php if (empty($chatList)): ?>
                      <li class="text-center p-3">
                        <p class="text-muted">No chat yet</p>
                      </li>
                    <?php else: ?>
                      <?php foreach ($chatList as $chat): ?>
                        <li class="clearfix chat-item <?= isset($activeRoom) && $activeRoom['id'] == $chat['room_id'] ? 'active' : '' ?>"
                          data-room-id="<?= $chat['room_id'] ?>">
                          <a href="<?= BASE_URL ?>/index.php?c=sellerChat&m=index&room=<?= $chat['room_id'] ?>"
                            class="d-flex align-items-center chat-room-link p-3">
                            <img class="rounded-circle user-image"
                              style="width: 50px; height: 50px; object-fit: cover;"
                              src="<?= !empty($chat['customer_avatar'])
                                      ? BASE_URL . '/uploads/avatars/' . htmlspecialchars($chat['customer_avatar'])
                                      : BASE_URL . '/assets/images/default-avatar.png' ?>">
                            <div class="flex-grow-1 ms-3">
                              <div class="about">
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="name fw-bold">
                                    <?= htmlspecialchars($chat['customer_name']) ?>
                                  </div>
                                  <?php if ($chat['unread_count'] > 0): ?>
                                    <span class="badge bg-primary rounded-pill"><?= $chat['unread_count'] ?></span>
                                  <?php endif; ?>
                                </div>
                                <?php if ($chat['last_message']): ?>
                                  <div class="text-muted small text-truncate mt-1" style="max-width: 200px;">
                                    <?= htmlspecialchars(substr($chat['last_message'], 0, 40)) ?><?= strlen($chat['last_message']) > 40 ? '...' : '' ?>
                                  </div>
                                  <div class="text-muted" style="font-size: 11px;">
                                    <?= $chat['last_message_time'] ? date('d M, H:i', strtotime($chat['last_message_time'])) : '' ?>
                                  </div>
                                <?php else: ?>
                                  <div class="text-muted small">No Messages Yet</div>
                                <?php endif; ?>
                              </div>
                            </div>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CHAT WINDOW -->
      <div class="col-xl-9 col-md-8 call-chat-body">
        <div class="card">
          <div class="card-body p-0">
            <div class="row chat-box">
              <div class="col chat-right-aside">
                <?php if ($activeRoom): ?>
                  <!-- Chat Header -->
                  <div class="chat">
                    <div class="d-flex chat-header clearfix align-items-center justify-content-between p-3 border-bottom">
                      <div class="d-flex align-items-center">
                        <img class="rounded-circle"
                          src="<?= !empty($activeRoom['customer_avatar']) ? BASE_URL . '/uploads/avatars/' . htmlspecialchars($activeRoom['customer_avatar']) : BASE_URL . '/assets/images/default-avatar.png' ?>"
                          alt="" style="width: 50px; height: 50px; object-fit: cover;">
                        <div class="ms-3">
                          <div class="name fw-bold"><?= htmlspecialchars($activeRoom['customer_name']) ?></div>
                          <div class="status">
                            <?= chatStatusHtml($activeRoom['customer_last_activity'] ?? null) ?>
                          </div>
                        </div>
                      </div>

                      <!-- BONUS: Show discussed products -->
                      <?php if (!empty($discussedProducts)): ?>
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa fa-shopping-bag"></i> Products Discussed (<?= count($discussedProducts) ?>)
                          </button>
                          <ul class="dropdown-menu">
                            <?php foreach ($discussedProducts as $prod): ?>
                              <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= BASE_URL ?>/index.php?c=customerProduct&m=detail&id=<?= $prod['id'] ?>">
                                  <img src="<?= BASE_URL ?>/uploads/products/<?= $prod['image'] ?>"
                                    style="width: 30px; height: 30px; object-fit: cover;"
                                    class="me-2 rounded">
                                  <span class="text-truncate"><?= htmlspecialchars($prod['name']) ?></span>
                                </a>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                        </div>
                      <?php endif; ?>
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-history chat-msg-box custom-scrollbar" id="chat-history" style="height: 500px; overflow-y: auto;">
                      <ul id="message-list" class="p-3">
                        <?php foreach ($messages as $msg): ?>
                          <?php $isMe = $msg['sender_id'] == $_SESSION['user']['id']; ?>

                          <li class="mb-3 clearfix">
                            <div class="message d-inline-block <?= $isMe ? 'my-message float-end text-end' : 'other-message float-start' ?>">

                              <div class="d-flex <?= $isMe ? 'flex-row-reverse' : 'flex-row' ?> align-items-start">
                                <img
                                  class="rounded-circle chat-user-img img-30 mx-2"
                                  src="<?= !empty($msg['sender_avatar'])
                                          ? BASE_URL . '/uploads/avatars/' . htmlspecialchars($msg['sender_avatar'])
                                          : BASE_URL . '/assets/images/default-avatar.png' ?>"
                                  alt="">

                                <div>
                                  <div class="message-content">
                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>

                                    <?php if ($msg['product_id'] && $msg['product_name']): ?>
                                      <div class="product-context mt-2 p-2 bg-light rounded">
                                        <small class="text-muted d-flex align-items-center">
                                          <i class="fa fa-tag me-1"></i>
                                          <?= htmlspecialchars($msg['product_name']) ?>
                                        </small>
                                      </div>
                                    <?php endif; ?>
                                  </div>

                                  <div class="message-data-time text-muted small mt-1 <?= $isMe ? 'text-end' : '' ?>">
                                    <?= date('H:i', strtotime($msg['created_at'])) ?>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-message clearfix p-3 border-top">
                      <div class="row">
                        <div class="col-xl-12">
                          <form id="chat-form">
                            <input type="hidden" name="room_id" id="room-id" value="<?= $activeRoom['id'] ?>">
                            <input type="hidden" name="product_id" id="product-id" value=""> <!-- Optional -->

                            <div class="input-group">
                              <input class="form-control"
                                id="message-input"
                                type="text"
                                name="message"
                                placeholder="Type a message..."
                                autocomplete="off"
                                required>
                              <button class="btn btn-primary" type="submit">
                                <i class="fa fa-paper-plane"></i> Send
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="chat-empty d-flex align-items-center justify-content-center h-100">
                    <div class="text-center">
                      <i class="fa fa-comments fa-5x text-muted mb-3"></i>
                      <p class="text-muted">Select a seller to start a conversation</p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .chat-item.active {
    background-color: #f0f0f0;
  }

  .chat-room-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: background-color 0.2s;
  }

  .chat-room-link:hover {
    background-color: #f8f9fa;
  }

  .call-chat-body .card {
    height: calc(100vh - 180px);
  }

  .chat-history {
    height: calc(100vh - 350px);
  }

  .chat-right-aside {
    min-height: 600px;
  }

  .chat-empty {
    height: 600px;
  }
</style>

<script>
  const BASE_URL = '<?= BASE_URL ?>';
  const CHAT_BASE_URL = '<?= BASE_URL ?>/index.php?c=sellerChat';
  const CURRENT_USER_ID = <?= $_SESSION['user']['id'] ?>;
  const CURRENT_ROOM_ID = <?= $activeRoom['id'] ?? 'null' ?>;
  window.USER_AVATAR = "<?= !empty($_SESSION['user']['avatar'])
                          ? BASE_URL . '/uploads/avatars/' . $_SESSION['user']['avatar']
                          : BASE_URL . '/assets/images/default-avatar.png' ?>";
</script>
<script src="<?= BASE_URL ?>/assets/js/chat.js"></script>