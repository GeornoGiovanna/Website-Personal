<?php
include 'function/app.php';

if (isset($_GET['id'])) {
    $pengguna = getPenggunaById($_GET['id']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];
    $status = $_POST['status'];
    $name = $_POST['name'];

    if (updatePengguna($id, $username, $password, $level, $status, $name)) {
        echo "<script>alert('Data berhasil diubah'); window.location.href='tables.php';</script>";
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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Edit</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
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
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Edit</h1>
                                <div class="text-left">
                                    <a href="tables.php" class="btn btn-primary">Kembali</a>
                                </div>
                                <br>
                            </div>
                            <?php if ($pengguna): ?>
                                <form class="user" method="post" action="">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                                name="id" value="<?php echo $pengguna['id']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                                name="name" value="<?php echo $pengguna['name']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                            name="username" value="<?php echo $pengguna['username']; ?>">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" id="exampleInputPassword" value="<?php echo $pengguna['password']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 mb-2 mb-sm-0">
                                            <select class="form-select" aria-label="Default select example" name="level">
                                                <option value="1" <?= $pengguna['level'] == 1 ? 'selected' : ''; ?>>null</option>
                                                <option value="2" <?= $pengguna['level'] == 2 ? 'selected' : ''; ?>>Admin</option>
                                                <option value="3" <?= $pengguna['level'] == 3 ? 'selected' : ''; ?>>Member</option>
                                            </select><br>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4 mb-2 mb-sm-0">
                                            <select class="form-select" aria-label="Default select example" name="status">
                                                <option value="1" <?= $pengguna['status'] == 1 ? 'selected' : ''; ?>>null</option>
                                                <option value="2" <?= $pengguna['status'] == 2 ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="3" <?= $pengguna['status'] == 3 ? 'selected' : ''; ?>>Tidak aktif</option>
                                            </select>
                                            <br>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Edit Account </button>
                                </form>
                            <?php else: ?>
                                <p>User data not found.</p>
                            <?php endif; ?>
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