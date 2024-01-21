<?php
// Mulai sesi
session_start();

// Memasukkan konfigurasi database
require_once('../../config.php');

// Mengambil ID dari parameter URL untuk menghapus data berdasarkan ID
$id = $_GET['id'];

// Menghapus data ketidakhadiran dengan ID yang sesuai
$result = mysqli_query($connection, "DELETE FROM ketidakhadiran WHERE id=$id");

// Set pesan berhasil dan redirect ke halaman ketidakhadiran
$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: ketidakhadiran.php");
exit(); // Menghentikan eksekusi script setelah melakukan redirect

include('../layout/footer.php');
