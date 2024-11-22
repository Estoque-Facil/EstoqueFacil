<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['produto'])) {
        try {
            $produto = $_POST['produto'];

            // Prepara e executa a consulta para verificar se o e-mail já está cadastrado
            $stmt = $conexao->prepare('SELECT * FROM VwConsultarComponentesProduto WHERE ProdutoId = :produto;');
            $stmt->bindParam(':produto', $produto);
            $stmt->execute();

            $componentes = [];
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $componentes[] = $linha;
            }

            // Retorna o JSON com as cidades
            echo json_encode($componentes);

            $stmt->closeCursor();
        } catch (Exception $e) {
            error_log('Erro ao verificar componentes: ' . $e->getMessage());
            echo json_encode(['erro' => 'Erro ao verificar componentes']);
        }
    } else {
        echo json_encode(['erro' => 'produto não encontrado']);
    }
?>
