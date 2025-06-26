<?php 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$conn = new mysqli($servername, $username, $password);
	
	if ($conn->connect_error) {
		die("Conexão falhou: " . $conn->connect_error);
	}
	
	$databaseName = "trabalhoweb";
	$query = "SHOW DATABASES LIKE '$databaseName'";
	$result = $conn->query($query);
	
	if ($result) {
		if ($result->num_rows > 0) {
			
			$conn->select_db($databaseName);
			//---------------------------------------------------
			$tableQueryclans = "SHOW TABLES LIKE 'clans'";
			$tableResult = $conn->query($tableQueryclans);
			if ($tableResult->num_rows == 0) {
				$createTableQueryclans = "
					CREATE TABLE clans (
						clan_id INT NOT NULL AUTO_INCREMENT,
						clan_name VARCHAR(100) NOT NULL,
						clan_password VARCHAR(100),
						PRIMARY KEY (clan_id)
					);
				";
				$conn->query($createTableQueryclans);
			}
			//-------------------------------------------------
			$tableQueryusers = "SHOW TABLES LIKE 'users'";
			$tableResult = $conn->query($tableQueryusers);
			if ($tableResult->num_rows == 0) {
				$createTableQueryusers = "
					CREATE TABLE users(
						user_id INT NOT NULL AUTO_INCREMENT,
						clan_id INT,
						username VARCHAR(100) NOT NULL,
						email VARCHAR(200) NOT NULL,
						password VARCHAR(200) NOT NULL,
						CONSTRAINT PKUSER PRIMARY KEY (user_id),
						CONSTRAINT FKUSERCLAN FOREIGN KEY (clan_id) REFERENCES clans(clan_id)
					);
				";
				$conn->query($createTableQueryusers);
			} 
			//-----------------------------------------------
			$tableQueryhistoric = "SHOW TABLES LIKE 'historic'";
			$tableResult = $conn->query($tableQueryhistoric);
			if ($tableResult->num_rows == 0) {
				$createTableQueryhistoric = "
					CREATE TABLE historic (
						match_id SERIAL,
						user_id INT NOT NULL,
						points INT NOT NULL,
						date_match TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						CONSTRAINT PKHISTORIC PRIMARY KEY(match_id),
						CONSTRAINT FKHISTORICUSER FOREIGN KEY (user_id) REFERENCES users(user_id)
					);
				";
				$conn->query($createTableQueryhistoric) ;
			} 
			//-----------------------------------------------
		} else {
			$createDatabaseQuery = "CREATE DATABASE $databaseName";
			if ($conn->query($createDatabaseQuery) === TRUE) {
				
				$conn->select_db($databaseName);
				//-------------------------------------
				$createTableQueryclans = "
					CREATE TABLE clans (
						clan_id INT NOT NULL AUTO_INCREMENT,
						clan_name VARCHAR(100) NOT NULL,
						clan_password VARCHAR(100),
						PRIMARY KEY (clan_id)
					);
				";
				$conn->query($createTableQueryclans);
				//-------------------------------------------------
				$createTableQueryusers = "
					CREATE TABLE users(
						user_id INT NOT NULL AUTO_INCREMENT,
						clan_id INT,
						username VARCHAR(100) NOT NULL,
						email VARCHAR(200) NOT NULL,
						password VARCHAR(200) NOT NULL,
						CONSTRAINT PKUSER PRIMARY KEY (user_id),
						CONSTRAINT FKUSERCLAN FOREIGN KEY (clan_id) REFERENCES clans(clan_id)
					);
				";
				$conn->query($createTableQueryusers);
				//---------------------------------------------
				$createTableQueryhistoric = "
					CREATE TABLE historic (
						match_id SERIAL,
						user_id INT NOT NULL,
						points INT NOT NULL,
						date_match TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						CONSTRAINT PKHISTORIC PRIMARY KEY(match_id),
						CONSTRAINT FKHISTORICUSER FOREIGN KEY (user_id) REFERENCES users(user_id)
					);
				";
				$conn->query($createTableQueryhistoric);
				//--------------------------------------------
			} 
		}
	}
	
	$conn->close();
	session_start();

	include("../config/connection.php");
	include("../config/functions.php");

	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{

		$username = $_POST['username'];
		$password = $_POST['password'];

		if(!empty($username) && !empty($password))
		{

			$query = "select * from users where username = '$username' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css"> <!-- Seu CSS personalizado -->
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo!</h1>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <p class="mt-3">Não tem uma conta? <a href="cadastro.php" class="btn btn-link">Cadastre-se</a></p>
    </div>
</body>
</html>