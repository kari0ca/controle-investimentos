<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	// manipular variaveis da pagina, para deixar o menu ativo
	reset_active();
	if(isset($page) && !empty($page)) {
		switch ($page) {
			case "Entrada":
			    $active_index='active';
			    break;
			case "Sobre":
			    $active_sobre='active';
			    break;
			case "Contato":
			    $active_contato='active';
			    break;
			case "Carteira":
			    $active_carteira='active';
			    break;
			case "Estat":
			    $active_estat='active';
			    break;
			case "Config":
			    $active_config='active';
			    break;
			default:
			//echo "Your favorite color is neither red, blue, nor green!";
		}
	}
	
	function reset_active() {
		$active_index='';
		$active_sobre='';
		$active_contato='';
		$active_carteira='';
		$active_estat='';
		$active_config='';
	}
?>
<html>
<head>
	<meta name="description" content="<?php
	
	if(isset($metaD) && !empty($metaD)) { 
	   echo $metaD; 
	} 
	else { 
	   echo "Some meta description"; 
	} ?>" />
	
	<title><?php 
	if(isset($title) && !empty($title)) { 
	   echo $title; 
	} 
	else { 
	   echo "Default title tag"; 
	} ?> </title>

	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		/* Remove the navbar's default margin-bottom and rounded borders */ 
		.navbar {
		  margin-bottom: 0;
		  border-radius: 0;
		}
		
		/* Set height of the grid so .sidenav can be 100% (adjust as needed) */
		.row.content {height: 450px}
		
		/* Set gray background color and 100% height */
		.sidenav {
		  padding-top: 20px;
		  background-color: #f1f1f1;
		  height: 100%;
		}
	</style>	
</head>
<body>
	<nav class="navbar navbar-inverse">
	   <div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Meu Investimento</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
				<?php
					if(!isset($_SESSION['login_user'])){
						echo '<li class='.$active_index.'><a href="./index.php">Início</a></li>';
						echo '<li class='.$active_sobre.'><a href="./sobre.php">Sobre</a></li>';
						echo '<li class='.$active_contato.'><a href="./contato.php">Contato</a></li>';
					}
					else {
						//echo '<li class='.$active_carteira.'><a href="./carteira.php">Carteira</a></li>';
						echo '<li class="dropdown '.$active_carteira.'">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Carteira
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="./carteira.php">Visualizar Carteira</a></li>
							  <li><a href="./cadastr-carteira.php">Cadastro de Investimento na Carteira</a></li>
							  <li><a href="./cadastr-valorcarteira.php">Leitura de valores</a></li>
							  <li><a href="./acomp-valorcarteira.php">Acompanhamento de valores</a></li>
							  <li><a href="./cadastr-fim-invest.php">Finalização de investimento da Carteira</a></li>
							</ul>
						</li>';
						//echo '<li class='.$active_estat.'><a href="./estatisticas.php">Estatíticas</a></li>';
						echo '<li class="dropdown '.$active_estat.'">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Estatíticas
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="./estat-carteira.php">Estatísticas da Carteira</a></li>
							  <li><a href="./estat-cart-mes.php">Estatísticas Mensal da Carteira</a></li>
							  <li><a href="./estat-cart-ano.php">Estatísticas Anual da Carteira</a></li>
							  <li><a href="#">Melhores Investimentos</a></li>
							</ul>
						</li>';
						//echo '<li class='.$active_config.'><a href="./configuracoes.php">Configurações</a></li>';
						echo '<li class="dropdown '.$active_config.'">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Configurações
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="./cadastr-invest.php">Novo Investimento</a></li>
							  <li><a href="./cadastr-tipo.php">Novo Tipo de Investimento</a></li>
							  <li><a href="./cadastr-subtipo.php">Novo SubTipo de Investimento</a></li>
							  <li><a href="./cadastr-entidade.php">Nova Entidade Gestora</a></li>
							</ul>
						</li>';
						
					}
				?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				<?php
					if(!isset($_SESSION['login_user'])){
						echo '<li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
					}
					else {
						echo '<li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
					}
				?>
				</ul>
			</div>
	   </div>
	</nav>

