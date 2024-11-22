<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    try {
        if (isset($_GET['UsuarioId'])) {
            $usuarioId = $_GET['UsuarioId'];
    
            // Prepara e executa a consulta para buscar o nome do usuário
            $stmt = $conexao->prepare('SELECT UsuarioNome FROM TbUsuario WHERE UsuarioId = :usuarioId');
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Obter o resultado
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                echo $user['UsuarioNome']; // Retorna o nome do usuário
            } else {
                echo 'Usuário Não Encontrado'; // Retorna uma string vazia caso não encontre o usuário
            }
        }
        
    } catch (Exception $e) {
        error_log('Erro no código digitado :' . $e->getMessage());
        echo 'Digite um código válido';
    }
    
?>
