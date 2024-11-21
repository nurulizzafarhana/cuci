<?php
session_start();
include 'koneksi.php';

// Fetch customers and services


$id = isset($_GET['detail']) ? $_GET['detail'] : '';
$queryTransDetail = mysqli_query($koneksi, "SELECT trans_order.order_code, trans_order.order_date, trans_order.order_status, type_of_service.service_name, type_of_service.price, trans_order_detail.* FROM trans_order_detail LEFT JOIN type_of_service ON type_of_service.id = trans_order_detail.id_service LEFT JOIN trans_order ON trans_order.id = trans_order_detail.id_order WHERE trans_order_detail.id_order = '$id'");
$rowTransDetail = mysqli_fetch_all($queryTransDetail, MYSQLI_ASSOC);

// $queryCustomer = mysqli_query($koneksi, "SELECT customer.customer_name, customer.phone, customer.address FROM trans_order 
// LEFT JOIN customer ON customer.id = trans_order.id_customer 
// WHERE trans_order.id = '$id'");

$customerName = mysqli_query($koneksi, "SELECT id, customer_name FROM customer");

$queryCustomer = mysqli_query($koneksi, "SELECT customer.id, customer.customer_name, customer.phone, customer.address FROM trans_order 
LEFT JOIN customer ON customer.id = trans_order.id_customer 
WHERE trans_order.id = '$id'");


$customerDetail = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);

$queryService = mysqli_query($koneksi, "SELECT id, service_name FROM type_of_service");
$rowService = mysqli_fetch_all($queryService, MYSQLI_ASSOC);



// No. Invoice Code
// 001, jika ada auto-increment id + 1 = 002, selain itu 001
// MAX - MIN

$queryInvoice = mysqli_query($koneksi, "SELECT MAX(id) AS order_code FROM trans_order");

$str_unique = "INV";
$date_now = date("dmY");

//jika di dalam table trans_order ada datanya, + 1
if (mysqli_num_rows($queryInvoice) > 0) {
    $rowInvoice = mysqli_fetch_assoc($queryInvoice);
    $incrementPlus = $rowInvoice['order_code'] + 1;
    $code = $str_unique . "" . $date_now . "" . "000" . $incrementPlus;
} else {
    $code = "0001";
}

// Handle form submissions, mengambil  nilai input dgn attribute name="" di form
if (isset($_POST['simpan'])) {
    $id_customer = $_POST['id_customer'];
    $order_code = $_POST['order_code'];
    $order_date = $_POST['order_date'];
    
    $id_service = $_POST['id_service'];
    $qty = $_POST['qty'];

    // Insert into table trans_order
    $insertTransOrder = mysqli_query($koneksi, "INSERT INTO trans_order (id_customer, order_code, order_date) VALUES ('$id_customer', '$order_code', '$order_date')");

    $last_id = mysqli_insert_id($koneksi);

    // Loop through services
    foreach ($id_service as $key => $value) {
        // Check if the service ID and quantity are valid
        if (!empty($value) && !empty($qty[$key]) && (int)$qty[$key] > 0) {
            $id_service = $value; // Current service ID
            $quantity = (int)$qty[$key]; // Current quantity

            // Query to get the price from table type_of_service
            $queryPaket = mysqli_query($koneksi, "SELECT price FROM type_of_service WHERE id='$id_service'");
            if ($rowPaketTransc = mysqli_fetch_assoc($queryPaket)) {
                $harga = $rowPaketTransc['price'];
                $subTotal = $quantity * $harga;

                // Insert into trans_order_detail
                $insertDetailTransaksi = mysqli_query($koneksi, "INSERT INTO trans_order_detail (id_order, id_service, qty, subtotal) VALUES ('$last_id', '$id_service', '$quantity', '$subTotal')");
            }
        }
    }
    
    header("location:transaction.php?tambah=berhasil");
}

