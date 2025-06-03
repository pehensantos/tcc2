<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>LOGIN ADM</title>
</head>
<body>

	<div>
		<form method="post" id="login">
			<h1>LOGIN ADM</h1>
			<label for="a">Usuário</label>
			<input type="text" name="usuario" maxlength="50" required><br>

			<label for="b">Senha</label>
			<input type="password" name="senha" maxlength="50" required><br>

			<button type="submit" name="loggar">Enviar</button>
		</form>
	</div>

<?php  
session_start();
include 'classes.php';

if (isset($_POST['loggar'])) {
	// Sanitização básica
	$usuario = trim($_POST['usuario'] ?? '');
	$senha = trim($_POST['senha'] ?? '');

	// Validação
	if (empty($usuario) || empty($senha)) {
		echo "<script>alert('Por favor, preencha todos os campos.');</script>";
	} elseif (strlen($usuario) > 50 || strlen($senha) > 50) {
		echo "<script>alert('Usuário ou senha muito longos. Máximo de 50 caracteres.');</script>";
	} else {
		$autenticacao = new autenticacao();
		$result = $autenticacao->autenticarAdm($usuario, $senha);

		if ($result === "Sucesso!") {
			$_SESSION['usuario'] = "adm";
			echo "<script>
				alert('Login realizado com sucesso!');
				window.location.href = 'index.php';
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