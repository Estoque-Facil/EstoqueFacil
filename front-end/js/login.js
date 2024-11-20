const form = document.getElementById('form-login');
const userName = document.getElementById('username');
const codUser = document.getElementById('coduser');
const senha = document.getElementById('password');
const btnLogin = document.getElementById('btn-login');
let erroCod = document.getElementById('erro-cod');
let erroSenha = document.getElementById('erro-senha');

function verficarUserName() {
    if (userName.value !== '' && userName.value !== 'Usuário Não Encontrado') {
        erroCod.innerHTML = '';
        return true;  
    }
    else {
        erroCod.innerHTML = 'Código não encontrado';
        return false;
    }
}

function verficarCódigo() {
    if (codUser.value !== '') {
        erroCod.innerHTML = '';
        return true;  
    }
    else {
        erroCod.innerHTML = 'Digite um código válido';
        return false;
    }
}

function verficarSenha() {
    if (senha.value !== '' && senha.value.length >= 8) {
        erroSenha.innerHTML = '';
        return true;  
    }
    else {
        erroSenha.innerHTML = 'A senha deve conter no mínimo 8 caracteres';
        return false;
    }
}

function validarForm() {
    if (verficarCódigo()) {
        if (verficarUserName()) {
            if (verficarSenha()) {
                form.submit();
            }
        }
    }
}