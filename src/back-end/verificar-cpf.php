<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['cpf'])) {
        try {
            $cpf = preg_replace('/\D/', '', $_POST['cpf']);

            // Prepara e executa a consulta para verificar se o e-mail já está cadastrado
            $stmt = $conexao->prepare('SELECT 1 AS CpfCadastrado FROM TbCliente C INNER JOIN TbClientePessoaFisica PF ON C.ClienteId = PF.ClienteId WHERE ClienteCpf = :cpf;');
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo 'CPF já cadastrado';  // Envia a resposta para o JavaScript
            } else {
                echo 'Cpf não cadastrado';  // Envia a resposta para o JavaScript
            }
        } catch (Exception $e) {
            error_log('Erro no cpf digitado :' . $e->getMessage());
            echo 'Erro';  // Envia erro se algo der errado
        }
    }
?>
