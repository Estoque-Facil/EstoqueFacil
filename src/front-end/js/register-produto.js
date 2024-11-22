function limitarDescricao() {
    const textarea = document.getElementById("descricao");
    const charCount = document.getElementById("char-count");
    const maxLength = 200;

    // Função para limitar os caracteres e atualizar o contador
    textarea.addEventListener("input", function() {
        const currentLength = textarea.value.length;
        const remaining = maxLength - currentLength;
        
        // Impede o texto de ultrapassar o limite
        if (remaining >= 0) {
            charCount.textContent = `Restam ${remaining} caracteres`;
        } else {
            textarea.value = textarea.value.substring(0, maxLength); // Limita os caracteres
        }
    });
};

const perguntaFabSim = document.getElementById("pergunta-fab-sim");
const perguntaFabNao = document.getElementById("pergunta-fab-nao");
const materiaPrimaSection = document.getElementById("materia-prima-section");
const componenteSection = document.getElementById("componente-section");

// Função para atualizar a exibição com base no tipo de produto
function atualizarVisibilidade() {
    const fab = document.getElementById("fabricacao");
    const comp = document.getElementById("componentes");
    let tipoProdutoSelect = document.getElementById('tipoProduto');
    let tipoProduto = tipoProdutoSelect.options[tipoProdutoSelect.selectedIndex].text;

    if (tipoProduto == 'Produto') {
        fab.style.display = "block";
        comp.style.display = "block";
    }
    if (tipoProduto == 'Componente') {
        fab.style.display = "Block";
        comp.style.display = "none";
    }
    if (tipoProduto == 'Matéria Prima') {
        fab.style.display = "none";
        comp.style.display = "none";
    }
}

// Função para manipular visibilidade com base na resposta de fabricação
function validarFabricacao() {
    let fabSelect = document.getElementById('fab-select');
    let fabSim = document.getElementById('pergunta-fab-sim');
    console.log(fabSim.value);
    if (fabSim.checked) {
        fabSelect.style.display = "flex";
    } else {
        fabSelect.style.display = "none";
    }
}

// Função para manipular visibilidade com base na resposta de componentes
function validarComponentes() {
    let compSelect = document.getElementById('comp-select');
    let compSim = document.getElementById('pergunta-comp-sim');
    if (compSim.checked) {
        compSelect.style.display = "flex";
    } else {
        compSelect.style.display = "none";
    }
}

function validarFormulario() {
    // Obter todos os campos obrigatórios
    const camposObrigatorios = document.querySelectorAll('input[required], select[required], textarea[required]');

    // Verificar se todos os campos obrigatórios estão preenchidos
    let valido = true;
    camposObrigatorios.forEach(campo => {
        const erro = campo.nextElementSibling;
        if (!campo.value.trim()) {
            erro.textContent = 'Campo obrigatório';
            valido = false;
        }
        else {
            erro.textContent = '';
        }
    });

    let tipoProdutoSelect = document.getElementById('tipoProduto');
    let tipoProduto = tipoProdutoSelect.options[tipoProdutoSelect.selectedIndex].text;
    if (tipoProduto == 'Produto') {
        // Verificar campos condicionais (fabricação)
        const perguntaFab = document.querySelector('input[name="pergunta-fab"]:checked');
        if (perguntaFab.value === 'sim') {
            const materiaPrima = document.getElementById('materia-prima');
            const qtdMateriaPrima = document.getElementById('qtd-materia-prima');
            if (!materiaPrima.value) {
                valido = false;
                document.getElementById('erro-materia-prima').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
            if (!qtdMateriaPrima.value) {
                valido = false;
                document.getElementById('erro-qtd-materia-prima').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
        }

        // Verificar campos condicionais (fabricação)
        const perguntaComp = document.querySelector('input[name="pergunta-comp"]:checked');
        if (perguntaComp.value === 'sim') {
            const componente = document.getElementById('componente');
            const qtdComponente = document.getElementById('qtd-componente');
            if (!componente.value) {
                valido = false;
                document.getElementById('erro-componente').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
            if (!qtdComponente.value) {
                valido = false;
                document.getElementById('erro-qtd-componente').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
        }
    }

    if (tipoProduto == 'Componente') {
        // Verificar campos condicionais (fabricação)
        const perguntaFab = document.querySelector('input[name="pergunta-fab"]:checked');
        if (perguntaFab.value === 'sim') {
            const materiaPrima = document.getElementById('materia-prima');
            const qtdMateriaPrima = document.getElementById('qtd-materia-prima');
            if (!materiaPrima.value) {
                valido = false;
                document.getElementById('erro-materia-prima').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
            if (!qtdMateriaPrima.value) {
                valido = false;
                document.getElementById('erro-qtd-materia-prima').innerHTML = 'Obrigatório quando selecionado "SIM"';
            }
        }
    }

    // Se todos os campos forem válidos, permitir o envio do formulário
    if (valido) {
        // Enviar o formulário
        document.getElementById('form-register-produto').submit();
    }
}
