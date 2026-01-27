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
          <h3>Order Management</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= BASE_URL ?>index.php?c=sellerDashboard&m=index">
                <i data-feather="home"></i>
              </a>
            </li>
            <li class="breadcrumb-item">Orders</li>
            <li class="breadcrumb-item active">Approve Orders</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header pb-0">
            <h4>Manage Orders</h4>
            <p class="mb-0">Review, approve, and manage customer orders</p>
          </div>
          <div class="card-body">
            <?php if (empty($orders)): ?>
              <div class="alert alert-info text-center">
                <i data-feather="info" class="me-2"></i>
                No orders found
              </div>
            <?php else: ?>
              <div class="table-responsive theme-scrollbar">
                <table class="display table table-striped table-hover" id="order-table">
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Payment</th>
                      <th>Shipping</th>
                      <th>Status</th>
                      <th width="180">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($orders as $order): ?>
                      <tr>
                        <!-- ORDER ID -->
                        <td>
                          <strong class="text-primary">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                          <br>
                        </td>

                        <!-- CUSTOMER INFO -->
                        <td>
                          <div>
                            <strong><?= htmlspecialchars($order['customer_name']) ?></strong>
                            <br>
                            <small class="text-muted">
                              <i data-feather="mail" width="12"></i>
                              <?= htmlspecialchars($order['customer_email']) ?>
                            </small>
                            <br>
                            <small class="text-muted">
                              <i data-feather="phone" width="12"></i>
                              <?= htmlspecialchars($order['customer_phone'] ?? '-') ?>
                            </small>
                          </div>
                        </td>

                        <!-- ITEMS -->
                        <td>
                          <button type="button"
                            class="btn btn-xs btn-outline-primary py-1 px-2"
                            data-bs-toggle="modal"
                            data-bs-target="#itemsModal<?= $order['id'] ?>">
                            <i data-feather="package" width="12"></i>
                            <?= count($order['items']) ?> item(s)
                          </button>
                        </td>

                        <!-- TOTAL -->
                        <td>
                          <strong class="text-success">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                        </td>

                        <!-- PAYMENT STATUS -->
                        <td>
                          <div class="d-flex flex-column">
                            <?php
                            $paymentBadge = '';
                            switch ($order['payment_status']) {
                              case 'waiting_payment':
                                $paymentBadge = '<span class="badge bg-warning">Waiting</span>';
                                break;
                              case 'completed':
                                $paymentBadge = '<span class="badge bg-success">Completed</span>';
                                break;
                              default:
                                $paymentBadge = '<span class="badge bg-secondary">' . ucfirst($order['payment_status']) . '</span>';
                            }
                            echo $paymentBadge;
                            ?>

                            <small class="text-muted mt-1">
                              <i data-feather="credit-card" width="10"></i>
                              <?= $order['payment_method'] === 'transfer' ? 'Bank Transfer' : 'QRIS' ?>
                            </small>

                            <?php if (!empty($order['payment_proof'])): ?>
                              <a href="<?= BASE_URL ?>index.php?c=sellerApprove&m=viewPaymentProof&order_id=<?= $order['id'] ?>"
                                target="_blank"
                                class="btn btn-xxs btn-outline-info mt-1 py-0 px-1">
                                <i data-feather="eye" width="10"></i> View Proof
                              </a>
                            <?php endif; ?>
                          </div>
                        </td>

                        <!-- SHIPPING STATUS -->
                        <td>
                          <div class="d-flex flex-column">
                            <?php
                            $shippingBadge = '';
                            switch ($order['shipping_status']) {
                              case 'pending':
                                $shippingBadge = '<span class="badge bg-warning">Pending</span>';
                                break;
                              case 'shipped':
                                $shippingBadge = '<span class="badge bg-success">Shipped</span>';
                                break;
                              default:
                                $shippingBadge = '<span class="badge bg-secondary">' . ucfirst($order['shipping_status']) . '</span>';
                            }
                            echo $shippingBadge;
                            ?>

                            <?php if (!empty($order['shipping_resi'])): ?>
                              <small class="text-muted mt-1">Resi:</small>
                              <strong class="text-primary small"><?= htmlspecialchars($order['shipping_resi']) ?></strong>
                            <?php endif; ?>
                          </div>
                        </td>

                        <!-- ORDER STATUS -->
                        <td>
                          <?php
                          $statusBadge = '';
                          switch ($order['status']) {
                            case 'pending':
                              $statusBadge = '<span class="badge bg-warning">Pending</span>';
                              break;
                            case 'approved':
                              $statusBadge = '<span class="badge bg-success">Approved</span>';
                              break;
                            case 'refund':
                              $statusBadge = '<span class="badge bg-danger">Refunded</span>';
                              break;
                            default:
                              $statusBadge = '<span class="badge bg-secondary">' . ucfirst($order['status']) . '</span>';
                          }
                          echo $statusBadge;
                          ?>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                          <div class="btn-group-vertical btn-group-sm w-100" role="group">
                            <!-- ⭐ JIKA STATUS = PENDING: Tampilkan Approve & Reject -->
                            <?php if ($order['status'] === 'pending'): ?>
                              <button type="button"
                                class="btn btn-success mb-1"
                                onclick="approveOrder(<?= $order['id'] ?>)"
                                title="Approve Order">
                                <i data-feather="check" width="14"></i> Approve
                              </button>

                              <button type="button"
                                class="btn btn-danger"
                                onclick="rejectOrder(<?= $order['id'] ?>)"
                                title="Reject Order">
                                <i data-feather="x" width="14"></i> Reject
                              </button>
                            <?php endif; ?>

                            <!-- ⭐ JIKA STATUS = APPROVED & BELUM ADA RESI: Tampilkan Input Resi -->
                            <?php if ($order['status'] === 'approved' && empty($order['shipping_resi'])): ?>
                              <button type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#resiModal<?= $order['id'] ?>"
                                title="Input Tracking Number">
                                <i data-feather="truck" width="14"></i> Input Resi
                              </button>
                            <?php endif; ?>

                            <!-- ⭐ JIKA SUDAH ADA RESI: Tampilkan Status -->
                            <?php if ($order['status'] === 'approved' && !empty($order['shipping_resi'])): ?>
                              <button type="button"
                                class="btn btn-outline-info w-100"
                                disabled
                                title="Tracking number already sent">
                                <i data-feather="check-circle" width="14"></i> Resi Sent
                              </button>
                            <?php endif; ?>

                            <!-- ⭐ JIKA STATUS = REFUND: Tampilkan Info -->
                            <?php if ($order['status'] === 'refund'): ?>
                              <span class="badge bg-danger p-2 w-100">Refunded</span>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- Page Body Ends-->

