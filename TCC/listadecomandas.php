<?php
session_start();

$permitidos = ["adm", "garcom", "cliente"];

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario'], $permitidos)) {
    echo "<script>
        alert('Acesso negado. Redirecionando para o login...');
        window.location.href = 'http://localhost/TCC/logingarcom.php';
    </script>";
    exit();
}


?>
<!DOCTYPE html> 
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>Lista de Comandas</title>
</head>
<body>


	<?php  
	for ($i = 1; $i <= 100; $i++) { 
		echo '<button type="button" class="botoesComandas" id="botao_'.$i.'" onclick="abrirComanda('.$i.')">'.$i.'</button>';
	}
	?>



<a href="?destroy=true" class="sair" style="position: absolute; z-index: 2000; left: 95vw; top: 2%; width: 50px; height: 50px; text-decoration: none; background-color: red; "><h1>Sair</h1></a>
<?php 	
	if (isset($_GET['destroy']) && $_GET['destroy'] == 'true') {
	    session_unset();     // Limpa as variáveis de sessão
	    session_destroy();   // Destroi a sessão
	    header("Location: loginadm.php"); // Redireciona após logout
	    exit;
	}


 ?>

	<script src="JS/javascript.js"></script>
</body>
</html>