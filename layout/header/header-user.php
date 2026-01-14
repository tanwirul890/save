<?php 
$halaman = basename($_SERVER['REQUEST_URI']);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SAVE</title>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/laporan.css">
<link rel="stylesheet" href="../css/transactions.css">
<link rel="stylesheet" href="../css/budget.css">

<!-- FONT & ICON -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

<!-- AOS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins';
    background: #fecaca;
}

/* ================= BURGER MENU ================= */
.burger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 5px;
    z-index: 101;
}

.burger span {
    width: 25px;
    height: 3px;
    background: #850E35;
    border-radius: 3px;
    transition: 0.3s;
}

.burger.active span:nth-child(1) {
    transform: rotate(45deg) translate(7px, 7px);
}

.burger.active span:nth-child(2) {
    opacity: 0;
}

.burger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

/* ================= SIDEBAR ================= */
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    background: #FFC4C4;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 2px 0 15px rgba(0,0,0,0.3);
    z-index: 100;
}

.sidebar-profile {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.sidebar h3 {
    font-size: 14px;
}

.sidebar span {
    color: #850E35;
}

.sidebar ul {
    list-style: none;
}

.sidebar ul li {
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    background: #ee69847a;
    transition: 0.3s;
}

.sidebar ul li.active{
     background:#EE6983;
    color:#fff;
}




.sidebar a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logout {
    padding: 12px 15px;
    border-radius: 8px;
    background: #ee69847a;
    transition: 0.3s;
}

.logout:hover {
    background: #EE6983;
    transform: translateX(5px);
}

.logout a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}



/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
    .sidebar {
        width: 220px;
        padding: 25px 15px;
    }

    .sidebar h3 {
        font-size: 13px;
    }

}

@media (max-width: 768px) {
    .burger {
        display: flex;
        position: fixed;
        top: 20px;
        left: 20px;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: fixed;
        top: 0;
        left: -100%;
        padding: 80px 20px 20px 20px;
        flex-direction: column;
        justify-content: flex-start;
        transition: 0.3s;
        z-index: 99;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-profile {
        margin-bottom: 20px;
    }

    .sidebar-profile i {
        font-size: 2rem !important;
    }

    .sidebar h3 {
        font-size: 12px;
    }

}

@media (max-width: 480px) {
    .sidebar-profile {
        width: 100%;
        margin-bottom: 15px;
    }

    .sidebar-profile i {
        font-size: 1.5rem !important;
    }

    .sidebar h3 {
        font-size: 11px;
    }

   
    .logout {
        width: 100%;
    }
}
</style>
</head>

<body>

<!-- ================= BURGER MENU ================= -->
<div class="burger" id="burger">
    <span></span>
    <span></span>
    <span></span>
</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar" id="sidebar" data-aos="fade-right">
    <div>

        <!-- PROFILE -->
        <div class="sidebar-profile">
            <?php
            $id_user = $_SESSION['id_user'];
            $halaman = basename($_SERVER['PHP_SELF']);

            $qProfile = mysqli_query($db, "SELECT foto FROM profile WHERE id_user='$id_user'");
            $profile  = mysqli_fetch_assoc($qProfile);
            ?>

            <a href="profile.php">
                <?php if (!empty($profile['foto'])) : ?>
                    <img src="../img/<?= $profile['foto']; ?>" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                <?php else : ?>
                    <i class="fas fa-user-circle fa-3x"></i>
                <?php endif; ?>
            </a>

            <h3>
                <span>Selamat Datang</span><br>
                <?= htmlspecialchars($_SESSION['nama']); ?>
            </h3>
        </div>

        <!-- MENU -->
      <ul>
    <li class="<?= ($halaman == 'dashboard.php') ? 'active' : '' ?>">
        <a href="dashboard.php">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </li>

    <li class="<?= ($halaman == 'transactions.php') ? 'active' : '' ?>">
        <a href="transactions.php">
            <i class="fas fa-exchange-alt"></i> Transaksi
        </a>
    </li>

    <li class="<?= ($halaman == 'laporan.php') ? 'active' : '' ?>">
        <a href="laporan.php">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
    </li>

    <li class="<?= ($halaman == 'budget.php') ? 'active' : '' ?>">
        <a href="budget.php">
            <i class="fas fa-calculator"></i> Budget
        </a>
    </li>
</ul>

    </div>

    <!-- LOGOUT -->
    <div class="logout">
        <a href="../../login.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>
