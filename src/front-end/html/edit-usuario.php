<?php 
    session_start();
    require('../../back-end/verificar-logado.php');

    if ($_SESSION['PermissaoNome'] !== 'Administrador') {
        require('../../back-end/logout.php');
    }
    else {
        $permissaoUsuario = 1;
    }
    // capturando o usuário selecionado
    if (isset($_GET['usuarioId'])){
        try {
            require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
    
            $stmt = $conexao->prepare('SELECT * FROM VwConsultarUsuarios WHERE UsuarioId = :usuarioId');
            $stmt->bindParam(':usuarioId', $_GET['usuarioId'], PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        } catch (PDOException $e) {
            error_log("Erro no banco: " . $e->getMessage());
            header('Location: ../../front-end/html/home.php');
            exit();
        } catch (Exception $e) {
            error_log("Erro ao registrar usuario: " . $e->getMessage());
            header('Location: ../../front-end/html/home.php');
            exit();
        }
    }
    else {
        header('Location: ../../front-end/html/usuarios.php');
        exit();
    }

    function getSelectOptions($sql, $selected) {
        try {
            include '../../back-end/conexao.php';
            $options = '';
  
            $stmt = $conexao->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();   
  
            if (empty($resultados)) {
                $options .= '<option value="" disabled>Nenhum item encontrado</option>';
            } else {
                foreach ($resultados as $resultado) {
                    if ($selected == $resultado['nome']) {
                        $options .= '<option value="' . $resultado['id'] . '" selected>' . $resultado['nome'] . '</option>';
                    }
                    else {
                        $options .= '<option value="' . $resultado['id'] . '">' . $resultado['nome'] . '</option>';
                    }
                }
            }
            
            return $options;
        } catch (Exception $e) {
            error_log("Erro na função getSelectOptions: " . $e->getMessage());
            echo '<script>
                    alert("Ocorreu um erro inesperado. Tente novamente.");
                    window.location.href = "../../frontend/html/home-contratante.php";
                </script>';
        }
          
    }
  
    $permissaoOptions = getSelectOptions('SELECT PermissaoId AS id, PermissaoNome AS nome FROM TbPermissao', $result['PermissaoNome']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Estoque fácil - registro usuário</title>
    <!-- Link do Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/edit-usuario.css" />
    <!-- Jquery e InputMask -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <script src="../js/msg.js"></script>
    <script src="../js/edit-usuario.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer>
        function redirecionarParaUsuarios() {
            setTimeout(() =>{
              window.location.href = '../../front-end/html/usuarios.php';
            }, 1000); 
        }
    </script>
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
        <div class="edit-usuario-container">
            <form action="../../back-end/processar-editar-user.php" method="post" id="form-edit-usuario">
                <h1 class="text-left mb-4">Editar Usuário</h1>
                <span id="error-edit"></span>
                <div class="part-form">
                    <div class="mb-3" id="div-coduser">
                        <label for="coduser" class="form-label">Código</label>
                        <input
                            type="text"
                            class="form-control"
                            id="coduser"
                            name="coduser"
                            value="<?php echo $result['UsuarioId'] ?>"
                            disabled
                        />
                        <input
                            type="text"
                            hidden
                            class="form-control"
                            id="coduser-hidden"
                            name="coduser-hidden"
                            value="<?php echo $result['UsuarioId'] ?>"
                        />
                    </div>
                    <div class="mb-3" id="div-data">
                        <label for="data" class="form-label">Data Cadastro</label>
                        <input
                            type="text"
                            class="form-control"
                            id="data"
                            name="data"
                            value="<?php echo date('d/m/Y', strtotime($result['UsuarioData'])); ?>"
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário</label>
                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            value="<?php echo $result['UsuarioNome'] ?>"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?php echo $result['UsuarioEmail'] ?>"
                        />
                    </div>
                </div>
                <div class="part-form">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status">
                            <?php
                                if ($result['UsuarioStatus'] == 1) {
                                    echo '<option value="1" selected>Ativo</option>';
                                    echo '<option value="0">Inativo</option>';
                                }
                                elseif ($result['UsuarioStatus'] == 0) {
                                    echo '<option value="1">Ativo</option>';
                                    echo '<option value="0" selected>Inativo</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                    <label for="permissions" class="form-label"
                        >Permissões</label
                    >
                    <select name="permissions" id="permissions">
                        <?php echo $permissaoOptions ?>
                    </select>
                    </div>
                </div>
                <h5 class="text-left mb-4">Nova senha</h5>
                <div class="part-form">
                    <div class="mb-3">
                            <label for="password-atual" class="form-label">Senha Atual</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password-atual"
                            name="password-atual"
                        />
                    </div>
                    <div class="mb-3">
                            <label for="password-nova" class="form-label">Nova Senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password-nova"
                            name="password-nova"
                        />
                    </div>
                    <div class="mb-3">
                            <label for="password-nova-confirm" class="form-label">Confirmar Nova senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password-nova-confirm"
                            name="password-nova-confirm"
                        />
                    </div>
                </div>

                <div id="btns">
                    <a href="./usuarios.php" class="btn w-20" id="cancel">Cancelar</a>
                    <button type="submit" class="btn btn-primary w-20" id="btn-edit">Editar</button>
                </div>
            </form>
        </div>
    </main>         
    <?php
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
            echo '<div class="edit-falha" id="edit-falha">
                    <p>Erro ao editar, tente novamente!</p>
                  </div>';
            echo '<script>fecharMensagem("edit-falha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso' && isset($_GET['usuarioId'])) {
            echo  '<div class="edit-ok" id="edit-ok">
                    <p>Usuário editado com sucesso!</p>
                    </div>';
            echo  '<script>redirecionarParaUsuarios();</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === '!senha') {
            echo  '<div class="edit-senha" id="edit-senha">
                    <p>Senha incorreta</p>
                    </div>';
            echo  '<script>fecharMensagem("edit-senha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === '!email') {
            echo  '<div class="edit-email" id="edit-email">
                    <p>Email já cadastrado</p>
                    </div>';
            echo  '<script>fecharMensagem("edit-email");</script>';
        }
    ?>
  </body>
</html>
