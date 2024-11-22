<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Estoque Fácil - Login</title>
    <link rel="stylesheet" href="./src/front-end/css/style.css" />
    <script src="./src/front-end/js/msg.js"></script>
    <script src="./src/front-end/js/login.js" defer></script>
  </head>
  <body>
    <div class="login-container">
      <div class="login-title">
        <img src="./src/front-end/img/Estoque-Fácil-Photoroom.png" alt="" />
      </div>

      <form action="./src/back-end/processar-login.php" method="post" id="form-login">
        <div class="input-label">
          <label for="coduser" class="form-label">Código</label>
          <input type="number" class="form-control" name="coduser" id="coduser" oninput="updateUsername()"/> <!-- Chama a função updateUsername() -->
          <span class="span-erro" id="erro-cod"></span>
        </div>
        <div class="input-label">
          <label for="username" class="form-label">Usuário</label>
          <input type="text" class="form-control" name="username" id="username" readonly />
        </div>
        <div class="input-label">
          <label for="password" class="form-label">Senha</label>
          <input type="password" class="form-control" name="password" id="password" />
          <span class="span-erro" id="erro-senha"></span>
        </div>

        <div id="rec-cod"> <a href="../../../index.php">Não sei meu código</a></div>

        <button type="button" class="btn-login" id="btn-login" onclick="validarForm()">Entrar</button>
      </form>
    </div>

    <script>
        function redirecionarParaHomeLogin() {
            setTimeout(() =>{
              window.location.href = './src/front-end/html/home.php';
            }, 1000); 
        }

        function updateUsername() {
            var usuarioId = document.getElementById("coduser").value;

            if (usuarioId !== '') {
                var xhr = new XMLHttpRequest(); // Cria o objeto XMLHttpRequest
                var url = './src/back-end/buscar-usuario.php?UsuarioId=' + usuarioId; // URL da requisição

                xhr.open('GET', url, true); // Faz a requisição GET
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var usuarioNome = xhr.responseText; // Resposta do servidor (nome do usuário)
                        document.getElementById("username").value = usuarioNome; // Preenche o campo 'username'
                    } else {
                        document.getElementById("username").value = '';
                        console.error("Erro na requisição: " + xhr.status); // Log de erro
                    }
                };
                xhr.send(); // Envia a requisição
            } else {
                document.getElementById("username").value = '';
            }
        }
    </script>

    <?php
        session_start();
        
        if (isset($_SESSION['UsuarioId'])) {
          $usuarioId = $_SESSION['UsuarioId'];
        }
        if (isset($_GET['status']) && $_GET['status'] === 'falha') {
          echo '<div class="login-falha" id="login-falha">
                  <p>Erro no login, tente novamente!</p>
                </div>';
          echo '<script>fecharMensagem("login-falha");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === 'sucesso' && isset($_SESSION['UsuarioId'])) {
          echo  '<div class="login-ok" id="login-ok">
                  <p>Login realizado com sucesso!</p>
                </div>';
          echo  '<script>redirecionarParaHomeLogin();</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === '!cadastro') {
          echo  '<div class="login-cadastro" id="login-cadastro">
                  <p>Usuário não encontrado</p>
                </div>';
          echo  '<script>fecharMensagem("login-cadastro");</script>';
        }
        if (isset($_GET['status']) && $_GET['status'] === '!senha') {
          echo  '<div class="login-senha" id="login-senha">
                  <p>Senha incorreta</p>
                </div>';
          echo  '<script>fecharMensagem("login-senha");</script>';
        }
    ?>
  </body>
</html>
