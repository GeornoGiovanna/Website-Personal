<?php
include 'function/app.php';

if (isset($_GET['id_kategori'])) {
    $kategori = getKategoriById($_GET['id_kategori']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_kategori = $_POST['id_kategori'];
    $namaKategori = $_POST['namaKategori']; // ← perhatikan nama ini sesuai dengan select

    if (updateKategori($id_kategori, $namaKategori)) {
        echo "<script>alert('Data berhasil diubah'); window.location.href='kategori.php';</script>";
    } else {
        echo "Error updating record.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Kategori</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Edit</h1>
                                <div class="text-left">
                                    <a href="kategori.php" class="btn btn-primary">Kembali</a>
                                </div>
                                <br>
                            </div>
                            <?php if ($kategori): ?>
                                <form method="post" action="">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                name="id_kategori"
                                                value="<?= $kategori['id_kategori']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                name="namaKategori"
                                                value="<?= $kategori['namaKategori']; ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Edit Kategori
                                    </button>
                                </form>
                            <?php else: ?>
                                <p>Kategori tidak ditemukan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>