
function consultarMovimentacoes() {
    let mov = document.getElementById('movimentacoes').value;
    let movTable = document.getElementById('mov-padrao'); // Campo de cidades
    let movFab = document.getElementById('mov-fabricacao');
  
    if (mov == 'S') {
        movTable.style.display = 'table';
        movFab.style.display = 'none';
        // Configuração da requisição
        let xhr = new XMLHttpRequest();
        let url = 'http://localhost/estoquefacil/src/back-end/buscar-movimentacoes.php';
    
        let formData = new FormData();
        formData.append('mov', mov); // Envia o UF selecionado
    
        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
            try {
                let movimentacoes = JSON.parse(xhr.responseText);
                let tbody = movTable.querySelector('tbody');
                tbody.innerHTML = '';
    
                if (movimentacoes.length !== 0) {
                    movimentacoes.forEach(mov => {
                        let row = tbody.insertRow();
                        let cells = [
                        mov.MovimentacaoId,
                        moment(mov.MovimentacaoData).format('DD/MM/YYYY'),
                        mov.TipoSaidaNome,
                        mov.ClienteNome || mov.ClienteRazaoSocial, // Escolhe o nome ou razão social
                        mov.ClienteCpf || mov.ClienteCnpj,
                        mov.SaidaNfe,
                        mov.SaidaDataNfe ? moment(mov.SaidaDataNfe).format('DD/MM/YYYY') : '',
                        mov.ProdutoNome,
                        mov.MovimentacaoQuantidade,
                        mov.UsuarioNome,
                        `<a href="detalhes-movimentacao.php?id=${mov.MovimentacaoId}">Ver mais</a>`,
                        ];
                        for (let i = 0; i < cells.length; i++) {
                        let cell = row.insertCell();
                        if (i == 10) {
                            let link = document.createElement('a');
                            link.href = `detalhes-movimentacao.php?id=${mov.MovimentacaoId}`;
                            link.textContent = 'Ver mais';
                            cell.appendChild(link);
                        }else {
                            cell.textContent = cells[i];
                        }
                        
                        }
                    });
                } else {
                    // Limpa o tbody antes de inserir a mensagem de erro
                    let tbody = movTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    // Cria uma nova linha para a mensagem de erro
                    let row = tbody.insertRow();
                    // Cria uma única célula que ocupa todas as colunas
                    let cell = row.insertCell();
                    cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
                    // Adiciona a mensagem de erro
                    cell.textContent = 'Nenhuma saída encontrada';
                }
                
            } catch (e) {
                    // Limpa o tbody antes de inserir a mensagem de erro
                    let tbody = movTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    // Cria uma nova linha para a mensagem de erro
                    let row = tbody.insertRow();
                    // Cria uma única célula que ocupa todas as colunas
                    let cell = row.insertCell();
                    cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
            
                    cell.textContent = 'Erro ao buscar saídas';
            }
            } else {
            // Limpa o tbody antes de inserir a mensagem de erro
            let tbody = movTable.querySelector('tbody');
            tbody.innerHTML = '';
            // Cria uma nova linha para a mensagem de erro
            let row = tbody.insertRow();
            // Cria uma única célula que ocupa todas as colunas
            let cell = row.insertCell();
            cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
    
            cell.textContent = 'Erro ao buscar saídas';
            }
        };
        xhr.send(formData);
    } else if (mov == 'E'){
        movTable.style.display = 'table';
        movFab.style.display = 'none';
        // Configuração da requisição
        let xhr = new XMLHttpRequest();
        let url = 'http://localhost/estoquefacil/src/back-end/buscar-movimentacoes.php';
    
        let formData = new FormData();
        formData.append('mov', mov); // Envia o UF selecionado
    
        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
            try {
                let movimentacoes = JSON.parse(xhr.responseText);
                let tbody = movTable.querySelector('tbody');
                tbody.innerHTML = '';
    
                if (movimentacoes.length !== 0) {
                    movimentacoes.forEach(mov => {
                        let row = tbody.insertRow();
                        let cells = [
                            mov.MovimentacaoId,
                            moment(mov.MovimentacaoData).format('DD/MM/YYYY'),
                            mov.TipoEntradaNome,
                            mov.ClienteNome || mov.ClienteRazaoSocial, // Escolhe o nome ou razão social
                            mov.ClienteCpf || mov.ClienteCnpj,
                            mov.EntradaNfe,
                            moment(mov.EntradaDataNfe).format('DD/MM/YYYY'),
                            mov.ProdutoNome,
                            mov.MovimentacaoQuantidade,
                            mov.UsuarioNome,
                            `<a href="detalhes-movimentacao.php?id=${mov.MovimentacaoId}">Ver mais</a>`,
                        ];
                        for (let i = 0; i < cells.length; i++) {
                            let cell = row.insertCell();
                            if (i == 10) {
                                let link = document.createElement('a');
                                link.href = `detalhes-movimentacao.php?id=${mov.MovimentacaoId}`;
                                link.textContent = 'Ver mais';
                                cell.appendChild(link);
                            }else {
                                cell.textContent = cells[i];
                            }
                            
                        }
                    });
                } else {
                    let tbody = movTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    let row = tbody.insertRow();
                    let cell = row.insertCell();
                    cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
                    cell.textContent = 'Nenhuma entrada encontrada';
                }
            } catch (e) {
                // Limpa o tbody antes de inserir a mensagem de erro
                let tbody = movTable.querySelector('tbody');
                tbody.innerHTML = '';
                // Cria uma nova linha para a mensagem de erro
                let row = tbody.insertRow();
                // Cria uma única célula que ocupa todas as colunas
                let cell = row.insertCell();
                cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
                // Adiciona a mensagem de erro
                cell.textContent = 'Nenhuma entrada encontrada';
            }
            } else {
            // Limpa o tbody antes de inserir a mensagem de erro
            let tbody = movTable.querySelector('tbody');
            tbody.innerHTML = '';
            // Cria uma nova linha para a mensagem de erro
            let row = tbody.insertRow();
            // Cria uma única célula que ocupa todas as colunas
            let cell = row.insertCell();
            cell.colSpan = 11; // Define o número de colunas que a célula irá ocupar
    
            cell.textContent = 'Erro ao buscar entradas';
            }
        };
        xhr.send(formData);
    } else if (mov == 'F') {
        // Configuração da requisição
        movTable.style.display = 'none';
        movFab.style.display = 'table';
        let xhr = new XMLHttpRequest();
        let url = 'http://localhost/estoquefacil/src/back-end/buscar-movimentacoes.php';
    
        let formData = new FormData();
        formData.append('mov', mov); // Envia o UF selecionado
    
        xhr.open('POST', url, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
            try {
                let movimentacoes = JSON.parse(xhr.responseText);
                let tbody = movFab.querySelector('tbody');
                tbody.innerHTML = '';
                
                if (movimentacoes !== 0) {
                    movimentacoes.forEach(mov => {
                        let row = tbody.insertRow();
                        let cells = [
                            mov.MovimentacaoId,
                            moment(mov.MovimentacaoData).format('DD/MM/YYYY'),
                            mov.TipoMovimentacao = 'F' ? 'Fabricação' : 'Desconhecido',
                            mov.EntradaId == null ? 'Saída' : 'Entrada',
                            mov.EntradaId || mov.SaidaId,
                            mov.ProdutoNome,
                            mov.MovimentacaoQuantidade,
                            mov.UsuarioNome,
                            `<a href="detalhes-movimentacao.php?id=${mov.MovimentacaoId}">Ver mais</a>`,
                        ];
                        for (let i = 0; i < cells.length; i++) {
                            let cell = row.insertCell();
                            if (i == 8) {
                                let link = document.createElement('a');
                                link.href = `detalhes-movimentacao.php?id=${mov.MovimentacaoId}`;
                                link.textContent = 'Ver mais';
                                cell.appendChild(link);
                            }else {
                                cell.textContent = cells[i];
                            }
                            
                        }
                    });
                } else {
                    let tbody = movTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    let row = tbody.insertRow();
                    let cell = row.insertCell();
                    cell.colSpan = 9; // Define o número de colunas que a célula irá ocupar
                    cell.textContent = 'Nenhuma fabricação encontrada ';
                }
                
            } catch (e) {
                // Limpa o tbody antes de inserir a mensagem de erro
                let tbody = movTable.querySelector('tbody');
                tbody.innerHTML = '';
                // Cria uma nova linha para a mensagem de erro
                let row = tbody.insertRow();
                // Cria uma única célula que ocupa todas as colunas
                let cell = row.insertCell();
                cell.colSpan = 9; // Define o número de colunas que a célula irá ocupar
                // Adiciona a mensagem de erro
                cell.textContent = 'Erro ao buscar fabricações';
            }
            } else {
            // Limpa o tbody antes de inserir a mensagem de erro
            let tbody = movTable.querySelector('tbody');
            tbody.innerHTML = '';
            // Cria uma nova linha para a mensagem de erro
            let row = tbody.insertRow();
            // Cria uma única célula que ocupa todas as colunas
            let cell = row.insertCell();
            cell.colSpan = 9; // Define o número de colunas que a célula irá ocupar
    
            cell.textContent = 'Erro ao buscar fabricações';
            }
        };
        xhr.send(formData);
    } else {
      let tbody = movTable.querySelector('tbody');
      tbody.innerHTML = '';
      let row = tbody.insertRow();
      let cell = row.insertCell();
      cell.textContent = 'Selecione um filtro';
    }
}

consultarMovimentacoes();

function criarMovimentacao() {
    let overlay = document.getElementById('overlay');
    let tipoMov = document.getElementById('tipo-mov');

    overlay.style.display = 'block';
    tipoMov.style.display = 'flex';
    tipoMov.innerHTML = 
    `   
        <a href="./register-saida.php" class="btn btn-primary" id="mov-s">Saída</a>
        <a href="./register-entrada.php" class="btn btn-primary" id="mov-e">Entrada</a>
        <a href="./register-fabricacao.php" class="btn btn-primary" id="mov-f">Fabricação</a>
    `;
}