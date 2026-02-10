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

                    <div class="d-flex gap-2 mt-auto">
                      <button
                        type="submit"
                        class="btn btn-sm btn-primary flex-grow-1"
                        <?= $product['stock'] <= 0 ? 'disabled' : '' ?>
                        style="padding: 5px 10px;">
                        <i class="icon-shopping-cart"></i> Add
                      </button>

                      <button class="btn btn-primary btn-chat-seller"
                        data-seller-id="<?= $product['seller_id'] ?>"
                        data-product-id="<?= $product['id'] ?>">
                        <i class="fa fa-comments"></i> Chat Seller
                      </button>
                    </div>
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
  (function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
      document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-chat-seller');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const sellerId = btn.dataset.sellerId;
        const productId = btn.dataset.productId;

        if (!sellerId || !productId) {
          alert('Data produk tidak lengkap');
          return;
        }

        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        // UBAH URL: Langsung ke customerChat controller dengan method start
        fetch('<?= BASE_URL ?>/index.php?c=customerChat&m=start', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'seller_id=' + encodeURIComponent(sellerId) + '&product_id=' + encodeURIComponent(productId)
          })
          .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              return response.text().then(text => {
                console.error('Response bukan JSON:', text);
                throw new Error('Server error: ' + text.substring(0, 200));
              });
            }
            return response.json();
          })
          .then(data => {
            console.log('Response:', data);

            if (data.status === 'success') {
              window.location.href = data.data.redirect_url;
            } else {
              alert(data.message || 'Gagal memulai chat');
              btn.disabled = false;
              btn.innerHTML = originalHTML;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
          });
      });
    });
  })();

  // Category filter
  document.querySelectorAll('.category-filter').forEach(el => {
    el.addEventListener('change', () => {
      let checked = document.querySelector('.category-filter:checked');
      let url = '<?= BASE_URL ?>index.php?c=customerProduct&m=index';
      if (checked) url += '&category_id=' + checked.value;
      window.location.href = url;
    });
  });
</script>