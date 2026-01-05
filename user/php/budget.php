<?php
session_start();
require '../../function.php';

if(!isset($_SESSION['id_user'])){
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$bulan   = date('F');   // contoh: December
$tahun   = date('Y');

/* ================= TAMBAH BUDGET ================= */
if(isset($_POST['simpan_budget'])){
    $id_kategori  = $_POST['id_kategori'];
    $limit_budget = $_POST['limit_budget'];

    // Cek sudah ada atau belum
    $cek = mysqli_query($db,"
        SELECT * FROM budget 
        WHERE id_user='$id_user'
        AND id_kategori='$id_kategori'
        AND bulan='$bulan'
        AND tahun='$tahun'
    ");

    if(mysqli_num_rows($cek)==0){
        mysqli_query($db,"
            INSERT INTO budget 
            (id_user,id_kategori,bulan,tahun,limit_budget)
            VALUES
            ('$id_user','$id_kategori','$bulan','$tahun','$limit_budget')
        ");
    }
}

/* ================= UPDATE BUDGET ================= */
if(isset($_POST['update_budget'])){
    $id_kategori  = $_POST['id_kategori'];
    $limit_budget = $_POST['limit_budget'];

    mysqli_query($db,"
        UPDATE budget SET limit_budget='$limit_budget'
        WHERE id_user='$id_user'
        AND id_kategori='$id_kategori'
        AND bulan='$bulan'
        AND tahun='$tahun'
    ");
}

/* ================= HAPUS BUDGET ================= */
if(isset($_POST['hapus_budget'])){
    $id_kategori = $_POST['id_kategori'];

    mysqli_query($db,"
        DELETE FROM budget
        WHERE id_user='$id_user'
        AND id_kategori='$id_kategori'
        AND bulan='$bulan'
        AND tahun='$tahun'
    ");
}

/* ================= TOTAL ================= */
$qTotalBudget = mysqli_query($db,"
    SELECT SUM(limit_budget) AS total
    FROM budget
    WHERE id_user='$id_user'
    AND bulan='$bulan'
    AND tahun='$tahun'
");
$total_budget = mysqli_fetch_assoc($qTotalBudget)['total'] ?? 0;

$qTotalOut = mysqli_query($db,"
    SELECT SUM(nominal) AS total
    FROM transaksi
    WHERE id_user='$id_user'
    AND jenis='Pengeluaran'
    AND MONTH(tanggal)=MONTH(CURDATE())
    AND YEAR(tanggal)=YEAR(CURDATE())
");
$total_out = mysqli_fetch_assoc($qTotalOut)['total'] ?? 0;

/* ================= DATA ================= */
$qKategori = mysqli_query($db,"
    SELECT 
        k.id_kategori,
        k.nama_kategori,
        IFNULL(b.limit_budget,0) AS limit_budget,
        IFNULL(SUM(t.nominal),0) AS terpakai
    FROM kategori k
    LEFT JOIN budget b 
        ON b.id_kategori=k.id_kategori
        AND b.id_user='$id_user'
        AND b.bulan='$bulan'
        AND b.tahun='$tahun'
    LEFT JOIN transaksi t
        ON t.id_kategori=k.id_kategori
        AND t.id_user='$id_user'
        AND t.jenis='Pengeluaran'
        AND MONTH(t.tanggal)=MONTH(CURDATE())
        AND YEAR(t.tanggal)=YEAR(CURDATE())
    WHERE k.jenis='Pengeluaran'
    GROUP BY k.id_kategori,k.nama_kategori,b.limit_budget
");

$qKategoriInput = mysqli_query($db,"
    SELECT * FROM kategori WHERE jenis='Pengeluaran'
");
?>

<?php include '../../layout/header/header-user.php'; ?>


<div class="main">

<!-- SUMMARY -->
<div class="summary">
    <div class="box" data-aos="fade-right">
        <small>Total Budget</small>
        <h2>Rp <?= number_format($total_budget,0,',','.') ?></h2>
    </div>

    <div class="box" data-aos="fade-left">
        <small>Terpakai</small>
        <h2 style="color:#d32f2f">
            Rp <?= number_format($total_out,0,',','.') ?>
        </h2>
    </div>
</div>

<!-- FORM INPUT -->
<div class="form-budget" data-aos="zoom-in">
<form method="post">
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($k=mysqli_fetch_assoc($qKategoriInput)): ?>
            <option value="<?= $k['id_kategori'] ?>">
                <?= $k['nama_kategori'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="number" name="limit_budget" placeholder="Limit Budget" required>
    <button name="simpan_budget" class="btn-add">Tambah</button>
</form>
</div>

<!-- LIST -->
<?php 
$i = 0;
while($k=mysqli_fetch_assoc($qKategori)):
    $i++;
    $persen = $k['limit_budget']>0 
        ? min(100,($k['terpakai']/$k['limit_budget'])*100) 
        : 0;

    $isEdit = isset($_POST['edit_id']) && $_POST['edit_id']==$k['id_kategori'];
?>
<div class="cat-card" data-aos="fade-up" data-aos-delay="<?= $i*80 ?>">
    
    <div class="cat-header">
        <strong><?= $k['nama_kategori'] ?></strong>
        <div style="display:flex;gap:6px">
            <form method="post">
                <input type="hidden" name="edit_id" value="<?= $k['id_kategori'] ?>">
                <button class="btn-edit">Edit</button>
            </form>

            <form method="post" onsubmit="return confirm('Hapus budget ini?')">
                <input type="hidden" name="id_kategori" value="<?= $k['id_kategori'] ?>">
                <button name="hapus_budget" class="btn-hapus">Hapus</button>
            </form>
        </div>
    </div>

<?php if($isEdit): ?>
    <form method="post" data-aos="fade-in">
        <input type="hidden" name="id_kategori" value="<?= $k['id_kategori'] ?>">
        <input type="number" name="limit_budget" value="<?= $k['limit_budget'] ?>" required>
        <button name="update_budget" class="btn-edit">Simpan</button>
        <a href="" class="btn-batal">Batal</a>
    </form>
<?php else: ?>
    <p>
        Rp <?= number_format($k['terpakai'],0,',','.') ?> /
        <?= number_format($k['limit_budget'],0,',','.') ?>
    </p>

    <div class="progress">
        <div style="width:<?= $persen ?>%"></div>
    </div>

    <small><?= round($persen) ?>% terpakai</small>
<?php endif; ?>

</div>
<?php endwhile; ?>

</div>


<?php include '../../layout/footer/footer-user.php'; ?>
