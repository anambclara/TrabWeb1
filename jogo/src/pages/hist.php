<?php
session_start();
include("../config/connection.php");
include("../config/functions.php");


$user_data = check_login($con);
$user_id = $user_data['user_id'];

if ($con->connect_error) {
    die("Erro de conexão: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['matchResult'])) {
    $points = $_POST['matchResult'];
    $user_id = $_POST['user_id'];
    $match_id = $_POST['match_id'];

    $sql = "SELECT COUNT(*) as count FROM usuarios WHERE user_id = '$user_id'";
    $result = $con->query($sql);

    if ($result && $result->fetch_assoc()['count'] > 0) {
        $sqlInsertHistoric = "INSERT INTO historic (user_id, points, date_match) VALUES ('$user_id', '$points', NOW())";
        $result = $con->query($sqlInsertHistoric);

        if (!$result) {
            die("Erro ao inserir no histórico: " . $con->error);
        }
    }
}

$sqlHistoric = "SELECT points, date_match FROM historic WHERE user_id = '$user_id' ORDER BY date_match DESC";
$resultHistoric = $con->query($sqlHistoric);


$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/hist.css">
    <title>Histórico</title>
</head>

<body>
    <?php 
    
    $hideLoginCheck = true; 
    include("../components/header.php"); 
    ?>

    <main class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="max-width: 700px; width: 100%;">
            <div class="card-body">
                <h1 class="text-center mb-4" style="color: #ff66b2;">Histórico de Partidas</h1>
                <p class="text-center">Aqui você pode visualizar o histórico de suas partidas.</p>
                
                <?php if ($resultHistoric && $resultHistoric->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Pontos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $resultHistoric->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['date_match'])); ?></td>
                                    <td><strong><?php echo $row['points']; ?></strong></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-muted">Nenhuma partida registrada ainda.</p>
                        <p>Jogue algumas partidas para ver seu histórico aqui!</p>
                        <a href="jogo.php" class="btn" style="background-color: #ff66b2; color: white; border: none;">Jogar Agora</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>