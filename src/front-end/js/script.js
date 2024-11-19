let products = [

];

// Função para carregar a lista de produtos
function loadProducts() {
    const productList = document.getElementById('product-list');
    productList.innerHTML = '';
    products.forEach((product) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.type}</td>
            <td>${product.category}</td>
            <td>${product.matter}</td>      
            <td>${product.quantity}</td>
            <td>${product.price.toFixed(2)}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Excluir</button>
            </td>
        `;
        productList.appendChild(row);
    });
}


// Função para buscar produtos
function searchProduct() {
    const searchTerm = document.getElementById('search-bar').value.toLowerCase();
    const filteredProducts = products.filter(product =>
        product.name.toLowerCase().includes(searchTerm)
    );
    const productList = document.getElementById('product-list');
    productList.innerHTML = '';
    filteredProducts.forEach((product) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.type}</td>
            <td>${product.category}</td>
            <td>${product.matter}</td>      
            <td>${product.quantity}</td>
            <td>${product.price.toFixed(2)}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Excluir</button>
            </td>
        `;
        productList.appendChild(row);
    });
}


// Função para adicionar um produto
function addProduct() {
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    document.getElementById('product-form').reset();
    modal.show();

    document.getElementById('product-form').onsubmit = (e) => {
        e.preventDefault();
        const name = document.getElementById('product-name').value;
        const type = document.getElementById('product-type').value;
        const category = document.getElementById('product-category').value;
        const matter = document.getElementById('product-matter').value;
        const quantity = parseInt(document.getElementById('product-quantity').value);
        const price = parseFloat(document.getElementById('product-price').value);

        const newProduct = {
            id: products.length + 1,
            name,
            type,
            category,
            matter,
            quantity,
            price
        };
        products.push(newProduct);
        modal.hide();
        loadProducts();
    };
}

// Função para editar um produto (simples placeholder)
function editProduct(id) {
    const product = products.find(p => p.id === id);
    document.getElementById('product-name').value = product.name;
    document.getElementById('product-type').value = product.type;
    document.getElementById('product-category').value = product.category;
    document.getElementById('product-matter').value = product.matter;
    document.getElementById('product-quantity').value = product.quantity;
    document.getElementById('product-price').value = product.price;

    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();

    // Atualizar o produto ao salvar o formulário
    document.getElementById('product-form').onsubmit = (e) => {
        e.preventDefault();
        product.name = document.getElementById('product-name').value;
        product.type = document.getElementById('product-type').value;
        product.category = document.getElementById('product-category').value;
        product.matter = document.getElementById('product-matter').value;
        product.quantity = parseInt(document.getElementById('product-quantity').value);
        product.price = parseFloat(document.getElementById('product-price').value);
        modal.hide();
        loadProducts();
    };
}

// Função para deletar um produto
function deleteProduct(id) {
    products = products.filter(product => product.id !== id);
    loadProducts();
}


function entradasaida() {
    const modal = new bootstrap.Modal(document.getElementById('entradaSaidaModal'));
    modal.show();

    document.getElementById('entrada-saida-form').onsubmit = (e) => {
        e.preventDefault();

        const tipoOperacao = document.getElementById('tipo_operacao').value;
        const produto = document.getElementById('produto').value;
        const quantidade = parseInt(document.getElementById('quantidade').value);
        const responsavel = document.getElementById('responsavel').value;
        const dataOperacao = document.getElementById('data_operacao').value;

        let materiaPrima = null;
        if (tipoOperacao === 'entrada') {
            materiaPrima = document.getElementById('materia_prima').value;
        }

        // Fechar o modal após o envio
        const modal = bootstrap.Modal.getInstance(document.getElementById('entradaSaidaModal'));
        modal.hide();
        document.getElementById('entrada-saida-form').reset();
        alert('Operação registrada com sucesso!');
    };



}

function mostrarCamposEntradaSaida() {
    const tipoOperacao = document.getElementById('tipo_operacao').value;
    const campoTipoEntrada = document.getElementById('campo_tipo_entrada');
    const campoMateriaPrima = document.getElementById('campo_materia_prima');
    const campoNotaFiscal = document.getElementById('campo_nota_fiscal');
    const campoCliente = document.getElementById('campo_cliente');
    const campoTurno = document.getElementById('campo_turno');

    // Resetando os campos
    campoTipoEntrada.style.display = 'none';
    campoMateriaPrima.style.display = 'none';
    campoNotaFiscal.style.display = 'none';
    campoCliente.style.display = 'none';
    campoTurno.style.display = 'none';

    if (tipoOperacao === 'entrada') {
        campoTipoEntrada.style.display = 'block';
        mostrarCamposEspecificosEntrada();
    } else if (tipoOperacao === 'saida') {
        campoNotaFiscal.style.display = 'block'; // Nota fiscal aparece para Saída
        campoCliente.style.display = 'block'; // Cliente aparece para Saída
    }
}

function mostrarCamposEspecificosEntrada() {
    const tipoEntrada = document.getElementById('tipo_entrada').value;
    const campoMateriaPrima = document.getElementById('campo_materia_prima');
    const campoNotaFiscal = document.getElementById('campo_nota_fiscal');
    const campoCliente = document.getElementById('campo_cliente');
    const campoTurno = document.getElementById('campo_turno');
    const campoResponsavel = document.getElementById('campo_responsavel')

    // Resetando os campos específicos
    campoMateriaPrima.style.display = 'none';
    campoNotaFiscal.style.display = 'none';
    campoTurno.style.display = 'none';
    campoResponsavel.style.display = 'none'

    if (tipoEntrada === 'fornecimento') {
        campoNotaFiscal.style.display = 'block'; // Nota fiscal para Fornecimento
        campoCliente.style.display = 'block'; // Cliente para Fabricação

        // campoMateriaPrima.style.display = 'block'; // Matéria Prima para Fornecimento
    } else if (tipoEntrada === 'fabricacao') {
        campoTurno.style.display = 'block'; // Turno para Fabricação
        campoResponsavel.style.display = 'block'

        campoCliente.style.display = 'none'; // Cliente para Fabricação

    }
}









// Chamar a função ao carregar a página para garantir que os campos estejam corretos
window.onload = mostrarCamposEspecificos;


// Carregar produtos na tabela ao iniciar
window.onload = loadProducts;
