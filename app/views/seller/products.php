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
          <h3>Product list</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">ECommerce</li>
            <li class="breadcrumb-item active">Product list</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid list-products">
    <div class="row">
      <div class="col-12">
        <div class="card">

          <!-- CARD HEADER -->
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

              <!-- SEARCH -->
              <div style="max-width:350px; width:100%;">
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fa fa-search"></i>
                  </span>
                  <input id="searchProduct"
                    class="form-control"
                    type="text"
                    placeholder="Search Product Name...">
                </div>
              </div>

              <!-- BUTTON -->
              <button class="btn btn-primary" id="btnAddProduct">
                Add Product
              </button>

            </div>
          </div>

          <!-- CARD BODY -->
          <div class="card-body pt-3">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover align-middle" id="productTable">

                <thead class="table-light">
                  <tr>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Cost Price</th>
                    <th>Margin</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th width="120">Action</th>
                  </tr>
                </thead>

                <tbody>
                  <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                      <tr>

                        <td class="product-name">
                          <?= htmlspecialchars($p['name']) ?>
                        </td>

                        <td>
                          <img
                            src="<?= $p['image']
                                    ? BASE_URL . '/uploads/products/' . $p['image']
                                    : BASE_URL . '/assets/images/ecommerce/no-image.png' ?>"
                            alt="<?= htmlspecialchars($p['name']) ?>"
                            class="img-fluid rounded"
                            style="max-width:70px;">
                        </td>

                        <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($p['cost_price'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($p['margin'], 0, ',', '.') ?></td>

                        <td class="<?= ($p['stock'] > 0) ? 'text-success fw-semibold' : 'text-danger fw-semibold' ?>">
                          <?= $p['stock'] ?>
                        </td>

                        <td><?= htmlspecialchars($p['description'] ?? '-') ?></td>

                        <td>
                          <span class="badge bg-light text-dark border">
                            <?= htmlspecialchars($p['category_name'] ?? '-') ?>
                          </span>
                        </td>

                        <td>
                          <div class="d-flex gap-2">
                            <button
                              class="btn btn-warning btn-sm text-white btnEdit"
                              data-id="<?= $p['id'] ?>"
                              title="Edit">
                              Edit
                            </button>

                            <button
                              class="btn btn-danger btn-sm btnDelete"
                              data-id="<?= $p['id'] ?>"
                              data-name="<?= htmlspecialchars($p['name']) ?>"
                              title="Delete">
                              Delete
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="9" class="text-center text-muted py-4">
                        No products found
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
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
        <h5 class="modal-title">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="addProductName" class="form-label">Name</label>
          <input type="text" class="form-control" id="addProductName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="addProductCategory" class="form-label">Category</label>
          <select class="form-select" id="addProductCategory" name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="addProductPrice" class="form-label">Price</label>
          <input type="number" class="form-control" id="addProductPrice" name="price" required>
        </div>
        <div class="mb-3">
          <label for="addProductCost" class="form-label">Cost Price</label>
          <input type="number" class="form-control" id="addProductCost" name="cost_price" required>
        </div>
        <div class="mb-3">
          <label for="addProductMargin" class="form-label">Margin</label>
          <input type="number" class="form-control" id="addProductMargin" name="margin" readonly>
        </div>
        <div class="mb-3">
          <label for="addProductStock" class="form-label">Stock</label>
          <input type="text" class="form-control" id="addProductStock" name="stock">
        </div>
        <div class="mb-3">
          <label for="addProductImage" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="addProductImage" name="product_image">
        </div>
        <div class="mb-3">
          <label for="addProductDescription" class="form-label">Description</label>
          <input type="text" class="form-control" id="addProductDescription" name="description">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="editForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editProductId">
        <div class="mb-3">
          <label for="editProductName" class="form-label">Name</label>
          <input type="text" class="form-control" id="editProductName" name="name" required>
        </div>
        <!-- Edit Modal Category -->
        <div class="mb-3">
          <label for="editProductCategory" class="form-label">Category</label>
          <select class="form-select" id="editProductCategoryId" name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="editProductPrice" class="form-label">Price</label>
          <input type="number" class="form-control" id="editProductPrice" name="price" required>
        </div>
        <div class="mb-3">
          <label for="editProductCost" class="form-label">Cost Price</label>
          <input type="number" class="form-control" id="editProductCost" name="cost_price" required>
        </div>
        <div class="mb-3">
          <label for="editProductMargin" class="form-label">Margin</label>
          <input type="number" class="form-control" id="editProductMargin" name="margin" readonly>
        </div>
        <div class="mb-3">
          <small class="text-muted">
            Current stock: <span id="currentStock"></span>
          </small>
        </div>
        <div class="mb-3">
          <label for="editProductStock" class="form-label">Stock</label>
          <input type="number" class="form-control" id="editProductStock" name="stock" value="0">
        </div>
        <div class="mb-3">
          <label for="editProductImage" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="editProductImage" name="product_image">
        </div>
        <div class="mb-3">
          <label for="editProductDescription" class="form-label">Description</label>
          <input type="text" class="form-control" id="editProductDescription" name="description">
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
  const PRODUCT_BASE_URL = '<?= BASE_URL ?>/index.php?c=sellerProduct';
</script>
<script src="<?= BASE_URL ?>/assets/js/product.js"></script>

<script>
  (function() {
    function autoMargin(priceInput, costInput, marginInput) {
      function calculate() {
        const price = parseFloat(priceInput.value) || 0;
        const cost = parseFloat(costInput.value) || 0;
        marginInput.value = price - cost;
      }

      priceInput.addEventListener('input', calculate);
      costInput.addEventListener('input', calculate);
    }

    document.addEventListener('DOMContentLoaded', () => {
      /* ADD MODAL */
      const addPrice = document.getElementById('addProductPrice');
      const addCost = document.getElementById('addProductCost');
      const addMargin = document.getElementById('addProductMargin');

      if (addPrice && addCost && addMargin) {
        autoMargin(addPrice, addCost, addMargin);
      }

      /* EDIT MODAL */
      const editPrice = document.getElementById('editProductPrice');
      const editCost = document.getElementById('editProductCost');
      const editMargin = document.getElementById('editProductMargin');

      if (editPrice && editCost && editMargin) {
        autoMargin(editPrice, editCost, editMargin);
      }
    });
  })();

  document.addEventListener("DOMContentLoaded", function() {

    const searchInput = document.getElementById("searchProduct");
    const rows = document.querySelectorAll("#productTable tbody tr");

    searchInput.addEventListener("keyup", function() {

      const keyword = this.value.toLowerCase();

      rows.forEach(function(row) {

        const name = row.querySelector(".product-name").textContent.toLowerCase();

        if (name.includes(keyword)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }

      });

    });

  });
</script>