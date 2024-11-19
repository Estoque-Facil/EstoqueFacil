<?php
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');
    session_start();

    try {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

            $usuarioId = $_POST['coduser'];
            $username = $_POST['username'];
            $senha = $_POST['password'];
            // Prepara e executa a consulta para buscar o nome do usuário
            $stmt = $conexao->prepare('SELECT U.UsuarioId, U.UsuarioSenhaHash, P.PermissaoId, P.PermissaoNome 
            FROM TbUsuario U 
            INNER JOIN TbUsuarioPermissao UP ON U.UsuarioId = UP.UsuarioId
            INNER JOIN TbPermissao P ON UP.PermissaoId = P.PermissaoId
            WHERE U.UsuarioId = :usuarioId');
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
        
            // Obter o resultado
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($senha, $user['UsuarioSenhaHash'])) {
                    $_SESSION['UsuarioId'] = $usuarioId;
                    $_SESSION['UsuarioNome'] = $username;
                    $_SESSION['PermissaoNome'] = $user['PermissaoNome'];

                    header('Location: ../../index.php?status=sucesso');
                    exit();
                }
                else {
                    header('Location: ../../index.php?status=!senha');
                    exit();
                }
            }
            else {
                header('Location: ../../index.php?status=!cadastro');
                exit();
            }
        }
        else {
            header('Location: ../../index.php?status=falha');
            exit();
        }
        
    } catch (PDOException $e) {
        error_log("Erro no banco: " . $e->getMessage());
        header('Location: ../../index.php?status=falha');
        exit();
    } catch (Exception $e) {
        error_log("Erro ao registrar usuario: " . $e->getMessage());
        header('Location: ../../index.php?status=falha');
            exit();
    }
?>
