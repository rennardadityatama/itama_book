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
              <h3 class="f-w-700">Seller Dashboard</h3>
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
          <div class="col-xl-8 col-lg-12">
            <div class="row">
              <div class="col-md-4">
                <div class="card profit-card shadow-sm border-0">
                  <div class="card-header pb-0 border-0 bg-transparent">
                    <p class="f-w-600 text-muted mb-1">Total Revenue</p>
                    <h4 class="f-w-700 text-primary">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h4>
                  </div>
                  <div class="card-body pt-2">
                    <div class="right-side icon-right-primary shadow-sm" style="opacity: 0.8;">
                      <i class="fa fa-usd"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card visitor-card shadow-sm border-0">
                  <div class="card-header pb-0 border-0 bg-transparent">
                    <p class="f-w-600 text-muted mb-1">Total Products</p>
                    <h4 class="f-w-700 text-info"><?= $totalProducts ?></h4>
                  </div>
                  <div class="card-body pt-2">
                    <div class="right-side icon-right-info shadow-sm" style="opacity: 0.8;">
                      <i class="fa fa-cube"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card sell-card shadow-sm border-0">
                  <div class="card-header pb-0 border-0 bg-transparent">
                    <p class="f-w-600 text-muted mb-1">Total Sold</p>
                    <h4 class="f-w-700 text-success"><?= $totalSold ?></h4>
                  </div>
                  <div class="card-body pt-2">
                    <div class="right-side icon-right-success shadow-sm" style="opacity: 0.8;">
                      <i class="fa fa-shopping-basket"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
              <div class="card-header pb-0 border-0 bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="f-w-700 mb-0">Recent Transactions</h5>
                <span class="badge badge-light-primary text-primary">Last 5 Orders</span>
              </div>
              <div class="card-body pt-3">
                <div class="table-responsive theme-scrollbar">
                  <table class="table table-hover">
                    <thead class="bg-light">
                      <tr>
                        <th class="border-0 f-w-600 py-3">ID</th>
                        <th class="border-0 f-w-600 py-3">Customer</th>
                        <th class="border-0 f-w-600 py-3">Date</th>
                        <th class="border-0 f-w-600 py-3 text-end">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($recentOrders)): ?>
                        <tr>
                          <td colspan="4" class="text-center py-4 text-muted">No recent orders found.</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                          <tr style="transition: all 0.3s ease;">
                            <td class="f-w-600 text-primary">#<?= $order['id'] ?></td>
                            <td>
                              <div class="f-w-600"><?= htmlspecialchars($order['customer_name']) ?></div>
                              <small class="text-muted">Verified</small>
                            </td>
                            <td class="text-muted"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="text-end f-w-700 text-dark">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
              <div class="card-header pb-2 border-0 bg-transparent">
                <h5 class="f-w-700 mb-0">Selling Product</h5>
                <p class="text-muted f-12">Product</p>
              </div>
              <div class="card-body pt-0">
                <div class="table-responsive theme-scrollbar">
                  <table class="table table-bordernone mb-0">
                    <tbody>
                      <?php if (empty($bestSellingProducts)): ?>
                        <tr>
                          <td class="text-center p-4 text-muted">No sales yet</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($bestSellingProducts as $product): ?>
                          <tr class="border-bottom-light">
                            <td class="py-3 px-3">
                              <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="width: 45px; height: 45px; background-color: #f4f4f4; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #eee;">
                                  <img class="img-fluid"
                                    src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?: 'default.png' ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                                </div>

                                <div class="flex-grow-1 ms-3">
                                  <div class="f-14 f-w-700 text-dark"><?= htmlspecialchars($product['name']) ?></div>
                                  <span class="text-muted f-12"><?= $product['total_sold'] ?> item</span>
                                </div>
                              </div>
                            </td>
                            <td class="text-end py-3 px-3">
                              <span class="f-w-700 f-14 text-dark">Rp.<?= number_format($product['price'], 2) ?></span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card shadow-sm border-0 h-auto" style="border-radius: 15px; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
              <div class="card-header pb-0 border-0 bg-transparent text-center">
                <h5 class="f-w-700 mb-0 text-dark">Revenue Insight</h5>
                <p class="text-muted f-12">Financial Distribution</p>
              </div>
              <div class="card-body p-2">
                <div id="order-donut-chart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      window.FINANCE_CHART = {
        revenue: <?= (float)($financeSummary['revenue'] ?? 0) ?>,
        cost: <?= (float)($financeSummary['cost'] ?? 0) ?>,
        margin: <?= (float)($financeSummary['margin'] ?? 0) ?>
      };

      document.addEventListener("DOMContentLoaded", function() {
        if (!window.FINANCE_CHART || !document.querySelector("#order-donut-chart")) return;

        const {
          revenue,
          cost,
          margin
        } = window.FINANCE_CHART;

        const options = {
          chart: {
            type: "donut",
            height: 320,
            animations: {
              enabled: true,
              easing: 'easeinout',
              speed: 800
            }
          },
          series: [revenue, cost, margin],
          labels: ["Revenue", "Cost", "Margin"],
          colors: ["#7366ff", "#f8d62b", "#51bb25"],
          stroke: {
            width: 2,
            colors: ["#fff"]
          },
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'light',
              type: "vertical",
              opacityFrom: 1,
              opacityTo: 0.8,
            }
          },
          legend: {
            position: "bottom",
            fontSize: '13px',
            fontFamily: 'Rubik, sans-serif',
            fontWeight: 500,
            markers: {
              radius: 12
            }
          },
          dataLabels: {
            enabled: false
          },
          plotOptions: {
            pie: {
              donut: {
                size: "75%",
                labels: {
                  show: true,
                  name: {
                    show: true,
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#888'
                  },
                  value: {
                    show: true,
                    fontSize: '18px',
                    fontWeight: 700,
                    color: '#333'
                  },
                  total: {
                    show: true,
                    label: "Total Sales",
                    color: '#888',
                    formatter: function() {
                      return "Rp " + (revenue / 1000000).toFixed(1) + "M";
                    }
                  }
                }
              }
            }
          }
        };

        const chart = new ApexCharts(document.querySelector("#order-donut-chart"), options);
        chart.render();
      });
    </script>