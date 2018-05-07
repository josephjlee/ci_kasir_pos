<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" />
</head>
<body onload="window.print()">
<div class="jumbotron" style="background:whitesmoke;">
    <h1 class="text-center">Detail Transaksi</h1>
    <div class="container">
        <?php $order = $this->session->userdata('order'); ?>
        <?php if($order['item']) : ?>
            <p>Id Order : <?=$order['id_order']?></p> 
            <p>Tanggal Order :<?=date('d - m - Y')?></p>
            <table class="table" style="width:100%">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['item'] as $val) : ?>
                <tr>
                    <td><?=$val['kode']?> / <?=$val['nama_roti']?></td>
                    <td><?=$val['qty']?></td>
                    <td>Rp <?=format_uang($val['harga'])?>,-</td>
                    <td>Rp <?=format_uang($val['total'])?>,-</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">&nbsp;
                    </th>
                    <th>Total</th>
                    <th>Rp <?=format_uang($order['total'])?>,-</th>
                </tr>
            </tfoot>
            </table>
        <?php else: ?>
            <p class="text-center">Data Kosong</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>