<!-- status.php -->
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>My Orders</h3>
        </div>
        <div class="col-sm-6 text-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= BASE_URL ?>index.php?c=customerDashboard&m=index"><i data-feather="home"></i></a>
            </li>
            <li class="breadcrumb-item active">Order Status</li>
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
                      <th>Seller</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Payment</th>
                      <th>Shipping</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($orders as $order): ?>
                      <tr>
                        <td>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td>
                          <strong><?= htmlspecialchars($order['seller_name'] ?? '-') ?></strong>
                          <?php if (!empty($order['seller_email']) || !empty($order['seller_phone'])): ?>
                            <br><small class="text-muted">
                              <?= htmlspecialchars($order['seller_email'] ?? '-') ?>
                              <?php if (!empty($order['seller_phone'])): ?> / <?= htmlspecialchars($order['seller_phone']) ?><?php endif; ?>
                            </small>
                          <?php endif; ?>
                        </td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#itemsModal<?= $order['id'] ?>">
                            <?= count($order['items']) ?> item(s)
                          </button>
                        </td>
                        <td><strong class="text-success">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></td>
                        <td>
                          <?php
                          $paymentClass = match ($order['payment_status']) {
                            'waiting_payment' => 'bg-warning',
                            'completed' => 'bg-success',
                            default => 'bg-secondary'
                          };
                          ?>
                          <div class="d-flex align-items-center gap-2">
                            <span class="badge <?= $paymentClass ?>"><?= ucfirst($order['payment_status']) ?></span>
                            <?php if (!empty($order['payment_proof'])): ?>
                              <a href="<?= BASE_URL ?>index.php?c=customerStatus&m=viewPaymentProof&order_id=<?= $order['id'] ?>" target="_blank" class="btn btn-outline-info btn-sm py-0 px-2">View Proof</a>
                            <?php endif; ?>
                            <small class="text-muted"><?= $order['payment_method'] === 'transfer' ? 'Bank Transfer' : 'QRIS' ?></small>
                          </div>
                        </td>
                        <td>
                          <?php
                          $shippingClass = match ($order['shipping_status']) {
                            'pending' => 'bg-warning',
                            'shipped' => 'bg-success',
                            default => 'bg-secondary'
                          };
                          ?>
                          <div>
                            <span class="badge <?= $shippingClass ?>"><?= ucfirst($order['shipping_status']) ?></span>
                            <?php if (!empty($order['shipping_resi'])): ?>
                              <br><small>Resi:</small> <strong class="text-primary"><?= htmlspecialchars($order['shipping_resi']) ?></strong>
                              <?php if (!empty($order['tracking_link'])): ?>
                                <br><a href="<?= htmlspecialchars($order['tracking_link']) ?>" target="_blank" class="btn btn-xxs btn-outline-primary mt-1">Track</a>
                              <?php endif; ?>
                            <?php endif; ?>
                          </div>
                        </td>
                        <td>
                          <?php
                          $statusClass = match ($order['status']) {
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'refund' => 'bg-danger',
                            default => 'bg-secondary'
                          };
                          ?>
                          <span class="badge <?= $statusClass ?>"><?= ucfirst($order['status']) ?></span>
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

<!-- Items Modal -->
<?php foreach ($orders as $order): ?>
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
<?php endforeach; ?>

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
</script>