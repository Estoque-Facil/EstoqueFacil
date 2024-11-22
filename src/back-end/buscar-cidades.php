<?php

    header('Content-Type: application/json');
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['estado'])) {
        try {
            $uf = $_POST['estado'];

            // Consulta para buscar cidades relacionadas ao estado
            $stmt = $conexao->prepare('SELECT CidadeId AS id, CidadeNome AS nome FROM TbCidade WHERE EstadoUf = :uf;');
            $stmt->bindParam(':uf', $uf);
            $stmt->execute();

            // Armazena os resultados em um array
            $cidades = [];
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cidades[] = $linha; // Adiciona cada linha ao array
            }

            // Retorna o JSON com as cidades
            echo json_encode($cidades);

            $stmt->closeCursor();
        } catch (Exception $e) {
            error_log('Erro ao buscar cidades: ' . $e->getMessage());
            echo json_encode(['erro' => 'Erro ao buscar cidades']);
        }
    } else {
        echo json_encode(['erro' => 'Uf não enviada']); // Caso o estado não seja enviado
    }
?>
