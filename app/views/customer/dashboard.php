<div class="tap-top"><i data-feather="chevrons-up"></i></div>

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
      <div class="row">
        <div class="col-sm-6">
          <h3>Dashboard</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid dashboard-2">
    <div class="row">

      <div class="col-xl-4 col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center gap-2 pb-0">
            <i data-feather="shopping-cart" class="txt-primary"></i>
            <h5 class="mb-0">Order Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span><i class="fa fa-circle-o me-2 txt-warning"></i>Pending Payment</span>
                <span class="badge rounded-pill badge-primary">
                  <?= $summary['pending_payment'] ?? 0 ?>
                </span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span><i class="fa fa-circle-o me-2 txt-info"></i>In Progress</span>
                <span class="badge rounded-pill badge-primary">
                  <?= $summary['in_progress'] ?? 0 ?>
                </span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <span><i class="fa fa-circle-o me-2 txt-success"></i>Completed Orders</span>
                <span class="badge rounded-pill badge-primary">
                  <?= $summary['completed_orders'] ?? 0 ?>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center gap-2 pb-0">
            <i data-feather="clock" class="txt-warning"></i>
            <h5 class="mb-0">Recent Orders</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th class="text-end">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recent_orders as $order): ?>
                    <tr>
                      <td class="f-w-600">#INV-<?= $order['id'] ?></td>
                      <td class="text-end">
                        <span class="badge badge-light-info">
                          <?= ucfirst($order['status']) ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center gap-2 pb-0">
            <i data-feather="package" class="txt-success"></i>
            <h5 class="mb-0">Recently Bought</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th class="text-end">Price</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recent_products as $product): ?>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <img class="img-30 me-2"
                            src="<?= BASE_URL ?>/public/uploads/products/<?= $product['image'] ?>"
                            alt="">
                          <span><?= $product['name'] ?></span>
                        </div>
                      </td>
                      <td class="text-end">
                        <span class="badge badge-light-success">
                          Rp <?= number_format($product['price']) ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="card">
          <div class="card-header pb-0 d-flex justify-content-between">
            <h5>Active Shipment Tracking</h5>
            <i data-feather="truck" class="txt-primary"></i>
          </div>
          <div class="card-body">
            <div class="table-responsive theme-scrollbar">
              <table class="table table-bordernone">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Estimated Arrival</th>
                    <th>Courier</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($active_shipments as $ship): ?>
                    <tr>
                      <td>
                        <span class="f-w-600">#INV-<?= str_pad($ship['id'], 5, '0', STR_PAD_LEFT) ?></span>
                      </td>

                      <td>
                        <div class="d-flex align-items-center">
                          <img class="img-30 me-2"
                            src="/public/uploads/products/<?= $ship['product_image'] ?>"
                            alt="">
                          <span><?= $ship['product_name'] ?></span>
                        </div>
                      </td>

                      <td>
                        <?= date('d M Y', strtotime($ship['created_at'] . ' +3 days')) ?>
                      </td>

                      <td>
                        <?= $ship['shipping_resi'] ?: '-' ?>
                      </td>

                      <td>
                        <span class="badge badge-light-primary">
                          <?= ucfirst($ship['shipping_status']) ?>
                        </span>
                      </td>

                      <td>
                        <?php if ($ship['tracking_link']): ?>
                          <a href="<?= $ship['tracking_link'] ?>" target="_blank"
                            class="btn btn-primary btn-xs">Track</a>
                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>