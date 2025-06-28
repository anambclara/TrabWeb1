<?php
session_start();
include("../config/connection.php");
include("../config/functions.php");

$user_data = check_login($con);


$user_id = $_SESSION["user_id"];
$sqlCurrentClan = "SELECT u.clan_id, c.clan_name FROM users u LEFT JOIN clans c ON u.clan_id = c.clan_id WHERE u.user_id = '$user_id'";
$resultCurrentClan = $con->query($sqlCurrentClan);
$currentClan = null;
$currentClanId = null;
if ($resultCurrentClan && $resultCurrentClan->num_rows > 0) {
    $rowCurrentClan = $resultCurrentClan->fetch_assoc();
    $currentClan = $rowCurrentClan['clan_name'];
    $currentClanId = $rowCurrentClan['clan_id'];
}

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];

    if ($acao == "criar") {
        $clan_name = isset($_POST["clan_name"]) ? trim($_POST["clan_name"]) : '';
        $clan_password = isset($_POST["clan_password"]) ? trim($_POST["clan_password"]) : '';

        if (!empty($clan_name) && !empty($clan_password)) {
            $checkSql = "SELECT clan_name FROM clans WHERE clan_name = '$clan_name'";
            $checkResult = $con->query($checkSql);
            
            if ($checkResult->num_rows > 0) {
                $message = "Já existe uma liga com este nome.";
                $messageType = "danger";
            } else {
                $sql = "INSERT INTO clans (clan_id, clan_name, clan_password) VALUES (NULL, '$clan_name', '$clan_password')";
                if ($con->query($sql) === TRUE) {
                    $clan_id = $con->insert_id;
                    $user_id = $_SESSION["user_id"];
                    $sqlUpdateUser = "UPDATE users SET clan_id = $clan_id WHERE user_id = $user_id";
                    $con->query($sqlUpdateUser);
                    
                    $message = "Liga criada! Você agora é membro da liga '$clan_name'.";
                    $messageType = "success";
                } else {
                    $message = "Erro ao criar liga: " . $con->error;
                    $messageType = "danger";
                }
            }
        } else {
            $message = "Por favor, preencha todos os campos.";
            $messageType = "warning";
        }
    } elseif ($acao == "entrar") {
        $idClanEscolhido = isset($_POST["id_clan"]) ? $_POST["id_clan"] : '';
        $clan_password_entered = isset($_POST["clan_password_entered"]) ? trim($_POST["clan_password_entered"]) : '';

        if (!empty($idClanEscolhido) && !empty($clan_password_entered)) {
            $sqlCheckPassword = "SELECT clan_password, clan_name FROM clans WHERE clan_id = '$idClanEscolhido'";
            $result = $con->query($sqlCheckPassword);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stored_password = $row["clan_password"];
                $clan_name = $row["clan_name"];

                if ($clan_password_entered == $stored_password) {
                    $user_id = $_SESSION["user_id"];
                    
                    if ($currentClanId == $idClanEscolhido) {
                        $message = "Você já está nesta liga!";
                        $messageType = "warning";
                    } else {
                        $sqlUpdateUser = "UPDATE users SET clan_id = '$idClanEscolhido' WHERE user_id = '$user_id'";
                        if ($con->query($sqlUpdateUser) === TRUE) {
                            $message = "Você se juntou à liga '$clan_name' com sucesso!";
                            $messageType = "success";
                            

                            $currentClan = $clan_name;
                            $currentClanId = $idClanEscolhido;
                        } else {
                            $message = "Erro ao entrar na liga: " . $con->error;
                            $messageType = "danger";
                        }
                    }
                } else {
                    $message = "Senha incorreta. Tente novamente.";
                    $messageType = "danger";
                }
            } else {
                $message = "Liga não encontrada.";
                $messageType = "danger";
            }
        } else {
            $message = "Selecione uma liga e digite a senha.";
            $messageType = "warning";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Ligas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/liga.css">
    <script>
        function toggleFields() {
            var acao = document.getElementById("acao").value;
            var criarClaDiv = document.getElementById("criar-cla");
            var entrarClaDiv = document.getElementById("entrar-cla");
            
            
            var allInputs = document.querySelectorAll('#formLogin input, #formLogin select');
            allInputs.forEach(function(input) {
                if (input.id !== 'acao') {
                    input.removeAttribute('required');
                    input.disabled = true;
                    input.value = '';
                }
            });
            
            if (acao === "criar") {
                criarClaDiv.style.display = "block";
                entrarClaDiv.style.display = "none";
                
                
                var createInputs = ['clan_name_create', 'clan_password_create'];
                createInputs.forEach(function(id) {
                    var element = document.getElementById(id);
                    if (element) {
                        element.disabled = false;
                        element.setAttribute('required', 'required');
                    }
                });
                
            } else if (acao === "entrar") {
                criarClaDiv.style.display = "none";
                entrarClaDiv.style.display = "block";
                
                
                var enterInputs = ['id_clan_select', 'clan_password_enter'];
                enterInputs.forEach(function(id) {
                    var element = document.getElementById(id);
                    if (element) {
                        element.disabled = false;
                        element.setAttribute('required', 'required');
                    }
                });
                
            } else {
                criarClaDiv.style.display = "none";
                entrarClaDiv.style.display = "none";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('formLogin');
            if (form) {
                form.addEventListener('submit', function(e) {
                    var acao = document.getElementById('acao').value;
                    
                    if (acao === 'nada' || acao === '') {
                        alert('Por favor, selecione uma ação.');
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
</head>

<body>
    <?php 
   
    $hideLoginCheck = true; 
    include("../components/header.php"); 
    ?>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4" style="color: #ff66b2;">Gerenciamento de Ligas</h3>
                        
                        <?php if ($currentClan): ?>
                        <div class="alert alert-info">
                            <strong>Liga Atual:</strong> <?php echo htmlspecialchars($currentClan); ?>
                            <br><small>Ao entrar em uma nova liga, você sairá automaticamente da liga atual.</small>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <strong>Você não está em nenhuma liga.</strong>
                        </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="formLogin">
                            <div class="mb-3">
                                <label for="acao" class="form-label">Escolha uma ação:</label>
                                <select name="acao" id="acao" class="form-select" onchange="toggleFields()">
                                    <option value="nada">---</option>
                                    <option value="criar">Criar Liga</option>
                                    <option value="entrar">Entrar em Liga Existente</option>
                                </select>
                            </div>

                            <div id="criar-cla" style="display:none;">
                                <div class="card mt-3">
                                    <div class="card-header" style="background-color: #ff66b2; color: white;">
                                        <h5 class="mb-0">Criar Nova Liga</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="clan_name_create" class="form-label">Nome da Liga:</label>
                                            <input type="text" id="clan_name_create" name="clan_name" class="form-control" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="clan_password_create" class="form-label">Senha da Liga:</label>
                                            <input type="password" id="clan_password_create" name="clan_password" class="form-control" disabled>
                                        </div>
                                        <button type="submit" class="btn w-100" style="background-color: #ff66b2; color: white; border: none;">Criar Liga</button>
                                    </div>
                                </div>
                            </div>

                            <div id="entrar-cla" style="display:none;">
                                <div class="card mt-3">
                                    <div class="card-header" style="background-color: #ff66b2; color: white;">
                                        <h5 class="mb-0">Entrar em Liga Existente</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="id_clan_select" class="form-label">Escolha uma Liga:</label>
                                            <select id="id_clan_select" name="id_clan" class="form-select" disabled>
                                                <option value="">Selecione uma liga...</option>
                                                <?php
                                                $sqlClans = "SELECT clan_id, clan_name FROM clans ORDER BY clan_name";
                                                $resultClans = $con->query($sqlClans);
                                                if ($resultClans && $resultClans->num_rows > 0) {
                                                    while ($rowClan = $resultClans->fetch_assoc()) {
                                                        echo "<option value='" . $rowClan['clan_id'] . "'>" . htmlspecialchars($rowClan['clan_name']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="clan_password_enter" class="form-label">Senha da Liga:</label>
                                            <input type="password" id="clan_password_enter" name="clan_password_entered" class="form-control" disabled>
                                        </div>
                                        <button type="submit" class="btn w-100" style="background-color: #ff66b2; color: white; border: none;">Entrar na Liga</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>