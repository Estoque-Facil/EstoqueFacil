<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

        // Obtendo os valores enviados pelo formulário
        $tipoProduto = $_POST['tipoProduto'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $preco = str_replace(',', '.', $_POST['preco'] ?? null); // Converte preço para formato decimal
        $categoria = $_POST['categoria'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        // Parâmetros de composição
        $composicao = $_POST['pergunta-comp'] ?? 'não';
        $componente = $_POST['componente'] ?? null;
        $componenteQtd = $_POST['qtd-componente'] ?? null;
        // Parâmetros de fabricação
        $fabricacao = $_POST['pergunta-fab'] ?? 'não';
        $materiaPrima = $_POST['materia-prima'] ?? null;
        $materiaPrimaQtd = $_POST['qtd-materia-prima'] ?? null;
        $usuarioId = $_SESSION['UsuarioId'];

        $preco = is_numeric($preco) ? number_format((float)$preco, 2, '.', '') : null;
        $componenteQtd = is_numeric($componenteQtd) ? number_format((float)$componenteQtd, 2, '.', '') : null;
        $materiaPrimaQtd = is_numeric($materiaPrimaQtd) ? number_format((float)$materiaPrimaQtd, 2, '.', '') : null;

        try {
            $stmt = $conexao->prepare('
                EXEC SpInserirProduto 
                @ProdutoNome = :nome,
                @ProdutoDescr = :descricao,
                @ProdutoPreco = :preco,
                @CategoriaId = :categoria,
                @UsuarioId = :usuarioId,
                @TipoProdutoId = :tipoProduto,
                @ComponenteId = :componente,
                @ComponenteQtd = :componenteQtd,
                @MateriaPrimaId = :materiaPrima,
                @MateriaPrimaQtd = :materiaPrimaQtd;');


            // Vinculando os parâmetros
            $stmt->bindParam(':tipoProduto', $tipoProduto, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':preco', $preco, PDO::PARAM_INT);
            $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':componente', $componente, PDO::PARAM_INT);
            $stmt->bindParam(':componenteQtd', $componenteQtd, PDO::PARAM_INT);
            $stmt->bindParam(':materiaPrima', $materiaPrima, PDO::PARAM_INT);
            $stmt->bindParam(':materiaPrimaQtd', $materiaPrimaQtd, PDO::PARAM_INT);

            // Executando a procedure
            if ($stmt->execute()) {
                header('Location: ../front-end/html/register-produto.php?status=sucesso');
                exit();
            } else {
                header('Location: ../front-end/html/register-produto.php?status=falha');
                exit();
            }
            
        } catch (Exception $e) {
            error_log("Erro ao registrar produto: " . $e->getMessage());
            header('Location: ../front-end/html/register-produto.php?status=falha');
            exit();
        }
    } else {
        header('Location: ../front-end/html/register-produto.php?status=falhaForm');
        exit();
    }
?>
