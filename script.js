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
        const quantity = parseInt(document.getElementById('product-quantity').value);
        const price = parseFloat(document.getElementById('product-price').value);
        const newProduct = {
            id: products.length + 1,
            name,
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
    alert(`Editar produto com ID: ${id}`);
}

// Função para deletar um produto
function deleteProduct(id) {
    products = products.filter(product => product.id !== id);
    loadProducts();
}

// Carregar produtos na tabela ao iniciar
window.onload = loadProducts;
