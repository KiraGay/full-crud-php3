use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;  

// Menggunakan autoloader Composer
require 'vendor/autoload.php';
$mail = new PHPMailer(true);

// Pengaturan server
$mail->SMTPDebug = 0;
$mail->isSMTP(); 
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'user@example.com';
$mail->Password = 'secret'; 
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

if (isset($_POST['kirim'])) {
    // Penerima
    $mail->setFrom('tutormubatekno@gmail.com', 'Tutorial Muba Teknologi');
    $mail->addAddress($_POST['email_penerima']);  // penerima
    $mail->addReplyTo('tutormubatekno@gmail.com', 'Tutorial Muba Teknologi');

    $mail->Subject = $_POST['subject'];
    $mail->Body = $_POST['pesan'];

    if ($mail->send()) {
        echo "<script>
                alert('Email Berhasil Dikirimkan');
                document.location.href = 'email.php';
              </script>";
    } else {
        echo "<script>
                alert('Email Gagal Dikirimkan');
                document.location.href = 'email.php';
              </script>"; 
    }
    exit;
}

?> 
