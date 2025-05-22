<?php
include 'function/app.php';

$kategori = mysqli_query($conn, "SELECT * FROM kategori");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $jml = $_POST['jml'];
    $nama = $_POST['namaKategori'];

    if (addBuku($judul, $pengarang, $tahun_terbit, $jml, $nama)) {
        echo "<script>alert('Buku berhasil ditambah'); window.location.href='buku.php';</script>";
    } else {
        echo "Gagal menyimpan buku.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Peminjaman</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="h3 mb-0 text-gray-800">
                                <h1 class="h4 text-gray-900 mb-4">Tambah Buku</h1>
                                <div class="text-left">
                                    <a href="buku.php" class="btn btn-primary">Kembali</a>
                                </div>
                                <br>
                            </div>
                            <form class="" method="post" action="">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            name="judul" placeholder="judul">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                        name="pengarang" placeholder="pengarang">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="number" class="form-control form-control-user"
                                            name="tahun_terbit" placeholder="tahun_terbit" id="exampleInputPassword">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="number" class="form-control form-control-user"
                                            name="jml" placeholder="jumlah" id="exampleInputPassword">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <select class="form-select form-control-user" name="namaKategori" id="namaKategori" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php 
                                            while ($row = mysqli_fetch_assoc($kategori)):
                                            ?>
                                                <option value="<?= htmlspecialchars($row['namaKategori']); ?>"><?= htmlspecialchars($row['namaKategori']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Tambah Buku </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>