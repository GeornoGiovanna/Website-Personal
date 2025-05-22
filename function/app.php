<?php
include 'koneksi.php';

if (isset($_POST['action']) && $_POST['action'] == "generateVerificationCode") {
    $kode_verifikasi_acak = generateVerificationCode();
    echo "Kode Verifikasi: $kode_verifikasi_acak";
    exit;
}

function generateVerificationCode()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $kode_verifikasi_acak = rand(1000, 9999);
    $_SESSION['kode_verifikasi_acak'] = $kode_verifikasi_acak;

    return $kode_verifikasi_acak;
}   

function addPengguna($username, $password, $level, $status, $name)
{
    global $conn;

    $sql = "INSERT INTO tbuser (name, username, password, level, status) VALUES ('$name', '$username', '$password', '$level', '$status')";
    return $conn->query($sql);
}

function addRegister($username, $password, $name)
{
    global $conn;
   
    $sql = "INSERT INTO tbuser (name, username, password) VALUES ('$name', '$username', '$password')";
    return $conn->query($sql);
}

function addKategori($namaKategori)
{
    global $conn;
    $sql = "INSERT INTO kategori (namaKategori) VALUES ('$namaKategori')";
    return $conn->query($sql);
}

function addBuku($judul, $pengarang, $tahun_terbit, $jml, $namaKategori)
{
    global $conn;

    // Ambil id_kategori dari namaKategori
    $query = "SELECT id_kategori FROM kategori WHERE namaKategori = '$namaKategori'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_kategori = $row['id_kategori'];
    } else {
        // Jika kategori belum ada, buat baru
        $insert_kategori = "INSERT INTO kategori (namaKategori) VALUES ('$namaKategori')";
        mysqli_query($conn, $insert_kategori);
        $id_kategori = mysqli_insert_id($conn);
    }

    // Insert buku lengkap dengan id_kategori
    $query_buku = "INSERT INTO buku (id_kategori, judul, pengarang, tahun_terbit, jml)
                   VALUES ($id_kategori, '$judul', '$pengarang', $tahun_terbit, $jml)";

    return mysqli_query($conn, $query_buku);
}


function getAllPengguna()
{
    global $conn;
    $sql = "SELECT * FROM tbuser";
    return $conn->query($sql);
}

function getAllKategori()
{
    global $conn;
    $sql = "SELECT * FROM kategori";
    return $conn->query($sql);
}

function getAllBuku()
{
    global $conn;
    $sql = "SELECT buku.*, kategori.namaKategori
            FROM buku
            JOIN kategori ON buku.id_kategori = kategori.id_kategori";
    return $conn->query($sql);
}

function getTotalJudulBuku()
{
    global $conn;
    $query = "SELECT COUNT(DISTINCT judul) AS total_judul FROM buku";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_judul'];
}

function getTotalBuku()
{
    global $conn;
    $query = "SELECT SUM(jml) AS total_buku FROM buku";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_buku'];
}

function getTotalAnggota()
{
    global $conn;
    $query = "SELECT count(distinct username) AS total_anggota FROM tbuser";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_anggota'];
}


function getTotalKategori()
{
    global $conn;
    $query = "SELECT count(distinct namaKategori) AS total_kategori FROM kategori";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_kategori'];
}

function totalBukuYangDipinjam($id_user)
{
    global $conn;
    $id_user_escaped = mysqli_real_escape_string($conn, $id_user);
    $query = "SELECT COUNT(dp.id_detail) AS total_buku_selesai_dipinjam
              FROM peminjaman p
              JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
              WHERE p.id = '$id_user_escaped' AND p.status = 'Dikembalikan'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_buku_selesai_dipinjam'] ? $row['total_buku_selesai_dipinjam'] : 0;
}


function getAllStatusPeminjaman()
{
    global $conn;

    $query = "
        SELECT
            p.id_peminjaman,
            u.name AS nama_peminjam,
            b.judul AS judul_buku,
            dp.jumlah_pinjam,
            p.tanggal_pinjam,
            p.tanggal_kembali,
            p.status,
            COALESCE(peng.tanggal_pengembalian, '-') AS tanggal_pengembalian_aktual,
            COALESCE(peng.denda, 0) AS denda
        FROM peminjaman p
        JOIN tbuser u ON p.id = u.id
        JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
        JOIN buku b ON dp.id_buku = b.id_buku
        LEFT JOIN pengembalian peng ON p.id_peminjaman = peng.id_peminjaman
        ORDER BY p.tanggal_pinjam DESC;
    ";

    $result = mysqli_query($conn, $query);
    return $result;
}

