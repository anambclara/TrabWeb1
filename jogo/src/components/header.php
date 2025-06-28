<?php

if (!isset($hideLoginCheck) || !$hideLoginCheck) {
    if (!isset($_SESSION)) {
        session_start();
    }
    include_once("../config/connection.php");
    include_once("../config/functions.php");
    
    
    $user_data = check_login($con);
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="p-3 bg-white text-dark border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
        <ul class="nav col-12 col-lg-auto mb-2 justify-content-center mb-md-0">
    <li><a href="index.php" class="nav-link px-2 text-dark <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
    <li><a href="hist.php" class="nav-link px-2 text-dark <?php echo ($current_page == 'hist.php') ? 'active' : ''; ?>">Histórico</a></li>
    <li><a href="ligas.php" class="nav-link px-2 text-dark <?php echo ($current_page == 'ligas.php') ? 'active' : ''; ?>">Minha Liga</a></li>
    <li><a href="ger_ligas.php" class="nav-link px-2 text-dark <?php echo ($current_page == 'ger_ligas.php') ? 'active' : ''; ?>">Criar Ligas</a></li>
</ul>
<a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</header>

<style>

.nav-link.active {
    background-color: #ff66b2 !important;
    color: white !important;
    border-radius: 5px;
    font-weight: bold;
}


.nav-link:hover {
    background-color: #ff99cc !important;
    color: white !important;
    border-radius: 5px;
    transition: all 0.3s ease;
}


.btn-logout {
    background-color: #ff66b2 !important;
    color: white !important;
    border: none !important;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background-color: #e55aa0 !important;
    color: white !important;
    transform: scale(1.05);
}

header {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>