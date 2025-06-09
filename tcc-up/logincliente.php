<?php  
session_start();
?>
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
		<div id="cobre">
			<h1>LOGIN CLIENTE</h1>

			<label for="usuario">Usuário</label>
			<input type="text" id="usuario" name="usuario" maxlength="50" required><br>

			<label for="senha">Senha</label>
			<input type="password" id="senha" name="senha" maxlength="50" required><br>

			<button type="submit" name="loggar">Enviar</button>
		</div>
	</form>
</div>

<?php  

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

<style>
body {
	background-color: lightblue;
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100vh;
	margin: 0;
}

#cobre {
	border: 1px solid black;
	background-color: whitesmoke;
	width: 400px;
	padding: 20px;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

#cobre input[type="submit"] {
	align-self: flex-end;
}

</style>