function getStatusPeminjamanByUser($id_user)
{
    global $conn;

    $query = "
        SELECT
            p.id_peminjaman,
            u.name AS nama_peminjam,
            b.judul AS judul_buku,
            dp.jumlah_pinjam,
            p.tanggal_pinjam,
            p.tanggal_kembali,
            p.status,
            COALESCE(peng.tanggal_pengembalian, '-') AS tanggal_pengembalian_aktual,
            COALESCE(peng.denda, 0) AS denda
        FROM peminjaman p
        JOIN tbuser u ON p.id = u.id
        JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
        JOIN buku b ON dp.id_buku = b.id_buku
        LEFT JOIN pengembalian peng ON p.id_peminjaman = peng.id_peminjaman
        WHERE p.id = $id_user
        ORDER BY p.tanggal_pinjam DESC;
    ";

    $result = $conn->query($query);
    return $result;
}

function getPenggunaById($id)
{
    global $conn;
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM tbuser WHERE id = '$id'";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}


function getKategoriById($id_kategori)
{
    global $conn;
    $id_kategori = $conn->real_escape_string($id_kategori);
    $sql = "SELECT * FROM kategori WHERE id_kategori = '$id_kategori'";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function getBukuById($id_buku)
{
    global $conn;
    $id_buku = $conn->real_escape_string($id_buku);
    $sql = "SELECT * FROM buku WHERE id_buku = '$id_buku'";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function updatePengguna($id, $username, $password, $level, $status, $name)
{
    global $conn;
    $sql = "UPDATE tbuser SET
            username = '$username',
            password = '$password',
            level = '$level',
            status = '$status',
            name = '$name'
            WHERE id = '$id'";
    return $conn->query($sql);
}

function updateKategori($id_kategori, $namaKategori)
{
    global $conn;
    $sql = "UPDATE kategori SET
            namaKategori = '$namaKategori'
            WHERE id_kategori = $id_kategori";
    return $conn->query($sql);
}

function updateBuku($id_buku, $judul, $pengarang, $thn, $jml)
{
    global $conn;
    $sql = "UPDATE buku SET
            judul = '$judul',
            pengarang = '$pengarang',
            tahun_terbit = '$thn',
            jml = '$jml'

            WHERE id_buku = $id_buku";
    return $conn->query($sql);
}

function deletePengguna($id)
{
    global $conn;
    $sql = "DELETE FROM tbuser WHERE id = $id";
    return $conn->query($sql);
}

function deleteKategori($id_kategori)
{
    global $conn;
    $sql = "DELETE FROM kategori WHERE id_kategori = $id_kategori";
    return $conn->query($sql);
}

function deleteBuku($id_buku)
{
    global $conn;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // 1. Dapatkan id_peminjaman yang terkait dengan id_buku ini
        $peminjaman_ids_to_delete = [];
        $result_peminjaman_ids = $conn->query("SELECT id_peminjaman FROM detail_peminjaman WHERE id_buku = $id_buku");
        if (!$result_peminjaman_ids) {
            throw new Exception("Gagal mendapatkan ID peminjaman terkait: " . $conn->error);
        }
        while ($row = $result_peminjaman_ids->fetch_assoc()) {
            $peminjaman_ids_to_delete[] = $row['id_peminjaman'];
        }

        // 2. Hapus entri di tabel detail_peminjaman
        if (!$conn->query("DELETE FROM detail_peminjaman WHERE id_buku = $id_buku")) {
            throw new Exception("Gagal menghapus detail peminjaman terkait: " . $conn->error);
        }

        // 3. Hapus entri di tabel 'pengembalian' dan 'peminjaman' utama jika ada peminjaman terkait
        if (!empty($peminjaman_ids_to_delete)) {
            $ids = implode(',', $peminjaman_ids_to_delete);
            if (!$conn->query("DELETE FROM pengembalian WHERE id_peminjaman IN ($ids)")) {
                throw new Exception("Gagal menghapus entri pengembalian terkait: " . $conn->error);
            }
            if (!$conn->query("DELETE FROM peminjaman WHERE id_peminjaman IN ($ids)")) {
                throw new Exception("Gagal menghapus peminjaman utama terkait: " . $conn->error);
            }
        }

        // 4. Setelah semua referensi dihapus,setealh itu hapus buku dari tabel 'buku'
        if (!$conn->query("DELETE FROM buku WHERE id_buku = $id_buku")) {
            throw new Exception("Gagal menghapus buku: " . $conn->error);
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $conn->rollback();
        return "Gagal menghapus buku: " . $e->getMessage(); 
    }
}

function login($username, $password, $kode_verifikasi)
{
    global $conn;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $sql = "SELECT * FROM tbuser WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['level'] = $user['level'];
            return true;
        } else {
            return "Password salah";
        }
    }
    return "Username tidak ditemukan.";
}

function prosesPeminjaman($id_user, $id_buku, $tanggal_pinjam, $tanggal_kembali, $jumlah_pinjam = 1)
{
    global $conn;

    $conn->begin_transaction(); // Mulai transaksi

    try {
        // 1. Cek stok buku
        $result_cek_buku = $conn->query("SELECT judul, jml FROM buku WHERE id_buku = $id_buku AND jml >= $jumlah_pinjam");
        if (!$result_cek_buku || $result_cek_buku->num_rows === 0) {
            throw new Exception("Buku tidak tersedia atau stok tidak cukup.");
        }
        $buku_data = $result_cek_buku->fetch_assoc();
        $judul_buku = $buku_data['judul'];

        // 2. Cek apakah user sudah meminjam buku ini dan belum dikembalikan (status 'Dipinjam')
        $result_cek_pinjam_ganda = $conn->query("SELECT p.id_peminjaman FROM peminjaman p JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman WHERE p.id = $id_user AND dp.id_buku = $id_buku AND p.status = 'Dipinjam'");
        if (!$result_cek_pinjam_ganda || $result_cek_pinjam_ganda->num_rows > 0) {
            throw new Exception("Anda sudah meminjam buku '$judul_buku' dan belum mengembalikannya.");
        }

        // 3. Insert ke tabel 'peminjaman' utama
        if (!$conn->query("INSERT INTO peminjaman (id, tanggal_pinjam, tanggal_kembali, status) VALUES ($id_user, '$tanggal_pinjam', '$tanggal_kembali', 'Dipinjam')")) {
            throw new Exception("Gagal membuat entri peminjaman utama: " . $conn->error);
        }
        $id_peminjaman_baru = $conn->insert_id;

        // 4. Insert ke tabel 'detail_peminjaman'
        if (!$conn->query("INSERT INTO detail_peminjaman (id_peminjaman, id_buku, jumlah_pinjam) VALUES ($id_peminjaman_baru, $id_buku, $jumlah_pinjam)")) {
            throw new Exception("Gagal membuat detail peminjaman: " . $conn->error);
        }

        $conn->commit(); // Commit transaksi jika semua berhasil
        return true;
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaksi jika ada kesalahan
        return $e->getMessage();
    }
}


function prosesPengembalian($id_peminjaman, $tanggal_pengembalian, $denda = 0)
{
    global $conn;

    $conn->begin_transaction(); // Mulai transaksi

    try {
        // 1. Ambil detail peminjaman dari tabel 'peminjaman' dan 'detail_peminjaman'
        $result_details = $conn->query("
            SELECT
                p.id,
                p.tanggal_kembali,
                dp.id_buku,
                dp.jumlah_pinjam
            FROM peminjaman p
            JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
            WHERE p.id_peminjaman = $id_peminjaman AND p.status = 'Dipinjam'
        ");

        if (!$result_details || $result_details->num_rows === 0) {
            throw new Exception("Peminjaman tidak ditemukan atau sudah dikembalikan.");
        }

        $peminjaman_data = $result_details->fetch_assoc();
        $id_user = $peminjaman_data['id'];
        $tanggal_kembali_seharusnya = $peminjaman_data['tanggal_kembali'];
        $id_buku = $peminjaman_data['id_buku'];
        $jumlah_pinjam = $peminjaman_data['jumlah_pinjam'];

        // 2. Insert ke tabel 'pengembalian'
        if (!$conn->query("INSERT INTO pengembalian (id_peminjaman, tanggal_pengembalian, denda) VALUES ($id_peminjaman, '$tanggal_pengembalian', $denda)")) {
            throw new Exception("Gagal mencatat pengembalian: " . $conn->error);
        }

        // 3. Update status peminjaman di tabel 'peminjaman' menjadi 'Dikembalikan'
        if (!$conn->query("UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = $id_peminjaman")) {
            throw new Exception("Gagal memperbarui status peminjaman: " . $conn->error);
        }

        $conn->commit(); 
        return true;
    } catch (Exception $e) {
        $conn->rollback(); 
        return $e->getMessage();
    }
}
