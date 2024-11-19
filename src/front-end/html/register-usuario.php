<?php
session_start();
require('../../back-end/verificar-logado.php');

if (isset($_SESSION['PermissaoNome']) && $_SESSION['PermissaoNome'] == 'Administrador') {
    $permissaoUsuario = 1;
} elseif (isset($_SESSION['PermissaoNome']) && $_SESSION['PermissaoNome'] == 'Estoquista') {
    $permissaoUsuario = 2;
} else {
    require('../../back-end/logout.php');
}

try {
    // Pegando os usuários cadastrados
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    $stmt = $conexao->prepare('SELECT * FROM VwSelecionarClientesBasico');
    $stmt->execute();
} catch (PDOException $e) {
    error_log("Erro no banco: " . $e->getMessage());
    header('Location: ../../front-end/html/home.php');
    exit();
} catch (Exception $e) {
    error_log("Erro ao registrar Usuário: " . $e->getMessage());
    header('Location: ../../front-end/html/home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Usuário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/register-cliente.css" />
    <script src="../js/max-caracteres.js"></script>
</head>

<body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3c4763;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img id="navbar-brand-img" src="../img/Estoque-Fácil.png" alt="Estoque Fácil" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php
            echo '<div class="collapse navbar-collapse" id="navbarNav">';
            echo '<ul class="navbar-nav ms-auto">';

            // Home é exibido para todos os níveis de permissão
            echo '<li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./home.php">Home</a>
                </li>';

            if ($permissaoUsuario == 1) {
                // Menu para permissão total
                echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuCadastros" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuCadastros">
                            <li><a class="dropdown-item" href="./usuarios.php">Usuários</a></li>
                            <li><a class="dropdown-item" href="./cliente.html">Produtos</a></li>
                            <li><a class="dropdown-item" href="./clientes.php">Clientes</a></li>
                        </ul>
                    </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="./cliente.html">Estoque</a>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" onclick="entradasaida()">Movimentações</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./cliente.html">Relatórios</a>
                    </li>';
            } elseif ($permissaoUsuario == 2) {
                // Menu para permissão intermediária
                echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuCadastros" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuCadastros">
                            <li><a class="dropdown-item" href="./clientes.php">Clientes</a></li>
                            <li><a class="dropdown-item" href="./cliente.html">Produtos</a></li>
                        </ul>
                    </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="./cliente.html">Estoque</a>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" onclick="entradasaida()">Movimentações</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./cliente.html">Relatórios</a>
                    </li>';
            }

            // Sempre exibe a opção "Sair"
            echo '<li class="nav-item">
                    <a class="nav-link" href="../../back-end/logout.php">Sair</a>
                </li>';

            echo '</ul>';
            echo '</div>';
            ?>
        </div>
    </nav>



    <main>
        <div class="container my-5">
            <!-- Título Principal -->
            <h1 class="text-left mb-4">Registrar Usuário</h1>

            <!-- Formulário -->
            <form method="POST">
                <!-- Seção de Informações -->

                <div class="mb-3">
                    <label for="usuarioNome" class="form-label">Nome do Usuário</label>
                    <input type="text" class="form-control" id="usuarioNome" name="usuarioNome" required />
                </div>

                <div class="mb-3">
                    <label for="usuarioEmail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="usuarioEmail" name="usuarioEmail" required />
                </div>

                <div class="mb-3">
                    <label for="usuarioSenha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="usuarioSenha" name="usuarioSenha" required />
                </div>

                <div class="mb-3">
                    <label for="usuarioConfirmarSenha" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" id="usuarioConfirmarSenha" name="usuarioConfirmarSenha" required />
                </div>

                <!-- Seleção de Permissões -->
                <div class="mb-3">
                    <label for="usuarioPermissao" class="form-label">Permissões</label>
                    <select class="form-control" id="usuarioPermissao" name="usuarioPermissao" required>
                        <option value="">Selecione</option>
                        <option value="1">Administrador</option>
                        <option value="2">Estoquista</option>
                        <option value="3">Operador</option>
                    </select>
                </div>

                <!-- Botão de Submissão -->
                <button type="submit" class="btn btn-primary">Registrar Usuário</button>
            </form>
        </div>

        </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>