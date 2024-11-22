<?php 
    session_start();
    require('../../back-end/verificar-logado.php');

    if (isset($_SESSION['PermissaoNome']) && $_SESSION['PermissaoNome'] == 'Administrador') {
        $permissaoUsuario = 1;
    } elseif (isset($_SESSION['PermissaoNome']) && $_SESSION['PermissaoNome'] == 'Estoquista') {
        $permissaoUsuario = 2;
    } elseif (isset($_SESSION['PermissaoNome']) && $_SESSION['PermissaoNome'] == 'Operador') {
        $permissaoUsuario = 3;
    } else {
        require('../../back-end/logout.php');
    }

    try {
        // Pegando os usuários cadastrados
        require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
    
        $stmt = $conexao->prepare('SELECT * FROM vwConsultarProdutos');
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
    <title>Estoque fácil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- Link para o CSS externo -->
    <link rel="stylesheet" href="../css/movimentacoes.css" />
    <script src="../js/movimentacoes.js" defer></script>
</head>

<body>
    <div id="overlay" class="overlay"></div>
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
                                <li><a class="dropdown-item" href="./produtos.php">Produtos</a></li>
                                <li><a class="dropdown-item" href="./clientes.php">Clientes</a></li>
                            </ul>
                        </li>';
                    echo '<li class="nav-item">
                            <a class="nav-link" href="./cliente.html">Estoque</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./movimentacoes.php">Movimentações</a>
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
                                <li><a class="dropdown-item" href="./produtos.php">Produtos</a></li>
                            </ul>
                        </li>';
                    echo '<li class="nav-item">
                            <a class="nav-link" href="./cliente.html">Estoque</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./movimentacoes.php">Movimentações</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./cliente.html">Relatórios</a>
                        </li>';
                } elseif ($permissaoUsuario == 3) {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="./cliente.html">Estoque</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./movimentacoes.php">Movimentações</a>
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
            <h1 class="text-left mb-4">Movimentações</h1>
            <!-- Barra de busca -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="search-bar" placeholder="Buscar movimentações..."
                        onkeyup="searchProduct()" />
                    <select class="form-select" id="movimentacoes" name="movimentacoes" role="button" onchange="consultarMovimentacoes()" required>
                        <option value="S" selected>Saídas</option>
                        <option value="E">Entradas</option>
                        <option value="F">Fabricação</option>
                    </select>
                    <button class="btn btn-primary" id="btn-pesquisar"> Pesquisar </button>
                    <button class="btn btn-primary" id="btn btn-novo" onclick="criarMovimentacao()"> Novo</button>
                    <div id="tipo-mov" class="nova-mov"></div>
                    <div id="tipo-e" class="nova-mov"></div>
                    <div id="tipo-s" class="nova-mov"></div>
                </div>
            </div>
            
            <table class="table table-bordered table-striped" id="mov-padrao" class="mov-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Cliente Nome/Razao</th>
                        <th>CPF/CNPJ</th>
                        <th>NFE</th>
                        <th>Data NFE</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Usuario</th>
                        <th>Ver mais</th>
                    </tr>
                </thead>
                <tbody id="mov-table-body"> 
                </tbody>
            </table>
            <table class="table table-bordered table-striped" id="mov-fabricacao" class="mov-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>E/S</th>
                        <th>Código E/S</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Usuario</th>
                        <th>Ver mais</th>
                    </tr>
                </thead>
                <tbody id="mov-table-body"> 
                </tbody>
            </table>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="./script/script.js"></script> -->
</body>

</html>