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

    function getSelectOptions($sql) {
        try {
            require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
            $options = '';
  
            $stmt = $conexao->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();   
  
            if (empty($resultados)) {
                $options .= '<option value="" disabled>Nenhum item encontrado</option>';
            } else {
                foreach ($resultados as $resultado) {
                    $options .= '<option value="' . $resultado['id'] . '">' . $resultado['nome'] . '</option>';
                }
            }
            
            return $options;
        } catch (Exception $e) {
            error_log("Erro na função getSelectOptions: " . $e->getMessage());
            echo '<script>
                    alert("Ocorreu um erro inesperado. Tente novamente.");
                    window.location.href = "../../front-end/html/home.php";
                </script>';
        }
          
    }

    $estadosOptions = getSelectOptions('SELECT EstadoUf AS id, EstadoNome AS nome FROM TbEstado');
    $tipoClienteOptions = getSelectOptions('SELECT TipoClienteId AS id, TipoClienteNome AS nome FROM TbTipoCliente');

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
    <script src="../js/register-cliente.js" defer></script>
    <script src="../js/max-caracteres.js"></script>
    <script src="../js/msg.js"></script>
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclua o jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#cpf').mask('000.000.000-00'); // Máscara para o CEP
            $('#cnpj').mask('00.000.000/0000-00');
            $('#cep').mask('00000-000');
        });
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
        <div class="container my-5">
            <h1 class="text-left mb-4">Registrar Cliente</h1>
            <form action="../../back-end/processar-register-cliente.php" method="post" id="form-register-cliente">
                <!-- Tópico Informações -->
                <h4>Informações</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoCliente" class="form-label">Tipo de Cliente</label>
                        <select class="form-control" id="tipoCliente" name="tipoCliente" required>
                            <?php echo $tipoClienteOptions; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoPessoaCliente" class="form-label">Tipo de Cadastro</label>
                        <select class="form-control" id="tipoPessoaCliente" name="tipoPessoaCliente" required onchange="mudarVerificacao()">
                            <option value="F" selected>Pessoa Física</option>
                            <option value="J">Pessoa Jurídica</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clienteRazaoSocial" class="form-label">Razão Social</label>
                        <input type="text" class="form-control" id="clienteRazaoSocial" name="clienteRazaoSocial" required />
                        <span class="erro-campo" id="erro-razao"></span>
                    </div>
                    <div class="form-group">
                        <label for="clienteNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="clienteNome" name="clienteNome" required />
                        <span class="erro-campo" id="erro-nome"></span>
                    </div>
                    <div class="form-group">
                        <label for="clienteSobrenome" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="clienteSobrenome" name="clienteSobrenome" required />
                        <span class="erro-campo" id="erro-sobrenome"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required oninput="verificarCPF()"/>
                        <span class="erro-campo" id="erro-cpf"></span>
                    </div>
                    <div class="form-group">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" oninput="verificarCNPJ()"/>
                        <span class="erro-campo" id="erro-cnpj"></span>
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
                        <label for="logradouro" class="form-label">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro" required />
                        <span class="erro-campo" id="erro-logradouro"></span>
                    </div>
                    <div class="form-group">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero" required />
                        <span class="erro-campo" id="erro-numero"></span>
                    </div>
                    <div class="form-group">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento" />
                    </div>
                    <div class="form-group">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro" />
                        <span class="erro-campo" id="erro-bairro"></span>
                    </div>
                    <div class="form-group">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" required />
                        <span class="erro-campo" id="erro-cep"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado" required onchange="buscarCidades()">
                            <option value="">Selecione o Estado</option>
                            <?php echo $estadosOptions; ?>
                        </select>
                        <span class="erro-campo" id="erro-estado"></span>
                    </div>
                    <div class="form-group" id="cidades">
                        <label for="cidade" class="form-label">Cidade</label>
                        <select class="form-control" id="cidade" name="cidade" required>
                            <option value="">Selecione</option>
                            <!-- Adicionar opções de cidade dinamicamente -->
                        </select>
                        <span class="erro-campo" id="erro-cidade"></span>
                    </div>
                </div>

                <h4 class="mt-4">Contato</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="text" class="form-control" id="email" name="email" required oninput="validarEmail()"/>
                        <span class="erro-campo" id="erro-email"></span>
                    </div>
                </div>

                <button type="button" class="btn btn-primary mt-4" onclick="validarFormulario()">Registrar Cliente</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
            echo '<div class="register-falha" id="register-falha">
                    <p>Erro ao cadastrar cliente, tente novamente!</p>
                  </div>';
            echo '<script>fecharMensagem("register-falha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
            echo  '<div class="register-ok" id="register-ok">
                    <p>Cliente cadastrado com sucesso!</p>
                    </div>';
            echo  '<script>redirecionarParaClientes();</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'falhaForm') {
            echo '<div class="register-falha" id="register-falha">
                    <p>Erro no formulário, envie novamente!</p>
                  </div>';
            echo '<script>fecharMensagem("register-falha");</script>';
        }
    ?>
</body>

</html>