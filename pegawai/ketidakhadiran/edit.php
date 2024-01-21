<?php
// Memulai output buffering dan sesi
ob_start();
session_start();

// Memeriksa apakah pengguna telah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    // Memeriksa peran pengguna, jika bukan pegawai, redirect ke halaman login dengan pesan tolak akses
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Edit Pengajuan Ketidakhadiran'; // Menetapkan judul halaman
include('../layout/header.php'); // Memasukkan file header.php
include_once("../../config.php"); // Memasukkan file konfigurasi

// Jika form update di-submit
if (isset($_POST['update'])) {
    // Ambil data dari form
    $id = $_POST['id'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    // Jika tidak ada file yang diunggah, gunakan file lama
    if ($_FILES['file_baru']['error'] === 4) {
        $file_lama = $_POST['file_lama'];
    } else {
        // Jika ada file yang diunggah, proses file baru
        $file = $_FILES['file_baru'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;

        // Validasi ekstensi file
        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ["jpg", "png", "jpeg", "pdf"];
        $max_ukuran_file = 10 * 1024 * 1024;

        // Pindahkan file ke direktori yang ditentukan
        move_uploaded_file($file_tmp, $file_direktori);
    }

    // Validasi form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Keterangan wajib diisi";
        }

        if (empty($tanggal)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Tanggal wajib diisi";
        }

        if (empty($deskripsi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Deskripsi wajib diisi";
        }

        // Validasi file yang diunggah
        if ($_FILES['file_baru']['error'] != 4) {
            if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG, PDF, dan PNG yang diperbolehkan!";
            }

            if ($ukuran_file > $max_ukuran_file) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10MB!";
            }
        }

        // Jika ada kesalahan, simpan pesan kesalahan di sesi
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Jika tidak ada kesalahan, update data dan redirect ke halaman ketidakhadiran
            $result = mysqli_query($connection, "UPDATE ketidakhadiran SET keterangan= '$keterangan', deskripsi= '$deskripsi', tanggal= '$tanggal', file= '$nama_file' WHERE id = $id");

            $_SESSION['berhasil'] = 'Data berhasil disimpan!';
            header("Location: ketidakhadiran.php");
            exit;
        }
    }
}

// Mengambil data ketidakhadiran berdasarkan ID dari URL
$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");
while ($data = mysqli_fetch_array($result)) {
    $keterangan = $data['keterangan'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
}

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
                            <option <?php if ($keterangan == 'Cuti') {
                                        echo 'selected';
                                    } ?> value="Cuti">Cuti</option>

                            <option <?php if ($keterangan == 'Izin') {
                                        echo 'selected';
                                    } ?> value="Izin">Izin</option>

                            <option <?php if ($keterangan == 'Sakit') {
                                        echo 'selected';
                                    } ?> value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="" cols="30" rows="5"><?= $deskripsi ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Surat Keterangan</label>
                        <input type="file" class="form-control" name="file_baru">
                        <input type="hidden" name="file_lama" value="<?= $file ?>">
                    </div>

                    <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>