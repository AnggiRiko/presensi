<?php
// Inisialisasi sesi
session_start();

// Mengecek apakah pengguna telah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    // Mengecek peran pengguna, jika bukan admin, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

// Menetapkan judul halaman
$judul = "Data Jabatan";

// Memasukkan file header.php
include('../layout/header.php');

// Memasukkan file konfigurasi
require_once('../../config.php');

// Mengambil data jabatan dari database dan mengurutkannya berdasarkan ID secara descending
$result = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY id DESC");

?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('admin/data_jabatan/tambah.php') ?>" class="btn btn-primary"><span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>

        <div class="row row-deck row-cards mt-3">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Aksi</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) : ?>
                    <tr>
                        <td colspan="3">Data masih kosong, silahkan tambahkan data baru</td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1;
                    while ($jabatan = mysqli_fetch_array($result)) : ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $jabatan['jabatan'] ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/data_jabatan/edit.php?id=' . $jabatan['id']) ?>" class="badge bg-primary badge-pill">Edit</a>
                                <a href="<?= base_url('admin/data_jabatan/hapus.php?id=' . $jabatan['id']) ?>" class="badge bg-danger badge-pill tombol-hapus">Hapus</a>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php endif; ?>

            </table>
        </div>
    </div>
</div>


<?php include('../layout/footer.php'); ?>