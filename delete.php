<?php
include 'function/app.php';

if (isset($_GET['id'])) {
    if (deletePengguna($_GET['id'])) {
        header("Location: tables.php");
    } else {
        echo "Error deleting record.";
    }
}

if (isset($_GET['id_kategori'])) {
    if (deleteKategori($_GET['id_kategori'])) {
        header("Location: kategori.php");
    } else {
        echo "Error deleting record.";
    }
}

if (isset($_GET['id_buku'])) {
    if (deleteBuku($_GET['id_buku'])) {
        header("Location: buku.php");
    } else {
        echo "Error deleting record.";
    }
}