<?php foreach ($orders as $order): ?>
  <!-- MODAL: View Items -->
  <div class="modal fade" id="itemsModal<?= $order['id'] ?>" tabindex="-1" aria-labelledby="itemsModalLabel<?= $order['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title" id="itemsModalLabel<?= $order['id'] ?>">
            <i data-feather="package" class="me-2"></i>
            Order Items - #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Product</th>
                  <th class="text-center" width="100">Qty</th>
                  <th class="text-end" width="150">Price</th>
                  <th class="text-end pe-4" width="150">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($order['items'] as $item): ?>
                  <tr>
                    <td class="ps-4">
                      <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                      <?php if (!empty($item['image'])): ?>
                        <br>
                        <small class="text-muted">
                          <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>"
                            class="img-thumbnail"
                            style="width: 50px; height: 50px; object-fit: cover;"
                            alt="<?= htmlspecialchars($item['product_name']) ?>">
                        </small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center"><?= $item['qty'] ?></td>
                    <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td class="text-end pe-4">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot class="table-light">
                <tr class="fw-bold">
                  <td colspan="3" class="text-end ps-4">TOTAL:</td>
                  <td class="text-end text-primary pe-4">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <!-- Order Summary -->
          <div class="p-4 border-top">
            <div class="row">
              <div class="col-md-6">
                <h6><i data-feather="user" class="me-2"></i> Customer Details</h6>
                <ul class="list-unstyled mb-0">
                  <li><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></li>
                  <li><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></li>
                  <li><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone'] ?? '-') ?></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6><i data-feather="info" class="me-2"></i> Order Details</h6>
                <ul class="list-unstyled mb-0">
                  <li><strong>Order Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></li>
                  <li><strong>Payment Method:</strong> <?= $order['payment_method'] === 'transfer' ? 'Bank Transfer' : 'QRIS' ?></li>
                  <li><strong>Payment Status:</strong>
                    <?php if ($order['payment_status'] === 'completed'): ?>
                      <span class="badge bg-success">Completed</span>
                    <?php else: ?>
                      <span class="badge bg-warning"><?= ucfirst($order['payment_status']) ?></span>
                    <?php endif; ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i data-feather="x" class="me-1"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: Input Resi -->
  <div class="modal fade" id="resiModal<?= $order['id'] ?>" tabindex="-1" aria-labelledby="resiModalLabel<?= $order['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="<?= BASE_URL ?>index.php?c=sellerApprove&m=inputResi" method="POST">
          <div class="modal-header bg-dark">
            <h5 class="modal-title" id="resiModalLabel<?= $order['id'] ?>">
              <i data-feather="truck" class="me-2"></i>
              Input Tracking Number
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <div class="mb-4">
              <div class="alert alert-info">
                <i data-feather="info" class="me-2"></i>
                <strong>Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                <br>
                <small>Customer: <?= htmlspecialchars($order['customer_name']) ?></small>
              </div>
            </div>

            <div class="mb-3">
              <label for="shipping_resi_<?= $order['id'] ?>" class="form-label fw-bold">
                Tracking Number <span class="text-danger">*</span>
              </label>
              <input type="text"
                class="form-control form-control-lg"
                id="shipping_resi_<?= $order['id'] ?>"
                name="shipping_resi"
                placeholder="Enter tracking number (e.g., ABC123456789)"
                required
                autofocus>
              <div class="form-text">
                Enter the shipping tracking number provided by your courier.
              </div>
            </div>

            <div class="alert alert-warning mb-0">
              <i data-feather="alert-triangle" class="me-2"></i>
              <small class="fw-bold">Note:</small>
              <small>After submitting, the shipping status will automatically change to "Shipped" and the customer will be notified.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i data-feather="x" class="me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <i data-feather="save" class="me-1"></i> Submit Tracking Number
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<script>
  // Initialize DataTable
  $(document).ready(function() {
    $('#order-table').DataTable({
      order: [
        [0, 'desc']
      ], // Sort by Order ID descending
      pageLength: 25,
      responsive: true,
      language: {
        search: "Search orders:",
        lengthMenu: "Show _MENU_ orders per page",
        info: "Showing _START_ to _END_ of _TOTAL_ orders",
        infoEmpty: "No orders available",
        infoFiltered: "(filtered from _MAX_ total orders)",
        zeroRecords: "No matching orders found",
        paginate: {
          first: "First",
          last: "Last",
          next: "Next",
          previous: "Previous"
        }
      },
      columnDefs: [{
          responsivePriority: 1,
          targets: 0
        }, // Order ID
        {
          responsivePriority: 2,
          targets: -1
        }, // Actions
        {
          responsivePriority: 3,
          targets: 3
        }, // Total
        {
          responsivePriority: 4,
          targets: 6
        }, // Status
        {
          orderable: false,
          targets: [2, -1]
        } // Disable sorting for Items and Actions
      ]
    });

    // Initialize Feather Icons
    feather.replace();
  });

  // Approve Order Function
  function approveOrder(orderId) {
    Swal.fire({
      title: 'Approve Order?',
      html: `Are you sure you want to approve Order <strong>#${String(orderId).padStart(6, '0')}</strong>?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i data-feather="check" class="me-1"></i> Yes, Approve',
      cancelButtonText: '<i data-feather="x" class="me-1"></i> Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>index.php?c=sellerApprove&m=approveOrder';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= Csrf::token() ?>';

        const orderInput = document.createElement('input');
        orderInput.type = 'hidden';
        orderInput.name = 'order_id';
        orderInput.value = orderId;

        form.appendChild(csrfToken);
        form.appendChild(orderInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Reject Order Function
  function rejectOrder(orderId) {
    Swal.fire({
      title: 'Reject Order?',
      html: `Are you sure you want to reject Order <strong>#${String(orderId).padStart(6, '0')}</strong>?<br><br>
             <small class="text-danger">This action cannot be undone and the customer will be notified.</small>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i data-feather="x" class="me-1"></i> Yes, Reject',
      cancelButtonText: '<i data-feather="x" class="me-1"></i> Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>index.php?c=sellerApprove&m=rejectOrder';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= Csrf::token() ?>';

        const orderInput = document.createElement('input');
        orderInput.type = 'hidden';
        orderInput.name = 'order_id';
        orderInput.value = orderId;

        form.appendChild(csrfToken);
        form.appendChild(orderInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Re-initialize Feather Icons after modal opens
  $('.modal').on('shown.bs.modal', function() {
    feather.replace();
  });
</script>