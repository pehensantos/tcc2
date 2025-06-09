<?php 
	include 'classes.php';

	//$connection = new connection();

	//$gg = $connection->getUltimoPedidoDoCliente(4);

	//print_r($gg);
try {
    $pdo = new PDO("mysql:host=localhost;dbname=tcc", "popgiv08_admin", "SENHA_AQUI");
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}


?>