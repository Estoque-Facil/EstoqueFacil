function redirecionarParaHome() {
    setTimeout(() =>{
      window.location.href = '../../front-end/html/home.php';
    }, 3000); 
}

function fecharMensagem(id) {
  setTimeout(() =>{
      document.getElementById(`${id}`).style.display = 'none';
  }, 2000); 
}
