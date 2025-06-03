<?php

class system{  

	public function inserirProduto(array $produto){
	    // Troca vírgula por ponto no preço
	    $produto[2] = str_replace(',', '.', $produto[2]);

	    $connection = new connection();
	    $connection->insert(
	        "produtos", //tabela
	        ["nome_produto", "descricao_produto", "preco_produto"],
	        $produto //colunas
	    );
	}

	public function listarProdutos(){
		$connection = new connection();
		$lista = $connection->select("produtos");

		foreach ($lista as $index => $linha) {
		    echo '<ul class="produto">';
		    echo '<div class="contador"><p id="contador_'.$index.'">0</p></div>
		    	  <button class="botaoProdutos" type="button" onclick="aumentarContador(' . $index . ')">Adicionar</button>
		    	  <button class="botaoProdutos2" type="button" onclick="diminuirContador(' . $index . ')">Remover</button>';

		    foreach ($linha as $coluna => $valor) {
		        echo "<li><strong>$coluna:</strong> $valor</li>";

			    // Adiciona campo oculto com o ID do produto
		        if ($coluna == "id_produto") {
		            echo '<input type="hidden" name="id_produto[]" value="' . $valor . '">';
		        }
		    }

			// Campo oculto que será atualizado com JS com o valor do contador
	    	echo '<input type="hidden" name="quantidade[]" id="input_qtd_' . $index . '" value="0">';


		    echo '</ul><hr>';
		}
	}

	public function excluirProduto($nome, $id){
		$connection = new connection();
		$connection->delete("produtos", "id_produto", $id, "nome_produto", $nome);
	}

	public function alterarProduto($nome_produto, $descricao_produto, $preco_produto, $id, $nome){
	    $connection = new connection();

	    // Corrige vírgula no preço, se necessário
	    //$preco_produto = str_replace(',', '.', $preco_produto);

	    $connection->update("produtos", [
	        "nome_produto" => $nome_produto,
	        "descricao_produto" => $descricao_produto,
	        "preco_produto" => $preco_produto
	    ], "id_produto", $id, "nome_produto", $nome);
	}

	public function validarEntradas(){

	}
} 

class Connection{
	protected $host;
	protected $database;
	protected $user;
	protected $password;
	protected $pdo;

	public function __construct(){
		$this->host = 'localhost';
		$this->database = 'tcc';			
		$this->user = 'root';
		$this->password = '';
		$this->pdo = new PDO("mysql:host={$this->host};dbname={$this->database};charset=utf8",$this->user,$this->password);
	}

	public function getPDO() {
		return $this->pdo;
	}



	public function select(string $table){

		$stringdeconexao = "SELECT * FROM $table";
		$stmt = $this->pdo->prepare($stringdeconexao);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);


