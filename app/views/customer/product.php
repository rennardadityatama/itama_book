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
          <h3>Product</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">ECommerce</li>
            <li class="breadcrumb-item active">Product</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid product-wrapper">
    <div class="product-grid">
      <div class="feature-products">
        <div class="row">
          <div class="col-md-12">
            <div class="pro-filter-sec">
              <div class="product-sidebar">
                <div class="filter-section">
                  <div class="card">
                    <div class="card-header">
                      <h6 class="mb-0 f-w-600">Filters<span class="pull-right"><i
                            class="fa fa-chevron-down toggle-data"></i></span></h6>
                    </div>
                    <div class="left-filter">
                      <div class="card-body filter-cards-view animate-chk">
                        <div class="product-filter">
                          <h6 class="f-w-600">Category</h6>
                          <div class="checkbox-animated mt-0">
                            <?php foreach ($categories as $cat): ?>
                              <label class="d-block">
                                <input class="checkbox_animated category-filter" type="checkbox"
                                  value="<?= $cat['id'] ?>"
                                  <?= ($selected_category == $cat['id']) ? 'checked' : '' ?>>
                                <?= $cat['name'] ?>
                              </label>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-search">
                <form>
                  <div class="form-group m-0">
                    <input class="form-control" type="search" placeholder="Search.." data-original-title="" title=""><i
                      class="fa fa-search"></i>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="product-wrapper-grid">
        <div class="row g-4">
          <?php foreach ($products as $product): ?>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
              <form action="<?= BASE_URL ?>index.php?c=customerCart&m=add" method="POST" class="add-to-cart-form h-100">
                <div class="card h-100">

                  <!-- IMAGE -->
                  <div class="ratio ratio-4x3">
                    <img
                      src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?>"
                      class="img-fluid object-fit-cover"
                      alt="<?= htmlspecialchars($product['name']) ?>">
                  </div>

                  <!-- BODY -->
                  <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-truncate mb-1">
                      <?= $product['name'] ?>
                    </h6>

                    <small class="text-muted mb-1">
                      Stock: <?= $product['stock'] ?>
                    </small>

                    <div class="fw-bold text-primary mb-2">
                      Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </div>

                    <p class="small text-muted mb-2">
                      <?= mb_strimwidth($product['description'], 0, 60, '...') ?>
                    </p>

                    <!-- DATA WAJIB -->
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="qty" value="1">

                    <button
                      type="submit"
                      class="btn btn-sm btn-primary mt-auto"
                      <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                      <i class="icon-shopping-cart me-1"></i>
                      Add to Cart
                    </button>
                  </div>
                </div>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

<script>
  document.querySelectorAll('.category-filter').forEach(el => {
    el.addEventListener('change', () => {
      let checked = document.querySelector('.category-filter:checked');
      let url = '<?= BASE_URL ?>index.php?c=customerProduct&m=index';
      if (checked) url += '&category_id=' + checked.value;
      window.location.href = url;
    });
  });
</script>