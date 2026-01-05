<?php
session_start();
require '../../function.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

/* ===== STAT ===== */
$total_user = mysqli_fetch_assoc(mysqli_query($db,
    "SELECT COUNT(*) total FROM users"
))['total'] ?? 0;

$total_masuk = mysqli_fetch_assoc(mysqli_query($db,
    "SELECT SUM(nominal) total FROM transaksi 
     WHERE jenis='Pemasukan' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"
))['total'] ?? 0;

$total_keluar = mysqli_fetch_assoc(mysqli_query($db,
    "SELECT SUM(nominal) total FROM transaksi 
     WHERE jenis='Pengeluaran' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'"
))['total'] ?? 0;

$user_aktif = mysqli_fetch_assoc(mysqli_query($db,
    "SELECT COUNT(DISTINCT id_user) total FROM transaksi"
))['total'] ?? 0;

/* ===== DATA USER ===== */
$qUser = mysqli_query($db,"
SELECT u.id_user,u.nama,
SUM(CASE WHEN t.jenis='Pemasukan' THEN t.nominal ELSE 0 END) pemasukan,
SUM(CASE WHEN t.jenis='Pengeluaran' THEN t.nominal ELSE 0 END) pengeluaran
FROM users u
LEFT JOIN transaksi t 
ON u.id_user=t.id_user 
AND MONTH(t.tanggal)='$bulan' 
AND YEAR(t.tanggal)='$tahun'
GROUP BY u.id_user
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Keuangan</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/keuangan.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

</head>

<body>
<div class="container">

<!-- HEADER -->
<div class="header" data-aos="fade-down">
    <h1>Manajemen Keuangan</h1>
    <a class="back" href="dashboard.php">⬅ Dashboard</a>
</div>

<!-- STATS -->
<div class="stats">
    <div class="stat" data-aos="zoom-in">
        <small>Total User</small>
        <h2><?= $total_user ?></h2>
    </div>
    <div class="stat" data-aos="zoom-in" data-aos-delay="100">
        <small>Pemasukan</small>
        <h2>Rp <?= number_format($total_masuk,0,',','.') ?></h2>
    </div>
    <div class="stat" data-aos="zoom-in" data-aos-delay="200">
        <small>Pengeluaran</small>
        <h2>Rp <?= number_format($total_keluar,0,',','.') ?></h2>
    </div>
    <div class="stat" data-aos="zoom-in" data-aos-delay="300">
        <small>User Aktif</small>
        <h2><?= $user_aktif ?></h2>
    </div>
</div>

<!-- FILTER -->
<form class="filter" data-aos="fade-up">
    <select name="bulan">
        <?php for($i=1;$i<=12;$i++): ?>
        <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>>
            <?= date('F', mktime(0,0,0,$i,1)) ?>
        </option>
        <?php endfor; ?>
    </select>

    <select name="tahun">
        <?php for($t=date('Y')-3;$t<=date('Y');$t++): ?>
        <option <?= $tahun==$t?'selected':'' ?>><?= $t ?></option>
        <?php endfor; ?>
    </select>

    <button>Filter</button>
</form>

<!-- TABLE -->
<div class="table-box" data-aos="fade-up">
<table>
<thead>
<tr>
<th>User</th>
<th>Pemasukan</th>
<th>Pengeluaran</th>
<th>Saldo</th>

</tr>
</thead>
<tbody>

<?php while($u=mysqli_fetch_assoc($qUser)):
$saldo = $u['pemasukan'] - $u['pengeluaran'];
?>
<tr>
<td><?= htmlspecialchars($u['nama']) ?></td>
<td><span class="badge in">Rp <?= number_format($u['pemasukan'],0,',','.') ?></span></td>
<td><span class="badge out">Rp <?= number_format($u['pengeluaran'],0,',','.') ?></span></td>
<td class="total" style="color:<?= $saldo<0?'#EF4444':'#16A34A' ?>">
    Rp <?= number_format($saldo,0,',','.') ?>
</td>

</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:800,once:true});
</script>

</body>
</html>
