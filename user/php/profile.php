<?php
session_start();
require '../../function.php'; // $db

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ================= USER ================= */
$user = mysqli_fetch_assoc(mysqli_query($db,"
    SELECT * FROM users WHERE id_user='$id_user'
"));

/* ================= PROFILE ================= */
$q = mysqli_query($db,"SELECT * FROM profile WHERE id_user='$id_user'");
if (mysqli_num_rows($q) == 0) {
    mysqli_query($db,"INSERT INTO profile (id_user) VALUES ('$id_user')");
    $profile = [];
} else {
    $profile = mysqli_fetch_assoc($q);
}

/* ================= FUNCTION UPDATE ================= */
function update_profile($db, $data, $file, $id_user) {

    $nama     = mysqli_real_escape_string($db, $data['nama']);
    $nickname = mysqli_real_escape_string($db, $data['nickname']);
    $negara   = mysqli_real_escape_string($db, $data['negara']);
    $no_hp    = mysqli_real_escape_string($db, $data['no_hp']);
    $alamat   = mysqli_real_escape_string($db, $data['alamat']);

    $gender = $data['gender'] ?? null;
    if (!in_array($gender, ['Laki-laki','Perempuan'])) {
        $gender = null;
    }

    // update users
    mysqli_query($db,"UPDATE users SET nama='$nama' WHERE id_user='$id_user'");

    // foto
    if (!empty($file['foto']['name'])) {
        $foto = time().'_'.$file['foto']['name'];
        move_uploaded_file($file['foto']['tmp_name'], "../img/".$foto);
        mysqli_query($db,"UPDATE profile SET foto='$foto' WHERE id_user='$id_user'");
    }

    // profile
    $stmt = mysqli_prepare($db,"
        UPDATE profile SET
            nickname=?,
            gender=?,
            negara=?,
            no_hp=?,
            alamat=?
        WHERE id_user=?
    ");
    mysqli_stmt_bind_param($stmt,"sssssi",
        $nickname,$gender,$negara,$no_hp,$alamat,$id_user
    );

    if (mysqli_stmt_execute($stmt)) {
        return 1; // sukses
    } else {
        return 0; // gagal
    }
}

/* ================= SUBMIT ================= */
if (isset($_POST['simpan'])) {
    $hasil = update_profile($db, $_POST, $_FILES, $id_user);

    if ($hasil == 1) {
        echo "<script>alert('Profile berhasil diperbarui');location='profile.php';</script>";
    } else {
        echo "<script>alert('Profile gagal diperbarui');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="../css/profile.css">


</head>

<body>

<div class="wrapper" data-aos="fade-up">

<div class="header">
    <a href="dashboard.php" class="back"><i class="fas fa-arrow-left"></i></a>
    Profile Saya
</div>

<div class="content">

<div class="profile-top" data-aos="fade-right">
    <div class="profile-info">
        <?php if (!empty($profile['foto'])): ?>
            <img src="../img/<?= $profile['foto'] ?>" class="avatar">
        <?php else: ?>
            <i class="fas fa-user-circle fa-5x"></i>
        <?php endif; ?>
        <div>
            <strong><?= htmlspecialchars($user['nama']) ?></strong><br>
            <small><?= htmlspecialchars($user['email']) ?></small>
        </div>
    </div>

    <button type="submit" name="simpan" form="formProfile" class="save-btn">
        Save
    </button>
</div>

<form method="POST" id="formProfile" enctype="multipart/form-data">

<div class="form-grid" data-aos="fade-up">

    <div class="field">
        <label>Full Name</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>">
    </div>

    <div class="field">
        <label>Nick Name</label>
        <input type="text" name="nickname" value="<?= htmlspecialchars($profile['nickname'] ?? '') ?>">
    </div>

    <div class="field">
        <label>Gender</label>
        <select name="gender">
            <option value="">Pilih Gender</option>
            <option value="Laki-laki" <?= ($profile['gender']??'')=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
            <option value="Perempuan" <?= ($profile['gender']??'')=='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <div class="field">
        <label>Negara</label>
        <input type="text" name="negara" value="<?= htmlspecialchars($profile['negara'] ?? '') ?>">
    </div>

    <div class="field">
        <label>No. HP</label>
        <input type="text" name="no_hp" value="<?= htmlspecialchars($profile['no_hp'] ?? '') ?>">
    </div>

    <div class="field">
        <label>Foto Profile</label>
        <input type="file" name="foto">
    </div>

    <div class="field" style="grid-column:1/3">
        <label>Alamat</label>
        <textarea name="alamat"><?= htmlspecialchars($profile['alamat'] ?? '') ?></textarea>
    </div>

</div>

</form>

<div class="email-box" data-aos="fade-left">
    <strong>Email</strong>
    <div class="email-row">
        <div class="email-icon"><i class="fas fa-envelope"></i></div>
        <div>
            <?= htmlspecialchars($user['email']) ?><br>
            <small>********</small>
        </div>
    </div>
</div>

</div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration:900,
    easing:'ease-out-cubic',
    once:true
});
</script>

</body>
</html>