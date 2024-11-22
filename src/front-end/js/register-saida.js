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

function verificarComponente() {
    let produto = document.getElementById('produto').value;
    let erro = document.getElementById('erro-produto');
    let qtdProd = document.getElementById('qtd-produto').value;
    qtdProd = parseFloat(1);

    let stts = true;
    if (produto) {
        erro.innerHTML = '';  // Limpa qualquer erro anterior
        var xhr = new XMLHttpRequest();
        var url = 'http://localhost/estoquefacil/src/back-end/verificar-componente.php';

        var formData = new FormData();
        formData.append('produto', produto);

        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status == 200) {
                let componentesProd = JSON.parse(xhr.responseText);
                if (componentesProd.length !== 0) {
                    document.getElementById('componentes').style.display = 'block';
                    let compInputs = document.getElementById('comp-inputs');

                    componentesProd.forEach(comp => {
                        const divNome = document.createElement('div');
                        divNome.classList.add('form-group');
                        
                        const labelNome = document.createElement('label');
                        labelNome.setAttribute('for', 'prod-nome-hidden');
                        labelNome.classList.add('form-label');
                        labelNome.textContent = 'Componente';

                        const inputDisabledNome = document.createElement('input');
                        inputDisabledNome.type = 'text';
                        inputDisabledNome.classList.add('form-control');
                        inputDisabledNome.id = 'prod-nome-disabled'
                        inputDisabledNome.value = comp.ProdutoNome;
                        inputDisabledNome.disabled = true;

                        divNome.appendChild(labelNome);
                        divNome.appendChild(inputDisabledNome);

                        const divQtd = document.createElement('div');
                        divQtd.classList.add('form-group');

                        const labelQtd = document.createElement('label');
                        labelQtd.setAttribute('for', 'prod-qtd-hidden');
                        labelQtd.classList.add('form-label');
                        labelQtd.textContent = 'Quantidade';

                        const inputDisabledQtd = document.createElement('input');
                        inputDisabledQtd.type = 'number';
                        inputDisabledQtd.classList.add('form-control');
                        inputDisabledQtd.id = 'prod-qtd-disabled'
                        inputDisabledQtd.value = parseFloat(comp.CompProdutoQuantidade);
                        inputDisabledQtd.disabled = true;

                        divQtd.appendChild(labelQtd);
                        divQtd.appendChild(inputDisabledQtd);

                        const divValue = document.createElement('div');
                        divValue.classList.add('form-group');
                
                        const inputQuantityId = document.createElement('input');
                        inputQuantityId.type = 'text';
                        inputQuantityId.value = comp.ProdutoComponenteId;
                        inputQuantityId.id = 'id-comp';
                        inputQuantityId.name = 'id-comp';

                        const inputQuantityQtd = document.createElement('input');
                        inputQuantityQtd.type = 'number';
                        inputQuantityQtd.value = parseFloat(comp.CompProdutoQuantidade);
                        inputQuantityQtd.id = 'qtd-comp';
                        inputQuantityQtd.name = 'qtd-comp';
                
                        divValue.appendChild(inputQuantityId);
                        divValue.appendChild(inputQuantityQtd);
                
                        // Adiciona o div ao container
                        compInputs.appendChild(divNome);
                        compInputs.appendChild(divQtd);
                        compInputs.appendChild(divValue);
                    });
                } else {
                    componentes.style.display = 'none';
                    const qtdCampo = document.getElementById('qtd-comp');
                    const idCampo = document.getElementById('id-comp');
                    if (qtdCampo || idCampo) {
                        qtdCampo.value = 0.0;
                        idCampo.value = 0.0;
                    }
                }
            } else {
                erro.innerHTML = 'Erro ao procurar componentes';
                stts = false;
            }
        };
        xhr.send(formData);  // Envia a requisição 
    } else {
        erro.innerHTML = 'Selecione um produto';  // Limpa qualquer erro anterior
    }
    return stts;
}

function atualizarQtdComp() {
    const qtdProduto = document.getElementById('qtd-produto');
    const qtdCampo = document.getElementById('qtd-comp');
    const prodQtdDisabled = document.getElementById('prod-qtd-disabled');
  
    // Handle potential empty or non-numeric values
    const qtdProdutoValue = parseFloat(qtdProduto.value) || 1;
    const qtdCampoValue = parseFloat(qtdCampo.value) || 1;
  
    const calculatedValue = qtdProdutoValue * qtdCampoValue;
  
    qtdCampo.value = qtdCampoValue; // Ensure original #qtd-campo value is preserved
    prodQtdDisabled.value = calculatedValue;
}