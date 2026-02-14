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
          <h3>Order History</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Reports</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <form
          id="exportPdfForm"
          method="POST"
          action="<?= BASE_URL ?>index.php?c=sellerReports&m=exportPdf"
          target="_blank">
          <input type="hidden" name="month" value="<?= $month ?>">
          <input type="hidden" name="year" value="<?= $year ?>">
          <input type="hidden" name="chart_image" id="chartImage">
        </form>

        <form
          method="GET"
          action="<?= BASE_URL ?>index.php"
          class="row g-2 mb-4 align-items-end">
          <input type="hidden" name="c" value="sellerReports">
          <input type="hidden" name="m" value="index">

          <div class="col-md-3">
            <label class="form-label">Month</label>
            <select name="month" class="form-control">
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                  <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Year</label>
            <select name="year" class="form-control">
              <?php for ($y = date('Y'); $y >= 2022; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                  <?= $y ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="col-md-4">
            <button type="submit" class="btn btn-primary me-2">
              <i data-feather="filter"></i> Filter
            </button>
          </div>
        </form>

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <h6 class="text-white-50">Total Spending</h6>
                <h3 class="mb-0 text-white">
                  Rp <?= number_format($summary['total_revenue'] ?? 0) ?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h6 class="text-muted">Orders Placed</h6>
                <h3 class="mb-0">
                  <?= $summary['total_orders'] ?? 0 ?> <small style="font-size: 14px;">Transactions</small>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h6 class="text-muted">Items Purchased</h6>
                <h3 class="mb-0">
                  <?= count($orders) ?> <small style="font-size: 14px;">Product Items</small>
                </h3>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLE -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Purchase Details</h5>
            <span class="badge badge-light-primary text-dark">History of <?= date('F Y', mktime(0, 0, 0, $month, 1)) ?></span>
          </div>
          <div class="card-body table-responsive theme-scrollbar">
            <table class="table table-hover">
              <thead class="bg-light">
                <tr>
                  <th>Date</th>
                  <th>Order ID</th>
                  <th>Total Payment</th>
                  <th>Payment</th>
                  <th>Order Status</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($orders)): ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                      <i data-feather="shopping-bag" class="mb-2"></i>
                      <p>You haven't made any purchases this month.</p>
                    </td>
                  </tr>
                <?php endif; ?>

                <?php foreach ($orders as $row): ?>
                  <tr>
                    <td class="f-w-600"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    <td><span class="text-muted">#ORD-<?= $row['id'] ?? '000' ?></span></td>
                    <td class="f-w-700 text-primary">Rp <?= number_format($row['total_amount']) ?></td>
                    <td>
                      <span class="badge rounded-pill border border-success text-success">
                        <?= ucfirst($row['payment_status']) ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-light-primary text-primary">
                        <?= ucfirst($row['order_status'] ?? 'Completed') ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <a href="#" class="btn btn-xs btn-outline-light text-dark border">Details</a>
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
  <!-- Container-fluid Ends  -->
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    const options = {
      chart: {
        type: 'area',
        height: 300,
        toolbar: {
          show: false
        },
        fontFamily: 'inherit'
      },
      colors: ['#7366FF'],
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 3
      },
      series: [{
        name: 'Revenue',
        data: <?= json_encode(array_values($chartData['totals'] ?? [])) ?>
      }],
      xaxis: {
        categories: <?= json_encode(array_values($chartData['dates'] ?? [])) ?>,
        labels: {
          style: {
            colors: '#999'
          }
        }
      },
      yaxis: {
        labels: {
          formatter: function(val) {
            return 'Rp ' + val.toLocaleString();
          }
        }
      },
      grid: {
        borderColor: '#f1f1f1'
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          opacityTo: 0.05,
          stops: [0, 90, 100]
        }
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return 'Rp ' + val.toLocaleString();
          }
        }
      }
    };

    const chart = new ApexCharts(
      document.querySelector("#salesChart"),
      options
    );

    chart.render();

    // simpan chart instance buat export
    window.salesChart = chart;
  });
</script>