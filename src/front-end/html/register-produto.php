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

    $tipoProdutoOptions = getSelectOptions('SELECT TipoProdutoId AS id, TipoProdutoNome AS nome FROM TbTipoProduto');
    $categoriaOptions = getSelectOptions('SELECT CategoriaId AS id, CategoriaNome AS nome FROM TbCategoria');
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
    <link rel="stylesheet" href="../css/register-produto.css" />
    <script src="../js/register-produto.js" defer></script>
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
            <h1 class="text-left mb-4">Registrar Produto</h1>
            <form action="../../back-end/processar-register-produto.php" method="post" id="form-register-produto">
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoProduto" class="form-label">Tipo do Produto</label>
                        <select class="form-select" role="button" id="tipoProduto" name="tipoProduto" required onchange="atualizarVisibilidade()">
                        <option value="" disabled selected>Selecione o Tipo do Produto</option>
                            <?php echo $tipoProdutoOptions; ?>
                        </select>
                        <span class="erro-campo" id="erro-produto"></span>
                    </div>
                </div>
                <!-- Tópico Informações -->
                <h4 class="mt-4">Informações</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" required/>
                        <span class="erro-campo" id="erro-nome"></span>
                    </div>
                    <div class="form-group">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="text" class="form-control" id="preco" name="preco" required/>
                        <span class="erro-campo" id="erro-preco"></span>
                    </div>
                    <div class="form-group">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" role="button" id="categoria" name="categoria" required>
                        <option value="" disabled selected>Selecione a Categoria</option>
                            <?php echo $categoriaOptions; ?>
                        </select>
                        <span class="erro-campo" id="erro-categoria"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" required oninput="limitarDescricao()"></textarea>
                        <span class="erro-campo" id="erro-descricao"></span>
                        <small id="char-count" class="form-text text-muted"></small>
                    </div>
                </div>
                <!-- Tópico Fabricação -->
                <div id="fabricacao">
                    <h4 class="mt-4">Fabricação</h4>
                    <div class="form-row">
                        <label for="">Cadastrar Fabricação?</label>
                        <div class="form-group form-check">
                            <label for="pergunta-fab-nao" class="form-check-label">Não</label>
                            <input class="form-check-input" type="radio" id="pergunta-fab-nao" name="pergunta-fab" value="não" checked onclick="validarFabricacao()"></input>
                            <span class="erro-campo" id="erro-pergunta-fab-nao"></span>
                        </div>
                        <div class="form-group form-check">
                            <label for="pergunta-fab-sim" class="form-check-label">Sim</label>
                            <input class="form-check-input" type="radio" id="pergunta-fab-sim" name="pergunta-fab" value="sim" onclick="validarFabricacao()"></input>
                            <span class="erro-campo" id="erro-pergunta-fab-sim"></span>
                        </div>
                    </div>
                    <div class="form-row" id="fab-select">
                        <div class="form-group">
                            <label for="materia-prima" class="form-label">Matéria Prima</label>
                            <select class="form-select" role="button" id="materia-prima" name="materia-prima">
                            <option value="">Selecione a matéria prima</option>
                                <?php echo $materiaPrimaOptions; ?>
                            </select>
                            <span class="erro-campo" id="erro-materia-prima"></span>
                        </div>
                        <div class="form-group">
                            <label for="qtd-materia-prima" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="qtd-materia-prima" name="qtd-materia-prima"/>
                            <span class="erro-campo" id="erro-qtd-materia-prima"></span>
                        </div>
                    </div>
                </div>
                <!-- Tópico Componentes -->
                <div id="componentes">
                    <h4 class="mt-4">Componentes</h4>
                    <div class="form-row">
                        <label for="">Cadastrar Componentes?</label>
                        <div class="form-group form-ckeck">
                            <label for="pergunta-comp-nao" class="form-check-label">Não</label>
                            <input class="form-check-input" type="radio" id="pergunta-comp-nao" name="pergunta-comp" value="não" checked onclick="validarComponentes()"></input>
                            <span class="erro-campo" id="erro-pergunta-comp-nao"></span>
                        </div>
                        <div class="form-group form-ckeck">
                            <label for="pergunta-comp-sim" class="form-check-label">Sim</label>
                            <input class="form-check-input" type="radio" id="pergunta-comp-sim" name="pergunta-comp" value="sim" onclick="validarComponentes()"></input>
                            <span class="erro-campo" id="erro-pergunta-comp-sim"></span>
                        </div>
                    </div>
                    <div class="form-row" id="comp-select">
                        <div class="form-group">
                            <label for="componente" class="form-label">Componente</label>
                            <select class="form-select" role="button" id="componente" name="componente">
                            <option value="">Selecione o componente</option>
                                <?php echo $componentesOptions; ?>
                            </select>
                            <span class="erro-campo" id="erro-componente"></span>
                        </div>
                        <div class="form-group">
                            <label for="qtd-componente" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="qtd-componente" name="qtd-componente"/>
                            <span class="erro-campo" id="erro-qtd-componente"></span>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary mt-4" onclick="validarFormulario()">Cadastrar Produto</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
            echo '<div class="register-falha" id="register-falha">
                    <p>Erro ao cadastrar produto, tente novamente!</p>
                  </div>';
            echo '<script>fecharMensagem("register-falha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
            echo  '<div class="register-ok" id="register-ok">
                    <p>Produto cadastrado com sucesso!</p>
                    </div>';
            echo  '<script>redirecionarParaProdutos();</script>';
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