?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Analytics</title>
    <?php include 'inc/head.php'; ?>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include 'inc/sidebar.php'; ?>
            <div class="layout-page">
                <?php include 'inc/nav.php'; ?>
                
                <div class="content-wrapper">

                <!-- Detail Transaksi Eye -->

                    <?php if (isset($_GET['detail'])): ?>

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="mb-3">
                                <a href="transaction.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h5>Transaksi Laundry <?php echo isset($customerDetail[0]['customer_name']) ? $customerDetail[0]['customer_name'] : 'N/A'; ?></h5>
                                            </div>
                                            <div class="col-sm-6" align="right">
                                                <a href="print.php?id=<?php echo $rowTransDetail[0]['id_order'] ?>" class="btn btn-success">Print</a>
                                                <a href="tambah-trans-pickup.php?ambil=<?php echo $rowTransDetail[0]['id_order'] ?>" class="btn btn-warning">Ambil Cucian</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Detail Data Transaksi -->
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Data Transaksi</h5>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>No. Invoice</th>
                                                    <th><?php echo isset($rowTransDetail[0]['order_code']) ? $rowTransDetail[0]['order_code'] : 'N/A'; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Laundry</th>
                                                    <th><?php echo isset($rowTransDetail[0]['order_date']) ? date('d-m-Y', strtotime($rowTransDetail[0]['order_date'])) : 'N/A'; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th><?php echo isset($rowTransDetail[0]['order_status']) ? $rowTransDetail[0]['order_status'] : 'N/A'; ?></th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Data Pelanggan -->
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Data Pelanggan</h5>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th><?php echo isset($customerDetail[0]['customer_name']) ? $customerDetail[0]['customer_name'] : 'N/A'; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>No. Telp</th>
                                                    <th><?php echo isset($customerDetail[0]['phone']) ? $customerDetail[0]['phone'] : 'N/A'; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Alamat</th>
                                                    <th><?php echo isset($customerDetail[0]['address']) ? $customerDetail[0]['address'] : 'N/A'; ?></th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Transaksi Detail  -->
                            <div class="col-sm-12 mt-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Transaksi Detail</h5>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No. </th>
                                                        <th>Nama Paket</th>
                                                        <th>Harga</th>
                                                        <th>Qty</th>
                                                        <th>Sub Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach ($rowTransDetail as $key => $value): ?>
                                                        <tr>
                                                            <td><?php echo $no++?></td>
                                                            <td><?php echo $value['service_name']?></td>
                                                            <td><?php echo "Rp" . number_format($value['price'])?></td>
                                                            <td><?php echo $value['qty']?></td>
                                                            <td><?php echo "Rp" . number_format($value['subtotal'])?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php else: ?>

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="mb-3">
                            <a href="transaction.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <form action="" method="POST">
                            <div class="row">
                                
                                    <!-- Tambah Transaksi -->
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header"><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah'; ?> Transaksi</div>
                                            <div class="card-body">
                                                <?php if (isset($_GET['hapus'])): ?>
                                                    <div class="alert alert-success" role="alert">Data berhasil dihapus!</div>
                                                <?php endif; ?>

                                                <div class="mb-3 row">
                                                    <div class="col-sm-6">
                                                        <label for="customer_name" class="form-label">Nama Customer</label>
                                                        <select name="id_customer" class="form-control" required>
                                                            <option value="">Pilih Customer</option>
                                                            <?php while ($customer = mysqli_fetch_assoc($customerName)): ?>
                                                                <option value="<?= $customer['id'] ?>"><?= $customer['customer_name'] ?></option>
                                                            <?php endwhile; ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="order_code" class="form-label">No. Invoice</label>
                                                        <input type="text" class="form-control" name="order_code" placeholder="Generated Order Code" value="#<?php echo $code?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <div class="col-sm-12">
                                                        <label for="order_date" class="form-label">Tanggal Order</label>
                                                        <input type="date" class="form-control" name="order_date" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detail Transaksi -->
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header">Detail Transaksi</div>
                                            <div class="card-body">
                                                <div class="mb-3 row">
                                                    <div class="col-sm-3">
                                                        <label class="form-label">Service Paket</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <select name="id_service[]" class="form-control" required>
                                                            <option value="">--Pilih Service--</option>
                                                            <?php foreach ($rowService as $value): ?>
                                                                <option value="<?= $value['id'] ?>"><?= $value['service_name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mt-3 col-sm-3">
                                                        <label class="form-label">Qty</label>
                                                    </div>
                                                    <div class="mt-3 col-9">
                                                        <input type="number" class="form-control" name="qty[]" placeholder="Masukkan qty (kg)" value="">
                                                    </div>
                                                </div>

                                                <!-- Repeat for another service -->
                                                <div class="mb-3 row">
                                                    <div class="col-sm-3">
                                                        <label class="form-label">Service Paket</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <select name="id_service[]" class="form-control">
                                                            <option value="">--Pilih Service--</option>
                                                            <?php foreach ($rowService as $value): ?>
                                                                <option value="<?= $value['id'] ?>"><?= $value['service_name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mt-3 col-sm-3">
                                                        <label class="form-label">Qty</label>
                                                    </div>
                                                    <div class="mt-3 col-9">
                                                        <input type="number" class="form-control" name="qty[]" placeholder="Masukkan qty (kg)" value="">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <button class="btn btn-primary" name="simpan" type="submit">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </form>
                     </div>
                    
                    </div>

                    <?php endif ?>



                </div>
                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            © <script>document.write(new Date().getFullYear());</script>, made with ❤️ by <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                        </div>
                        <div>
                            <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                            <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
                            <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>
                            <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
                        </div>
                    </div>
                </footer>
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <?php include 'inc/footer.php'; ?>
</body>
</html>
