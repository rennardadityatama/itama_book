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
    <?php if (empty($groupedCart)): ?>
      <div class="card">
        <div class="card-body text-center text-muted">
          Keranjang masih kosong
        </div>
      </div>
    <?php else: ?>

      <?php foreach ($groupedCart as $seller): ?>
        <?php $sellerSubtotal = 0; ?>

        <div class="row mb-4">
          <div class="col-sm-12">
            <div class="card">

              <!-- SELLER HEADER -->
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                  Seller: <?= htmlspecialchars($seller['seller_name']) ?>
                </h4>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered align-middle text-center">
                    <thead>
                      <tr>
                        <th width="80">Product</th>
                        <th>Product Name</th>
                        <th width="120">Price</th>
                        <th width="160">Quantity</th>
                        <th width="100">Stock</th>
                        <th width="150">Total</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php foreach ($seller['items'] as $item): ?>
                        <?php
                        $itemSubtotal = $item['price'] * $item['qty'];
                        $sellerSubtotal += $itemSubtotal;
                        ?>
                        <tr>
                          <!-- IMAGE -->
                          <td>
                            <img
                              src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>"
                              class="img-fluid img-40"
                              alt="">
                          </td>

                          <!-- NAME -->
                          <td class="text-start">
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                          </td>

                          <!-- PRICE -->
                          <td>
                            Rp <?= number_format($item['price'], 0, ',', '.') ?>
                          </td>

                          <!-- QTY CONTROL -->
                          <td>
                            <?php if ($item['stock'] <= 0): ?>
                              <span class="badge bg-danger">Stok habis</span>
                            <?php else: ?>
                              <form
                                action="<?= BASE_URL ?>index.php?c=customerCart&m=updateQty"
                                method="POST"
                                class="d-flex justify-content-center align-items-center gap-1">

                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">

                                <button
                                  type="submit"
                                  name="action"
                                  value="minus"
                                  class="btn btn-sm btn-outline-secondary"
                                  <?= $item['qty'] <= 1 ? 'disabled' : '' ?>>
                                  −
                                </button>

                                <input
                                  type="text"
                                  class="form-control text-center"
                                  value="<?= $item['qty'] ?>"
                                  readonly
                                  style="width:50px">

                                <button
                                  type="submit"
                                  name="action"
                                  value="plus"
                                  class="btn btn-sm btn-outline-secondary"
                                  <?= $item['qty'] >= $item['stock'] ? 'disabled' : '' ?>>
                                  +
                                </button>
                              </form>
                            <?php endif; ?>
                          </td>

                          <!-- STOCK -->
                          <td>
                            <?php if ($item['stock'] <= 0): ?>
                              <span class="text-danger fw-bold">0</span>
                            <?php else: ?>
                              <span class="text-success fw-bold"><?= $item['stock'] ?></span>
                            <?php endif; ?>
                          </td>

                          <!-- TOTAL -->
                          <td class="fw-bold">
                            Rp <?= number_format($itemSubtotal, 0, ',', '.') ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>

                      <!-- FOOTER -->
                      <tr>
                        <td colspan="5" class="text-end fw-bold">
                          Subtotal Seller
                        </td>
                        <td class="fw-bold text-primary">
                          Rp <?= number_format($sellerSubtotal, 0, ',', '.') ?>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="5" class="text-end">
                          <a
                            href="<?= BASE_URL ?>index.php?c=customerProduct&m=index"
                            class="btn btn-secondary">
                            Continue Shopping
                          </a>
                        </td>
                        <td>
                          <a
                            href="<?= BASE_URL ?>index.php?c=customerOrder&m=checkout&seller_id=<?= $seller['seller_id'] ?>"
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
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>