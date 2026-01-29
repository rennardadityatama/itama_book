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
        <div class="col-sm-6 text-end">
          <button class="btn btn-primary" id="btnAddSeller">Add Seller</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row g-4" id="sellerCards">
      <?php foreach ($sellers as $seller): ?>
        <div class="col-xl-4 col-lg-6">

          <div class="card shadow-sm h-100 border-0">

            <!-- BODY -->
            <div class="card-body">

              <div class="d-flex align-items-center mb-3">
                <img
                  src="<?= BASE_URL ?>/uploads/avatars/<?= $seller['avatar'] ?? 'default.png' ?>"
                  class="rounded-circle me-3"
                  style="width:64px;height:64px;object-fit:cover;">

                <div>
                  <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($seller['name']) ?></h6>
                  <small class="text-muted">Seller</small>
                </div>
              </div>

              <ul class="list-unstyled mb-0 small">
                <li class="mb-2">Email: <?= htmlspecialchars($seller['email']) ?></li>
                <li class="mb-2">NIK: <?= htmlspecialchars($seller['nik']) ?></li>
                <?php if ($seller['address']): ?>
                  <li class="mb-2">Address: <?= htmlspecialchars($seller['address']) ?></li>
                <?php endif; ?>
                <?php if ($seller['account_number']): ?>
                  <li class="mb-2">Account : <?= htmlspecialchars($seller['account_number']) ?></li>
                <?php endif; ?>
              </ul>

              <!-- QRIS Thumbnail -->
              <?php if (!empty($seller['qris_photo'])): ?>
                <div class="mt-3">
                  <img
                    src="<?= BASE_URL ?>/uploads/qris/<?= $seller['qris_photo'] ?>"
                    class="rounded border shadow-sm"
                    style="width:48px;height:48px;object-fit:cover;cursor:pointer"
                    data-bs-toggle="modal"
                    data-bs-target="#qrisModal"
                    data-img="<?= BASE_URL ?>/uploads/qris/<?= $seller['qris_photo'] ?>">
                </div>
              <?php endif; ?>

            </div>

            <!-- FOOTER -->
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
              <button class="btn btn-warning btn-sm btnEdit" data-id="<?= $seller['id'] ?>">Edit</button>
              <button class="btn btn-danger btn-sm btnDelete" data-id="<?= $seller['id'] ?>" data-name="<?= htmlspecialchars($seller['name']) ?>">Delete</button>
            </div>

          </div>

        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="toastContainer"></div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="addForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Add Seller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="addSellerName" class="form-label">Name</label>
          <input type="text" class="form-control" id="addSellerName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="addSellerNIK" class="form-label">NIK</label>
          <input type="text" class="form-control" id="addSellerNIK" name="nik" required>
        </div>
        <div class="mb-3">
          <label for="addSellerEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="addSellerEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="addSellerPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="addSellerPassword" name="password">
        </div>
        <div class="mb-3">
          <label for="addSellerAddress" class="form-label">Address</label>
          <input type="text" class="form-control" id="addSellerAddress" name="address">
        </div>
        <div class="mb-3">
          <label for="addSellerAccount" class="form-label">Account Number</label>
          <input type="text" class="form-control" id="addSellerAccount" name="account_number">
        </div>
        <div class="mb-3">
          <label for="addSellerQris" class="form-label">Avatar</label>
          <input type="file" class="form-control" id="addSellerAvatar" name="avatar">
        </div>
        <div class="mb-3">
          <label for="addSellerQris" class="form-label">QRIS Photo</label>
          <input type="file" class="form-control" id="addSellerQris" name="qris_photo">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Seller</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="editForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Seller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editSellerId">
        <div class="mb-3">
          <label for="editSellerName" class="form-label">Name</label>
          <input type="text" class="form-control" id="editSellerName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="editSellerEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="editSellerEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="editSellerEmail" class="form-label">NIK</label>
          <input type="text" class="form-control" id="editSellerNik" name="nik" required>
        </div>
        <div class="mb-3">
          <label for="editSellerEmail" class="form-label">New Password</label>
          <input type="password" class="form-control" id="editSellerPassword" name="password">
        </div>
        <div class="mb-3">
          <label for="editSellerAddress" class="form-label">Address</label>
          <input type="text" class="form-control" id="editSellerAddress" name="address">
        </div>
        <div class="mb-3">
          <label for="editSellerAccount" class="form-label">Account Number</label>
          <input type="text" class="form-control" id="editSellerAccount" name="account_number">
        </div>
        <div class="mb-3">
          <label for="editSellerQris" class="form-label">Avatar</label>
          <input type="file" class="form-control" id="editSellerAvatar" name="avatar">
        </div>
        <div class="mb-3">
          <label for="editSellerQris" class="form-label">QRIS Photo</label>
          <input type="file" class="form-control" id="editSellerQris" name="qris_photo">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview QRIS -->
<div class="modal fade" id="qrisModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title">QRIS Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="qrisPreview" src="" class="img-fluid rounded" style="max-height:360px;">
      </div>
    </div>
  </div>
</div>

<script>
  const SELLER_BASE_URL = '<?= BASE_URL ?>/index.php?c=adminSeller';
</script>
<script src="<?= BASE_URL ?>/assets/js/seller.js"></script>