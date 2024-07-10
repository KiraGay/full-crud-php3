<?php
session_start();
include 'config/app.php';

// cek apakah tombol login di tekan
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // secret key
    $secret_key = "6LcdwwoqAAAAANHyyoPEQPleWf9CyKv-pR2yb64w";

    $verifikasi = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
    $response = json_decode($verifikasi);

    // cek apakah reCAPTCHA valid
    if ($response->success) {
        // cek username
        $stmt = $db->prepare("SELECT * FROM akun WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // jika ada usernya
        if ($result->num_rows == 1) {
            // fetch data user
            $hasil = $result->fetch_assoc();
            // check username
            $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");
            // jika ada usernya
          $hasil = mysqli_fetch_assoc($result);
            // cek password nya
            if (password_verify($password, $hasil['password'])) {
                // set session
                session_regenerate_id(true);
                $_SESSION['login'] = true;
                $_SESSION['id_akun'] = $hasil['id_akun'];
                $_SESSION['nama'] = $hasil['nama'];
                $_SESSION['username'] = $hasil['username'];
                $_SESSION['email'] = $hasil['email'];
                $_SESSION['level'] = $hasil['level'];

                // arahkan ke file index.php
                header("Location: index.php");
                exit;
            }
        }
        // jika username/password salah
        $error = true;
    } else {
        // jika reCAPTCHA tidak valid
        $errorRecaptcha = true;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
    </style>
    <link href="css/signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">
        <form action="" method="POST">
            <img class="mb-4" src="assets/img/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Admin Login</h1>

            <?php if (isset($error)) : ?>
            <div class="alert alert-danger text-center">
                <b>Username/Password SALAH</b>
            </div>
            <?php endif; ?>

            <?php if (isset($errorRecaptcha)) : ?>
            <div class="alert alert-danger text-center">
                <b>reCAPTCHA tidak valid. Silakan coba lagi.</b>
            </div>
            <?php endif; ?>

            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username"
                    required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <label for="floatingInput">Username</label>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password"
                    required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        <label for="floatingPassword">Password</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="6LcdwwoqAAAAAFXAWllMzqA5CYfHRXtUnSz9JjDN"></div>
            </div>
            <div class="row">
                <div class="col-8"></div>
                <div class="col-4">
                    <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Kirim</button>
                </div>
            </div>
        </form>

        <hr>
        <p class="mb-1 text-center">
            <span class="mt-5 mb-3 text-muted">Developer &copy;
                <a href="https://tambahpinter.com/cara-mengubah-dosa-menjadi-saldo-dana/">Kira Liebert</a> <?= date('Y') ?>
            </span>
        </p>

        <script src="assets-template/plugins/jquery.min.js"></script>
        <script src="assets-template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets-template/dist/js/adminlte.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>

    </main>
</body>

</html>
