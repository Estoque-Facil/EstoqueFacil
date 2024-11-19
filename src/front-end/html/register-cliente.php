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
    error_log("Erro ao registrar usuario: " . $e->getMessage());
    header('Location: ../../front-end/html/home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Cliente</title>
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
            <h1 class="text-left mb-4">Registrar Cliente</h1>
            <form method="POST">
                <!-- Tópico Informações -->
                <h4>Informações</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoClienteId" class="form-label">Tipo de Cliente</label>
                        <select class="form-control" id="tipoClienteId" name="tipoClienteId" required>
                            <option value="">Selecione</option>
                            <option value="1">Cliente Físico</option>
                            <option value="2">Cliente Jurídico</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clienteRazaoSocial" class="form-label">Razão Social</label>
                        <input type="text" class="form-control" id="clienteRazaoSocial" name="clienteRazaoSocial" required />
                    </div>
                    <div class="form-group">
                        <label for="clienteNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="clienteNome" name="clienteNome" required />
                    </div>
                    <div class="form-group">
                        <label for="clienteSobrenome" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="clienteSobrenome" name="clienteSobrenome" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="clienteCpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="clienteCpf" name="clienteCpf" required />
                    </div>
                    <div class="form-group">
                        <label for="clienteCnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="clienteCnpj" name="clienteCnpj" />
                    </div>
                    <div class="form-group">
                        <label for="clienteObser" class="form-label">Observação</label>
                        <textarea class="form-control" id="clienteObser" name="clienteObser"></textarea>
                        <small id="char-count" class="form-text text-muted"></small>
                    </div>
                </div>

                <!-- Tópico Endereço -->
                <h4 class="mt-4">Endereço</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="clienteEnderecoLogra" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="clienteEnderecoLogra" name="clienteEnderecoLogra" required />
                    </div>
                    <div class="form-group">
                        <label for="clienteEnderecoNum" class="form-label">Número</label>
                        <input type="text" class="form-control" id="clienteEnderecoNum" name="clienteEnderecoNum" required />
                    </div>
                    <div class="form-group">
                        <label for="clienteEnderecoComp" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="clienteEnderecoComp" name="clienteEnderecoComp" />
                    </div>
                    <div class="form-group">
                        <label for="clienteEnderecoBai" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="clienteEnderecoBai" name="clienteEnderecoBai" />
                    </div>
                    <div class="form-group">
                        <label for="clienteEnderecoCep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="clienteEnderecoCep" name="clienteEnderecoCep" required />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cidadeId" class="form-label">Cidade</label>
                        <select class="form-control" id="cidadeId" name="cidadeId" required>
                            <option value="">Selecione</option>
                            <!-- Adicionar opções de cidade dinamicamente -->
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Registrar Cliente</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>