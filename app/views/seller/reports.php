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

            <button type="button" class="btn btn-danger" onclick="exportPdfWithChart()">
              <i data-feather="file-text"></i> Export PDF
            </button>
          </div>
        </form>

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h6 class="text-muted">Total Revenue</h6>
                <h3 class="mb-0">
                  Rp <?= number_format($summary['total_revenue'] ?? 0) ?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h6 class="text-muted">Total Orders</h6>
                <h3 class="mb-0">
                  <?= $summary['total_orders'] ?? 0 ?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h6 class="text-muted">Avg Order Value</h6>
                <h3 class="mb-0">
                  Rp <?= number_format($summary['avg_order'] ?? 0) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>

        <!-- CHART -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">Sales Chart</h5>
            <small class="text-muted">
              Sales summary for <?= date('F', mktime(0, 0, 0, $month, 1)) ?> <?= $year ?>
            </small>
          </div>
          <div class="card-body">
            <canvas id="myGraph" height="100"></canvas>
          </div>
        </div>

        <!-- TABLE -->
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Revenue Report</h5>
          </div>
          <div class="card-body table-responsive theme-scrollbar">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Payment Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($revenues)): ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted">
                      No data available
                    </td>
                  </tr>
                <?php endif; ?>

                <?php foreach ($revenues as $row): ?>
                  <tr>
                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td>Rp <?= number_format($row['total_amount']) ?></td>
                    <td>
                      <span class="badge bg-success">
                        <?= ucfirst($row['payment_status']) ?>
                      </span>
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
  function exportPdfWithChart() {
    const canvas = document.getElementById('myGraph');
    if (!canvas) {
      alert('Chart not ready');
      return;
    }
    const image = canvas.toDataURL('image/png');
    document.getElementById('chartImage').value = image;
    document.getElementById('exportPdfForm').submit();
  }
</script>