<?php

    session_start();
    require('../../back-end/verificar-logado.php');

    if ($_SESSION['PermissaoNome'] !== 'Administrador') {
        require('../../back-end/logout.php');
    }

    function getSelectOptions($sql) {
      try {
          include '../../back-end/conexao.php';
          $options = '';

          $stmt = $conexao->prepare($sql);
          $stmt->execute();
          $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $stmt->closeCursor();   

          if (empty($resultados)) {
              $options .= '<option value="" disabled>Nenhum item encontrado</option>';
          } else {
              foreach ($resultados as $resultado) {
                  $options .= '<option value="' . $resultado['id'] . '">' . $resultado['nome'] . '</option>';
              }
          }
          
          return $options;
      } catch (Exception $e) {
          error_log("Erro na função getSelectOptions: " . $e->getMessage());
          echo '<script>
                  alert("Ocorreu um erro inesperado. Tente novamente.");
                  window.location.href = "../../frontend/html/home-contratante.php";
              </script>';
      }
        
    }

    $permissaoOptions = getSelectOptions('SELECT PermissaoId AS id, PermissaoNome AS nome FROM TbPermissao');

?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - estoque fácil</title>
    <!-- Link do Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/register-usuario.css" />
    <script src="../js/msg.js"></script>
  </head>
  <body>
    <div class="register-container">
      <div class="register-title">
        <img src="../img/logo.jpg" alt="" />
      </div>

      <h2 class="register-title">Registro</h2>
      <form action="../../back-end/processar-registro.php" method="post" id="form-register">
        <div class="mb-3">
          <label for="username" class="form-label">Usuário</label>
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
            placeholder="Digite seu usuário"
          />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="Digite seu email"
          />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Senha</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Digite sua senha"
          />
        </div>
        <div class="mb-3">
          <label for="confirm-password" class="form-label"
            >Confirmar Senha</label
          >
          <input
            type="password"
            class="form-control"
            id="confirm-password"
            placeholder="Confirme sua senha"
          />
        </div>
        <div class="mb-3">
          <label for="permissions" class="form-label"
            >Permissões</label
          >
          <select name="permissions" id="permissions">
            <option value="" disabled selected>Escolha ao menos uma</option>
            <?php echo $permissaoOptions ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
      </form>
    </div>
    <?php
        if (isset($_GET['status']) && $_GET['status'] === 'existente') {
          echo '<div class="register-nao-ok" id="register-nao-ok">
                  <p>Email já cadastrado!</p>
                </div>';
          echo '<script>fecharMensagem("register-nao-ok");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso' && isset($_GET['usuarioid'])) {
          echo  '<div class="register-ok" id="register-ok">
                  <p>Cadastro realizado com sucesso!</p>
                </div>';
          echo  '<script>fecharMensagem("register-ok");</script>';
          echo  '<div class="cod-user" id="cod-user">
                  <p> Código do Usuário Cadastrado: '.$_GET['usuarioid'].'</p>
                </div>';
          echo  '<script>redirecionarParaHome();</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
          echo  '<div class="register-nao-ok" id="register-nao-ok">
                  <p>Erro no cadastro, tente novamente!</p>
                </div>';
          echo  '<script>fecharMensagem("register-nao-ok");</script>';
        }
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
  </body>
</html>
