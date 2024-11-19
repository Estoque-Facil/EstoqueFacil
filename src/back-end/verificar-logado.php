<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
    try {
        if (isset($_SESSION['UsuarioId'], $_SESSION['UsuarioNome'], $_SESSION['PermissaoNome'])) {

            $UsuarioId = $_SESSION['UsuarioId'];
            $stmt = $conexao->prepare("SELECT UsuarioId FROM TbUsuario WHERE UsuarioId = :UsuarioId");

            $stmt->bindParam(':UsuarioId', $UsuarioId, PDO::PARAM_INT);
            $stmt->execute();

            if (!$stmt->fetch(PDO::FETCH_NUM)) {
                header('Location: ../../back-end/logout.php');
                exit();
            } 

            $stmt->closeCursor();
        }
        else {
            header('Location: ../../back-end/logout.php');
            exit();
        }

    } catch (Exception $e){
         // Exibe a mensagem de erro e redireciona para logout
         error_log("Erro ao verificar usuário logado : " . $e->getMessage());
         header('Location: ../../index.php?status=falha');
         exit();
    }
    
?>