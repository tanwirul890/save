<?php
session_start();
require '../../function.php'; // koneksi $conn

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ===============================
   AMBIL DATA KATEGORI
================================ */
$q_kategori = mysqli_query($db, "
    SELECT * FROM kategori
    ORDER BY jenis, nama_kategori
");

/* ===============================
   TAMBAH TRANSAKSI
================================ */
if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $id_kategori = $_POST['id_kategori'];
    $nominal = $_POST['nominal'];

    // Ambil jenis dari kategori
    $qJenis = mysqli_query($db, "
        SELECT jenis FROM kategori WHERE id_kategori='$id_kategori'
    ");
    $jenis = mysqli_fetch_assoc($qJenis)['jenis'];

    mysqli_query($db, "
        INSERT INTO transaksi (id_user, id_kategori, jenis, nominal, tanggal)
        VALUES ('$id_user','$id_kategori','$jenis','$nominal','$tanggal')
    ");

    header("Location: transactions.php");
    exit;
}

/* ===============================
   RINGKASAN BULAN INI
================================ */
$q_pemasukan = mysqli_query($db, "
    SELECT IFNULL(SUM(nominal),0) total
    FROM transaksi
    WHERE id_user='$id_user'
      AND jenis='Pemasukan'
      AND MONTH(tanggal)=MONTH(CURDATE())
");
$pemasukan = mysqli_fetch_assoc($q_pemasukan)['total'];

$q_pengeluaran = mysqli_query($db, "
    SELECT IFNULL(SUM(nominal),0) total
    FROM transaksi
    WHERE id_user='$id_user'
      AND jenis='Pengeluaran'
      AND MONTH(tanggal)=MONTH(CURDATE())
");
$pengeluaran = mysqli_fetch_assoc($q_pengeluaran)['total'];

/* ===============================
   LIST TRANSAKSI
================================ */
$q_transaksi = mysqli_query($db, "
    SELECT t.tanggal, k.nama_kategori, t.nominal, t.jenis
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id_kategori
    WHERE t.id_user='$id_user'
    ORDER BY t.tanggal DESC
");
?>

<?php include '../../layout/header/header-user.php'; ?>



<div class="main">
    <div class="card" data-aos="fade-up">
        <h2>Tambah Transaksi</h2>

        <form method="POST" class="form-group">
            <input type="date" name="tanggal" required data-aos="fade-right">
            <select name="id_kategori" required data-aos="fade-right" data-aos-delay="100">
                <option value="">Pilih Kategori</option>
                <?php while($k = mysqli_fetch_assoc($q_kategori)): ?>
                    <option value="<?= $k['id_kategori'] ?>">
                        <?= $k['nama_kategori'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="nominal" placeholder="Nominal" required data-aos="fade-right" data-aos-delay="200">
            <button class="btn" name="tambah" data-aos="fade-right" data-aos-delay="300">Tambah</button>
        </form>

        <div class="summary" data-aos="zoom-in" data-aos-delay="400">
            <div class="summary-item">
                <small>Pemasukan Bulan Ini</small>
                <span class="in">Rp <?= number_format($pemasukan, 0, ',', '.') ?></span>
            </div>
            <div class="summary-item">
                <small>Pengeluaran Bulan Ini</small>
                <span class="out">Rp <?= number_format($pengeluaran, 0, ',', '.') ?></span>
            </div>
        </div>

        <h3>Daftar Transaksi</h3>
        <div class="list">
            <?php $delay = 0; while($t = mysqli_fetch_assoc($q_transaksi)): ?>
                <div class="row" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                    <span><?= date('d/m/Y', strtotime($t['tanggal'])) ?></span>
                    <span><?= $t['nama_kategori'] ?></span>
                    <span class="<?= ($t['jenis'] == 'Pemasukan') ? 'in' : 'out' ?>">
                        Rp <?= number_format($t['nominal'], 0, ',', '.') ?>
                    </span>
                </div>
            <?php $delay += 50; endwhile; ?>
        </div>
    </div>
</div>




<?php include '../../layout/footer/footer-user.php'; ?>
