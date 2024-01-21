<?php
ob_start(); // Mulai output buffering
session_start(); // Memulai atau melanjutkan sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION["login"])) {
    // Jika tidak, redirect ke halaman login dengan pesan belum login
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    // Jika rolenya bukan 'pegawai', redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Pengajuan Ketidakhadiran'; // Set judul halaman
include('../layout/header.php'); // Memasukkan file header
include_once("../../config.php"); // Memasukkan file konfigurasi database

// Memeriksa apakah form pengajuan ketidakhadiran telah disubmit
if (isset($_POST['submit'])) {
    // Mendapatkan data dari formulir
    $id = $_POST['id_pegawai'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $status_pengajuan = 'PENDING';

    // Memeriksa apakah file diupload
    if (isset($_FILES['file'])) {
        // Mendapatkan informasi file
        $file = $_FILES['file'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;

        // Mengambil ekstensi file
        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        // Menentukan ekstensi file yang diizinkan
        $ekstensi_diizinkan = ["jpg", "png", "jpeg", "pdf"];
        // Menentukan batasan ukuran file
        $max_ukuran_file = 10 * 1024 * 1024;

        // Mengunggah file ke direktori yang ditentukan
        move_uploaded_file($file_tmp, $file_direktori);
    }

    // Memeriksa metode permintaan
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Memeriksa kelengkapan data yang diinputkan
        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Keterangan wajib diisi";
        }

        if (empty($tanggal)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Tanggal wajib diisi";
        }

        if (empty($deskripsi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Deskripsi wajib diisi";
        }

        // Memeriksa ekstensi dan ukuran file
        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG, PDF, dan PNG yang diperbolehkan!";
        }

        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10MB!";
        }

        // Jika terdapat pesan kesalahan, simpan dalam sesi validasi
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Jika tidak ada kesalahan, masukkan data ke database
            $result = mysqli_query($connection, "INSERT INTO ketidakhadiran(id_pegawai, keterangan, deskripsi, tanggal, status_pengajuan, file) VALUE ('$id', '$keterangan', '$deskripsi', '$tanggal', '$status_pengajuan', '$nama_file')");

            // Set pesan berhasil dan redirect ke halaman ketidakhadiran
            $_SESSION['berhasil'] = 'Data berhasil disimpan!';
            header("Location: ketidakhadiran.php");
            exit;
        }
    }
}

$id = $_SESSION['id']; // Mendapatkan ID pengguna dari sesi

// Mengambil data ketidakhadiran untuk pengguna dengan ID tertentu, diurutkan berdasarkan ID secara descending
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id_pegawai = '$id' ORDER BY id DESC");

?>

<div class="page-body">
    <div class="container-xl">

        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $_SESSION['id'] ?>" name="id_pegawai">

                    <div class="mb-3">
                        <label for="">Keterangan</label>
                        <select name="keterangan" class="form-control">
                            <option value="">- Pilih Keterangan -</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Cuti') {
                                        echo 'selected';
                                    } ?> value="Cuti">Cuti</option>

                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Izin') {
                                        echo 'selected';
                                    } ?> value="Izin">Izin</option>

                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Sakit') {
                                        echo 'selected';
                                    } ?> value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="" cols="30" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal">
                    </div>

                    <div class="mb-3">
                        <label for="">Surat Keterangan</label>
                        <input type="file" class="form-control" name="file">
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>