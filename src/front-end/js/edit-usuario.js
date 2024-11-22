
function verficarSenhaAtual() {
    let usuarioId = document.getElementById("coduser").value;
    let senhaAtual = document.getElementById('password-atual').value;
    let erro = document.getElementById('erro-password-atual');
    
    if (senhaAtual.length >= 8) {
        var xhr = new XMLHttpRequest();
        var url = 'http://localhost/estoquefacil/src/back-end/verificar-senha-edit.php';

        // Cria um objeto FormData para enviar os dados no corpo da requisição
        var formData = new FormData();
        formData.append('usuarioId', usuarioId); 
        formData.append('senhaAtual', senhaAtual); 

        xhr.open('POST', url, true);

        xhr.onload = () => {
            if (xhr.status == 200) {
                var sttsSenha = xhr.responseText;
                if (sttsSenha == 'Senha incorreta') {
                    erro.innerHTML = sttsSenha;
                    return false;
                }
                else if (sttsSenha == 'Senha correta'){
                    erro.innerHTML = '';
                    return true;
                }
            } else {
                erro.innerHTML = '';
                return false;
            }
        };

        xhr.send(formData);  
    } else {
        erro.innerHTML = 'Mínimo de 8 caracteres';
        return false;
    }
}

function validarSenhas() {
    let senhaNova = document.getElementById('password-nova').value;
    let senhaNovaConfirm = document.getElementById('password-nova-confirm').value;
    let erroNova = document.getElementById('erro-password-nova');
    let erroConfirm = document.getElementById('erro-password-nova-confirm');

    if (senhaNova.length >= 8) {
        erroNova.innerHTML = '';
        if (senhaNovaConfirm == senhaNova) {
            erroConfirm.innerHTML = '';
            return true;
        }
        else if (senhaNovaConfirm.length >= 8 && senhaNovaConfirm !== senhaNova){
            erroConfirm.innerHTML = 'Senhas devem ser iguais';
            return false;  
        }
    }
    else {
        erroNova.innerHTML = 'Mínimo de 8 caracteres';
        return false;
    }
}

function validarEmail() {
    let email = document.getElementById('email').value;
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (regex.test(email)) {
        return true;
    } else {
        return false;
    }
}

function verificarEmail() {
    let usuarioId = document.getElementById("coduser").value;
    let email = document.getElementById('email').value;
    let erro = document.getElementById('erro-email');

    let stts = true;
    if (validarEmail()) {
        erro.innerHTML = '';  // Limpa qualquer erro anterior
        var xhr = new XMLHttpRequest();
        var url = 'http://localhost/estoquefacil/src/back-end/verificar-email-edit.php';

        var formData = new FormData();
        formData.append('usuarioId', usuarioId); 
        formData.append('email', email); 

        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status == 200) {
                console.log('Resposta do servidor:', xhr.responseText);
                var sttsEmail = xhr.responseText.trim();  // Garantir que não tenha espaços extras
                if (sttsEmail == 'E-mail não cadastrado') {
                    erro.innerHTML = '';  // Limpa a mensagem de erro
                }
                else {
                    erro.innerHTML = sttsEmail;  // Exibe a mensagem no erro
                    stts = false;
                }
            } else {
                erro.innerHTML = 'Erro ao verificar e-mail';
                stts = false;
            }
        };
        xhr.send(formData);  // Envia a requisição
    } else {
        erro.innerHTML = 'E-mail inválido';  // Exibe o erro
        stts = false;
    }

    if (stts === true) {
        return true;
    } else {
        return false;
    }
}

function redirecionarParaUsuarios() {
    setTimeout(() =>{
      window.location.href = '../../front-end/html/usuarios.php';
    }, 1000); 
}

function validarUsername() {
    let username = document.getElementById('username').value;
    let erro = document.getElementById('erro-username');

    if (username.length >= 3) {
        erro.innerHTML = '';
        return true;
    }
    else {
        erro.innerHTML = 'Mínimo de 3 caracteres';
        return false;
    }
}

function validarForm() {
    let form = document.getElementById('form-edit-usuario');
    let senhaAtual = document.getElementById('password-atual').value;

    if (validarUsername()) {
        if (verificarEmail()) {
            if (senhaAtual !== '') {
                console.log('Senha digitada');
                if (verficarSenhaAtual()) {
                    if (validarSenhas()) {
                        form.submit();
                    }
                }
            }
            else {
                form.submit();
            }
        }
    }
}