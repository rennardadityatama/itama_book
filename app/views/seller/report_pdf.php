<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h3 {
            margin-bottom: 5px;
        }

        .row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            width: 100%;
        }

        .card h6 {
            margin: 0;
            font-size: 11px;
            color: #888;
        }

        .card h3 {
            margin: 5px 0 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
        }

        table th {
            background: #f5f5f5;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Sales Report</h3>
    <p>
        Period:
        <?= date('F', mktime(0, 0, 0, $month, 1)) ?>
        <?= $year ?>
    </p>

    <!-- SUMMARY CARDS -->
    <div class="row">
        <div class="card">
            <h6>Total Revenue</h6>
            <h3>Rp <?= number_format($summary['total_revenue'] ?? 0) ?></h3>
        </div>

        <div class="card">
            <h6>Total Orders</h6>
            <h3><?= $summary['total_orders'] ?? 0 ?></h3>
        </div>

        <div class="card">
            <h6>Avg Order Value</h6>
            <h3>Rp <?= number_format($summary['avg_order'] ?? 0) ?></h3>
        </div>
    </div>

    <!-- CHART IMAGE -->
    <div class="card">
        <h6>Sales Chart</h6>

        <?php if (!empty($chartImage)): ?>
            <div style="margin:20px 0;text-align:center;">
                <img src="<?= $chartImage ?>" style="width:100%;max-width:700px;">
            </div>
        <?php endif; ?>
    </div>

    <!-- REVENUE TABLE -->
    <div class="card" style="margin-top:15px">
        <h6>Revenue Report</h6>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($revenues)): ?>
                    <tr>
                        <td colspan="4">No data available</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($revenues as $row): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_amount']) ?></td>
                        <td><?= ucfirst($row['payment_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>