<?php
session_start();
require '../../function.php';

if (!isset($_SESSION['id_user'])) exit;

$id_user = $_SESSION['id_user'];
$dari   = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$jenis  = $_GET['jenis'] ?? 'Semua';

/* ================= WHERE ================= */
$where = "WHERE t.id_user='$id_user'";

if ($dari && $sampai) {
    $where .= " AND t.tanggal BETWEEN '$dari' AND '$sampai'";
}
if ($jenis !== 'Semua') {
    $where .= " AND t.jenis='$jenis'";
}

/* ================= QUERY ================= */
$q = mysqli_query($db,"
    SELECT t.tanggal, k.nama_kategori, t.jenis, t.nominal
    FROM transaksi t
    JOIN kategori k ON t.id_kategori=k.id_kategori
    $where
    ORDER BY t.tanggal DESC
");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_keuangan.xls");

echo "Tanggal\tKategori\tJenis\tNominal\n";

while($r=mysqli_fetch_assoc($q)){
    echo $r['tanggal']."\t".
         $r['nama_kategori']."\t".
         $r['jenis']."\t".
         $r['nominal']."\n";
}
