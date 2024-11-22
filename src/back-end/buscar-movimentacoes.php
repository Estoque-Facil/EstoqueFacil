<?php

header('Content-Type: application/json');
require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

if (isset($_POST['mov'])) {
    try {
        $mov = $_POST['mov'];

        if ($mov == 'S') {
            // Consulta para buscar cidades relacionadas ao estado
            $stmt = $conexao->prepare("SELECT * FROM VwConsultarSaidas WHERE TipoSaidaNome <> 'Fabricação';");
            $stmt->execute();

            // Armazena os resultados em um array
            $saidas = [];
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Formatar CPF e CNPJ
                $linha['ClienteCpf'] = formatarCpfCnpj($linha['ClienteCpf']);
                $linha['ClienteCnpj'] = formatarCpfCnpj($linha['ClienteCnpj']);

                $saidas[] = $linha;
            }

            // Retorna o JSON com as cidades
            echo json_encode($saidas);

            $stmt->closeCursor();
        } else if ($mov == 'E') {
            // Consulta para buscar cidades relacionadas ao estado
            $stmt = $conexao->prepare("SELECT * FROM VwConsultarEntradas WHERE TipoEntradaNome <> 'Fabricação';");
            $stmt->execute();

            // Armazena os resultados em um array
            $entradas = [];
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Formatar CPF e CNPJ
                $linha['ClienteCpf'] = formatarCpfCnpj($linha['ClienteCpf']);
                $linha['ClienteCnpj'] = formatarCpfCnpj($linha['ClienteCnpj']);

                $entradas[] = $linha;
            }

            // Retorna o JSON com as cidades
            echo json_encode($entradas);

            $stmt->closeCursor();
        } else if ($mov == 'F') {
            // Consulta para buscar cidades relacionadas ao estado
            $stmt = $conexao->prepare("SELECT * FROM VwConsultarFabricacoes WHERE TipoEntradaNome = 'Fabricação' OR TipoSaidaNome = 'Fabricação';");
            $stmt->execute();

            // Armazena os resultados em um array
            $fabricacoes = [];
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $fabricacoes[] = $linha;
            }

            // Retorna o JSON com as cidades
            echo json_encode($fabricacoes);

            $stmt->closeCursor();
        }
    } catch (Exception $e) {
        error_log('Erro ao buscar movimentacoes: ' . $e->getMessage());
        echo json_encode(['erro' => 'Erro ao buscar movimentacoes']);
    }
} else {
    echo json_encode(['erro' => 'movimentacoes não encontradas']);
}

function formatarCpfCnpj($valor) {
    // Remove todos os caracteres não numéricos
    $valor = preg_replace('/[^0-9]/', '', $valor);

    // Verifica se é CPF ou CNPJ
    if (strlen($valor) === 11) {
        // Formata como CPF
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $valor);
    } else if (strlen($valor) === 14) {
        // Formata como CNPJ
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $valor);
    } else {
        // Caso não seja CPF nem CNPJ, retorna o valor original
        return $valor;
    }
}