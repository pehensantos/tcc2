<?php
	include('classes/MySql.php');
	include('classes/Site.php');
	session_start();
	date_default_timezone_set('America/Sao_Paulo');
	

	//Caminho: /home2/popgiv08/public_html/site

	define('INCLUDE_PATH','https://www.popgivet.com/site/');
	define('INCLUDE_PATH_PAINEL',INCLUDE_PATH.'painel/');

	define('INCLUDE_PATH_MOBILE','https://www.popgivet.com/site/');
	define('INCLUDE_PATH_PAINEL_MOBILE',INCLUDE_PATH_MOBILE.'painel/');
	
	//Conectar com banco de dados
	define('HOST', 'localhost');
	define('USER', 'popgiv08_admin');
	define('PASSWORD', 'admin!0408');
	define('DATABASE', 'popgiv08_popgivet');

	define('NOME_EMPRESA', 'PopGivet');

	$vdx = $_SESSION['destaque'];
	$xdv = 'liberado'; //Constante...

	//Funções

	//Painel.php
	class Painel {
    	public static function logado() {
        	return isset($_SESSION['login']) ? true : false;
    	}
    	public static function admLogado() {
        	return isset($_SESSION['admLogin']) ? true : false;
    	}

    	public static function loggout(){
    		unset($_SESSION['id_autenticacao'], $_SESSION['nickname'], $_SESSION['login'], $_SESSION['user'], $_SESSION['password'], $_SESSION['novoUsuario'], $_SESSION['textoPessoal'] );
    		echo '<script>
	        	window.location.href = "https://www.popgivet.com/";
	    	</script>';
    		exit(); // Certifique-se de usar exit() após o redirecionamento
    	}

    	public static function loggout_adm(){
    		unset($_SESSION['user_type'], $_SESSION['admLogin'], $_SESSION['user'], $_SESSION['password'], $_SESSION['cargo'], $_SESSION['nome'], $_SESSION['img'] );
    		header('Location: https://popgivet.com/site/painel/');
    		exit(); // Certifique-se de usar exit() após o redirecionamento
    	}

    	public static function carregarPagina(){
    		if (isset($_GET['url'])) {
    				$url = explode('/', $_GET['url']);
    				if (file_exists('pages/'.$url[0].'.php')) {
    					include('pages/'.$url[0].'.php');
    				}else{
    					//página não existe
    					header('Location: https://popgivet.com/site/painel/');
    				}
    		}else{
    			include('painel_pages/home.php');
    		}
    	}

    	public static function listarUsuariosOnline(){
    		self::limparUsuariosOnline();
    		$sql = MySsql::conectar()->prepare("SELECT * FROM `tb_admin_online`");
    		$sql->execute();
    		return $sql->fetchAll();
    	}

    	public static function limparUsuariosOnline(){
    		$date = date('Y-m-d H:i:s');
    		$sql = MySsql::conectar()->exec("DELETE FROM `tb_admin_online` WHERE ultima_acao < '$date' - INTERVAL 1 MINUTE");
    	}
	}
	
	function pegaCargo($cargo){
		$arr = [
		'0' => 'Normal',
		'1' => 'Sub Administrador',
		'2' => 'Administrador'];
		
	}

/////////////////////////////////////////////////////////////////////

