<?php
// Inisialisasi sesi dan output buffering
session_start();
ob_start();

// Mengecek apakah pengguna telah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    // Mengecek peran pengguna, jika bukan admin, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

// Menetapkan judul halaman
$judul = "Tambah Data Jabatan";

// Memasukkan file header.php
include('../layout/header.php');

// Memasukkan file konfigurasi
require_once('../../config.php');

// Memproses data yang dikirim melalui form
if (isset($_POST['submit'])) {
    $jabatan = htmlspecialchars($_POST['jabatan']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengecek apakah form telah di-submit dan memastikan metode request adalah POST
        if (empty($jabatan)) {
            $pesan_kesalahan = "Nama jabatan wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            // Jika terdapat pesan kesalahan, simpan ke dalam sesi
            $_SESSION['validasi'] = $pesan_kesalahan;
        } else {
            // Jika tidak ada kesalahan, tambahkan data jabatan baru ke dalam database
            $result = mysqli_query($connection, "INSERT INTO jabatan(jabatan) VALUE('$jabatan')");

            // Set pesan berhasil dan redirect ke halaman jabatan.php
            $_SESSION['berhasil'] = "Data berhasil disimpan";
            header("Location: jabatan.php");
            exit();
        }
    }
}

?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <div class="card col-md-6">
            <div class="card-body">

                <form action="<?= base_url('admin/data_jabatan/tambah.php') ?>" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan">
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                </form>

            </div>
        </div>
    </div>
</div>


<?php include('../layout/footer.php'); ?>