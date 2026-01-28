<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->

<!-- Loader starts-->
<div class="loader-wrapper d-none" id="globalSpinner">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>
<!-- Loader ends-->

<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>List Customer</h3>
        </div>
        <div class="col-sm-6 text-end">
          <button class="btn btn-primary" id="btnAddCustomer">Add Customer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row g-4" id="customerCards">
      <?php foreach ($customers as $customer): ?>
        <div class="col-xl-4 col-lg-6">

          <div class="card shadow-sm h-100 border-0">

            <!-- BODY -->
            <div class="card-body">

              <div class="d-flex align-items-center mb-3">
                <img
                  src="<?= BASE_URL ?>/uploads/avatars/<?= $customer['avatar'] ?? 'default.png' ?>"
                  class="rounded-circle me-3"
                  style="width:64px;height:64px;object-fit:cover;">

                <div>
                  <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($customer['name']) ?></h6>
                  <small class="text-muted">Customer</small>
                </div>
              </div>

              <ul class="list-unstyled mb-0 small">
                <li class="mb-2">Email: <?= htmlspecialchars($customer['email']) ?></li>
                <li class="mb-2">NIK: <?= htmlspecialchars($customer['nik']) ?></li>
                <?php if ($customer['address']): ?>
                  <li class="mb-2">Address: <?= htmlspecialchars($customer['address']) ?></li>
                <?php endif; ?>
                <?php if ($customer['phone']): ?>
                  <li class="mb-2">Phone : <?= htmlspecialchars($customer['phone']) ?></li>
                <?php endif; ?>
              </ul>
            </div>

            <!-- FOOTER -->
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
              <button class="btn btn-warning btn-sm btnEdit" data-id="<?= $customer['id'] ?>">Edit</button>
              <button class="btn btn-danger btn-sm btnDelete" data-id="<?= $customer['id'] ?>" data-name="<?= htmlspecialchars($customer['name']) ?>">Delete</button>
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
        <h5 class="modal-title">Add Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="addCustomerName" class="form-label">Name</label>
          <input type="text" class="form-control" id="addCustomerName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="addCustomerNIK" class="form-label">NIK</label>
          <input type="text" class="form-control" id="addCustomerNIK" name="nik" required>
        </div>
        <div class="mb-3">
          <label for="addCustomerEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="addCustomerEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="addCustomerPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="addCustomerPassword" name="password">
        </div>
        <div class="mb-3">
          <label for="addCustomerAddress" class="form-label">Address</label>
          <input type="text" class="form-control" id="addCustomerAddress" name="address">
        </div>
        <div class="mb-3">
          <label for="addCustomerPhone" class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="addCustomerPhone" name="phone">
        </div>
        <div class="mb-3">
          <label for="addCustomerQris" class="form-label">Avatar</label>
          <input type="file" class="form-control" id="addCustomerAvatar" name="avatar">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Customer</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="editForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editCustomerId">
        <div class="mb-3">
          <label for="editCustomerName" class="form-label">Name</label>
          <input type="text" class="form-control" id="editCustomerName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="editCustomerEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="editCustomerEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="editCustomerEmail" class="form-label">NIK</label>
          <input type="text" class="form-control" id="editCustomerNik" name="nik" required>
        </div>
        <div class="mb-3">
          <label for="editCustomerEmail" class="form-label">New Password</label>
          <input type="password" class="form-control" id="editCustomerPassword" name="password">
        </div>
        <div class="mb-3">
          <label for="editCustomerAddress" class="form-label">Address</label>
          <input type="text" class="form-control" id="editCustomerAddress" name="address">
        </div>
        <div class="mb-3">
          <label for="editCustomerPhone" class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="editCustomerPhone" name="phone">
        </div>
        <div class="mb-3">
          <label for="editCustomerQris" class="form-label">Avatar</label>
          <input type="file" class="form-control" id="editCustomerAvatar" name="avatar">
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

<script>
  const CUSTOMER_BASE_URL = '<?= BASE_URL ?>/index.php?c=adminCustomer';
</script>
<script src="<?= BASE_URL ?>/assets/js/customer.js"></script>