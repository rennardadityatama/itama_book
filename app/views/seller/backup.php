<script>
  window.chartData = <?= json_encode($chartData) ?>;
  const PRODUCT_BASE_URL = '<?= BASE_URL ?>/index.php?c=sellerReports';
</script>
<script src="<?= BASE_URL ?>/assets/js/reports.js"></script>

  <!-- CHART -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">Sales Chart</h5>
            <small class="text-muted">
              Sales summary for <?= date('F', mktime(0, 0, 0, $month, 1)) ?> <?= $year ?>
            </small>
          </div>
          <div class="card-body">
            <canvas id="myGraph" width="1000" height="300"></canvas>
          </div>
        </div>

        