<?php 
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION["login"])) {
    echo "<script>
    alert('Silakan login terlebih dahulu.');
    document.location.href = 'login.php';
    </script>";
    exit;
}

include 'config/app.php';

// Menerima ID akun yang dipilih pengguna dari URL
$id_akun = (int)$_GET['id_akun'];

// Mencoba menghapus akun
if (delete_akun($id_akun) > 0) {
    echo "<script>
        alert('Data akun berhasil dihapus.');
        document.location.href = 'crud-modal.php';
        </script>";
} else {
    echo "<script>
    alert('Data akun gagal dihapus.');
    document.location.href = 'crud-modal.php';
    </script>";
}
?>
