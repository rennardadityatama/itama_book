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
      <h3>List Category</h3>
    </div>

    <!-- TABLE -->
    <div class="card">
      <div class="card-body">

        <!-- Input tambah kategori -->
        <div class="row mb-3 align-items-end">
          <form action="<?= BASE_URL ?>?c=adminCategories&m=store" method="POST">
            <div class="input-group">
              <input type="text" name="name" class="form-control" placeholder="Insert Category Name" required>
              <button type="submit" class="btn btn-primary">
                Add
              </button>
            </div>
          </form>
        </div>
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>
        <!-- Tabel -->
        <div class="table-responsive theme-scrollbar">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="50">No</th>
                <th>Name</th>
                <th width="120">Action</th>
              </tr>
            </thead>
            <tbody id="categoryTableBody">
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $i => $cat): ?>
                  <tr data-id="<?= $cat['id'] ?>">
                    <td><?= $i + 1 ?></td>
                    <td class="cat-name"><?= htmlspecialchars($cat['name']) ?></td>
                    <td>
                      <div class="d-flex gap-2">

                        <!-- Edit -->
                        <button
                          class="btn btn-sm btn-warning"
                          data-bs-toggle="modal"
                          data-bs-target="#editModal"
                          data-id="<?= $cat['id'] ?>"
                          data-name="<?= htmlspecialchars($cat['name']) ?>"
                          title="Edit">
                          <i data-feather="edit-2"></i>
                        </button>

                        <!-- Delete -->
                        <button
                          class="btn btn-sm btn-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#deleteModal"
                          data-id="<?= $cat['id'] ?>"
                          data-name="<?= htmlspecialchars($cat['name']) ?>"
                          title="Delete">
                          <i data-feather="trash-2"></i>
                        </button>

                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center text-muted">
                    Belum ada data kategori
                  </td>
                </tr>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="toastContainer"></div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="editForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id" id="editCategoryId">
        <div class="mb-3">
          <label class="form-label">Category Name</label>
          <input type="text" name="name" id="editCategoryName" class="form-control" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>


<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="deleteForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id" id="deleteCategoryId">
        <p id="deleteText"></p>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {

    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');

    editModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;

      const id = button.getAttribute('data-id');
      const name = button.getAttribute('data-name');

      document.getElementById('editCategoryId').value = id;
      document.getElementById('editCategoryName').value = name;

      document.getElementById('editForm').action =
        "<?= BASE_URL ?>?c=adminCategories&m=update&id=" + id;
    });

    deleteModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;

      const id = button.getAttribute('data-id');
      const name = button.getAttribute('data-name');

      document.getElementById('deleteCategoryId').value = id;
      document.getElementById('deleteText').innerText =
        'Yakin mau hapus kategori "' + name + '" ?';

      document.getElementById('deleteForm').action =
        "<?= BASE_URL ?>?c=adminCategories&m=destroy&id=" + id;
    });

  });
</script>