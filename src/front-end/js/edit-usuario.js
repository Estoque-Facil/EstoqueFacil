const form = document.getElementById('form-edit-usuario');
const userName = document.getElementById('username');
const senhaAtual = document.getElementById('password-atual');
const senhaNova = document.getElementById('password-nova');
const senhaNovaConfirm = document.getElementById('password-nova-confirm');
const btnEditar= document.getElementById('btn-edit');
let errorEdit = document.getElementById('error-edit');

function verficarSenha() {
    if (senhaAtual.value !== '' && senhaAtual.value.length >= 8) {
        errorEdit.innerHTML = '';
        return true;  
    }
    else {
        errorEdit.innerHTML = 'A senha atual deve conter no mínimo 8 caracteres';
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