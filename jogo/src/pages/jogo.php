<?php

session_start();
include("../config/connection.php");
include("../config/functions.php");
include("../components/header.php");
$user_data = check_login($con);
$user_id = $user_data['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["letterspoints"])) {
    $points = $_POST["letterspoints"];
    if ($points > 0) {
        $sql = "INSERT INTO historic (user_id, points, date_match) VALUES ('$user_id', '$points', NOW())";
        $result = $con->query($sql);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Pontuação salva']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar: ' . $con->error]);
        }
        exit; 
    }
}
$sqlHighestScore = "SELECT MAX(points) as highestScore FROM historic WHERE user_id = '$user_id'";
$resultHighestScore = $con->query($sqlHighestScore);
$highestScore = $resultHighestScore->fetch_assoc()['highestScore']; 
$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/jogo.css">
  <title>Jogo</title>
</head>

<body>
  <header class="p-3  menu">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap" />
                    </svg>
                </a>
                
                
            </div>
        </div>
  </header>
  <main class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg" style="max-width: 700px; width: 100%;">
      <div class="card-body text-center">
        
        <div id="content">
          <div class="button" id="divButton">
            <button type="button" class="btn btn-rosa btn-lg" style="font-size: 50px;" id="button">JOGAR</button>
            <p id="texto">Faça seus dedos voarem pelo teclado com o jogo de digitação e em 60 segundos descubra quem é o mais rápido</p>
          </div>
        </div>
       
        <div id="timer_structure" style="display:none;">
          <div class="timer" id="timer" style="font-size: 2.5rem; color: #ff66b2;"></div>
        </div>
        
        <div class="containerJogo" style="display:none;">
          <div class="quote-display" id="quoteDisplay"></div>
          <textarea id="quoteInput" class="quote-input" autofocus></textarea>
        </div>
        
        <div class="result" id="divResults" style="display:none;">
          <div class="titleResult">
            <div class="pointsResult" id="pointsResult">
              <h3>Pontos da partida:</h3>
              <h4 id="matchResult"></h4>
              <h3>Recorde</h3>
              <h4 id="highScore"><?php echo $highestScore;?></h4>
              <button id="ButtonRestart" type="button" class="btn btn-rosa btn-lg" style="font-size: 50px;">Jogar novamente</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="../assets/js/jogo.js" defer></script>
</body>

</html>