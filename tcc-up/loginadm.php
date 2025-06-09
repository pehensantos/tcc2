<?php
// Corrigir: INICIAR SESSÃO ANTES DE QUALQUER SAÍDA
session_start();

// Exibir erros (para debug em desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Corrigir: inclusão correta do arquivo com a classe
include_once 'classes.php';
?>

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
			<div id="cobre">
				<h1>LOGIN ADM</h1>
				<label for="a">Usuário</label>
				<input type="text" name="usuario" maxlength="50" required><br>

				<label for="b">Senha</label>
				<input type="password" name="senha" maxlength="50" required><br>

				<input type="submit" name="loggar" value="Enviar">
			</div>
		</form>
	</div>

<?php  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$usuario = trim($_POST['usuario'] ?? '');
	$senha = trim($_POST['senha'] ?? '');

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
<style>
body {
	background-color: lightgreen;
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