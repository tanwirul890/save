<?php
session_start();
include 'function.php';

// Registrasi
if (isset($_POST['register'])) {
    if (pendaftaran_akun($_POST) > 0) {
        echo "<script>alert('Akun berhasil dibuat');</script>";
    } elseif (pendaftaran_akun($_POST) == -1) {
        echo "<script>alert('Email sudah terdaftar');</script>";
    } else {
        echo "<script>alert('Registrasi gagal');</script>";
    }
}


// login
if (isset($_POST['login'])) {

    // Pastikan koneksi database tersedia
    if (!$db) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Ambil input
    $email    = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Query berdasarkan EMAIL
    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    // Jika user ditemukan
    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password (HASH)
        if (password_verify($password, $data['password'])) {

            // Set session
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['email']   = $data['email'];
            $_SESSION['role']    = $data['role'];

            // Redirect berdasarkan role
            if ($data['role'] === 'admin') {
                header("Location: admin/php/dashboard.php");
            } else {
                header("Location: user/php/dashboard.php");
            }
            exit();

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Page</title>
<link href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="css/style.css">
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #191818;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 10px;
    }

    .container {
        width: 100% !important;
        padding: 0 !important;
        max-width: 1200px;
    }

    .container .row {
        display: flex;
        width: 100%;
        max-width: 1200px;
        height: auto;
        min-height: 700px;
        box-shadow: 0 4px 25px rgba(30,30,30,0.58);
        border-radius: 20px;
        overflow: hidden;
        flex-direction: row;
        margin: 0 !important;
    }

    .left {
        flex: 1;
        background: #FFF5E4;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 30px;
    }

    .left img {
        margin-left: 40px;
        margin-top: -50px;
    }

    .left img.bg-img1, .left img.bg-img2 {
        position: absolute;
        width: 650px;
        opacity: 0.3;
    }

    .left img.bg-img3 {
        position: absolute;
        width: 100px;
        bottom: 12px;
        left: -20px;
    }


    .left .bg-img1 {
        top: 50px;
        left: -150px;
        width: 650px;
        transform: rotate(-17deg);
    }

    .left .bg-img2 {
        top: -70px;
        left: 20px;
        width: 600px;
        transform: rotate(159deg);
    }

    .right {
        flex: 1;
        background: #FFC4C4;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 30px;
    }

    .right h2 {
        font-family: 'Cherry Bomb One', cursive;
        font-size: 36px;
        color: #850E35;
        margin-bottom: 20px;
        margin-right: 0;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .form-group {
        width: 320px;
        margin-bottom: 20px;
        position: relative;
    }

    .form-group input {
        width: 100%;
        height: 44px;
        border-radius: 50px;
        border: none;
        padding: 10px 14px;
        font-size: 16px;
    }

    .form-group input::placeholder {
        color: #FFC4C4;
    }

    .submit-btn {
        min-width: 120px;
        height: 44px;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 50px;
        border: none;
        background: #EE6983;
        color: #FFC4C4;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        background: #850E35;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .create-account {
        margin-top: 10px;
        font-size: 16px;
        color: #FFFFFF;
        margin-left: 0;
        transition: all 0.3s ease;
        text-align: center;
    }

    .create-account-link {
        text-decoration: none;
    }

    .create-account:hover {
        text-decoration: none;
        color: #EE6983;
    }

    @media (max-width: 992px) {
      
        .left  {
            display: none;
        }

        .right h2 {
            font-size: 28px;
        }

        .form-group {
            width: 280px;
        }
    }

    @media (max-width: 768px) {
        .container .row {
            flex-direction: column;
            min-height: auto;
            height: auto;
        }

        .left, .right {
            flex: none;
            width: 100%;
            padding: 25px;
            min-height: auto;
        }

        .left {
            min-height: 300px;
        }

        .left h1 {
            padding-top: 0;
            margin-right: 0;
            font-size: 24px;
        }

        .left img.bg-img1, .left img.bg-img2 {
            width: 250px;
            opacity: 0.2;
        }

        .left img.bg-img3 {
            width: 50px;
        }

        .right h2 {
            margin-right: 0;
            font-size: 24px;
        }

        .form-group {
            width: 100%;
            max-width: 300px;
        }

        .create-account {
            margin-left: 0;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 5px;
        }

        .container .row {
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(30,30,30,0.3);
        }

        .left, .right {
            padding: 20px;
            min-height: auto;
        }

        .left h1, .right h2 {
            font-size: 20px;
        }

        .left img.bg-img1, .left img.bg-img2 {
            width: 200px;
        }

        .form-group {
            width: 100%;
        }

        .form-group input {
            font-size: 14px;
            height: 40px;
        }

        .submit-btn {
            width: 100%;
            height: 40px;
            font-size: 14px;
        }

        .create-account {
            font-size: 14px;
        }
    }
</style>
<body>