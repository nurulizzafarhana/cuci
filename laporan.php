<?php
    session_start();
  //muncul/pilih sebuah atau semua kolom dari table user
  include 'koneksi.php';
  

  $tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
  $tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
  $order_status = isset($_GET['order_status']) ? $_GET['order_status'] : '';

  $query = "SELECT trans_order.id AS order_id,
                 trans_order.order_code,
                 trans_order.order_date,
                 trans_order.order_status,
                 customer.id AS customer_id,
                 customer.customer_name,
                 customer.phone,
                 customer.address
          FROM trans_order
          JOIN customer ON trans_order.id_customer = customer.id WHERE 1";

          if ($tanggal_dari != "") {
            $query .= " AND order_date >= '$tanggal_dari'";
          }

          if ($tanggal_sampai != "") {
            $query .= " AND order_date <= '$tanggal_sampai'";
          }

          if ($order_status != "") {
            $query .= " AND order_status = '$order_status'";
          }

          $query .= " ORDER BY trans_order.id DESC";



// Initialize an array to hold the conditions
// $conditions = [];

// // Add conditions based on input
// if ($tanggal_dari != "") {
//     $conditions[] = "trans_order.order_date >= '$tanggal_dari'";
// }

// if ($tanggal_sampai != "") {
//     $conditions[] = "trans_order.order_date <= '$tanggal_sampai'";
// }

// // Handle order status filter
// if ($order_status !== "") {
//     $conditions[] = "trans_order.order_status = '$order_status'";
// }

// // Combine conditions with AND and add to query if there are any conditions
// if (count($conditions) > 0) {
//     $query .= " WHERE " . implode(" AND ", $conditions);
// }

// Order the results
// $query .= " ORDER BY trans_order.id DESC";

$queryTransOrder = mysqli_query($koneksi, $query);

  //mysqli_fetch_assoc($query) = untuk menjadikan hasil query menjadi sebuah data (object/array)

  //jika parameter ada ?delete=value[id] param
// Delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Query to delete the transaction
    $delete = mysqli_query($koneksi, "DELETE FROM trans_order WHERE id='$id'");

    if ($delete) {
        header("location:transaction.php?hapus=berhasil");
        exit(); // Always exit after a header redirect
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
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
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">Data Transaksi Laundry</div>
                                    <div class="card-body">
                                        <?php if(isset($_GET['hapus'])): ?>
                                        <div class="alert alert-success" role="alert">
                                            Data berhasil dihapus!
                                        </div>
                                        <?php endif ?>

                                       
                                        <!-- FILTER DATA TRANSAKSI -->
                                         <form action="" method="GET">
                                            <div class="mb-3 row">
                                                <div class="col-sm-3">
                                                    <label for="">Dari Tanggal</label>
                                                    <input type="date" name="tanggal_dari" class="form-control">
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="">Sampai Tanggal</label>
                                                    <input type="date" name="tanggal_sampai" class="form-control">
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="">Status</label>
                                                    <select name="order_status" id="" class="form-control">
                                                        <option value="">--Pilih Status--</option>
                                                        <option value="0">Baru</option>
                                                        <option value="1">Sudah dikembalikan</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 p-3">
                                                    <button name="filter" class="mt-2 btn btn-primary">Tampilkan laporan</button>
                                                </div>
                                            </div>
                                         </form>




                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No. Invoice</th>
                                                    <th>Nama Customer</th>
                                                    <th>Tgl Order</th>
                                                    <th>Status Order</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; while ($rowOrder = mysqli_fetch_assoc($queryTransOrder)) { ?>
                                                <tr>
                                                    <td><?php echo $no++ ?></td>
                                                    <td><?php echo $rowOrder['order_code'] ?></td>
                                                    <td><?php echo $rowOrder['customer_name'] ?></td>
                                                    <td><?php echo $rowOrder['order_date'] ?></td>
                                                    <td>
                                                        <?php 
                                                            switch ($rowOrder['order_status']) {
                                                                case '1':
                                                                    $badge = "<span class='badge bg-success'>Sudah dikembalikan</span>";
                                                                    break;
                                                                
                                                                default:
                                                                    $badge = "<span class='badge bg-primary'>Baru</span>";
                                                                    break;
                                                            }
                                                            echo $badge;
                                                        ?> 
                                                    </td>
                                                    <td>
                                                        <a href="tambah-transaction.php?detail=<?php echo $rowOrder['order_id']?>" class="btn btn-primary btn-sm">
                                                            <span class="tf-icon bx bx-show bx-18px"></span>
                                                        </a>
                                                        <a target="_blank" href="print.php?id=<?php echo $rowOrder['order_id']?>" class="btn btn-secondary btn-sm">
                                                            <span class="tf-icon bx bx-printer bx-18px"></span>
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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

                                <a
                                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                                    target="_blank"
                                    class="footer-link me-4">Documentation</a>

                                <a
                                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                                    target="_blank"
                                    class="footer-link me-4">Support</a>
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
    include "inc/footer.php";
    ?>
</body>

</html>