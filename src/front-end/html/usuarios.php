<?php 
    session_start();
    require('../../back-end/verificar-logado.php');

    if ($_SESSION['PermissaoNome'] !== 'Administrador') {
      require('../../back-end/logout.php');
    }
    else {
      $permissaoUsuario = 1;
    }

    try {
        // Pegando os usuários cadastrados
        require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

        $stmt = $conexao->prepare('SELECT * FROM VwConsultarUsuarios');
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
    <title>Estoque fácil - Usuários</title>
    <!-- Link do Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/usuarios.css" />
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
            <h1 class="text-left mb-4">Usuários </h1>
            <!-- Barra de busca -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="search-bar" placeholder="Buscar usuário..."
                        onkeyup="searchProduct()" />
                    <button class="btn btn-primary" id="btn-pesquisar"> Pesquisar </button>
                    <a href="./register-usuario.php" class="btn btn-primary">Novo</a>
                </div>
            </div>
            
            <?php
                echo '<table class="table table-bordered table-striped" id="product-table">';
                echo '<thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome do Usuário</th>
                            <th>E-mail Cadastrado</th>
                            <th>Permissão</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Admin</th>
                            <th>Editar</th>
                        </tr>
                    </thead>';
                echo '<tbody id="product-list">';

                // Verifica se há resultados na consulta
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    // Exibe a primeira linha já capturada no fetch
                    do {
                        $usuarioId = htmlspecialchars($result['UsuarioId']);
                        $usuarioNome = htmlspecialchars($result['UsuarioNome']);
                        $usuarioEmail = htmlspecialchars($result['UsuarioEmail']);
                        $permissaoNome = htmlspecialchars($result['PermissaoNome']);
                        $usuarioStatus = $result['UsuarioStatus'] == 1 ? 'Ativo' : 'Inativo';
                        $usuarioData = date('d/m/Y', strtotime($result['UsuarioData']));
                        $usuarioNomeAdmin = htmlspecialchars($result['UsuarioNomeAdmin']);

                        echo "<tr>
                                <td>{$usuarioId}</td>
                                <td>{$usuarioNome}</td>
                                <td>{$usuarioEmail}</td>
                                <td>{$permissaoNome}</td>
                                <td>{$usuarioStatus}</td>
                                <td>{$usuarioData}</td>
                                <td>{$usuarioNomeAdmin}</td>
                                <td><a href='./edit-usuario.php?usuarioId=".$usuarioId."'>Editar</a></td>
                            </tr>";
                    } while ($result = $stmt->fetch(PDO::FETCH_ASSOC)); // Continua pegando as próximas linhas
                } else {
                    // Caso não tenha resultados
                    echo '<tr>
                            <td colspan="7" class="text-center">Nenhum Usuário Encontrado</td>
                          </tr>';
                }

                echo '</tbody>';
                echo '</table>';
                ?>

        </div>
    </main>         
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
