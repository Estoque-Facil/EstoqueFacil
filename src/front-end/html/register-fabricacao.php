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

    function getSelectProd($sql,$param, $paramValor) {
        try {
            require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
            $options = '';
  
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam($param, $paramValor);
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
    function getSelectCliente($sql) {
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
                    $options .= '<option value="' . $resultado['id'] . '">' . $resultado['nome'] . " " .$resultado['sobrenome'] ?? null .'</option>';
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

    $produtosOptions = getSelectOptions("SELECT ProdutoId AS id, ProdutoNome AS nome FROM TbProduto");
    $clienteOptions =  getSelectCliente("SELECT c.ClienteId AS id, COALESCE(cpf.ClienteNome, cnpj.ClienteRazaoSocial) AS nome,
    COALESCE(cpf.ClienteSobrenome, '') AS sobrenome FROM TbCliente c LEFT JOIN TbClientePessoaFisica cpf ON c.ClienteId = cpf.ClienteId
    LEFT JOIN TbClientePessoaJuridica cnpj ON c.ClienteId = cnpj.ClienteId;");
    $materiaPrimaOptions = getSelectProd('SELECT P.ProdutoId AS id, P.ProdutoNome AS nome FROM TbProduto P INNER JOIN TbTipoProduto TP ON P.TipoProdutoId = TP.TipoProdutoId WHERE TipoProdutoNome = :tipo', ':tipo', 'Matéria Prima');
    $componentesOptions = getSelectProd('SELECT P.ProdutoId AS id, P.ProdutoNome AS nome FROM TbProduto P INNER JOIN TbTipoProduto TP ON P.TipoProdutoId = TP.TipoProdutoId WHERE TipoProdutoNome = :tipo', ':tipo', 'Componente');
    

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Cliente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/register-fabricacao.css" />
    <script src="../js/register-fabricacao.js" defer></script>
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
            $('#preco').mask('000.000.000.000,00', {reverse: true});    
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
            <h1 class="text-left mb-4">Registrar Fabricação</h1>
            <form action="../../back-end/processar-register-fabricacao.php?tipo=F" method="post" id="form-register-saida">
                <!-- Tópico Produto -->
                <div>
                    <h4 class="mt-4">Produtos</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="produto" class="form-label">Produto</label>
                            <select class="form-select" role="button" id="produto" name="produto" required onchange="verificarFabricacao()">
                            <option value="" disabled selected>Selecione o produto</option>
                                <?php echo $produtosOptions; ?>
                            </select>
                            <span class="erro-campo" id="erro-produto"></span>
                        </div>
                        <div class="form-group">
                            <label for="qtd-produto" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="qtd-produto" name="qtd-produto" oninput="atualizarQtdComp()"/>
                            <span class="erro-campo" id="erro-qtd-produto"></span>
                        </div>
                    </div>
                </div>
                <!-- Tópico Informações -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" required oninput="limitarDescricao()"></textarea>
                        <span class="erro-campo" id="erro-descricao"></span>
                        <small id="char-count" class="form-text text-muted"></small>
                    </div>
                </div>

                <!-- Tópico Fabricacao -->
                <div id="mat-prima">
                    <h4 class="mt-4">Matéria Prima</h4>
                    <div class="form-row" id="fab-inputs">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-4" onclick="validarFormulario()">Registrar</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
            echo '<div class="register-falha" id="register-falha">
                    <p>Erro ao fabricar produto, tente novamente!</p>
                  </div>';
            echo '<script>fecharMensagem("register-falha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
            echo  '<div class="register-ok" id="register-ok">
                    <p>Produto fabricado com sucesso!</p>
                    </div>';
            echo  '<script>redirecionarParaMovimentacoes();</script>';
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