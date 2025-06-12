<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <form method="post" id="loginForm" class="formLogin">
                    <h1 id="top" class="mb-4 text-center">Login</h1>
                    <p class="mb-3 text-center">Digite os seus dados de acesso nos campos abaixo.</p>

                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Login</label>
                        <input id="username" type="text" name="username" class="form-control" autofocus="true">
                        <?php 
                            if (!empty($err_username)) {
                                echo "<p class='text-danger'>" . $err_username . "</p>";
                            }
                        ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input id="password" type="password" name="password" class="form-control">
                        <?php 
                            if (!empty($err_password)) {
                                echo "<p class='text-danger'>" . $err_password . "</p>";
                            }
                        ?>
                    </div>

                    <div class="form-group mb-3">
                        <input id="button" type="submit" value="Logar" class="btn btn-primary w-100">
                    </div>

                    <div class="text-center">
                        <a href="cadastro.php" class="btn btn-link">Clique para se Cadastrar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>