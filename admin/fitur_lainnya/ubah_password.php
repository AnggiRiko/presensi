<?php
ob_start();
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Ubah Password";
include('../layout/header.php');
require_once('../../config.php');


if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password baru wajib diisi";
        }

        if (empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ulangi password baru wajib diisi";
        }

        if ($_POST['password_baru'] != $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $pegawai = mysqli_query($connection, "UPDATE users SET
                password = '$password_baru'
                WHERE id_pegawai = $id");

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