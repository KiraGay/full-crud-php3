<?php

session_start();

// Membatasi halaman login
if (!isset($_SESSION['login'])) {
    echo "<script>
        alert('Login dulu bos');
        document.location.href = 'login.php';
        </script>";
    exit;
}

// Membatasi halaman sesuai user login
if ($_SESSION['level'] != 1 && $_SESSION['level'] != 3) {
    echo "<script>
        alert('Anda tidak punya hak akses');
        document.location.href = 'crud-modal.php';
        </script>";
    exit;
}

require __DIR__ . '/vendor/autoload.php';
require 'config/app.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$data_barang = select("SELECT * FROM mahasiswa");

$content = '<style type="text/css">
    .gambar {
        width: 50px;
    }
</style>';

$content .= '
<page>
    <table border="1" align="center">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Program Studi</th>
        <th>Jenis Kelamin</th>
        <th>Telepon</th>
        <th>Email</th>
        <th>Foto</th>
    </tr>';

$no = 1;
foreach ($data_barang as $barang) {
    $imgPath = 'assets/img/ambanut.jpg' . $barang['foto'];
    $imgTag = file_exists($imgPath) ? '<img src="'.$imgPath.'" class="gambar" />' : 'Tidak ada gambar';

    $content .= '
    <tr>
        <td>'.$no++.'</td>
        <td>'.$barang['nama'].'</td>
        <td>'.$barang['prodi'].'</td>
        <td>'.$barang['jk'].'</td>
        <td>'.$barang['telepon'].'</td>
        <td>'.$barang['email'].'</td>
        <td>'.$imgTag.'</td>
    </tr>';
}

$content .= '
    </table>
</page>';

try {
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($content);
    ob_start();
    $html2pdf->output('Laporan-mahasiswa.pdf');
} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
?>
