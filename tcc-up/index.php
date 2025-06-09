<?php 
session_start();

$permitidos = ["adm"];

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario'], $permitidos)) {
    echo "<script>
        alert('Acesso negado. Redirecionando para o login...');
        window.location.href = 'http://popgivet.com/tcc/loginadm.php';
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
	<title>Sistema do Garçom</title>
</head>
<body id="body_index">
	<?php
	include 'classes.php';

	/*
	$system = new system();
	$system->atualizarProduto("Guaraná garraf", "Guaraná", 4.90, 4); 
	$system->listarProdutos();
	*/

	?>
	
<form method="post">
    <h1>Inserir itens</h1>
    <label for="a">Nome</label>
    <input type="text" name="nome"><br>
    <label for="b">Descrição</label>
    <input type="text" name="descricao"><br>
    <label for="c">Preço</label>
    <input type="text" name="preco"><br>

    <button type="submit" name="inserir">Enviar</button>
</form>

<form method="post">
    <p>_______________________________________</p>
    <h1>Excluir itens</h1>
    <label for="a">Nome</label>
    <input type="text" name="nome"><br>
    <label for="b">ID</label>
    <input type="text" name="id"><br>

    <button type="submit" name="excluir">Enviar</button>
</form>

<form method="post">
    <p>_______________________________________</p>
    <h1>Alterar itens</h1>
    <label for="a">Nome do produto</label>
    <input type="text" name="nome"><br>
    <label for="b">Id do produto</label>
    <input type="text" name="id"><br><br>
    <br>
    <label for="c">Novo Nome</label>
    <input type="text" name="novoNome"><br>
    <label for="c">Nova Descrição</label>
    <input type="text" name="novaDescricao"><br>
    <label for="c">Novo Preço</label>
    <input type="text" name="novoPreco"><br>

    <button type="submit" name="alterar">Enviar</button>
</form>

<form method="post">
    <p>_______________________________________</p>
    <h1>Cadastrar Cliente</h1>
    <label for="a">Nome</label>
    <input type="text" name="nome"><br>
    <label for="b">CPF</label>
    <input type="text" name="cpf"><br>
    <label for="c">Telefone</label>
    <input type="text" name="telefone"><br>
    <label for="d">Login</label>
    <input type="text" name="login"><br>
    <label for="e">Senha</label>
    <input type="password" name="senha"><br> <!-- ALTERADO AQUI -->

    <button type="submit" name="cadastrar_cliente">Enviar</button>
</form>

<form method="post">
    <p>_______________________________________</p>
    <h1>Cadastrar Atendente</h1>
    <label for="a">Nome</label>
    <input type="text" name="nome"><br>
    <label for="b">Login</label>
    <input type="text" name="login"><br>
    <label for="a">Senha</label>
    <input type="password" name="senha"><br> <!-- ALTERADO AQUI -->
    <label for="cargo">Cargo</label>
    <select name="cargo" id="cargo">
      <option value="garcom">Garçom</option>
      <option value="adm">Administrador</option>
    </select><br>

    <button type="submit" name="cadastrar_atendente">Enviar</button>
</form>


<?php 
// Inserir Produto
if (isset($_POST['inserir'])) {
	$nome = trim($_POST['nome'] ?? '');
	$descricao = trim($_POST['descricao'] ?? '');
	$preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));

	if ($nome && $descricao && is_numeric($preco)) {
		$system = new system();
		$system->inserirProduto([$nome, $descricao, floatval($preco)]);
	} else {
		echo "<script>alert('Preencha todos os campos corretamente (preço numérico).');</script>";
	}
}

// Excluir Produto
if (isset($_POST['excluir'])) {
	$nome = trim($_POST['nome'] ?? '');
	$id = trim($_POST['id'] ?? '');

	if ($nome && ctype_digit($id)) {
		$system = new system();
		$system->excluirProduto($nome, intval($id));
	} else {
		echo "<script>alert('Informe um nome e um ID numérico para excluir.');</script>";
	}
}

// Alterar Produto
if (isset($_POST['alterar'])) {
	$nome = trim($_POST['nome'] ?? '');
	$id = trim($_POST['id'] ?? '');
	$novoNome = trim($_POST['novoNome'] ?? '');
	$novaDescricao = trim($_POST['novaDescricao'] ?? '');
	$novoPreco = str_replace(',', '.', trim($_POST['novoPreco'] ?? ''));

	if ($nome && ctype_digit($id) && $novoNome && $novaDescricao && is_numeric($novoPreco)) {
		$system = new system();
		$system->alterarProduto($novoNome, $novaDescricao, floatval($novoPreco), intval($id), $nome);
	} else {
		echo "<script>alert('Preencha todos os campos corretamente para alterar. Preço deve ser numérico.');</script>";
	}
}

if (isset($_POST['cadastrar_cliente'])) {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    $senhaValida = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $senha);

    if ($nome && preg_match('/^\d{11}$/', $cpf) && preg_match('/^\d{8,15}$/', $telefone) && $login && $senhaValida) {
        $connection = new Connection();

        if ($connection->exists('clientes', 'cpf', $cpf)) {
            echo "<script>alert('CPF já cadastrado. Por favor, use outro.');</script>";
        } elseif ($connection->exists('clientes', 'login', $login)) {
            echo "<script>alert('Login já em uso. Por favor, escolha outro.');</script>";
        } else {
            $connection->insert('clientes', ['nome', 'cpf', 'telefone', 'login', 'senha'], [$nome, $cpf, $telefone, $login, $senha]);
            echo "<script>alert('Cliente cadastrado com sucesso!');</script>";
        }
    } else {
        echo "<script>alert('Verifique os campos: CPF e telefone devem conter apenas números ou a senha precisa ter ao menos 6 caracteres, incluindo letras e números.');</script>";
    }
}

if (isset($_POST['cadastrar_atendente'])) {
    $nome = trim($_POST['nome'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $cargo = $_POST['cargo'] ?? '';

    $senhaValida = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $senha);

    if ($nome && $login && $senhaValida && in_array($cargo, ['garcom', 'adm'])) {
        $connection = new Connection();

        if ($connection->exists('atendentes', 'login', $login)) {
            echo "<script>alert('Login já em uso. Por favor, escolha outro.');</script>";
        } else {
            $connection->insert('atendentes', ['nome_atendente', 'login', 'senha', 'cargo'], [$nome, $login, $senha, $cargo]);
            echo "<script>alert('Atendente cadastrado com sucesso!');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos e selecione um cargo válido. A senha precisa ter ao menos 6 caracteres, incluindo letras e números.');</script>";
    }
}
?>

	<div id="screen_adm">
		<?php  
		$connection = new Connection();
			$produtos = $connection->select("produtos");

			foreach ($produtos as $produto) {
	    		echo "<ul>";
	    		foreach ($produto as $coluna => $valor) {
	        		echo "<li><strong>$coluna:</strong> $valor</li>";
	    		}
	    		echo "</ul><hr>";
			}

				



		?>
	</div>




<a href="?destroy=true" class="sair" style="position: absolute; z-index: 2000; left: 95%; top: 2%; width: 50px; height: 50px; text-decoration: none; background-color: red; "><h1>Sair</h1></a>
<?php 	
	if (isset($_GET['destroy']) && $_GET['destroy'] == 'true') {
	    session_unset();     // Limpa as variáveis de sessão
	    session_destroy();   // Destroi a sessão
	    echo "<script>
        		window.location.href = 'http://popgivet.com/tcc/loginadm.php';
    		</script>";
	    exit;
	}


 ?>
</body>
</html>

