<?php
session_start();
include 'function/app.php'; // Memuat fungsi dari app.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $kode_verifikasi = $_POST['kode_verifikasi'];


    $login_result = login($username, $password, $kode_verifikasi);

    if ($login_result === true) {
        if ($_SESSION['level'] == 'Member') {
            echo "<script>alert('Berhasil login sebagai Anggota!'); window.location.href = 'index_anggota.php';</script>";
        } elseif ($_SESSION['level'] == 'Admin') {
            echo "<script>alert('Berhasil login sebagai Admin!'); window.location.href = 'index_admin.php';</script>";
        }
        exit();
    } else {
        $error = $login_result;
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

    <title>SB Admin 2 - Login</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">


</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <?php if (isset($error)) : ?>
                                        <div class="alert alert-danger" role="alert" onclick="this.remove()">
                                            <?= $error; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                name="username" placeholder="Enter Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-user"
                                                id="exampleInputPassword" name="kode_verifikasi" placeholder="kode verifikasi" required>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="generate-kode-verifikasi" onclick="generateVerificationCode()">Generate Kode Verifikasi</button>
                                        <br>
                                        <span id="kode-verifikasi-acak"></span>

                                        <script>
                                            function generateVerificationCode() {
                                                var xhr = new XMLHttpRequest();
                                                xhr.open("POST", "function/app.php", true);
                                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                                xhr.send("action=generateVerificationCode");
                                                xhr.onload = function() {
                                                    if (xhr.status === 200) {
                                                        document.getElementById("kode-verifikasi-acak").innerHTML = xhr.responseText;
                                                    }
                                                };
                                            }
                                        </script>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>