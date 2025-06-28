<?php 
	session_start();

	include("../config/auth.php");
	include("../config/connection.php");
	include("../config/functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if(!$err)
		{
			
			$check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
			$check_stmt = mysqli_prepare($con, $check_query);
			mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
			mysqli_stmt_execute($check_stmt);
			$result = mysqli_stmt_get_result($check_stmt);
			
			if(mysqli_num_rows($result) > 0) {
				$err_cadastro = "Usuário ou email já cadastrado.";
				$err = true;
			} else {
				
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				
				
				$query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
				$stmt = mysqli_prepare($con, $query);
				mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
				
				if(mysqli_stmt_execute($stmt)) {
					header("Location: login.php?success=1");
					die;
				} else {
					$err_cadastro = "Erro ao cadastrar. Tente novamente.";
					$err = true;
				}
			}
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
   

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h1 class="text-center mb-4" style="color: #ff66b2;">Cadastro</h1>
                        
                        <?php if(isset($err_cadastro)): ?>
                            <div class="alert alert-danger"><?= $err_cadastro ?></div>
                        <?php endif; ?>
                        
                        <form method="post" class="w-100">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Usuário</label>
                                    <input id="username" type="text" name="username" class="form-control" placeholder="Digite seu usuário" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
                                    <?php if(isset($err_username)): ?>
                                        <div class="text-danger small"><?= $err_username ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" class="form-control" placeholder="Digite seu email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                                    <?php if(isset($err_email)): ?>
                                        <div class="text-danger small"><?= $err_email ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="c_email" class="form-label">Confirmar Email</label>
                                    <input id="c_email" type="email" name="c_email" class="form-control" placeholder="Confirme seu email" value="<?= isset($c_email) ? htmlspecialchars($c_email) : '' ?>" required>
                                    <?php if(isset($err_c_email)): ?>
                                        <div class="text-danger small"><?= $err_c_email ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Senha</label>
                                    <input id="password" type="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                                    <?php if(isset($err_password)): ?>
                                        <div class="text-danger small"><?= $err_password ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="c_password" class="form-label">Confirmar Senha</label>
                                    <input id="c_password" type="password" name="c_password" class="form-control" placeholder="Confirme sua senha" required>
                                    <?php if(isset($err_c_password)): ?>
                                        <div class="text-danger small"><?= $err_c_password ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-grid mb-3">
                                <input type="submit" value="Cadastrar" class="btn w-100" style="background-color: #ff66b2; color: white; border: none;">
                            </div>
                        </form>
                        <p class="text-center">
                            Já tem cadastro? 
                            <a href="login.php" class="btn btn-outline-secondary">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>