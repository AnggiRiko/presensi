<?php
// Inisialisasi sesi dan output buffering
ob_start();
session_start();

// Mengecek apakah pengguna telah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    // Mengecek peran pengguna, jika bukan admin, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Detail Ketidakhadiran"; // Menetapkan judul halaman
include('../layout/header.php'); // Memasukkan file header.php
require_once('../../config.php'); // Memasukkan file konfigurasi

// Memproses data yang dikirim melalui form untuk update status pengajuan
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status_pengajuan = $_POST['status_pengajuan'];

    // Melakukan update status pengajuan di database
    $result = mysqli_query($connection, "UPDATE ketidakhadiran SET status_pengajuan = '$status_pengajuan' WHERE id=$id");

    // Set pesan berhasil dan redirect ke halaman ketidakhadiran.php
    $_SESSION['berhasil'] = 'Status pengajuan berhasil diupdate!';
    header("Location: ketidakhadiran.php");
    exit;
}

// Mendapatkan ID dari query string
$id = $_GET['id'];

// Mengambil data ketidakhadiran berdasarkan ID
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");

// $result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");

// Mendapatkan data ketidakhadiran
while ($data = mysqli_fetch_array($result)) {
    $keterangan = $data['keterangan'];
    $status_pengajuan = $data['status_pengajuan'];
    $tanggal = $data['tanggal'];
}

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="">Keterangan</label>
                        <input type="text" class="form-control" name="tanggal" value="<?= $keterangan ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="">Status Pengajuan</label>
                        <select name="status_pengajuan" class="form-control">
                            <option value="">- Pilih Status -</option>
                            <option <?php if ($status_pengajuan == 'PENDING') {
                                        echo 'selected';
                                    } ?> value="Pending">Pending</option>

                            <option <?php if ($status_pengajuan == 'REJECTED') {
                                        echo 'selected';
                                    } ?> value="REJECTED">REJECTED</option>

                            <option <?php if ($status_pengajuan == 'APPROVED') {
                                        echo 'selected';
                                    } ?> value="APPROVED">APPROVED</option>
                        </select>
                    </div>

                    <input type="hidden" value="<?= $id ?>" name="id">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>