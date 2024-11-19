document.addEventListener("DOMContentLoaded", function() {
    const textarea = document.getElementById("clienteObser");
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
});
