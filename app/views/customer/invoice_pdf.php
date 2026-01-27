<?php
$order = $order ?? [];
$orderItems = $orderItems ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #<?= str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .card { border:1px solid #ddd; border-radius:5px; padding:15px; margin-bottom:20px; }
        .card-header { font-weight:bold; margin-bottom:10px; color:#0d6efd; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        table th, table td { border:1px solid #ddd; padding:5px; text-align:left; }
        table th { background-color:#f8f9fa; }
        .text-end { text-align:right; }
        .text-center { text-align:center; }
        .text-primary { color:#0d6efd; }
        .badge { padding:4px 8px; border-radius:5px; color:#fff; font-size:12px; }
        .badge-success { background-color:#198754; }
        .badge-warning { background-color:#ffc107; }
        .badge-secondary { background-color:#6c757d; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">INVOICE #<?= str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></div>
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <div>
                <strong>Customer:</strong><br>
                Name          :<?= htmlspecialchars($order['customer_name'] ?? '-') ?><br>
                Email         :<?= htmlspecialchars($order['customer_email'] ?? '-') ?><br>
                Phone Number  :<?= htmlspecialchars($order['customer_phone'] ?? '-') ?><br>
                Address       :<?= htmlspecialchars($order['customer_address'] ?? '-') ?>
            </div>
            <div>
                <strong>Seller:</strong><br>
                <?= htmlspecialchars($order['seller_name'] ?? '-') ?><br>
                <?= htmlspecialchars($order['seller_email'] ?? '-') ?><br>
                <?= htmlspecialchars($order['seller_phone'] ?? '-') ?>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <div>
                <strong>Payment:</strong><br>
                <?= ($order['payment_method'] ?? '-') === 'transfer' ? 'Bank Transfer' : 'QRIS' ?><br>
                <span class="badge 
                    <?= ($order['payment_status'] ?? '')=='completed'?'badge-success':(($order['payment_status'] ?? '')=='failed'?'badge-warning':'badge-secondary') ?>">
                    <?= ucfirst($order['payment_status'] ?? '-') ?>
                </span>
            </div>
            <div>
                <strong>Shipping:</strong><br>
                <span class="badge 
                    <?= ($order['shipping_status'] ?? '')=='shipped'?'badge-success':(($order['shipping_status'] ?? '')=='pending'?'badge-warning':'badge-secondary') ?>">
                    <?= ucfirst($order['shipping_status'] ?? '-') ?>
                </span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Item Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($orderItems as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($item['product_name'] ?? '-') ?></td>
                    <td class="text-center"><?= $item['qty'] ?? 0 ?></td>
                    <td class="text-end">Rp <?= number_format($item['price'] ?? 0,0,',','.') ?></td>
                    <td class="text-end">Rp <?= number_format($item['subtotal'] ?? 0,0,',','.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td class="text-end text-primary"><strong>Rp <?= number_format($order['total_amount'] ?? 0,0,',','.') ?></strong></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>

</body>
</html>
