<?php
session_start();

if (isset($_GET['destroy']) && $_GET['destroy'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: logingarcom.php");
    exit;
}




include 'classes.php';

$permitidos = ["adm", "garcom", "cliente"];

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario'], $permitidos)) {
    echo "<script>
        alert('Acesso negado. Redirecionando para o login...');
        window.location.href = 'http://popgivet.com/tcc/logincliente.php';
    </script>";
    exit();
}




// Verifica se veio uma comanda via GET
if (isset($_GET['comanda'])) {
    $numero = (int)$_GET['comanda']; // converte para inteiro por segurança
    $_SESSION['comanda_atual'] = $numero;

    date_default_timezone_set('America/Sao_Paulo');
    $data_hora = date("Y-m-d H:i:s");


    $comanda = new comanda;

    if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'cliente') {
    	$id_cliente = $_SESSION['id_cliente'];
    	$id_atendente = 0;
    }elseif (isset($_SESSION['usuario']) && ($_SESSION['usuario'] === 'garcom' || $_SESSION['adm'])) {
    	$id_cliente = 0;
    	$id_atendente = $_SESSION['id_atendente'];
    }

    $_SESSION['id_comanda_atual'] = $comanda->abrirComanda([$numero, $data_hora, $id_cliente, $id_atendente]);

    
} elseif (isset($_SESSION['comanda_atual'])) {
    $numero = $_SESSION['comanda_atual'];
    
} else {
    // Nenhuma comanda selecionada
    echo "Nenhuma comanda selecionada.";
    exit;
}

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario'], $permitidos)) echo "<script>alert('Acesso negado. Redirecionando para o login...'); window.location.href = '" . ($_SESSION['usuario'] === 'cliente' ? 'http://popgivet.com/TCC/logincliente.php' : 'http://popgivet.com/tcc/logingarcom.php') . "';</script>", exit();


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>Lista de Produtos</title>
</head>
<body id="bodylogin">
	<div class="faixaTop">
		<h1 style="margin-left: 2%"><a href="listadecomandas.php" style="text-decoration: none; color: inherit; ">Comanda </a><?php echo $_SESSION['comanda_atual']; ?></h1>
	</div>

	<form method="post" action="">

		<div id="janelaConfirmacaoFundo" style="display: none"></div>
		<div id="janelaConfirmacao" class="janela" style="display: none">
		  <div class="conteudo">
		    <h2>Confirmar Pedido</h2>
		    <ul id="listaItens"></ul>
		    <button type="submit" name="submit" onclick="confirmar()">Confirmar</button>
		    <button type="button" onclick="fechar()">Cancelar</button>
		  </div>
		</div>

		<a href="?destroy=true" class="sair" style="position: absolute; z-index: 2000; left: 95%; top: 2%; width: 50px; height: 50px; text-decoration: none; background-color: red; "><h1>Sair</h1></a>


		<div class="container2">
		<div class="container">
			
			<?php 

			$system = new system();
			$system->listarProdutos();

			?>

		</div>
		</div>
	
	</form>
	
		<div class="faixaBottom">
			<div></div>
			<div></div>
			<div><button type="button" id="esse" onclick="mostrarConfirmacao()">Confirmar</button>
			<!--<div><button type="submit" name="submit">Confirmar</button>-->
			<!--<button onclick="mostrarConfirmacao()">Finalizar Pedido</button>-->
		</div>
	





	

	



	<?php
		$comanda = new comanda();

			if (isset($_POST['quantidade']) && isset($_POST['id_produto'])) {
			    $quantidades = $_POST['quantidade'];
			    $ids = $_POST['id_produto'];

			    foreach ($quantidades as $i => $qtd) {
			        if (intval($qtd) > 0) {
			            $produto = [
			            	$_SESSION['id_comanda_atual'],
			                $ids[$i],      // ID do produto
			                '',            // Descrição (adicione se precisar)
			                $qtd           // Quantidade
			            ];

			            if (!isset($_SESSION['id_atendente'])) {
			            	$id_atendente = 0;
			            }

			            if (!isset($_SESSION['id_cliente'])) {
			            	$id_cliente = 0;
			            }

			            echo '<script>alert("Produto: ' . implode(', ', $produto) . '");</script>';
			            $horaAtual = date('Y-m-d H:i:s');
			            $comanda->confirmarPedido($_SESSION['id_comanda_atual'], (int)$ids[$i], $id_atendente, $id_cliente, "nao", (int)$qtd, $horaAtual);
			        }
			    }
			}
		?>
		    	  
	<script src="JS/javascript.js"></script>
</body>
</html>