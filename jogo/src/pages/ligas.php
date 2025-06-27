<?php
session_start();
include("../config/connection.php");
include("../config/functions.php");

$user_data = check_login($con);
$user_id = $user_data['user_id'];

$sqlClanInfo = "SELECT c.clan_name, c.clan_id 
                FROM users u 
                LEFT JOIN clans c ON u.clan_id = c.clan_id 
                WHERE u.user_id = '$user_id'";
$resultClanInfo = $con->query($sqlClanInfo);

$weeklyPoints = 0;
$totalClanPoints = 0;
$resultClanMembers = null;

if ($resultClanInfo && $resultClanInfo->num_rows > 0) {
    $clanInfo = $resultClanInfo->fetch_assoc();
    $clan_id = $clanInfo['clan_id'];
    
    if ($clan_id) {
        $sqlWeeklyPoints = "SELECT COALESCE(SUM(h.points), 0) as weekly_points 
                           FROM historic h 
                           JOIN users u ON h.user_id = u.user_id 
                           WHERE u.clan_id = '$clan_id' 
                           AND h.date_match >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $resultWeeklyPoints = $con->query($sqlWeeklyPoints);
        if ($resultWeeklyPoints && $resultWeeklyPoints->num_rows > 0) {
            $weeklyData = $resultWeeklyPoints->fetch_assoc();
            $weeklyPoints = $weeklyData['weekly_points'];
        }
        
        $sqlClanMembers = "SELECT u.username, COALESCE(SUM(h.points), 0) as total_points 
                          FROM users u 
                          LEFT JOIN historic h ON u.user_id = h.user_id 
                          WHERE u.clan_id = '$clan_id' 
                          GROUP BY u.user_id, u.username 
                          ORDER BY total_points DESC";
        $resultClanMembers = $con->query($sqlClanMembers);
        
        $sqlTotalClanPoints = "SELECT COALESCE(SUM(h.points), 0) as total_points 
                              FROM historic h 
                              JOIN users u ON h.user_id = u.user_id 
                              WHERE u.clan_id = '$clan_id'";
        $resultTotalClanPoints = $con->query($sqlTotalClanPoints);
        if ($resultTotalClanPoints && $resultTotalClanPoints->num_rows > 0) {
            $totalData = $resultTotalClanPoints->fetch_assoc();
            $totalClanPoints = $totalData['total_points'];
        }
    }
    
    $resultClanInfo->data_seek(0);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Liga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/liga.css">
    
</head>

<body>
    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <ul class="nav col-12 col-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="hist.php" class="nav-link px-2 text-white">Histórico</a></li>
                    <li><a href="ligas.php" class="nav-link px-2 text-white">Minha Liga</a></li>
                    <li><a href="ger_ligas.php" class="nav-link px-2 text-white">Criar/Entrar Ligas</a></li>
                </ul>
                <a href="logout.php" class="btn btn-warning">Logout</a>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4" style="color: #ff66b2;">Liga em que estou</h3>
                        <p class="card-text">
                            <?php
                            if ($resultClanInfo->num_rows > 0) {
                                $rowClanInfo = $resultClanInfo->fetch_assoc();
                                echo "<strong>{$rowClanInfo['clan_name']}</strong>";
                            } else {
                                echo "Você não está em nenhuma liga.";
                            }
                            ?>
                        </p>
                        <hr>
                        <h5>Pontuação Semanal da Liga</h5>
                        <p>Pontuação Total Semanal: <strong><?php echo $weeklyPoints; ?></strong></p>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #ff66b2;">Membros da Liga</h3>
                        <p>Total de Pontos da Liga: <strong><?php echo $totalClanPoints; ?></strong></p>
                        <table class="table table-bordered mt-3 bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>Username</th>
                                    <th>Pontuação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultClanMembers->num_rows > 0) {
                                    while ($rowClanMembers = $resultClanMembers->fetch_assoc()) {
                                        echo "<tr><td>{$rowClanMembers['username']}</td><td>{$rowClanMembers['total_points']}</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>Nenhum membro encontrado.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4" style="color: #ff66b2;">Ligas Existentes</h3>
                        <table class="table table-bordered bg-white mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlOtherGuilds = "SELECT clan_name FROM clans";
                                $resultOtherGuilds = $con->query($sqlOtherGuilds);
                                while ($rowOtherGuilds = $resultOtherGuilds->fetch_assoc()) {
                                    echo "<tr><td>{$rowOtherGuilds['clan_name']}</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
