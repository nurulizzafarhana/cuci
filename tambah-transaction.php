<?php
    session_start();
  //muncul/pilih sebuah atau semua kolom dari table user
  include 'koneksi.php';
  

  $queryCustomer = mysqli_query($koneksi, "SELECT id, customer_name FROM customer");
  $queryService = mysqli_query($koneksi, "SELECT id, service_name FROM type_of_service");
  $rowPaket = [];
  while ($data = mysqli_fetch_assoc($queryService)) {
    $rowPaket[] = $data;
  }

  
  //jika button simpan ditekan, POST ambil value CREATE NEW ACCOUNT
  if (isset($_POST['simpan'])) {
    $id_customer = $_POST['id_customer'];
    $order_code = $_POST['order_code']; // Kode yang dihasilkan di form
    $order_date = $_POST['order_date'];
    $order_status = $_POST['order_status'];

    $insert = mysqli_query($koneksi, "INSERT INTO trans_order (id_customer, order_code, order_date, order_status) VALUES ('$id_customer', '$order_code', '$order_date', '$order_status')");
    header("location:transaction.php?tambah=berhasil");
}


 // Edit
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM trans_order WHERE id='$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $id_customer = $_POST['id_customer'];
    $order_code = $_POST['order_code'];
    $order_date = $_POST['order_date'];
    $order_status = $_POST['order_status'];

    $update = mysqli_query($koneksi, "UPDATE trans_order SET id_customer='$id_customer', order_code='$order_code', order_date='$order_date', order_status='$order_status' WHERE id='$id'");
    header("location:transaction.php?ubah=berhasil");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM trans_order WHERE id='$id'");
    
    header("location:transaction.php?hapus=berhasil");
}
?>


<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <?php include 'inc/head.php' ?>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <?php include 'inc/head.php' ?>
        <?php include 'inc/sidebar.php' ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            <?php include 'inc/nav.php' ?>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="mb-3">
                            <a href="transaction.php" class="btn btn-secondary">
                                 <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>

                        <!-- Tambah Transaksi -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header"><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Transaksi</div>
                                <div class="card-body">
                                    <?php if (isset($_GET['hapus'])): ?>
                                    <div class="alert alert-success" role="alert">
                                        Data berhasil dihapus!
                                    </div>
                                    <?php endif ?>

                                    

                                    <form action="" method="POST">
                                        <div class="mb-3 row">
                                            <div class="col-sm-6">
                                                <label for="customer_name" class="form-label">Nama Customer</label>
                                                <select name="id_customer" class="form-control" required>
                                                    <option value="">Pilih Customer</option>
                                                    <?php
                                                    // Ambil data customer dari database
                                                    
                                                    while ($customer = mysqli_fetch_assoc($queryCustomer)) {
                                                        echo "<option value='{$customer['id']}'>{$customer['customer_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="order_code" class="form-label">No. Invoice</label>
                                                <input type="text" class="form-control" name="order_code" placeholder="Generated Order Code" value="<?php echo 'ORD-' . date('YmdHis'); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="col-sm-12">
                                                <label for="order_date" class="form-label">Tanggal Order</label>
                                                <input type="date" class="form-control" name="order_date" required>
                                            </div>
                                            
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-primary" name="simpan" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Detail Transaksi -->
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">Detail Transaksi</div>
                                <div class="card-body">
                                    <?php if (isset($_GET['hapus'])): ?>
                                    <div class="alert alert-success" role="alert">
                                        Data berhasil dihapus!
                                    </div>
                                    <?php endif ?>

                                    <!-- <div class="mb-3">
                                        <a href="transaction.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                    </div> -->

                                    <form action="" method="POST">
                                        <div class="mb-3 row">
                                            <div class="col-sm-3">
                                                <label for="" class="form-label">Service Paket</label>
                                            </div>
                                            <div class="col-9">
                                                <select name="id_service[]" class="form-control" required>
                                                    <option value="">--Pilih Service--</option>
                                                    <?php
                                                    
                                                    foreach ($rowPaket as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['service_name']?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mt-3 col-sm-3">
                                                <label for="" class="form-label">Qty</label>
                                            </div>
                                            <div class="mt-3 col-9">
                                                <input type="number" class="form-control" name="qty[]" placeholder="Masukkan qty (kg)" value="">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="col-sm-3">
                                                <label for="" class="form-label">Service Paket</label>
                                            </div>
                                            <div class="col-9">
                                                <select name="id_service[]" class="form-control" required>
                                                    <option value="">--Pilih Service--</option>
                                                    <?php
                                                    
                                                    foreach ($rowPaket as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['service_name']?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mt-3 col-sm-3">
                                                <label for="" class="form-label">Qty</label>
                                            </div>
                                            <div class="mt-3 col-9">
                                                <input type="number" class="form-control" name="qty[]" placeholder="Masukkan qty (kg)" value="">
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <button class="btn btn-primary" name="simpan" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            , made with ❤️ by
                            <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                        </div>
                        <div>
                            <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                            <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
                            <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>
                            <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
   <?php
   include 'inc/footer.php';
   ?>
</body>

</html>