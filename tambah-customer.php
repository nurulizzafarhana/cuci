<?php
    session_start();
  //muncul/pilih sebuah atau semua kolom dari table user
  include 'koneksi.php';
  
  //jika button simpan ditekan, POST ambil value CREATE NEW ACCOUNT
  if (isset($_POST['simpan'])) {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    //$_POST: form input name=''
    //$_GET: url ?param='nilai'
    //$_FILES: from uploaded files

    // if(!empty($_FILES['foto']['name'])){
    //     $nama_foto = $_FILES['foto']['name'];
    //     $ukuran_foto = $_FILES['foto']['size'];


    //     //png, jpg, jpeg
    //     $ext = array('png', 'jpg', 'jpeg');
    //     $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

    //     // jika extension foto tidak ada/ tidak sesuai dengan ext yang telah di-declare di array $ext
    //     if (!in_array($extFoto, $ext)) {
    //         echo "Ekstensi/jenis file tidak ditemukan. Ekstensi yang diizinkan: " . implode(", ", $extFoto);
    //         die;
    //     }else {
    //         //pindah directory gambar ke folder upload (tmp/temporary path)
    //         move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

    //         $insert = mysqli_query($koneksi, "INSERT INTO user (nama, email, password, foto) VALUES ('$nama', '$email', '$password','$nama_foto')");

    //     }
    // } else {
    $insert = mysqli_query($koneksi, "INSERT INTO customer (customer_name, phone, address) VALUES ('$customer_name', '$phone', '$address')");
    // }

    header("location:customer.php?tambah=berhasil");
  }

  //EDIT/UPDATA ACCOUNT DATA
  $id = isset($_GET['edit']) ? $_GET['edit'] : '';
  $queryEdit = mysqli_query($koneksi, "SELECT * FROM customer WHERE id='$id'");
  $rowEdit = mysqli_fetch_assoc($queryEdit);
  
//   $dataService = mysqli_query($koneksi, "SELECT * FROM type_of_service ORDER BY id DESC");

  // when button edit is clicked, insert/update into db
  if (isset($_POST['edit'])) {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];


    $update = mysqli_query($koneksi, "UPDATE customer SET customer_name='$customer_name', phone='$phone', address='$address' WHERE id='$id'");
    header("location:customer.php?ubah=berhasil");
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
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header"><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Customer</div>
                                    <div class="card-body">
                                        <?php if(isset($_GET['hapus'])): ?>
                                        <div class="alert alert-success" role="alert">
                                            Data berhasil dihapus!
                                        </div>
                                        <?php endif ?>


                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama Customer</label>
                                                    <input type="text" class="form-control" name="customer_name" placeholder="Masukkan nama customer" required value="<?php echo isset($_GET['edit']) ? $rowEdit['customer_name'] : '' ?>">
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Phone</label>
                                                    <input type="number" class="form-control" name="phone" placeholder="Masukkan no phone" required value="<?php echo isset($_GET['edit']) ? $rowEdit['phone'] : '' ?>">
                                                </div>

                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Alamat</label>
                                                    <input type="text" class="form-control" name="address" placeholder="Masukkan note" required value="<?php echo isset($_GET['edit']) ? $rowEdit['address'] : '' ?>">
                                                </div> 
                                            </div>
                                           
                                            <div class="mb-3">
                                                <button class="btn btn-primary" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
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
   include 'inc/footer.php';
   ?>
</body>

</html>