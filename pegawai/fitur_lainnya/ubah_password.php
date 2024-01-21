<?php
ob_start(); // Memulai output buffering
session_start(); // Memulai sesi

// Mengecek apakah pengguna telah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    // Mengecek peran pengguna, jika bukan pegawai, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Ubah Password"; // Menetapkan judul halaman
include('../layout/header.php'); // Memasukkan file header.php
require_once('../../config.php'); // Memasukkan file konfigurasi

// Memproses pembaruan password jika ada data yang dikirimkan melalui metode POST
if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    // Mengenkripsi password baru menggunakan algoritma bcrypt
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Memeriksa apakah field password baru diisi
        if (empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password baru wajib diisi";
        }

        // Memeriksa apakah field ulangi password baru diisi
        if (empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ulangi password baru wajib diisi";
        }

        // Memeriksa apakah password baru dan ulangi password baru cocok
        if ($_POST['password_baru'] != $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }

        // Jika terdapat pesan kesalahan, simpan ke dalam sesi validasi
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Jika tidak ada pesan kesalahan, update password di database
            $pegawai = mysqli_query($connection, "UPDATE users SET
                password = '$password_baru'
                WHERE id_pegawai = $id");

            // Menetapkan pesan berhasil dan redirect ke halaman home
            $_SESSION['berhasil'] = 'Password Berhasil diubah!';
            header("Location: ../home/home.php");
            exit;
        }
    }
}

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <form action="" method="POST">

            <div class="card col-md-6">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="">Password Baru</label>
                        <input type="password" class="form-control" name="password_baru">
                    </div>

                    <div class="mb-3">
                        <label for="">Ulangi Password</label>
                        <input type="password" class="form-control" name="ulangi_password_baru">
                    </div>

                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
            </div>

        </form>

    </div>
</div>

<?php include('../layout/footer.php'); ?>