<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if (isset($_POST['usuarioId'], $_POST['senhaAtual'])) {
        try {
            // Validação de senha
            $usuarioId = $_POST['usuarioId'];
            $senhaAtual = $_POST['senhaAtual'];
    
            // Prepara e executa a consulta para buscar a senha hash do usuário
            $stmt = $conexao->prepare('SELECT UsuarioSenhaHash FROM TbUsuario WHERE UsuarioId = :usuarioId');
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verifica se a senha atual está correta
            if ($user && password_verify($senhaAtual, $user['UsuarioSenhaHash'])) {
                echo 'Senha correta';
            } else {
                echo 'Senha incorreta';
            }
            
        } catch (Exception $e) {
            error_log('Erro na senha digitada :' . $e->getMessage());
            echo 'Erro';
        }
    }
?>
