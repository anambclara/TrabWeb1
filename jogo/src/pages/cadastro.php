<?php 
	session_start();

	include("../config/auth.php");
	include("../config/connection.php");
	include("../config/functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$username = $_POST['username'];
		$email = $_POST['email'];
		$c_email = $_POST['c_email'];
		$password = $_POST['password'];
		$c_password = $_POST['c_password'];

		if(!$err)
		{
	
			$query = "insert into users (username,email,password) values ('$username','$email','$password')";

			mysqli_query($con, $query);

			header("Location: login.php");
			die;
		}else
		{
			
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/cadastro.css">
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
                    <li><a href="" class="nav-link px-2 text-white">Home</a></li>
                </ul>
                
            </div>
        </div>
    </header>

    
    
        <div class="card shadow-lg" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h1 class="text-center mb-4 text-danger">Cadastro</h1>
                <form method="post" class="w-100">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Usuário</label>
                            <input id="username" type="text" name="username" class="form-control" placeholder="Digite seu usuário" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" placeholder="Digite seu email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Senha</label>
                            <input id="password" type="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_password" class="form-label">Confirmar Senha</label>
                            <input id="c_password" type="password" name="c_password" class="form-control" placeholder="Confirme sua senha" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <input id="button" type="submit" value="Cadastrar" class="btn btn-primary">
                    </div>
                </form>
                <p class="text-center mt-3">
                    Já tem cadastro? 
                    <a href="login.php" class="btn btn-warning">Login</a>
                </p>
            </div>
        </div>
    
</body>
</html>