		if ($WHEREthis == '' || $isEQUAL == '') {
			$stringdeconexao = "SELECT $columns FROM $table";
			$stmt = $this->pdo->prepare($stringdeconexao);
			$stmt->execute();
		}else{
			$stringdeconexao = "SELECT $columns FROM $table WHERE $WHEREthis = ?";
			$stmt = $this->pdo->prepare($stringdeconexao);
			$stmt->execute([$isEQUAL]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert(string $table, array $columns, array $values) {
	    // Junta os nomes das colunas: nome_produto, descricao_produto, preco_produto
	    $columnsString = implode(", ", $columns);

	    // Cria placeholders: ?, ?, ?
	    $placeholders = implode(", ", array_fill(0, count($values), "?"));

	    // Monta o SQL final
	    $sql = "INSERT INTO $table ($columnsString) VALUES ($placeholders)";

	    // Prepara e executa
	    $stmt = $this->pdo->prepare($sql);
	    $stmt->execute($values);
	}

	public function update(string $table, array $column_value, $WHEREthis, $isEQUAL, $ANDthis, $isEQUALthis){ $column_value_dict = [];
		foreach ($column_value as $column => $value) {
			$column_value_dict[] = "$column = '$value'";
		}
		$column_value_dict = implode(',', $column_value_dict);
		$stringdeconexao = "UPDATE $table SET $column_value_dict WHERE $WHEREthis = ? AND $ANDthis = ?";
		
		$stmt = $this ->pdo->prepare($stringdeconexao);
		$stmt->execute([$isEQUAL, $isEQUALthis]);
	}

	public function delete($table, $WHEREthis, $isEQUAL, $ANDthis, $isEQUALthis){
		$stringdeconexao = "DELETE FROM $table WHERE $WHEREthis = ? AND $ANDthis = ?";
		$stmt = $this->pdo->prepare($stringdeconexao);
		$stmt->execute([$isEQUAL, $isEQUALthis]);
	}
}

class cliente{
	private $nome;


}

class autenticacao extends Connection{

	public function autenticarCliente($login, $senha) {
	    $stringdeconexao = "SELECT `id_cliente`, `login`, `senha` FROM `clientes` WHERE `login` = ?";
	    $stmt = $this->pdo->prepare($stringdeconexao);
	    $stmt->execute([$login]);

	    $result = $stmt->fetch(PDO::FETCH_ASSOC); // pega só um resultado

	    if ($result) {
	        if ($senha === $result['senha']) {
	            $_SESSION['id_cliente'] = $result['id_cliente'];
	            return "Sucesso!"; // Login e senha corretos
	        } else {
	            return "Senha incorreta!"; // Senha incorreta
	        }
	    } else {
	        return "Usuário não encontrado!"; // Login não encontrado
	    }
	}


	public function autenticarGarcom($login, $senha) {
	    $stringdeconexao = "SELECT `id_atendente`, `login`, `senha` FROM `atendentes` WHERE `login` = ? AND `cargo` = 'garcom'";
	    $stmt = $this->pdo->prepare($stringdeconexao);
	    $stmt->execute([$login]);

	    $result = $stmt->fetch(PDO::FETCH_ASSOC); // pega só um resultado

	    if ($result) {
	        if ($senha === $result['senha']) {
	        	$_SESSION['id_atendente'] = $result['id_atendente'];

	            return "Sucesso!"; // Login e senha corretos
	        } else {
	            return "Senha incorreta!"; // Senha incorreta
	        }
	    } else {
	        return "Usuário não encontrado!"; // Login não encontrado
	    }
	}




	public function autenticarAdm($login, $senha) {
		$stringdeconexao = "SELECT `id_atendente`, `login`, `senha`, `cargo` FROM `atendentes` WHERE `login` = ? AND `cargo` = 'adm'";

	    $stmt = $this->pdo->prepare($stringdeconexao);
	    $stmt->execute([$login]);

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    

	    if ($result) {

	        if ($senha === $result['senha'] && $result['cargo'] === 'adm') {
	        	$_SESSION['id_atendente'] = $result['id_atendente'];
	            return "Sucesso!"; // Login e senha corretos
	        } else {
	            return "Senha incorreta!"; // Senha incorreta
	        }
	    } else {
	        return "Usuário não encontrado!"; // Login não encontrado
	    }
	}


}


class comanda{

	private $id_comanda;
	private $numero;
	private $nomeCliente;
	private $itens = [];



	public function abrirComanda(array $values){
		$connection = new connection(); // Corrigido: não se usa "new $connection()"

		$connection->insert(
			"comandas", 
			["numero_comanda", "data", "id_cliente", "id_atendente"],
			$values
		);

		// Recuperar o ID da última comanda inserida
		$this->id_comanda = $connection->getPDO()->lastInsertId();
		return $this->id_comanda;

	}



	public function adicionarPedido(array $listaDeItens) {
		
	}

	public function excluirItens(array $listaDeItens){

	}

	public function getItens() {
		return $this->itens;
	}

	public function confirmarPedido(int $id_comanda, int $id_produto, int $id_atendente, int $id_cliente, string $impresso, int $quantidade) {
		$connection = new connection();



		$values = [$id_comanda, $id_produto, $id_atendente, $id_cliente, $impresso, $quantidade]; // agora temos 4 valores
		$connection->insert(
				"pedidos", 
				["id_comanda", "id_produto", "id_atendente", "id_cliente", "impresso", "quantidade"],
				$values
			);

	}


}


?>