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
              <h3>Ecommerce</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Ecommerce</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- Container-fluid starts-->
      <div class="container-fluid dashboard-2">
        <div class="row">
          <div class="col-xl-9 col-md-12 box-col-70 xl-70">
            <div class="row">
              <!-- Card 1: Total Revenue -->
              <div class="col-lg-4 col-md-6 box-col-4">
                <div class="card profit-card">
                  <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                      <div class="flex-grow-1">
                        <p class="square-after f-w-600 header-text-primary">Total Revenue<i class="fa fa-circle"></i></p>
                        <h4>Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h4>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <div class="flex-grow-1">
                        <p class="mb-0">Total revenue from sales</p>
                      </div>
                    </div>
                    <div class="right-side icon-right-primary">
                      <i class="fa fa-usd"></i>
                      <div class="shap-block">
                        <div class="rounded-shap animate-bg-primary"><i></i><i></i></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Card 2: Total Products -->
              <div class="col-lg-4 col-md-6 box-col-4">
                <div class="card visitor-card">
                  <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                      <div class="flex-grow-1">
                        <p class="square-after f-w-600 header-text-info">Total Products<i class="fa fa-circle"></i></p>
                        <h4><?= $totalProducts ?></h4>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <div class="flex-grow-1">
                        <p class="mb-0">Active products in your store</p>
                      </div>
                    </div>
                    <div class="right-side icon-right-info">
                      <i class="fa fa-cube"></i>
                      <div class="shap-block">
                        <div class="rounded-shap animate-bg-primary"><i></i><i></i></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Card 3: Total Sold -->
              <div class="col-lg-4 col-md-12 box-col-4">
                <div class="card sell-card">
                  <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                      <div class="flex-grow-1">
                        <p class="square-after f-w-600 header-text-success">Total Sold<i class="fa fa-circle"></i></p>
                        <h4><?= $totalSold ?></h4>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <div class="flex-grow-1">
                        <p class="mb-0">Total products sold</p>
                      </div>
                    </div>
                    <div class="right-side icon-right-success">
                      <i class="fa fa-shopping-basket"></i>
                      <div class="shap-block">
                        <div class="rounded-shap animate-bg-secondary"><i></i><i></i></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Table: Best Selling Products -->
          <div class="col-xl-3 col-lg-5 col-md-6 box-col-30 xl-30">
            <div class="card product">
              <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                  <div class="flex-grow-1">
                    <p class="square-after f-w-600">Best Selling Products<i class="fa fa-circle"></i></p>
                    <h4>Top 5 AMBON</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive theme-scrollbar">
                  <table class="table">
                    <tbody>
                      <?php if (empty($bestSellingProducts)): ?>
                        <tr>
                          <td colspan="3" class="text-center">No products sold yet</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($bestSellingProducts as $product): ?>
                          <tr>
                            <td>
                              <div class="d-flex align-items-center">
                                <img class="img-fluid circle"
                                  src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?: 'default.png' ?>"
                                  alt="<?= htmlspecialchars($product['name']) ?>"
                                  style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="flex-grow-1 ms-2">
                                  <span><?= htmlspecialchars($product['name']) ?></span>
                                  <p class="mb-0"><?= $product['total_sold'] ?> sold</p>
                                </div>
                              </div>
                            </td>
                            <td><span>Rp <?= number_format($product['price'], 0, ',', '.') ?></span></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
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