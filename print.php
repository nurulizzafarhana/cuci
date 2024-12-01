<?php

include "koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

//mengambil data detail_penjualan dan penjualan



$queryDetail = mysqli_query($koneksi, "
    SELECT 
        trans_order.id, 
        trans_order.order_code, 
        type_of_service.service_name, 
        type_of_service.price, 
        customer.customer_name, 
        trans_order_detail.*, 
        trans_laundry_pickup.pickup_pay, 
        trans_laundry_pickup.pickup_change, 
        trans_laundry_pickup.pickup_date,
        trans_laundry_pickup.discount,
        trans_laundry_pickup.final_total
    FROM trans_order_detail
    LEFT JOIN trans_order 
        ON trans_order.id = trans_order_detail.id_order
    LEFT JOIN type_of_service 
        ON type_of_service.id = trans_order_detail.id_service
    LEFT JOIN customer 
        ON customer.id = trans_order.id_customer
    LEFT JOIN trans_laundry_pickup 
        ON trans_laundry_pickup.id_order = trans_order.id
    WHERE trans_order_detail.id_order = '$id'
");

$row = [];
while ($rowDetail = mysqli_fetch_assoc($queryDetail)) {
    $row[] = $rowDetail;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Doto:wght@100..900&display=swap');
        body {
            margin: 20px;
            font-family: "Doto", sans-serif;
        }

        .struk {
            width: 80mm;
            max-width: 100%;
            border: 1px solid #000;
            padding: 10px;
            margin: 0 auto;
        }

        .struk-header,
        .struk-footer{
            text-align: center;
            margin-bottom: 10px;
        }

        .struk-header h1{
            font-size: 30px;
            font-weight: 800;
            margin: 0;
        }

        .struk-header p{
            font-size: 10px;
            font-weight: 800;
            margin: 0;
        }

        .struk-body {
            margin-bottom: 10px;
        }

        .struk-body table {
            border-collapse: collapse;
            width: 100%;
        }

        .struk-body table th,
        .struk-body table td {
            padding: 5px;
            text-align: left;
        }

        .struk-body table th {
            border-bottom: 1px solid #000;
        }

        .total,
        .payment,
        .change,
        .disc,
        .final_total {
            display: flex;
            justify-content: space-evenly;
            padding: 5px 0;
            font-weight: 750;
        }

        .total {
            margin-top: 10px;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .struk {
                width: auto;
                border: none;
                margin: 0;
                padding: 0;
            }

            .struk-header h1,
            .struk-footer {
                font-size: 14px;
            }

            .struk-body table th,
            .struk-body table td {
                padding: 2px;
            }

            .total,
            .payment,
            .change,
            .disc,
            .final_total {
                padding: 2px 0;
            }
        }

    </style>

    <title>Cetak Transaksi: </title>
</head>
<body>

    <div class="struk">
        <div class="struk-header">
            <h1>Laundry Ni</h1>
            <p>Jl. Together ♥</p>
            <p>Tel: 000000000000</p>
        </div>

        <div class="struk-body">
            <div class="container-sm">
                <p>Invoice No: <?php echo $row[0]['order_code']; ?></p>
                <p>Tanggal Pengambilan Laundry <?php echo $row[0]['pickup_date']; ?></p>
                <p>Nama Customer: <?php echo $row[0]['customer_name'] ?></p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($row as $key => $rowDetail): ?>
                    <tr>
                        <td><?php echo $rowDetail['service_name'] ?></td>
                        <td><?php echo $rowDetail['qty'] ?></td>
                        <td><?php echo "Rp " . number_format($rowDetail['price']) ?></td>
                        <td><?php echo "Rp " . number_format($rowDetail['subtotal']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <div class="total">
                <span>Total: </span>
                <span><?php echo "Rp " . number_format($row[0]['subtotal']) ?></span>
            </div>

            <div class="disc">
                <span>Diskon: </span>
                <span><?php echo number_format($row[0]['discount']) . "%" ?></span>
            </div>

            <div class="final_total">
                <span>Total Akhir: </span>
                <span><?php echo "Rp" . number_format($row[0]['final_total']) ?></span>
            </div>

            <div class="payment">
                <span>Bayar: </span>
                <span><?php echo "Rp " . number_format($row[0]['pickup_pay']) ?></span>
            </div>

            <div class="change">
                <span>Kembalian: </span>
                <span><?php echo "Rp " . number_format($row[0]['pickup_change']) ?></span>
            </div>
        </div>

        <div class="struk-footer">
            <p>Thx for coming!</p>
            <p>Have a great day!</p>
            <p>♥</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>