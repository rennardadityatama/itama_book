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
          <div class="col-md-6">
            <label class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryName" placeholder="Insert Category Name">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary d-flex align-items-center gap-1" id="addCategoryBtn">
              <i data-feather="plus-circle"></i> Add
            </button>
          </div>
        </div>

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
                      <ul class="action">
                        <li class="edit">
                          <a href="javascript:void(0)" onclick="openEditModal(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>', this)">
                            <i class="icon-pencil-alt"></i>
                          </a>
                        </li>
                        <li class="delete">
                          <a href="javascript:void(0)" onclick="openDeleteModal(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>', this)">
                            <i class="icon-trash"></i>
                          </a>
                        </li>
                      </ul>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="editForm">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editCategoryId">
        <div class="mb-3">
          <label for="editCategoryName" class="form-label">Category Name</label>
          <input type="text" class="form-control" id="editCategoryName" required>
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
const CATEGORY_BASE_URL = '<?= BASE_URL ?>/index.php?c=adminCategories';
</script>
<script src="<?= BASE_URL ?>/assets/js/category.js"></script>
