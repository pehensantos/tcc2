<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>LOGIN CLIENTE</title>
</head>
<body>

<div>
	<form method="post" id="login">
		<h1>LOGIN CLIENTE</h1>

		<label for="usuario">Usuário</label>
		<input type="text" id="usuario" name="usuario" maxlength="50" required><br>

		<label for="senha">Senha</label>
		<input type="password" id="senha" name="senha" maxlength="50" required><br>

		<button type="submit" name="loggar">Enviar</button>
	</form>
</div>

<?php  
session_start();
include 'classes.php';

if (isset($_POST['loggar'])) {
	$usuario = trim($_POST['usuario'] ?? '');
	$senha = trim($_POST['senha'] ?? '');

	if (empty($usuario) || empty($senha)) {
		echo "<script>alert('Por favor, preencha todos os campos.');</script>";
	} elseif (strlen($usuario) > 50 || strlen($senha) > 50) {
		echo "<script>alert('Usuário ou senha muito longos. Máximo de 50 caracteres.');</script>";
	} else {
		$autenticacao = new autenticacao();
		$result = $autenticacao->autenticarCliente($usuario, $senha);

		if ($result === "Sucesso!") {
			$_SESSION['usuario'] = "cliente";
			echo "<script>
				alert('Login realizado com sucesso!');
				window.location.href = 'listadecomandas.php';
			</script>";
			exit();
		} else {
			echo "<script>alert('Usuário ou senha incorretos.');</script>";
		}
	}
}
?>


</body>
</html>







