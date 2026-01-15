<?php
$db = mysqli_connect('localhost', 'moon', '123', 'save');

function select($query)
{
  global $db;
  $result = mysqli_query($db, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

// tambah akun (REGISTER)
function pendaftaran_akun($data) {
    global $db; // sesuaikan dengan koneksi kamu

    $nama  = htmlspecialchars($data['nama']);
    $email = htmlspecialchars($data['email']);
    // ROLE DEFAULT
    $role = 'user';

    // Hash password
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Cek email sudah terdaftar
    $cek = mysqli_query($db, "SELECT email FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        return -1; // email sudah ada
    }

    // Insert ke tabel users
    $query = "INSERT INTO users (nama, email, password, role)
              VALUES ('$nama', '$email', '$password', '$role')";

    mysqli_query($db, $query) or die(mysqli_error($db));

    return mysqli_affected_rows($db);
}



?>
