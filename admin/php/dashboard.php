<?php
session_start();
require '../../function.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../../login.php");
    exit;
}

$bulan = date('m');
$tahun = date('Y');

$total_user = mysqli_fetch_assoc(mysqli_query($db,"SELECT COUNT(*) total FROM users"))['total'] ?? 0;
$total_masuk = mysqli_fetch_assoc(mysqli_query($db,"SELECT SUM(nominal) total FROM transaksi WHERE jenis='Pemasukan'"))['total'] ?? 0;
$total_keluar = mysqli_fetch_assoc(mysqli_query($db,"SELECT SUM(nominal) total FROM transaksi WHERE jenis='Pengeluaran' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"))['total'] ?? 0;

$qUser = mysqli_query($db,"SELECT nama,email,role,created_at FROM users ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/dashboard.css">

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<!-- AOS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<style>
  
</style>

<body>
<div class="container">

<!-- HEADER -->
<div class="header" data-aos="fade-down">
    <div>
        <h1>Dashboard Admin</h1>
        <small>Ringkasan keuangan sistem</small>
    </div>

    <div class="header-right">
        <div class="user-info">
            <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong><br>
            <small><?= date('F Y') ?></small>
        </div>
        <a href="../../login.php" class="logout-btn"
           onclick="return confirm('Yakin ingin logout?')">
            Logout
        </a>
    </div>
</div>


<!-- SUMMARY -->
<div class="stats">
    <div class="stat-card" data-aos="zoom-in">
        <span>Total User</span>
        <h2><?= $total_user ?></h2>
     
    </div>

    <div class="stat-card success" data-aos="zoom-in" data-aos-delay="150">
        <span>Total Pemasukan</span>
        <h2>Rp <?= number_format($total_masuk,0,',','.') ?></h2>
    
    </div>

    <div class="stat-card danger" data-aos="zoom-in" data-aos-delay="300">
        <span>Pengeluaran Bulan Ini</span>
        <h2>Rp <?= number_format($total_keluar,0,',','.') ?></h2>
  
    </div>
</div>

<!-- MENU -->
<div class="menu">
    <div class="menu-card" data-aos="fade-right">
        <h3>Manajemen User</h3>
        <p>Kelola akun pengguna dan role sistem.</p>
        <a href="user.php">Kelola User</a>
    </div>

    <div class="menu-card" data-aos="fade-left">
        <h3>Manajemen Keuangan</h3>
        <p>Monitoring pemasukan & pengeluaran.</p>
        <a href="keuangan.php">Kelola Keuangan</a>
    </div>
</div>

<!-- USER TERBARU -->
<div class="table-box" data-aos="fade-up">
    <h3>User Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        <?php while($u=mysqli_fetch_assoc($qUser)): ?>
        <tr>
            <td><?= htmlspecialchars($u['nama']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge <?= $u['role']=='admin'?'admin':'user' ?>"><?= ucfirst($u['role']) ?></span></td>
            <td><?= date('d M Y',strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({duration:900,once:true});</script>
</body>
</html>
