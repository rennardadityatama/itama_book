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
          <div class="row mb-4">
            <!-- Search -->
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa fa-search"></i>
                </span>
                <input id="searchCategory" class="form-control"
                  type="text"
                  placeholder="Search Category Here...">
              </div>
            </div>
            <!-- Add Category -->
            <div class="col-md-6">
              <form action="<?= BASE_URL ?>?c=adminCategories&m=store" method="POST">
                <div class="input-group">
                  <input type="text"
                    name="name"
                    class="form-control"
                    placeholder="Insert Category Name"
                    required>

                  <button type="submit" class="btn btn-primary">
                    Add
                  </button>
                </div>
              </form>
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
                        <div class="d-flex gap-3 align-items-center">

                          <!-- Edit -->
                          <button
                            class="icon-action text-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal"
                            data-id="<?= $cat['id'] ?>"
                            data-name="<?= htmlspecialchars($cat['name']) ?>"
                            title="Edit">
                            <i data-feather="edit-2"></i>
                          </button>

                          <!-- Delete -->
                          <button
                            class="icon-action text-danger"
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

<style>
  .icon-action {
    background: transparent;
    border: none;
    padding: 4px;
    cursor: pointer;
    transition: 0.2s;
  }

  .icon-action:hover {
    transform: scale(1.15);
    opacity: 0.8;
  }
</style>


<script>
  function showToast(message, type = "success") {

    const toastContainer = document.getElementById("toastContainer");

    const toast = document.createElement("div");

    let bgClass = "bg-success text-white";

    if (type === "danger") {
      bgClass = "bg-danger text-white";
    }

    toast.className = `toast align-items-center ${bgClass} border-0 show mb-2`;

    toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        ${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
        onclick="this.closest('.toast').remove()">
      </button>
    </div>
  `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
      toast.remove();
    }, 4000);
  }

  function renumberTable() {
    const rows = document.querySelectorAll("#categoryTableBody tr");

    rows.forEach((row, index) => {
      const cell = row.querySelector("td:first-child");
      if (cell) {
        cell.textContent = index + 1;
      }
    });
  }

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
        'Are you sure you want to delete this "' + name + '" ?';

      document.getElementById('deleteForm').action =
        "<?= BASE_URL ?>?c=adminCategories&m=destroy&id=" + id;
    });

  });

  document.addEventListener("DOMContentLoaded", function() {

    const searchInput = document.getElementById("searchCategory");
    const tableRows = document.querySelectorAll("#categoryTableBody tr");

    searchInput.addEventListener("keyup", function() {
      const keyword = this.value.toLowerCase();

      tableRows.forEach(function(row) {
        const name = row.querySelector(".cat-name").textContent.toLowerCase();

        if (name.includes(keyword)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });

    });

  });

  const addForm = document.querySelector('form[action*="store"]');

  addForm.addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(addForm);

    const res = await fetch(addForm.action, {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.success) {

      showToast(data.message, "success");

      const table = document.getElementById("categoryTableBody");

      const rowCount = table.querySelectorAll("tr").length + 1;

      const newRow = `
      <tr data-id="${data.data.id}">
        <td>${rowCount}</td>
        <td class="cat-name">${data.data.name}</td>
        <td>
          <div class="d-flex gap-3 align-items-center">
            <button class="icon-action text-warning"
              data-bs-toggle="modal"
              data-bs-target="#editModal"
              data-id="${data.data.id}"
              data-name="${data.data.name}">
              <i data-feather="edit-2"></i>
            </button>

            <button class="icon-action text-danger"
              data-bs-toggle="modal"
              data-bs-target="#deleteModal"
              data-id="${data.data.id}"
              data-name="${data.data.name}">
              <i data-feather="trash-2"></i>
            </button>
          </div>
        </td>
      </tr>
    `;

      table.insertAdjacentHTML("afterbegin", newRow);
      renumberTable();
      addForm.reset();
      feather.replace();

    } else {
      showToast(data.message, "danger");
    }

  });

  const editForm = document.getElementById("editForm");

  editForm.addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(editForm);

    const res = await fetch(editForm.action, {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.success) {

      showToast(data.message, "success");

      const row = document.querySelector(`tr[data-id="${data.data.id}"]`);
      row.querySelector(".cat-name").textContent = data.data.name;

      const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
      modal.hide();

    } else {
      showToast(data.message, "danger");
    }

  });

  const deleteForm = document.getElementById("deleteForm");

  deleteForm.addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(deleteForm);
    const id = document.getElementById("deleteCategoryId").value;

    const res = await fetch(deleteForm.action, {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.success) {

      showToast(data.message, "success");

      document.querySelector(`tr[data-id="${id}"]`).remove();
      renumberTable();

      const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
      modal.hide();

    } else {
      showToast(data.message, "danger");
    }

  });
</script>