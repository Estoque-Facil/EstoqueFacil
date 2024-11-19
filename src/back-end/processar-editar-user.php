<?php

    session_start();
    require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

    if ($_SESSION['PermissaoNome'] !== 'Administrador') {
        require('../../back-end/logout.php');
    }

    try {
        $codigo = $_POST['coduser-hidden'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $permissions = $_POST['permissions'];
        $senhaAtual = $_POST['password-atual'];
        $senhaNova = $_POST['password-nova'];
        $senhaNovaConfirm = $_POST['password-nova-confirm'];

        $stmtGetEmailUsuario = $conexao->prepare('SELECT UsuarioEmail FROM TbUsuario WHERE UsuarioId = :codigo');
        $stmtGetEmailUsuario->bindParam(':codigo', $codigo);
        $stmtGetEmailUsuario->execute();
        $resultGetEmailUsuario = $stmtGetEmailUsuario->fetch(PDO::FETCH_ASSOC);

        if (!$resultGetEmailUsuario) {
            header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
            exit();
        }

        $emailAtual = $resultGetEmailUsuario['UsuarioEmail'];

        if ($emailAtual !== $email) {
            $stmtCheckEmailExistente = $conexao->prepare('SELECT 1 FROM TbUsuario WHERE UsuarioEmail = :email');
            $stmtCheckEmailExistente->bindParam(':email', $email);
            $stmtCheckEmailExistente->execute();

            if ($stmtCheckEmailExistente->fetch()) {
                header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=!email');
                exit();
            }
        }

        if (!empty($senhaAtual) && !empty($senhaNova) && !empty($senhaNovaConfirm)) {
            $stmtGetSenhaHash = $conexao->prepare('SELECT UsuarioSenhaHash FROM TbUsuario WHERE UsuarioId = :codigo');
            $stmtGetSenhaHash->bindParam(':codigo', $codigo);
            $stmtGetSenhaHash->execute();
            $resultGetSenhaHash = $stmtGetSenhaHash->fetch(PDO::FETCH_ASSOC);

            if (!$resultGetSenhaHash) {
                header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
                exit();
            }
            if (!password_verify($senhaAtual, $resultGetSenhaHash['UsuarioSenhaHash'])) {
                header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=!senha');
                exit();
            }

            $senhaHash = password_hash($senhaNova, PASSWORD_BCRYPT); 
            $stmtSetSenha = $conexao->prepare('EXEC SpEditarSenhaUsuario @UsuarioId = :codigo, @UsuarioSenhaHash = :senhaHash');
            $stmtSetSenha->bindParam(':codigo', $codigo);
            $stmtSetSenha->bindParam(':senhaHash', $senhaHash);

            if (!$stmtSetSenha->execute()) {
                header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
                exit();
            }
        }

        // Atualizar informações do usuário
        $stmtSetUsuario = $conexao->prepare('EXEC SpEditarUsuario @UsuarioId = :codigo, @UsuarioNome = :username, @UsuarioEmail = :email, @UsuarioStatus = :status, @PermissaoId = :permissions');
        $stmtSetUsuario->bindParam(':codigo', $codigo);
        $stmtSetUsuario->bindParam(':username', $username);
        $stmtSetUsuario->bindParam(':email', $email);
        $stmtSetUsuario->bindParam(':status', $status);
        $stmtSetUsuario->bindParam(':permissions', $permissions);

        if ($stmtSetUsuario->execute()) {
            header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=sucesso');
            exit();
        }
        else {
            header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
            exit();
        }

    } catch (PDOException $e) {
        error_log("Erro no banco: " . $e->getMessage());
        header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
        exit();
    } catch (Exception $e) {
        error_log("Erro ao registrar usuario: " . $e->getMessage());
        header('Location: ../front-end/html/edit-usuario.php?usuarioId='.$codigo.'&status=falha');
        exit();
    }

?>