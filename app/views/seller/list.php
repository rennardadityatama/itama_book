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
          <h3>List Seller</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row g-4">
      <?php foreach ($sellers as $seller): ?>
        <div class="col-xl-4 col-lg-6">
          <div class="card shadow-sm h-100 border-0">

            <div class="card-body">

              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <img
                    src="<?= BASE_URL ?>/uploads/avatars/<?= $seller['avatar'] ?? 'default.png' ?>"
                    class="rounded-circle me-3"
                    style="width:64px;height:64px;object-fit:cover;">

                  <div>
                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($seller['name']) ?></h6>
                    <small class="text-muted">Seller</small>
                  </div>
                </div>

                <!-- STATUS -->
                <?php if ($seller['online_status'] === 'online'): ?>
                  <span class="badge bg-success">Online</span>
                <?php else: ?>
                  <span class="badge bg-danger">Offline</span>
                <?php endif; ?>
              </div>

              <ul class="list-unstyled small mb-0">
                <li>Email: <?= htmlspecialchars($seller['email']) ?></li>
                <li>NIK: <?= htmlspecialchars($seller['nik']) ?></li>
                <?php if ($seller['address']): ?>
                  <li>Address: <?= htmlspecialchars($seller['address']) ?></li>
                <?php endif; ?>
                <?php if ($seller['account_number']): ?>
                  <li>Account Number: <?= htmlspecialchars($seller['account_number']) ?></li>
                <?php endif; ?>
              </ul>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>