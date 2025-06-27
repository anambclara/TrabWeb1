<?php
session_start();
include("../config/connection.php");

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
    <header class="p-3 menu bg-dark text-white fixed-top">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap" />
                    </svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="hist.php" class="nav-link px-2 text-white">Histórico</a></li>
                    <li><a href="ligas.php" class="nav-link px-2 text-white">Minha Liga</a></li>
                    <li><a href="ger_ligas.php" class="nav-link px-2 text-white">Criar/Entrar Ligas</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="max-width: 700px; width: 100%;">
            <div class="card-body">
                <h1 class="text-center mb-4 text-danger">Histórico de Partidas</h1>
                <p class="text-center">Aqui você pode visualizar o histórico de suas partidas.</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Pontos</th>
                        </tr>
                    </thead>
                 
                </table>
            </div>
        </div>
    </main>
</body>

</html>