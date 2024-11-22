<?php
// Configurações do banco de dados
    session_start();
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    try {
        // Recebe os dados do formulário
        $tipoMovimentacaoES = $_GET['tipo'];
        $tipoMovimentacao = $_POST['tipo-saida'];
        $nfe = $_POST['nfe'];
        $dataNfe = $_POST['data-nfe'];
        $dateObj = new DateTime($dataNfe); // Cria um objeto DateTime
        $dataNfe = $dateObj->format('Y-m-d H:i:s');
        $saidaObser = $_POST['descricao'];
        $usuarioId = $_SESSION['UsuarioId'];
        $clienteId = $_POST['cliente'];
        $estoqueId = 1;
        $produtoId = $_POST['produto'];
        $quantidade = $_POST['qtd-produto'];

        // Prepara a chamada da procedure
        $stmt = $conexao->prepare("EXEC SpSaidaEstoque
            @TipoSaidaId = :tipoMovimentacao,
            @Nfe = :nfe,
            @DataNfe = :dataNfe,
            @SaidaObser = :saidaObser,
            @UsuarioId = :usuarioId,
            @ClienteId = :clienteId,
            @EstoqueId = :estoqueId,
            @ProdutoId = :produtoId,
            @Quantidade = :quantidade
        ");


        // Vincula os parâmetros
        $stmt->bindParam(':tipoMovimentacao', $tipoMovimentacao, PDO::PARAM_STR);
        $stmt->bindParam(':nfe', $nfe, PDO::PARAM_INT);
        $stmt->bindParam(':dataNfe', $dataNfe, PDO::PARAM_STR);
        $stmt->bindParam(':saidaObser', $movimentacaoObser, PDO::PARAM_STR);
        $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindParam(':estoqueId', $estoqueId, PDO::PARAM_INT);
        $stmt->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);

        // Executa a procedure
        if ($stmt->execute()) {
            header('Location: ../front-end/html/register-saida.php?status=sucesso');
            exit();
        } else {
            header('Location: ../front-end/html/register-saida.php?status=falha');
            exit();
        }

    } catch (Exception $e) {
        error_log("Erro ao registrar saida: " . $e->getMessage());
        header('Location: ../front-end/html/register-saida.php?status=falha');
        exit();
    }
?>
