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
            <h3>Dashboard</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php?c=admin&m=dashboard"><i data-feather="home"></i></a></li>
              <li class="breadcrumb-item">Dashboard</li>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default">
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
          <div class="card o-hidden small-widget border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <span class="text-muted small fw-semibold d-block mb-1">Total Orders</span>
                  <h3 class="mb-0 fw-bold"><?= number_format($summary['total_orders']) ?></h3>
                </div>
                <div class="bg-primary-subtle p-3 rounded-circle">
                  <i data-feather="shopping-cart" class="text-primary" style="width: 22px; height: 22px;"></i>
                </div>
              </div>
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card o-hidden small-widget border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <span class="text-muted small fw-semibold d-block mb-1">Total Revenue</span>
                  <h3 class="mb-0 fw-bold">Rp <?= number_format($summary['total_revenue']) ?></h3>
                </div>
                <div class="bg-success-subtle p-3 rounded-circle">
                  <i data-feather="dollar-sign" class="text-success" style="width: 22px; height: 22px;"></i>
                </div>
              </div>
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card o-hidden small-widget border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <span class="text-muted small fw-semibold d-block mb-1">Customers</span>
                  <h3 class="mb-0 fw-bold"><?= $summary['total_customers'] ?></h3>
                </div>
                <div class="bg-info-subtle p-3 rounded-circle">
                  <i data-feather="users" class="text-info" style="width: 22px; height: 22px;"></i>
                </div>
              </div>
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card o-hidden small-widget border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <span class="text-muted small fw-semibold d-block mb-1">Products</span>
                  <h3 class="mb-0 fw-bold"><?= $summary['total_products'] ?></h3>
                </div>
                <div class="bg-warning-subtle p-3 rounded-circle">
                  <i data-feather="package" class="text-warning" style="width: 22px; height: 22px;"></i>
                </div>
              </div>
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- TOP CATEGORIES -->
        <div class="col-xl-4 col-lg-6">
          <div class="card">
            <div class="card-header d-flex align-items-center gap-2 pb-0">
              <i data-feather="layers" class="txt-primary"></i>
              <h5 class="mb-0">Top Categories</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php foreach ($top_categories as $cat): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= $cat['category'] ?></span>
                    <span class="badge rounded-pill badge-primary">
                      <?= $cat['total_sold'] ?>
                    </span>
                  </li>
                <?php endforeach ?>
              </ul>
            </div>
          </div>
        </div>

        <!-- LOW STOCK -->
        <div class="col-xl-4 col-lg-6">
          <div class="card">
            <div class="card-header d-flex align-items-center gap-2 pb-0">
              <i data-feather="alert-triangle" class="txt-warning"></i>
              <h5 class="mb-0">Low Stock</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th class="text-end">Stock</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($low_stock as $p): ?>
                      <tr>
                        <td><?= $p['name'] ?></td>
                        <td class="text-end">
                          <span class="badge badge-light-danger">
                            <?= $p['stock'] ?>
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

        <!-- BEST SELLING -->
        <div class="col-xl-4 col-lg-12">
          <div class="card">
            <div class="card-header d-flex align-items-center gap-2 pb-0">
              <i data-feather="trending-up" class="txt-success"></i>
              <h5 class="mb-0">Best Selling</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th class="text-end">Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($best_products as $p): ?>
                      <tr>
                        <td><?= $p['name'] ?></td>
                        <td class="text-end">
                          <span class="badge badge-light-success">
                            <?= $p['total_sold'] ?>
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
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>

  <script>
    feather.replace()
  </script>