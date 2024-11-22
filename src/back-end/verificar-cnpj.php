<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['cnpj'])) {
        try {
            $cnpj = preg_replace('/\D/', '', $_POST['cnpj']);

            // Prepara e executa a consulta para verificar se o e-mail já está cadastrado
            $stmt = $conexao->prepare('SELECT 1 AS CnpjCadastrado FROM TbCliente C INNER JOIN TbClientePessoaJuridica PJ ON C.ClienteId = PJ.ClienteId WHERE ClienteCnpj = :cnpj;');
            $stmt->bindParam(':cnpj', $cnpj);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo 'CNPJ já cadastrado';  // Envia a resposta para o JavaScript
            } else {
                echo 'Cnpj não cadastrado';  // Envia a resposta para o JavaScript
            }
        } catch (Exception $e) {
            error_log('Erro no CNPJ digitado :' . $e->getMessage());
            echo 'Erro no CNPJ';  // Envia erro se algo der errado
        }
    }
?>
