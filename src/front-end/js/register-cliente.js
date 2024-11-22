function redirecionarParaHome() {
    setTimeout(() =>{
      window.location.href = '../../front-end/html/home.php';
    }, 3000); 
}

function buscarCidades() {
    let estado = document.getElementById('estado').value; // Obtém o estado selecionado
    let cidadeSelect = document.getElementById('cidade'); // Campo de cidades
    let cidades = document.getElementById('cidades'); // Campo de cidades
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>'; // Mostra um carregamento temporário
    cidades.style.display = 'block';

    if (estado) {
        // Configuração da requisição
        let xhr = new XMLHttpRequest();
        let url = 'http://localhost/estoquefacil/src/back-end/buscar-cidades.php';

        let formData = new FormData();
        formData.append('estado', estado); // Envia o UF selecionado

        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                try {
                    console.log('Resposta do servidor:', xhr.responseText);
                    let cidades = JSON.parse(xhr.responseText); // Parse da resposta JSON
                    console.log('Cidades após parse:', cidades);
                    cidadeSelect.innerHTML = '<option value="" disabled selected>Selecione uma cidade</option>'; // Limpa a lista
                    cidades.forEach(cidade => {
                        // Adiciona as opções no select
                        let option = document.createElement('option');
                        option.value = cidade.id; // ID da cidade
                        option.textContent = cidade.nome; // Nome da cidade
                        cidadeSelect.appendChild(option);
                    });
                } catch (e) {
                    cidadeSelect.innerHTML = '<option value="" disabled selected>Erro ao carregar cidades</option>';
                    console.error('Erro ao processar a resposta:', e);
                }
            } else {
                cidadeSelect.innerHTML = '<option value="" disabled selected>Erro ao buscar cidades</option>';
            }
        };
        xhr.send(formData);
    } else {
        cidadeSelect.innerHTML = '<option value="" disabled selected>Selecione um estado</option>'; // Caso nenhum UF seja selecionado
    }
}

// Função para verificar CPF no banco
function verificarCPF() {
    let tipoPessoa = document.getElementById('tipoPessoaCliente').value;
    let cpf = document.getElementById('cpf').value;
    let erro = document.getElementById('erro-cpf');

    let stts = true;
    if (tipoPessoa == 'F') {
        if (cpf === '') {
            erro.innerHTML = 'CPF é obrigatório';  // Exibe o erro
            stts = false;
        } else if (cpf.length < 14) {
            erro.innerHTML = 'CPF inválido';  // Exibe o erro
            stts = false;
        } else {
            erro.innerHTML = '';  // Limpa qualquer erro anterior
            var xhr = new XMLHttpRequest();
            var url = 'http://localhost/estoquefacil/src/back-end/verificar-cpf.php';

            var formData = new FormData();
            formData.append('cpf', cpf);  // Envia CPF desformatado para o backend

            xhr.open('POST', url, true);
            xhr.onload = () => {
                if (xhr.status == 200) {
                    console.log('Resposta do servidor:', xhr.responseText);
                    var sttsCpf = xhr.responseText.trim();  // Garantir que não tenha espaços extras
                    if (sttsCpf == 'Cpf não cadastrado') {
                        erro.innerHTML = '';  // Limpa a mensagem de erro
                    } else {
                        erro.innerHTML = sttsCpf;  // Exibe a mensagem no erro
                        stts = false;
                    }
                } else {
                    erro.innerHTML = 'Erro ao verificar CPF';
                    stts = false;
                }
            };
            xhr.send(formData);  // Envia a requisição
        } 
    } else {
        erro.innerHTML = '';  // Limpa qualquer erro anterior
    }
    return stts;
}

function verificarCNPJ() {
    let tipoPessoa = document.getElementById('tipoPessoaCliente').value;
    let cnpj = document.getElementById('cnpj').value;
    let erro = document.getElementById('erro-cnpj');

    let stts = true;
    if (tipoPessoa == 'J') {
        if (cnpj === '') {
            erro.innerHTML = 'CNPJ é obrigatório';  // Exibe o erro
            stts = false;
        } else if (cnpj.length < 18) {
            erro.innerHTML = 'CNPJ inválido';  // Exibe o erro
            stts = false;
        } else {
            erro.innerHTML = '';  // Limpa qualquer erro anterior
            var xhr = new XMLHttpRequest();
            var url = 'http://localhost/estoquefacil/src/back-end/verificar-cnpj.php';

            var formData = new FormData();
            formData.append('cnpj', cnpj); 

            xhr.open('POST', url, true);
            xhr.onload = () => {
                if (xhr.status == 200) {
                    console.log('Resposta do servidor:', xhr.responseText);
                    var sttsCnpj = xhr.responseText.trim();  // Garantir que não tenha espaços extras
                    if (sttsCnpj == 'Cnpj não cadastrado') {
                        erro.innerHTML = '';  // Limpa a mensagem de erro
                    } else {
                        erro.innerHTML = sttsCnpj;  // Exibe a mensagem no erro
                        stts = false;
                    }
                } else {
                    erro.innerHTML = 'Erro ao verificar CNPJ';
                    stts = false;
                }
            };
            xhr.send(formData);  // Envia a requisição
        } 
    } else {
        erro.innerHTML = '';
    }
    return stts;
}

