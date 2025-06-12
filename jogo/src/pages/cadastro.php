<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="..\public\signup.css">
</head>
<body>
	<header class="p-3 menu">
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
                <div class="text-end">
                    <a href="login.php"><button type="button"
                            class="btn btn-warning">Login</button></a>
                </div>
            </div>
        </div>
    </header>
	<main>
		<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
			<div class="card p-4" style="width: 300px;">
				<form method="post" class="formLogin" id="formLogin">
					<h1 class="mb-4 text-center">Cadastro</h1>

					<div class="form-group mb-3">
						<label for="username" class="form-label">Username</label>
						<input id="username" type="text" name="username" class="form-control" placeholder="Digite seu username" autofocus="true">
						<?php 
							if (!empty($err_username)) {
								echo "<p class='text-danger'>" . $err_username . "</p>";
							}
						?>
					</div>

					<div class="form-group mb-3">
						<label for="email" class="form-label">Email</label>
						<input id="email" type="text" name="email" class="form-control" placeholder="Digite seu Email" autofocus="true">
						<?php 
							if (!empty($err_email)) {
								echo "<p class='text-danger'>" . $err_email . "</p>";
							}
						?>
					</div>

					<div class="form-group mb-3">
						<label for="c_email" class="form-label">Confirmar email</label>
						<input id="c_email" type="text" name="c_email" class="form-control" placeholder="Confirme seu Email" autofocus="true">
						<?php
							if (!empty($err_c_email)) {
								echo "<p class='text-danger'>" . $err_c_email . "</p>";
							}
						?>
					</div>

					<div class="form-group mb-3">
						<label for="password" class="form-label">Senha</label>
						<input id="password" type="password" name="password" class="form-control" placeholder="Digite sua Senha" autofocus="true">
						<?php 
							if (!empty($err_password)) {
								echo "<p class='text-danger'>" . $err_password . "</p>";
							}
						?>
					</div>

					<div class="form-group mb-3">
						<label for="c_password" class="form-label">Confirmar senha</label>
						<input id="c_password" type="password" name="c_password" class="form-control" placeholder="Confirme sua senha" autofocus="true">
						<?php
							if (!empty($err_c_password)) {
								echo "<p class='text-danger'>" . $err_c_password . "</p>";
							}
						?>
					</div>

					<div class="form-group mb-3">
						<input id="button" type="submit" value="Cadastrar" class="btn btn-primary w-100">
					</div>

					<div class="text-center">
						<a href="login.php" class="btn btn-link">Clique para logar</a>
					</div>
				</form>
			</div>
		</div>
	</main>
</body>
</html>