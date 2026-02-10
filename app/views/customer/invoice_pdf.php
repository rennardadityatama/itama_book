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
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #555;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Header Section */
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border: none;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }

        .order-id {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }

        .status-pending {
            background-color: #f8d62b;
            color: #fff;
            padding: 5px 15px;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            float: right;
        }

        /* Grid Information Boxes */
        .info-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #fbfbfb;
            border-radius: 12px;
            padding: 15px;
            width: 48%;
            vertical-align: top;
            border: 1px solid #f0f0f0;
        }

        .info-title {
            color: #0d6efd;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            display: block;
        }

        .info-title img {
            width: 14px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .info-content {
            line-height: 1.6;
            color: #666;
        }

        /* Table Items */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .item-table th {
            background-color: #f8f9fa;
            color: #888;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .item-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            background-color: #fff;
        }

        .item-table tr:last-child td {
            border-bottom: none;
        }

        /* Typography & Helper */
        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .f-w-700 {
            font-weight: 700;
        }

        .total-row {
            font-size: 14px;
            color: #333;
        }

        .grand-total {
            font-size: 18px;
            color: #0d6efd;
            font-weight: bold;
        }

        .badge {
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 10px;
            color: #fff;
            font-weight: bold;
            display: inline-block;
        }

        .bg-paid {
            background-color: #51bb25;
        }

        .bg-pending {
            background-color: #f8d62b;
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <div class="invoice-title">INVOICE</div>
                    <div class="order-id">Order #<?= str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></div>
                </td>
                <td class="text-end">
                    <span class="status-pending">Pending</span>
                </td>
            </tr>
        </table>

        <table class="info-container" cellspacing="0" cellpadding="0">
            <tr>
                <td class="info-box">
                    <span class="info-title">👤 Customer Information</span>
                    <div class="info-content">
                        <strong style="color:#333;"><?= htmlspecialchars($order['customer_name'] ?? '-') ?></strong><br>
                        ✉️ <?= htmlspecialchars($order['customer_email'] ?? '-') ?><br>
                        📞 <?= htmlspecialchars($order['customer_phone'] ?? '-') ?><br>
                        📍 <?= htmlspecialchars($order['customer_address'] ?? '-') ?>
                    </div>
                </td>
                <td width="4%"></td>
                <td class="info-box">
                    <span class="info-title">🛒 Seller Information</span>
                    <div class="info-content">
                        <strong style="color:#333;"><?= htmlspecialchars($order['seller_name'] ?? 'Admin Store') ?></strong><br>
                        ✉️ <?= htmlspecialchars($order['seller_email'] ?? '-') ?><br>
                        📞 <?= htmlspecialchars($order['seller_phone'] ?? '-') ?>
                    </div>
                </td>
            </tr>
        </table>

        <table class="info-container" cellspacing="0" cellpadding="0" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td class="info-box" style="width: 48%; background-color: #fbfbfb; border: 1px solid #f0f0f0; border-radius: 12px; padding: 15px; vertical-align: top;">
                    <span class="info-title" style="color: #0d6efd; font-weight: bold; font-size: 12px; margin-bottom: 10px; display: block;">
                        <span style="font-family: DejaVu Sans, sans-serif;">&#128179;</span> Payment Information
                    </span>
                    <div class="info-content" style="line-height: 1.6; color: #666;">
                        <table width="100%" style="border:none;">
                            <tr>
                                <td width="50%" style="border:none; padding:0; font-size: 11px;">Payment Method:</td>
                                <td style="border:none; padding:0; font-size: 11px;"><span class="f-w-700" style="color:#333;"><?= ($order['payment_method'] ?? '-') === 'transfer' ? 'Bank Transfer' : 'QRIS' ?></span></td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:5px 0 0 0; font-size: 11px;">Payment Status:</td>
                                <td style="border:none; padding:5px 0 0 0;">
                                    <span style="background-color: #e8f5e9; color: #2e7d32; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 9px;">
                                        <span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span> PAID
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <div style="margin-top:12px;">
                            <a href="#" style="color:#0d6efd; text-decoration:none; border: 1px solid #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 10px; display: inline-block;">
                                <span style="font-family: DejaVu Sans, sans-serif;">&#128443;</span> View Payment Proof
                            </a>
                        </div>
                    </div>
                </td>
                <td width="4%" style="border:none;"></td>
                <td class="info-box" style="width: 48%; background-color: #fbfbfb; border: 1px solid #f0f0f0; border-radius: 12px; padding: 15px; vertical-align: top;">
                    <span class="info-title" style="color: #0d6efd; font-weight: bold; font-size: 12px; margin-bottom: 10px; display: block;">
                        <span style="font-family: DejaVu Sans, sans-serif;">&#128666;</span> Shipping Information
                    </span>
                    <div class="info-content" style="line-height: 1.6; color: #666;">
                        <table width="100%" style="border:none;">
                            <tr>
                                <td width="50%" style="border:none; padding:0; font-size: 11px;">Shipping Status:</td>
                                <td style="border:none; padding:0;">
                                    <span style="background-color: #fff8e1; color: #f57f17; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 9px;">
                                        <span style="font-family: DejaVu Sans, sans-serif;">&#9203;</span> <?= strtoupper($order['shipping_status'] ?? 'PENDING') ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:5px 0 0 0; font-size: 11px;">Courier:</td>
                                <td style="border:none; padding:5px 0 0 0; font-size: 11px; color:#333;" class="f-w-700">Standard Shipping</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th width="40" class="text-center">#</th>
                    <th>Item Description</th>
                    <th width="80" class="text-center">Quantity</th>
                    <th width="100" class="text-end">Price</th>
                    <th width="100" class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($orderItems as $item): ?>
                    <tr>
                        <td class="text-center text-muted"><?= $no++ ?></td>
                        <td class="f-w-700 text-dark"><?= htmlspecialchars($item['product_name'] ?? '-') ?></td>
                        <td class="text-center"><?= $item['qty'] ?? 0 ?></td>
                        <td class="text-end">Rp <?= number_format($item['price'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-end f-w-700">Rp <?= number_format($item['subtotal'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table width="100%" style="margin-top: 20px;">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    <table width="100%">
                        <tr class="total-row">
                            <td class="text-end" style="padding: 10px 0;">TOTAL:</td>
                            <td class="text-end grand-total" style="padding: 10px 0;">Rp <?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>