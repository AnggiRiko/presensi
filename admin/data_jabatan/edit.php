<?php
// Inisialisasi sesi dan buffer output
session_start();
ob_start();

// Cek apakah pengguna sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    // Jika role bukan admin, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

// Set judul halaman
$judul = "Edit Data Jabatan";

// Masukkan header dan file konfigurasi
include('../layout/header.php');
require_once('../../config.php');

// Handle proses update data jabatan
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $jabatan = htmlspecialchars($_POST['jabatan']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validasi form, pastikan nama jabatan tidak kosong
        if (empty($jabatan)) {
            $pesan_kesalahan = "Nama jabatan wajib diisi";
        }

        // Jika ada kesalahan validasi, simpan pesan kesalahan ke sesi
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = $pesan_kesalahan;
        } else {
            // Update data jabatan ke database
            $result = mysqli_query($connection, "UPDATE jabatan SET jabatan='$jabatan' WHERE id=$id");

            // Set pesan berhasil dan redirect ke halaman jabatan
            $_SESSION['berhasil'] = "Data berhasil diupdate";
            header("Location: jabatan.php");
            exit();
        }
    }
}

// $id =$_GET['id'];
// Ambil id dari parameter GET atau POST
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

// Ambil data jabatan berdasarkan id
$result = mysqli_query($connection, "SELECT * FROM jabatan WHERE id=$id");

// Ambil nama jabatan dari hasil query
while ($jabatan = mysqli_fetch_array($result)) {
    $nama_jabatan = $jabatan['jabatan'];
}


?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <div class="card col-md-6">
            <div class="card-body">

                <form action="<?= base_url('admin/data_jabatan/edit.php') ?>" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="<?= $nama_jabatan ?>">
                    </div>
                    <input type="text" value="<?= $id ?>" name="id" hidden>

                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>

            </div>
        </div>
    </div>
</div>


<?php include('../layout/footer.php'); ?>