// Função para detectar se o dispositivo é móvel
	function isMobile() {
	    return preg_match('/(android|iphone|ipad|ipod|webos|mobile|opera mini|blackberry|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);
	}

	// URL base dependendo do tipo de dispositivo
	$baseURL = isMobile() ? 'https://www.popgivet.com/site/pages/' : 'https://www.popgivet.com/site/pages/';
	$baseURLi = isMobile() ? 'https://www.popgivet.com/site/' : 'https://www.popgivet.com/site/';
	$baseURLj = '/home/usuario/public_html/site/pages/';

/////////////////////////////////////////////////////////////////////




	$sql = MySsql::conectar()->prepare("SELECT nickname, idade, tamanho_do_pe, atendimento, estado, cidade, preco, telefone, textoPessoal FROM `tb_caracteristicas_modelos`");     // Vou fazer agora selecionando todos os dados mas
	$sql->execute();
	$result = $sql->fetchALL();

	$numRegistros = count($result);
	//echo "Número de registros: ".$numRegistros."<br>";

	for ($i=0; $i < $numRegistros; $i++) { 
		$info = $result[$i];

		$path = '../site/pages/'.$info['nickname'];

		if (!file_exists($path)) {
			
			if (mkdir($path, 0777, true)) { // Lembrar de alterar as permissões posteriormente.
				//echo "´Diretório ".$path." criado com sucesso!";
			} else {
				//echo "Falha ao criar o diretório ".$path.".";
			}
		} else {
			//echo "O diretório ".$path." já existe.";
		} // Adiciona os diretórios


		//echo 'Registro ID: '. $info['id_caracteristicas'];
		//echo 'Nick da Modelo: '.$info['nickname'];
		//echo 'Path: '.$path;


		$apenas_online = $info['estado'];

		$codigo = "
	    <?php
			\$diretorio = \"/home2/popgiv08/public_html/site/pages/{$info['nickname']}/\";
			\$arquivos = scandir(\$diretorio);

			\$extensao = \"\"; // Variável para armazenar a extensão
			\$nome_base = \"perfil\"; // Nome do arquivo sem extensão
			\$arquivo_encontrado = \"\"; // Variável para armazenar o caminho completo do arquivo

			foreach (\$arquivos as \$arquivo) {
			    if (pathinfo(\$arquivo, PATHINFO_FILENAME) === \$nome_base) {
			        \$extensao = pathinfo(\$arquivo, PATHINFO_EXTENSION);
			        \$arquivo_encontrado = \$arquivo;
			        break; // Para o loop assim que encontrar o arquivo
			    }
			}

	    echo '
	    <div class=\"cobreTudo1\">
	        <div class=\"foto-perfil\">
	            <img src=\"' . \$baseURL . '{$info['nickname']}' . '/perfil.' . \$extensao . '\" alt=\"foto-perfil\">
	        </div> <!--foto-perfil-->

		    <div class=\"informacoes\">
		        <h1>{$info['nickname']}</h1>
		        <p style=\"color: white; font-family: \'sofia\', sans-serif; text-align: center;\">" .addslashes($info['textoPessoal'])."</p>
		        <table>
		            <tr>
		                <th>Idade: <span style=\"color: white\">" .addslashes($info['idade'])."</th>
		            </tr>
		            <tr>
		                <th>Tamanho do pé: <span style=\"color: white\">" .addslashes($info['tamanho_do_pe'])."</th>
		            </tr>
		            <tr>
		                <th>Atendimento: <span style=\"color: white\">" . addslashes($info['atendimento']) . "</span></th>
		            </tr>';
		            
		            // Estado
		            if ('$apenas_online' === 'apenas_online') {
		                echo '<tr style=\"display: none;\">
		                        <th>Estado: <span style=\"color: white\">" .addslashes($info['estado'])."</th>
		                      </tr>';
		            } else {
		                echo '<tr>
		                        <th>Estado: <span style=\"color: white\">" .addslashes($info['estado'])."</th>
		                      </tr>';
		            }

		            // Cidade
		            if ('$apenas_online' === 'apenas_online') {
		                echo '<tr style=\"display: none;\">
		                        <th>Cidade: <span style=\"color: white\">" .addslashes($info['cidade'])."</th>
		                      </tr>';
		            } else {
		                echo '<tr>
		                        <th>Cidade: <span style=\"color: white\">" .addslashes($info['cidade'])."</th>
		                      </tr>';
		            }
		    echo '
				    <tr>
		                <th>Preço: <span style=\"color: white\">" .addslashes($info['preco'])."</th>
		            </tr>
		            <tr>
		                <th>WhatsApp: <span style=\"color: white\">" .addslashes($info['telefone'])."</th>
		            </tr>
		        </table>
		    </div>

	        <section class=\"pand\">
	            <div class=\"container\">
	                '; // colocar diretorio da modelo

					    foreach (\$arquivos as \$arquivo) {
					        \$extensao = pathinfo(\$arquivo, PATHINFO_EXTENSION);
					        if (in_array(strtolower(\$extensao), ['jpg', 'jpeg', 'png', 'gif'])) {
					        	echo '<div class=\"alinhar\">';
					            echo '<img src=\"{$baseURL}{$info['nickname']}/' . \$arquivo . '\" alt=\"Ícone 1\">';
					            echo '</div>';
					        }
					    }
					

	    echo '	
	    		<div style=\"clear:both;\"></div>
	            </div>
	        </section>
	        </div>

	        <a target=\"_blank\" href=\"https://api.whatsapp.com/send/?phone=55{$info['telefone']}&amp;text=Olá!+Te+vi+no+popgivet.com+e+te+achei+muito+linda!+Quais são os valores?&amp;type=phone_number&amp;app_absent=0\">
				<img src=\"{$baseURLi}/images/iconewpp.png\" style=\"position: fixed; bottom: 10%; right: 11%; width: 70px; opacity: 0.5;\">
			</a>';
	    ?>
	    <script>
			window.onload = function() {
			    var container = document.querySelector('.container');
			    var images = container.getElementsByTagName('img');
			    var totalImages = images.length;

			    if (totalImages % 2 !== 0 && totalImages !== 1) {
	                if (window.innerWidth > 700) {
	                    // Se o total de imagens for ímpar e a largura da tela for maior que 700px
	                    images[totalImages - 1].style.float = 'left';
	                    /*images[totalImages - 1].style.marginLeft = '9%';*/
	                }
			    }

			};
		</script>

		<script>
			var menuAberto = false;
			var menuClick = document.getElementById('menuClick');
			function mostrarMenu(){
				var hiddenMenuItems = document.querySelectorAll('nav.desktop ul.hiddenMenu li:not(:first-child)');

				if (!menuAberto) {
						hiddenMenuItems.forEach(function(item) {
			        	item.style.display = 'block';
			        });

			        menuAberto = true;

			    } else {
			        hiddenMenuItems.forEach(function(item) {
			            item.style.display = 'none';
			        });
			        menuAberto = false;
			    }
			}

			// Adicione o evento de clique no menu
			menuClick.addEventListener('click', mostrarMenu);
		</script>
		<script>
			// Seleciona o botão de menu e o menu
			const menuButton = document.getElementById('menuButton');
			const sideMenu = document.getElementById('sideMenu');

			// Abre o menu ao clicar no botão
			menuButton.addEventListener('click', function() {
			    sideMenu.classList.toggle('open');
			});

			// Variáveis para rastrear o gesto de deslizar
			let startX = 0;
			let moveX = 0;
			let isDragging = false;

			// Início do gesto de deslizar - no documento inteiro
			document.addEventListener('touchstart', function(e) {
			    startX = e.touches[0].clientX;
			    isDragging = true;
			});

			// Durante o gesto de deslizar - no documento inteiro
			document.addEventListener('touchmove', function(e) {
			    if (!isDragging) return;

			    moveX = e.touches[0].clientX;
			    const diffX = startX - moveX;

			    if (diffX > 50) {  // Distância mínima para fechar o menu
			        sideMenu.classList.remove('open');
			        isDragging = false;  // Desabilita o arraste após fechar
			    }
			});

			// Quando o gesto de deslizar termina
			document.addEventListener('touchend', function() {
			    isDragging = false; // Desabilita o arraste quando o toque termina
			});

			// Fechar o menu ao clicar fora (opcional)
			document.addEventListener('click', function(e) {
			    if (!sideMenu.contains(e.target) && !menuButton.contains(e.target)) {
			        sideMenu.classList.remove('open');
			    }
			});
		</script>
		";

		$file_path = '/home2/popgiv08/public_html/site/pages/'.$info['nickname'].'/index.php';

		$file = fopen($file_path, 'w'); // Open the file for writing (create if not exists)

		if ($file === false) {
    		echo 'Não foi possível abrir o arquivo para escrita.';
		}

		if (fwrite($file, $codigo) === false) {
    		echo 'Não foi possível escrever no arquivo.';
		}

		fclose($file);

	}



ini_set('log_errors', '1');
ini_set('error_log', '/home2/popgiv08/public_html/site/erros.log');

ini_set('display_errors', '0');

// Testando o log de erro
//trigger_error("Este é um teste de log de erro!", E_USER_NOTICE);




?>