function mudarVerificacao() {
    let campos = document.querySelectorAll('.erro-campo');

    campos.forEach(campo => {
        campo.innerHTML = '';
    })
}

function validarEmail() {
    let email = document.getElementById('email').value;
    let erro = document.getElementById('erro-email');
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    erro.innerHTML = '';

    if (email !== '') {
        if (regex.test(email)) {
            erro.innerHTML = '';
            return true;
        } else {
            erro.innerHTML = 'E-mail inválido';
            return false;
        }
    } else {
        erro.innerHTML = 'E-mail é obrigatório';
        return false;
    }
    
}

function validarRazaoNome() {
    let tipoPessoa = document.getElementById('tipoPessoaCliente').value;
    let razaoSocial = document.getElementById('clienteRazaoSocial').value;
    let nome = document.getElementById('clienteNome').value;
    let sobreNome = document.getElementById('clienteSobrenome').value;
    let erroRazao = document.getElementById('erro-razao');
    let erroNome = document.getElementById('erro-nome');
    let erroSobrenome = document.getElementById('erro-sobrenome');

    if (tipoPessoa == 'F') {
        if (nome == '') {
            erroNome.innerHTML = 'Nome é obrigatório';
            return false;
        } else if (nome != '') {
            erroNome.innerHTML = '';
            return true;
        }
        if (sobreNome == '') {
            erroSobrenome.innerHTML = 'Sobrenome é obrigatório';
            return false;
        } else {
            erroSobrenome.innerHTML = '';
            return true;
        }
    } else if (tipoPessoa == 'J') {
        if (razaoSocial == '') {
            erroRazao.innerHTML = 'Razão Social é obrigatório';
            return false;
        } else {
            erroRazao.innerHTML = '';
            return true;
        }
    }
}

function validarEndereco() {
    let logradouro = document.getElementById('logradouro').value;
    let numero = document.getElementById('numero').value;
    let bairro = document.getElementById('bairro').value;
    let cep = document.getElementById('cep').value;
    let uf = document.getElementById('estado').value;
    let cidade = document.getElementById('cidade').value;

    let erroLogradouro = document.getElementById('erro-logradouro');
    let erroNumero = document.getElementById('erro-numero');
    let erroBairro = document.getElementById('erro-bairro');
    let erroCep = document.getElementById('erro-cep');
    let erroUf = document.getElementById('erro-estado');
    let erroCidade = document.getElementById('erro-cidade');

    // Verifica o logradouro
    if (logradouro === '') {
        erroLogradouro.textContent = 'Logradouro é obrigatório';
        return false;
    } else {
        erroLogradouro.textContent = ''; // Limpa o erro
    }

    // Verifica o número
    if (numero === '') {
        erroNumero.textContent = 'Número é obrigatório';
        return false;
    } else {
        erroNumero.textContent = ''; // Limpa o erro
    }

    // Verifica o bairro
    if (bairro === '') {
        erroBairro.textContent = 'Bairro é obrigatório';
        return false;
    } else {
        erroBairro.textContent = ''; // Limpa o erro
    }

    // Verifica o CEP
    if (cep === '') {
        erroCep.textContent = 'CEP é obrigatório';
        return false;
    } else {
        if (cep.length < 9) {
            erroCep.textContent = 'CEP inválido';
            return false;
        }else {
            erroCep.textContent = ''; // Limpa o erro
        }
    }

    // Verifica o estado (UF)
    if (uf === '') {
        erroUf.textContent = 'Estado é obrigatório';
        return false;
    } else {
        erroUf.textContent = ''; // Limpa o erro
    }

    // Verifica a cidade
    if (uf !== '') {
        if (cidade === '') {
            erroCidade.textContent = 'Cidade é obrigatória';
            return false;
        } else {
            erroCidade.textContent = ''; // Limpa o erro
        }
    }
    
    // Se todos os campos estiverem preenchidos corretamente, retorna true
    return true;
}

function validarFormulario() {
    // Valida as funções necessárias
    let valido = true;

    // Verifica cada validação, se qualquer uma retornar false, define valido como false
    if (!validarRazaoNome()) valido = false
    if (!validarEmail()) valido = false;
    if (!verificarCPF()) valido = false;
    if (!verificarCNPJ()) valido = false;
    if (!validarEndereco()) valido = false;

    if (valido) {
        document.getElementById('form-register-cliente').submit(); 
    }
}
