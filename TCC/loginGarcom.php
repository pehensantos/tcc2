<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<title>LOGIN GARÇOM</title>
</head>
<body>

	<div>
		<form method="post" id="login">
		    <h1>LOGIN GARÇOM</h1>
		    
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







