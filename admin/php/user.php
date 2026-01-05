<?php
session_start();
require '../../function.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

/* DELETE USER (AMAN FK) */
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id == $_SESSION['id_user']) {
        echo "<script>alert('Tidak bisa menghapus akun sendiri');</script>";
    } else {
        mysqli_query($db,"DELETE FROM transaksi WHERE id_user=$id");
        mysqli_query($db,"DELETE FROM budget WHERE id_user=$id");
        mysqli_query($db,"DELETE FROM profile WHERE id_user=$id");
        mysqli_query($db,"DELETE FROM users WHERE id_user=$id");
        echo "<script>alert('User berhasil dihapus');location='user.php';</script>";
    }
}

if (isset($_POST['update'])) {
    $id=(int)$_POST['id_user'];
    $nama=mysqli_real_escape_string($db,$_POST['nama']);
    $email=mysqli_real_escape_string($db,$_POST['email']);
    $role=$_POST['role'];
    $pass=$_POST['password'];

    if ($pass) {
        $hash=password_hash($pass,PASSWORD_DEFAULT);
        mysqli_query($db,"UPDATE users SET nama='$nama',email='$email',password='$hash',role='$role' WHERE id_user=$id");
    } else {
        mysqli_query($db,"UPDATE users SET nama='$nama',email='$email',role='$role' WHERE id_user=$id");
    }
    echo "<script>alert('User diperbarui');location='user.php';</script>";
}

$keyword=$_GET['keyword']??'';
$role_f=$_GET['role']??'';

$where="WHERE 1";
if($keyword){
    $safe=mysqli_real_escape_string($db,$keyword);
    $where.=" AND (nama LIKE '%$safe%' OR email LIKE '%$safe%')";
}
if($role_f) $where.=" AND role='$role_f'";

$total_user=mysqli_fetch_assoc(mysqli_query($db,"SELECT COUNT(*) total FROM users"))['total'];
$total_admin=mysqli_fetch_assoc(mysqli_query($db,"SELECT COUNT(*) total FROM users WHERE role='admin'"))['total'];
$total_user_biasa=mysqli_fetch_assoc(mysqli_query($db,"SELECT COUNT(*) total FROM users WHERE role='user'"))['total'];

$qUser=mysqli_query($db,"SELECT * FROM users $where ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen User</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/user.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

</head>

<body>

<div class="container">

<div class="header" data-aos="fade-down">
    <h1>Manajemen User</h1>
    <a class="back" href="dashboard.php">⬅ Dashboard</a>
</div>

<div class="stats">
    <div class="stat" data-aos="zoom-in"><small>Total User</small><h2><?= $total_user ?></h2></div>
    <div class="stat" data-aos="zoom-in" data-aos-delay="100"><small>Admin</small><h2><?= $total_admin ?></h2></div>
    <div class="stat" data-aos="zoom-in" data-aos-delay="200"><small>User</small><h2><?= $total_user_biasa ?></h2></div>
</div>

<form class="filter" data-aos="fade-up">
    <input type="text" name="keyword" placeholder="Cari nama / email" value="<?= htmlspecialchars($keyword) ?>">
    <select name="role">
        <option value="">Semua Role</option>
        <option value="admin" <?= $role_f=='admin'?'selected':'' ?>>Admin</option>
        <option value="user" <?= $role_f=='user'?'selected':'' ?>>User</option>
    </select>
    <button>Filter</button>
</form>

<?php if(isset($_GET['edit'])):
$e=mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM users WHERE id_user=".(int)$_GET['edit']));
?>
<div class="form-edit" data-aos="fade-right">
<h3> Edit User</h3>
<form method="post">
<input type="hidden" name="id_user" value="<?= $e['id_user'] ?>">
<input type="text" name="nama" value="<?= htmlspecialchars($e['nama']) ?>" required>
<input type="email" name="email" value="<?= htmlspecialchars($e['email']) ?>" required>
<input type="password" name="password" placeholder="Password baru (opsional)">
<select name="role">
<option value="admin" <?= $e['role']=='admin'?'selected':'' ?>>Admin</option>
<option value="user" <?= $e['role']=='user'?'selected':'' ?>>User</option>
</select>
<button name="update">Simpan</button>
</form>
</div>
<?php endif; ?>

<div class="table-box" data-aos="fade-up">
<table>
<thead>
<tr>
<th>Nama</th>
<th>Email</th>
<th>Role</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($u=mysqli_fetch_assoc($qUser)): ?>
<tr>
<td><?= htmlspecialchars($u['nama']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><span class="badge <?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
<td><?= date('d M Y',strtotime($u['created_at'])) ?></td>
<td>
<a class="btn edit" href="?edit=<?= $u['id_user'] ?>">Edit</a>
<a class="btn delete" href="?hapus=<?= $u['id_user'] ?>" onclick="return confirm('Hapus user ini?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({
    duration:800,
    once:true
});
</script>

</body>
</html>
