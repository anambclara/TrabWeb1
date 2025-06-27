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
