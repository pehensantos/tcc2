function aumentarContador(index) {
    var contador = document.getElementById('contador_' + index);
    var inputQtd = document.getElementById('input_qtd_' + index);
    
    var valor = parseInt(contador.innerText);
    valor++;
    contador.innerText = valor;
    inputQtd.value = valor;
}

function diminuirContador(index) {
    var contador = document.getElementById('contador_' + index);
    var inputQtd = document.getElementById('input_qtd_' + index);
    
    var valor = parseInt(contador.innerText);
    if (valor > 0) {
        valor--;
        contador.innerText = valor;
        inputQtd.value = valor;
    }
}

function abrirComanda(index){
    var botao = document.getElementById('botao_' + index);

    botao.style.backgroundColor = "black";

    window.location.href = "lista.php?comanda=" + index;


}

function mostrarConfirmacao() {
    var janelaConfirmacaoFundo = document.getElementById('janelaConfirmacaoFundo');
    var janelaConfirmacao = document.getElementById('janelaConfirmacao');
    janelaConfirmacao.style.display = "block";
    janelaConfirmacaoFundo.style.display = "block";

    
}

function confirmar() {
    
}

function fechar() {
    var janelaConfirmacaoFundo = document.getElementById('janelaConfirmacaoFundo');
    var janelaConfirmacao = document.getElementById('janelaConfirmacao');
    janelaConfirmacao.style.display = "none";
    janelaConfirmacaoFundo.style.display = "none";
}