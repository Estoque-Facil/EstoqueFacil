<?php

require('./conexao.php');
session_start();

    try {

        if (!$conexao) {
            throw new Exception("Erro ao conectar ao banco de dados.");
        }

        $usuario = $_POST['username'];
        $email = $_POST['email'];
        $senhaHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $permissaoId = $_POST['permissions'];
        $usuarioIdAdmin = $_SESSION['UsuarioId'];

        $stmtGetEmail = $conexao->prepare('SELECT UsuarioId FROM TbUsuario WHERE UsuarioEmail = :email');
        $stmtGetEmail->bindParam(':email', $email);
        $stmtGetEmail->execute();

        if ($resultGetEmail = $stmtGetEmail->fetch(PDO::FETCH_ASSOC)) {
            header('Location: ../front-end/html/register-usuario.php?status=existente');
            exit();
        }
        else {
            $stmtSpRegistrar = $conexao->prepare('EXEC SpInserirUsuario @UsuarioNome = :usuario, @UsuarioEmail = :email, @UsuarioSenhaHash = :senhaHash, @PermissaoId = :permissaoId, @UsuarioIdAdmin = :usuarioIdAdmin;');

            $stmtSpRegistrar->bindParam(':usuario', $usuario);
            $stmtSpRegistrar->bindParam(':email', $email);
            $stmtSpRegistrar->bindParam(':senhaHash', $senhaHash);
            $stmtSpRegistrar->bindParam(':permissaoId', $permissaoId);
            $stmtSpRegistrar->bindParam(':usuarioIdAdmin', $usuarioIdAdmin);

            $stmtSpRegistrar->execute();

            $stmtGetUsuarioId = $conexao->prepare('SELECT UsuarioId FROM TbUsuario WHERE UsuarioNome = :usuario AND UsuarioEmail = :email');
            $stmtGetUsuarioId->bindParam(':usuario', $usuario);
            $stmtGetUsuarioId->bindParam(':email', $email);
            $stmtGetUsuarioId->execute();

            if ($result = $stmtGetUsuarioId->fetch(PDO::FETCH_ASSOC)) {
                header('Location: ../front-end/html/register-usuario.php?status=sucesso&usuarioid='.$result['UsuarioId']);
                exit();
            } else {
                header('Location: ../front-end/html/register-usuario.php?status=falha');
                exit();
            }
        }

    } catch (PDOException $e) {
        error_log("Erro no banco: " . $e->getMessage());
        echo '<script>
                window.location.href = "../front-end/html/register-usuario.php?status=falha";
            </script>';
        exit();
    } catch (Exception $e) {
        error_log("Erro ao registrar usuario: " . $e->getMessage());
        echo '<script>
                window.location.href = "../front-end/html/register-usuario.php?status=falha";
            </script>';
        exit();
    }

?>