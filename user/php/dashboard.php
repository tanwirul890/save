




<?php
session_start();
include '../../function.php';

// Proteksi login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Total pemasukan
$q_pemasukan = mysqli_query($db, "
    SELECT SUM(nominal) AS total 
    FROM transaksi 
    WHERE id_user='$id_user' AND jenis='Pemasukan'
");
$pemasukan = mysqli_fetch_assoc($q_pemasukan)['total'] ?? 0;

// Total pengeluaran
$q_pengeluaran = mysqli_query($db, "
    SELECT SUM(nominal) AS total 
    FROM transaksi 
    WHERE id_user='$id_user' AND jenis='Pengeluaran'
");
$pengeluaran = mysqli_fetch_assoc($q_pengeluaran)['total'] ?? 0;

// Saldo
$saldo = $pemasukan - $pengeluaran;

// Pengeluaran per kategori (pie)
$q_kategori = mysqli_query($db, "
    SELECT k.nama_kategori, SUM(t.nominal) AS total
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id_kategori
    WHERE t.id_user='$id_user' AND t.jenis='Pengeluaran'
    GROUP BY k.nama_kategori
");
?>

<?php include '../../layout/header/header-user.php'; ?>

<style>
/* ================= ACTIVE SIDEBAR ================= */
.sidebar ul li:hover,
.sidebar ul li.satu {
    background: #EE6983;
    transform: translateX(6px);
}

/* ================= MAIN ================= */
.main {
    margin-left: 260px;
    padding: 40px 25px;
    transition: 0.3s;
}

h1 {
    text-align: center;
    font-family: 'Cherry Bomb One', cursive;
    color: #850E35;
    margin-bottom: 35px;
    font-size: 2.4rem;
}

/* ================= CARDS ================= */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.card {
    background: #fff;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    min-height: 120px;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-6px);
}

.card p {
    font-size: 14px;
    color: #777;
    margin-bottom: 8px;
}

.card h2 {
    font-size: 1.9rem;
    color: #2d3561;
    line-height: 1.2;
}

/* ================= CHART ================= */
.charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
    gap: 25px;
}

.chart-box {
    background: #fff;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.chart-box h3 {
    margin-bottom: 15px;
    color: #2d3561;
    font-size: 1.15rem;
    text-align: center;
}

/* biar chart auto */
.chart-box canvas {
    flex: 1;
    width: 80% !important;
    height: 80% !important;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
    .main {
        margin-left: 220px;
        padding: 30px 20px;
    }

    h1 {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px 15px;
    }

    h1 {
        font-size: 1.7rem;
        margin-bottom: 25px;
    }

    .cards {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .charts {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.5rem;
    }

    .card h2 {
        font-size: 1.4rem;
    }

    .chart-box {
        padding: 18px;
    }
}
</style>


<!-- ================= MAIN ================= -->
<div class="main">

    <div class="cards">
        <div class="card" data-aos="zoom-in">
            <p>Saldo</p>
            <h2>Rp <?= number_format($saldo,0,',','.') ?></h2>
        </div>

        <div class="card" data-aos="zoom-in">
            <p>Pemasukan</p>
            <h2>Rp <?= number_format($pemasukan,0,',','.') ?></h2>
        </div>

        <div class="card" data-aos="zoom-in">
            <p>Pengeluaran</p>
            <h2>Rp <?= number_format($pengeluaran,0,',','.') ?></h2>
        </div>
    </div>

    <div class="charts">
        <div class="chart-box" data-aos="fade-up">
            <h3>Pengeluaran per Kategori</h3>
            <canvas id="pieChart"></canvas>
        </div>

        <div class="chart-box" data-aos="fade-up">
            <h3>Perbandingan Kategori</h3>
            <canvas id="barChart"></canvas>
        </div>
    </div>

</div>



<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
<?php
$labels = [];
$totals = [];

mysqli_data_seek($q_kategori, 0); // reset pointer
while ($row = mysqli_fetch_assoc($q_kategori)) {
    $labels[] = $row['nama_kategori'];
    $totals[] = (int)$row['total'];
}
?>

const labels = <?= json_encode($labels) ?>;
const dataTotal = <?= json_encode($totals) ?>;

const colors = [
    '#EE6983',
    '#FFC4C4',
    '#850E35',
    '#FF9A8B',
    '#FEC8D8',
    '#E8A0BF'
];

// ================= PIE CHART =================
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            data: dataTotal,
            backgroundColor: colors.slice(0, dataTotal.length),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12,
                    padding: 12,
                    font: { size: 12 }
                }
            }
        }
    }
});

// ================= BAR CHART =================
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            data: dataTotal,
            backgroundColor: colors.slice(0, dataTotal.length),
            borderRadius: 8,
            barThickness: 35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>




<?php include '../../layout/footer/footer-user.php'; ?>
