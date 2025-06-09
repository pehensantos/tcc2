<?php 
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>LOGIN GARÇOM</title>
</head>
<body>

		<form method="post" id="login">
			<div id="cobre">
			    <h1>LOGIN GARÇOM</h1>
			    
			    <label for="a">Usuário</label>
			    <input type="text" name="usuario" maxlength="50" required><br>
			    
			    <label for="b">Senha</label>
			    <input type="password" name="senha" maxlength="50" required><br>

			    <button type="submit" name="loggar">Enviar</button>
			</div>
		</form>

<?php  

include 'classes.php';

if (isset($_POST['loggar'])) {
    // Limpeza e validação básica
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verificar se os campos estão preenchidos
    if (empty($usuario) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
    } elseif (strlen($usuario) > 50 || strlen($senha) > 50) {
        echo "<script>alert('Usuário ou senha muito longos. Máximo de 50 caracteres.');</script>";
    } else {
        // Se passou na validação, continua a autenticação
        $autenticacao = new autenticacao();
        $result = $autenticacao->autenticarGarcom($usuario, $senha);

        if ($result === 'Sucesso!') {
            $_SESSION['usuario'] = "garcom";
            echo '<script>window.location.href = "listadecomandas.php";</script>';
        } else {
            echo "<script>alert('Usuário ou senha inválidos.');</script>";
        }
    }
}
?>



</body>
</html>

<style>
body {
	background-color: lightcoral;
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







