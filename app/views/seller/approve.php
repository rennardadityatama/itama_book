<!-- Page Body Start -->
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>Approve Orders</h3>
        </div>
        <div class="col-sm-6 text-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= BASE_URL ?>index.php?c=sellerDashboard&m=index"><i data-feather="home"></i></a>
            </li>
            <li class="breadcrumb-item active">Approve Orders</li>
          </ol>
        </div>
      </div>
    </div>

    <!-- Session Toast -->
    <?php if (!empty($_SESSION['toast'])): ?>
      <div class="alert alert-<?= $_SESSION['toast']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['toast']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <?php if (empty($orders)): ?>
              <div class="alert alert-info text-center"><i data-feather="info"></i> No orders found</div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered" id="order-table">
                  <thead class="table-light">
                    <tr>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Payment</th>
                      <th>Shipping</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($orders as $order): ?>
                      <tr>
                        <td>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td>
                          <strong><?= htmlspecialchars($order['customer_name']) ?></strong><br>
                          <small class="text-muted"><?= htmlspecialchars($order['customer_email']) ?></small><br>
                          <small class="text-muted"><?= htmlspecialchars($order['customer_phone'] ?? '-') ?></small>
                        </td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#itemsModal<?= $order['id'] ?>">
                            <?= count($order['items']) ?> item(s)
                          </button>
                        </td>
                        <td><strong class="text-success">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></td>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <!-- Badge -->
                            <?php
                            $paymentBadge = match ($order['payment_status']) {
                              'waiting_payment' => '<span class="badge bg-warning">Waiting</span>',
                              'completed' => '<span class="badge bg-success">Completed</span>',
                              default => '<span class="badge bg-secondary">' . ucfirst($order['payment_status']) . '</span>'
                            };
                            echo $paymentBadge;
                            ?>
                            <!-- View Proof Button -->
                            <?php if (!empty($order['payment_proof'])): ?>
                              <a href="<?= BASE_URL ?>index.php?c=sellerApprove&m=viewPaymentProof&order_id=<?= $order['id'] ?>"
                                target="_blank"
                                class="btn btn-outline-info btn-sm py-0 px-2">
                                View Proof
                              </a>
                            <?php endif; ?>
                            <!-- Payment Method -->
                            <small class="text-muted"><?= $order['payment_method'] === 'transfer' ? 'Bank Transfer' : 'QRIS' ?></small>
                          </div>
                        </td>
                        <td>
                          <?php
                          $shippingBadge = match ($order['shipping_status']) {
                            'pending' => '<span class="badge bg-warning">Pending</span>',
                            'shipped' => '<span class="badge bg-success">Shipped</span>',
                            default => '<span class="badge bg-secondary">' . ucfirst($order['shipping_status']) . '</span>'
                          };
                          echo $shippingBadge;
                          ?>
                          <?php if (!empty($order['shipping_resi'])): ?>
                            <br><small>Resi: </small><strong class="text-primary"><?= htmlspecialchars($order['shipping_resi']) ?></strong>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php
                          $statusBadge = match ($order['status']) {
                            'pending' => '<span class="badge bg-warning">Pending</span>',
                            'approved' => '<span class="badge bg-success">Approved</span>',
                            'refund' => '<span class="badge bg-danger">Refunded</span>',
                            default => '<span class="badge bg-secondary">' . ucfirst($order['status']) . '</span>'
                          };
                          echo $statusBadge;
                          ?>
                        </td>
                        <td>
                          <div class="btn-group-vertical btn-group-sm w-100">
                            <?php if ($order['status'] === 'pending'): ?>
                              <button class="btn btn-success mb-1" onclick="showModal(<?= $order['id'] ?>,'approve')">Approve</button>
                              <button class="btn btn-danger" onclick="showModal(<?= $order['id'] ?>,'reject')">Reject</button>
                            <?php elseif ($order['status'] === 'approved' && empty($order['shipping_resi'])): ?>
                              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resiModal<?= $order['id'] ?>">Input Resi</button>
                            <?php elseif (!empty($order['shipping_resi'])): ?>
                              <button class="btn btn-outline-info w-100" disabled>Resi Sent</button>
                            <?php elseif ($order['status'] === 'refund'): ?>
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
</div>

<!-- Modals for Items & Resi -->
<?php foreach ($orders as $order): ?>
  <!-- Items Modal -->
  <div class="modal fade" id="itemsModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?> Items</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered table-striped mb-0">
            <thead>
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($order['items'] as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['product_name']) ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                  <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Resi Modal -->
  <div class="modal fade" id="resiModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="POST" action="<?= BASE_URL ?>index.php?c=sellerApprove&m=inputResi">
          <div class="modal-header">
            <h5>Input Tracking Number</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <div class="mb-3">
              <label class="form-label">Tracking Number</label>
              <input type="text" name="shipping_resi" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tracking Link</label>
              <input type="text" name="tracking_link" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage">Are you sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="POST">
          <input type="hidden" name="order_id" id="deleteOrderId" value="">
          <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Yes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#order-table').DataTable({
      order: [
        [0, 'desc']
      ],
      pageLength: 25,
      responsive: true
    });
    feather.replace();
  });

  // Show modal for approve/reject
  function showModal(orderId, action) {
    const modalTitle = action === 'approve' ? 'Confirm Approve' : 'Confirm Reject';
    const modalMessage = action === 'approve' ?
      `Are you sure you want to approve Order #${String(orderId).padStart(6,'0')}?` :
      `Are you sure you want to reject Order #${String(orderId).padStart(6,'0')}?`;

    document.getElementById('deleteModalTitle').innerText = modalTitle;
    document.getElementById('deleteMessage').innerText = modalMessage;
    document.getElementById('deleteOrderId').value = orderId;

    const form = document.getElementById('deleteForm');
    form.action = action === 'approve' ?
      '<?= BASE_URL ?>index.php?c=sellerApprove&m=approveOrder' :
      '<?= BASE_URL ?>index.php?c=sellerApprove&m=rejectOrder';

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
  }

  function approveOrder(orderId) {
    if (confirm("Approve order #" + orderId + "?")) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= BASE_URL ?>index.php?c=sellerApprove&m=approveOrder';
      const input = document.createElement('input');
      input.name = 'order_id';
      input.value = orderId;
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }

  function rejectOrder(orderId) {
    if (confirm("Reject order #" + orderId + "?")) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= BASE_URL ?>index.php?c=sellerApprove&m=rejectOrder';
      const input = document.createElement('input');
      input.name = 'order_id';
      input.value = orderId;
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }
</script>