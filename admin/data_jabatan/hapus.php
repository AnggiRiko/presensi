<?php
// Inisialisasi sesi
session_start();

// Memasukkan file konfigurasi
require_once('../../config.php');

// Mengambil ID jabatan dari parameter GET
$id = $_GET['id'];

// Menghapus data jabatan berdasarkan ID
$result = mysqli_query($connection, "DELETE FROM jabatan WHERE id=$id");

// Set pesan berhasil ke sesi
$_SESSION['berhasil'] = 'Data berhasil dihapus';

// Redirect ke halaman jabatan setelah penghapusan dan menghentikan eksekusi script
header("Location: jabatan.php");
exit();

include('../layout/footer.php');