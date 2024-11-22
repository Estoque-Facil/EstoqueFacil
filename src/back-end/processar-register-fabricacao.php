<?php
// Configurações do banco de dados
    session_start();
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    try {
        // Recebe os dados do formulário
        $tipoMovimentacaoES = $_GET['tipo'];
        $tipoMovimentacao = 2;
        $entradaTurno = '1';
        $entradaObser = $_POST['descricao'];
        $usuarioId = $_SESSION['UsuarioId'];
        $estoqueId = 1;
        $produtoId = $_POST['produto'];
        $quantidade = $_POST['qtd-produto'];

        // Prepara a chamada da procedure
        $stmt = $conexao->prepare("EXEC SpFabricacaoEstoque
            @TipoEntradaId = :tipoMovimentacao,
            @EntradaTurno = :entradaTurno,
            @EntradaObser = :entradaObser,
            @UsuarioId = :usuarioId,
            @EstoqueId = :estoqueId,
            @ProdutoId = :produtoId,
            @Quantidade = :quantidade
        ");


        // Vincula os parâmetros
        $stmt->bindParam(':tipoMovimentacao', $tipoMovimentacao, PDO::PARAM_STR);
        $stmt->bindParam(':entradaTurno', $entradaTurno, PDO::PARAM_STR);
        $stmt->bindParam(':entradaObser', $movimentacaoObser, PDO::PARAM_STR);
        $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(':estoqueId', $estoqueId, PDO::PARAM_INT);
        $stmt->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);

        // Executa a procedure
        if ($stmt->execute()) {
            header('Location: ../front-end/html/register-fabricacao.php?status=sucesso');
            exit();
        } else {
            header('Location: ../front-end/html/register-fabricacao.php?status=falha');
            exit();
        }

    } catch (Exception $e) {
        error_log("Erro ao registrar entrada: " . $e->getMessage());
        header('Location: ../front-end/html/register-fabricacao.php?status=falha');
        exit();
    }
?>
