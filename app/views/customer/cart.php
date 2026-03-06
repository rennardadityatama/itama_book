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
          <h3>Cart</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item active">Cart</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="container-fluid">
    <?php if (empty($cartItems)): ?>
      <div class="card">
        <div class="card-body text-center text-muted">
          Keranjang masih kosong
        </div>
      </div>
    <?php else: ?>
      <?php $grandTotal = 0; ?>
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">

              <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">

                  <thead>
                    <tr>
                      <th width="80">Product</th>
                      <th>Product Name</th>
                      <th>Seller</th>
                      <th width="120">Price</th>
                      <th width="160">Quantity</th>
                      <th width="100">Stock</th>
                      <th width="150">Total</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($cartItems as $item): ?>
                      <?php
                      $itemSubtotal = $item['price'] * $item['qty'];
                      $grandTotal += $itemSubtotal;
                      ?>
                      <tr>
                        <td>
                          <img
                            src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>"
                            class="img-fluid img-40"
                            alt="">
                        </td>
                        <td class="text-start">
                          <strong><?= htmlspecialchars($item['name']) ?></strong>
                        </td>
                        <td>
                          <?= htmlspecialchars($item['seller_name']) ?>
                        </td>
                        <td>
                          Rp <?= number_format($item['price'], 0, ',', '.') ?>
                        </td>
                        <td>
                          <form
                            class="cart-qty-form d-flex justify-content-center align-items-center gap-1"
                            data-cart-id="<?= $item['cart_id'] ?>"
                            data-price="<?= $item['price'] ?>">

                            <button
                              type="button"
                              data-action="minus"
                              class="btn btn-sm btn-outline-secondary">
                              −
                            </button>

                            <input
                              type="text"
                              class="form-control text-center qty-input"
                              value="<?= $item['qty'] ?>"
                              readonly
                              style="width:50px">

                            <button
                              type="button"
                              data-action="plus"
                              class="btn btn-sm btn-outline-secondary"
                              <?= $item['qty'] >= $item['stock'] ? 'disabled' : '' ?>>
                              +
                            </button>
                          </form>
                        </td>

                        <td>
                          <?php if ($item['stock'] <= 0): ?>
                            <span class="text-danger fw-bold">0</span>
                          <?php else: ?>
                            <span class="text-success fw-bold"><?= $item['stock'] ?></span>
                          <?php endif; ?>
                        </td>
                        <td class="item-subtotal fw-bold"
                          data-cart-id="<?= $item['cart_id'] ?>">
                          Rp <?= number_format($itemSubtotal, 0, ',', '.') ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr>
                      <td colspan="6" class="text-end fw-bold">
                        Total Belanja
                      </td>
                      <td class="fw-bold text-success">
                        Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="6" class="text-end">
                        <a
                          href="<?= BASE_URL ?>index.php?c=customerProduct&m=index"
                          class="btn btn-secondary">
                          Continue Shopping
                        </a>
                      </td>
                      <td>
                        <a
                          href="<?= BASE_URL ?>index.php?c=customerOrder&m=checkoutAll"
                          class="btn btn-success w-100">
                          Checkout
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  document.querySelectorAll('.cart-qty-form button').forEach(btn => {
    btn.addEventListener('click', function() {

      const form = this.closest('.cart-qty-form');
      const cartId = form.dataset.cartId;
      const price = parseInt(form.dataset.price);
      const sellerId = form.dataset.sellerId;

      fetch('<?= BASE_URL ?>index.php?c=customerCart&m=updateQty', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            cart_id: cartId,
            action: this.dataset.action
          })
        })
        .then(res => res.json())
        .then(res => {

          // ITEM DIHAPUS
          if (res.status === 'deleted') {
            const row = document
              .querySelector(`.item-subtotal[data-cart-id="${cartId}"]`)
              .closest('tr');

            row.remove();
            updateSellerSubtotal(res.data.sellerId);

            showToast(res.message, 'warning');
            return;
          }

          // ERROR / WARNING
          if (res.status !== 'success') {
            showToast(res.message, res.status);
            return;
          }

          // UPDATE QTY
          form.querySelector('.qty-input').value = res.data.qty;

          // UPDATE SUBTOTAL ITEM
          const newSubtotal = res.data.qty * res.data.price;
          document
            .querySelector(`.item-subtotal[data-cart-id="${cartId}"]`)
            .innerText = formatRupiah(newSubtotal);

          // UPDATE SUBTOTAL SELLER
          updateSellerSubtotal(res.data.sellerId);

          showToast(res.message, 'success');
        });
    });
  });

  // =========================
  // HITUNG SUBTOTAL SELLER
  // =========================
  function updateSellerSubtotal(sellerId) {
    let total = 0;

    document
      .querySelectorAll(`.item-subtotal[data-seller-id="${sellerId}"]`)
      .forEach(el => {
        total += rupiahToInt(el.innerText);
      });

    const sellerSubtotalEl =
      document.querySelector(`.seller-subtotal[data-seller-id="${sellerId}"]`);

    if (sellerSubtotalEl) {
      sellerSubtotalEl.innerText = formatRupiah(total);
    }
  }

  // =========================
  // HELPER
  // =========================
  function rupiahToInt(text) {
    return parseInt(text.replace(/[^\d]/g, '')) || 0;
  }

  function formatRupiah(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
  }

  function showToast(msg, type = 'info') {
    toastr[type](msg);
  }
</script>