function redirecionarParaHome() {
    setTimeout(() =>{
      window.location.href = '../../front-end/html/home.php';
    }, 3000); 
}

function redirecionarParaClientes() {
  setTimeout(() =>{
    window.location.href = '../../front-end/html/clientes.php';
  }, 1000); 
}

function redirecionarParaProdutos() {
  setTimeout(() =>{
    window.location.href = '../../front-end/html/produtos.php';
  }, 1000); 
}

function redirecionarParaMovimentacoes() {
  setTimeout(() =>{
    window.location.href = '../../front-end/html/movimentacoes.php';
  }, 1000); 
}

function fecharMensagem(id) {
  setTimeout(() =>{
      document.getElementById(`${id}`).style.display = 'none';
  }, 2000); 
}
