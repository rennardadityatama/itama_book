<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->

<!-- Loader starts-->
<div class="loader-wrapper">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>
<!-- Loader ends-->

<!-- Page Body Start-->
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>Invoice</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= BASE_URL ?>index.php?c=customerProduct&m=index">
                <i data-feather="home"></i>
              </a>
            </li>
            <li class="breadcrumb-item">Order</li>
            <li class="breadcrumb-item active">Invoice</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Container-fluid starts-->
  <div class="container invoice">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <!-- SUCCESS ALERT -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i data-feather="check-circle" class="me-2"></i>
              <strong>Order Placed Successfully!</strong> Your order has been received and is being processed.
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <div id="invoice-content">
              <!-- INVOICE HEADER -->
              <div class="row mb-4">
                <div class="col-sm-6">
                  <h2 class="text-primary mb-0">INVOICE</h2>
                  <p class="text-muted mb-0">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></p>
                </div>
                <div class="col-sm-6 text-end">
                  <div class="invoice-status">
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch ($order['status']) {
                      case 'pending':
                        $statusClass = 'warning';
                        $statusText = 'Pending';
                        break;
                      case 'approved':
                        $statusClass = 'info';
                        $statusText = 'Approved';
                        break;
                      case 'refund':
                        $statusClass = 'danger';
                        $statusText = 'Refunded';
                        break;
                      default:
                        $statusClass = 'secondary';
                        $statusText = ucfirst($order['status']);
                    }
                    ?>
                    <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2">
                      <?= $statusText ?>
                    </span>
                  </div>
                </div>
              </div>

              <hr class="my-4">

              <!-- CUSTOMER & SELLER INFO -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="text-primary mb-3">
                        <i data-feather="user" class="me-2"></i>Customer Information
                      </h6>
                      <p class="mb-1"><strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>
                      <p class="mb-1 text-muted">
                        <i data-feather="mail" width="14"></i>
                        <?= htmlspecialchars($order['customer_email']) ?>
                      </p>
                      <p class="mb-1 text-muted">
                        <i data-feather="phone" width="14"></i>
                        <?= htmlspecialchars($order['customer_phone'] ?? '-') ?>
                      </p>
                      <p class="mb-0 text-muted">
                        <i data-feather="map-pin" width="14"></i>
                        <?= htmlspecialchars($order['customer_address'] ?? 'No address') ?>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="text-primary mb-3">
                        <i data-feather="shopping-bag" class="me-2"></i>Seller Information
                      </h6>
                      <p class="mb-1"><strong><?= htmlspecialchars($order['seller_name']) ?></strong></p>
                      <p class="mb-1 text-muted">
                        <i data-feather="mail" width="14"></i>
                        <?= htmlspecialchars($order['seller_email']) ?>
                      </p>
                      <p class="mb-0 text-muted">
                        <i data-feather="phone" width="14"></i>
                        <?= htmlspecialchars($order['seller_phone'] ?? '-') ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PAYMENT INFO -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="text-primary mb-3">
                        <i data-feather="credit-card" class="me-2"></i>Payment Information
                      </h6>
                      <div class="row">
                        <div class="col-6">
                          <p class="text-muted mb-1">Payment Method:</p>
                          <p class="mb-0 fw-bold">
                            <?= $order['payment_method'] === 'transfer' ? 'Bank Transfer' : 'QRIS' ?>
                          </p>
                        </div>
                        <div class="col-6">
                          <p class="text-muted mb-1">Payment Status:</p>
                          <p class="mb-0">
                            <?php
                            $paymentStatusClass = '';
                            $paymentStatusText = '';
                            switch ($order['payment_status']) {
                              case 'failed':
                                $paymentStatusClass = 'warning';
                                $paymentStatusText = 'Failed';
                                break;
                              case 'completed':
                                $paymentStatusClass = 'success';
                                $paymentStatusText = 'Completed';
                                break;
                              default:
                                $paymentStatusClass = 'secondary';
                                $paymentStatusText = ucfirst($order['payment_status']);
                            }
                            ?>
                            <span class="badge bg-<?= $paymentStatusClass ?>">
                              <?= $paymentStatusText ?>
                            </span>
                          </p>
                        </div>
                      </div>

                      <?php if (!empty($order['payment_proof'])): ?>
                        <div class="mt-3">
                          <p class="text-muted mb-2">Payment Proof:</p>
                          <a href="<?= BASE_URL ?>uploads/payments/<?= $order['payment_proof'] ?>"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary me-2 d-inline-flex align-items-center">
                            <i data-feather="image" width="14"></i> View Payment Proof
                          </a>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="text-primary mb-3">
                        <i data-feather="truck" class="me-2"></i>Shipping Information
                      </h6>
                      <div class="row">
                        <div class="col-6">
                          <p class="text-muted mb-1">Shipping Status:</p>
                          <p class="mb-0">
                            <?php
                            $shippingStatusClass = '';
                            $shippingStatusText = '';
                            switch ($order['shipping_status']) {
                              case 'pending':
                                $shippingStatusClass = 'warning';
                                $shippingStatusText = 'Pending';
                                break;
                              case 'shipped':
                                $shippingStatusClass = 'success';
                                $shippingStatusText = 'Shipped';
                                break;
                              default:
                                $shippingStatusClass = 'secondary';
                                $shippingStatusText = ucfirst($order['shipping_status']);
                            }
                            ?>
                            <span class="badge bg-<?= $shippingStatusClass ?>">
                              <?= $shippingStatusText ?>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ORDER ITEMS TABLE -->
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" width="60">#</th>
                      <th>Item Description</th>
                      <th class="text-center" width="100">Quantity</th>
                      <th class="text-end" width="150">Price</th>
                      <th class="text-end" width="150">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($orderItems as $item):
                    ?>
                      <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                          <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        </td>
                        <td class="text-center"><?= $item['qty'] ?></td>
                        <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr class="table-light">
                      <td colspan="4" class="text-end fw-bold">TOTAL:</td>
                      <td class="text-end fw-bold text-primary fs-5">
                        Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>


            </div>

            <!-- ACTION BUTTONS -->
            <div class="row mt-4">
              <div class="col-12 text-center">
                <a href="<?= BASE_URL ?>index.php?c=customerStatus&m=downloadInvoice&order_id=<?= $order['id'] ?>"
                  class="btn btn-primary me-2 d-inline-flex align-items-center">
                  <i data-feather="printer" class="me-2"></i>
                  <span>Download Invoice</span>
                </a>
                <a href="<?= BASE_URL ?>index.php?c=customerStatus&m=index"
                  class="btn btn-secondary d-inline-flex align-items-center me-2">
                  <i data-feather="list" class="me-2"></i>
                  <span>View Order History</span>
                </a>
                <a href="<?= BASE_URL ?>index.php?c=customerProduct&m=index"
                  class="btn btn-success d-inline-flex align-items-center">
                  <i data-feather="shopping-cart" class="me-2"></i>
                  <span>Continue Shopping</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- Page Body Ends-->

<!-- Print Invoice Script -->
<script>
  function printInvoice() {
    // Hide buttons before print
    const buttons = document.querySelector('.row.mt-4');
    const alert = document.querySelector('.alert.alert-success');

    buttons.style.display = 'none';
    if (alert) alert.style.display = 'none';

    // Print
    window.print();

    // Show buttons after print
    setTimeout(() => {
      buttons.style.display = 'block';
      if (alert) alert.style.display = 'block';
    }, 100);
  }

  // Auto-hide success alert after 5 seconds
  setTimeout(() => {
    const alert = document.querySelector('.alert.alert-success');
    if (alert) {
      alert.classList.remove('show');
      setTimeout(() => alert.remove(), 150);
    }
  }, 5000);
</script>