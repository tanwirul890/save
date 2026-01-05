<?php
session_start();
require '../../function.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$dari   = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$jenis  = $_GET['jenis'] ?? 'Semua';

/* ================= WHERE ================= */
$where = "WHERE t.id_user='$id_user'";

if ($dari && $sampai) {
    $where .= " AND t.tanggal BETWEEN '$dari' AND '$sampai'";
}

if ($jenis != 'Semua') {
    $where .= " AND t.jenis='$jenis'";
}

/* ================= DATA ================= */
$q_laporan = mysqli_query($db,"
    SELECT t.tanggal, k.nama_kategori, t.jenis, t.nominal
    FROM transaksi t
    JOIN kategori k ON t.id_kategori=k.id_kategori
    $where
    ORDER BY t.tanggal DESC
");

/* ================= TOTAL ================= */
$q_in  = mysqli_query($db,"SELECT SUM(nominal) total FROM transaksi WHERE id_user='$id_user' AND jenis='Pemasukan'");
$q_out = mysqli_query($db,"SELECT SUM(nominal) total FROM transaksi WHERE id_user='$id_user' AND jenis='Pengeluaran'");
$q_fil = mysqli_query($db,"SELECT SUM(nominal) total FROM transaksi t $where");

$total_in     = mysqli_fetch_assoc($q_in)['total'] ?? 0;
$total_out    = mysqli_fetch_assoc($q_out)['total'] ?? 0;
$total_filter = mysqli_fetch_assoc($q_fil)['total'] ?? 0;

/* ================= PER KATEGORI ================= */
$q_kategori = mysqli_query($db,"
    SELECT k.nama_kategori, SUM(t.nominal) total
    FROM transaksi t
    JOIN kategori k ON t.id_kategori=k.id_kategori
    $where
    GROUP BY k.id_kategori
");



?>

<?php include '../../layout/header/header-user.php'; ?>

<!-- AOS -->



<div class="main">

<!-- FILTER + EXPORT -->
<div class="filter-wrapper" data-aos="fade-up">
    <form class="filter-box" method="GET">
        <input type="date" name="dari" value="<?= $dari ?>">
        <input type="date" name="sampai" value="<?= $sampai ?>">
        <select name="jenis">
            <option value="Semua">Semua</option>
            <option value="Pemasukan" <?= $jenis=='Pemasukan'?'selected':'' ?>>Pemasukan</option>
            <option value="Pengeluaran" <?= $jenis=='Pengeluaran'?'selected':'' ?>>Pengeluaran</option>
        </select>
        <button type="submit">Terapkan</button>
    </form>

    <div class="export-box">
        <a href="export_excel.php?<?= $_SERVER['QUERY_STRING'] ?>" class="btn-export excel">Export Excel</a>
    </div>
</div>

<!-- TABLE -->
<table data-aos="zoom-in">
<tr>
    <th>Tanggal</th>
    <th>Kategori</th>
    <th>Jenis</th>
    <th>Nominal</th>
</tr>

<?php if(mysqli_num_rows($q_laporan)==0): ?>
<tr><td colspan="4" align="center">Tidak ada data</td></tr>
<?php endif; ?>

<?php while($r=mysqli_fetch_assoc($q_laporan)): ?>
<tr>
    <td data-label="Tanggal"><?= $r['tanggal'] ?></td>
    <td data-label="Kategori"><?= $r['nama_kategori'] ?></td>
    <td data-label="Jenis"><?= $r['jenis'] ?></td>
    <td data-label="Nominal">Rp <?= number_format($r['nominal'],0,',','.') ?></td>
</tr>
<?php endwhile; ?>
</table>

<!-- TOTAL -->
<div class="total-box" data-aos="fade-up">
    Total Filter : Rp <?= number_format($total_filter,0,',','.') ?><br>
    Total Pemasukan : Rp <?= number_format($total_in,0,',','.') ?><br>
    Total Pengeluaran : Rp <?= number_format($total_out,0,',','.') ?><br>
    Saldo : Rp <?= number_format($total_in - $total_out,0,',','.') ?>
</div>

<!-- PER KATEGORI -->
<div class="kategori-box" data-aos="fade-up">
    <h3>Total per Kategori</h3>
    <?php while($k=mysqli_fetch_assoc($q_kategori)): ?>
        <p>
            <span><?= $k['nama_kategori'] ?></span>
            <b>Rp <?= number_format($k['total'],0,',','.') ?></b>
        </p>
    <?php endwhile; ?>
</div>

</div>



<?php include '../../layout/footer/footer-user.php'; ?>
