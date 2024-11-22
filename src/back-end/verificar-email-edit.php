<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['usuarioId'], $_POST['email'])) {
        try {
            $usuarioId = $_POST['usuarioId'];
            $email = $_POST['email'];

            // Prepara e executa a consulta para verificar se o e-mail já está cadastrado
            $stmt = $conexao->prepare('SELECT 1 AS EmailCadastrado FROM TbUsuario WHERE UsuarioEmail = :email AND UsuarioId != :usuarioId');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT); // Exclui o próprio usuário da verificação
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo 'E-mail já cadastrado';  // Envia a resposta para o JavaScript
            } else {
                echo 'E-mail não cadastrado';  // Envia a resposta para o JavaScript
            }
        } catch (Exception $e) {
            error_log('Erro no email digitado :' . $e->getMessage());
            echo 'Erro';  // Envia erro se algo der errado
        }
    }
?>
