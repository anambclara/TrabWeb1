<?php 
session_start();

include("../config/connection.php");
include("../config/functions.php");

$user_data = check_login($con);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="..\assets\css\index.css">
</head>
<body>
    <?php 
    
    $hideLoginCheck = true; 
    include("../components/header.php"); 
    ?>
    <main>
        <div class="page">
            <?php 
            if ($user_data) {
                echo '<h1> Bem vindo, ' . $user_data['username'] . "</h1>";
            } else {
                header("Location: login.php");
                exit();
            }
            ?>
            <div class="btn" id="divButtonStart">
                <a href="jogo.php"><button type="button" class="btn btn-lg" id="buttonStart">JOGAR</button></a>
            </div>
        </div>
    </main>
</body>
</html>