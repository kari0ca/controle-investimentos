<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}

	$error="";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$nomeinv = mysqli_real_escape_string($db,$_POST['nome']);
		$entidade = mysqli_real_escape_string($db,$_POST['entidade']);
		$tipoinv = mysqli_real_escape_string($db,$_POST['tipoinv']);
		$detalhe = mysqli_real_escape_string($db,$_POST['detalhe']);
		$dataini = mysqli_real_escape_string($db,$_POST['dataini']);
		$datafinal = mysqli_real_escape_string($db,$_POST['datafim']);
		$ativo = mysqli_real_escape_string($db,$_POST['ativo']);
		$ativo2 = mysqli_real_escape_string($db,$_POST['ativo2']);
		
		// Procura por investimento com o mesmo nome
		$sql = "SELECT nome FROM investdb.invest WHERE nome = '$nomeinv'";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$active = $row['active'];
		
		//Verifica se existe algum investimento com o mesmo nome
		$count = mysqli_num_rows($result);
		if($count >= 1) {
			$error="Já existe um Investimento com este nome";
		}
		else { //Se não existe um investimento com o mesmo nome, pode inserir no banco
			// Obtem o maior idsubtipo
			$sql = "SELECT max(idinvest) as idsubtipo FROM investdb.invest";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$idinvest = $row[idsubtipo];
			$idinvest = $idinvest + 1;
			
			//$sql_insert = "INSERT INTO investdb.invest values(".$idinvest.",'".$nomeinv."',".$entidade.",".$tipoinv.",'".$detalhe."','".$dataini."','".$datafinal."','".$ativo."')";
			$sql_insert = "INSERT INTO investdb.invest values(".$idinvest.",'".$nomeinv."',".$entidade.",".$tipoinv.",'".$detalhe."')";
			
			if (!mysqli_query($db, $sql_insert)) {
				echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
			}
		}
	}

?>
 
 <html lang="en">
<head>
	<title>Controle de investimentos</title>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
     
     /* Set black background color, white text and some padding */
     footer {
       background-color: #555;
       color: white;
       padding: 15px;
     }
     
     /* On small screens, set height to 'auto' for sidenav and grid */
     @media screen and (max-width: 767px) {
       .sidenav {
         height: auto;
         padding: 15px;
       }
       .row.content {height:auto;} 
     }
   </style>
</head>
<body>
 
	<!-- Barra de navegação -->
	<nav class="navbar navbar-inverse">
	   <div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="navbar-brand" href="#">Logo</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="./index.php">Início</a></li>
					<li><a href="./sobre.php">Sobre</a></li>
					<li><a href="./contato.php">Contato</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				</ul>
			</div>
	   </div>
	</nav>
     
 
	<!-- Conteúdo -->
	<div class="container">
		<div class="row justify-content-center">
			<form action = "" method = "post" name = "FormCadastroInvestimento">
				<p><h3>Cadastro de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-4 form-group">
						<input class="form-control" id="nome" name="nome" placeholder="Nome do investimento" type="text" required>
					</div>
					<div class="col-xs-4 form-group">
						<select class="form-control" name="entidade">
							<?php
								$query = "SELECT identidade, entidade FROM investdb.entidade;";
								
								//Execute query
								$qry_result = mysqli_query($db,$query) or die(mysql_error());
								
								//Build Result String
								$display_string = "<optgroup label='Entidade Gestora'>";
								
								// Insert a new row in the table for each person returned
								while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
									$display_string .= '<option value="'. $row[identidade] . '">'. $row[entidade] .'</option>';
								}
								echo $display_string;
							?>
						</select>
						<!-- <input class="form-control" id="entidade" name="entidade" placeholder="Entidade gestora" type="text" required>
						-->
					</div>
					<div class="col-xs-4 form-group">
						<select class="form-control" name="tipoinv">
							<?php
								$query = "select t.idtipoinvest, t.tipo, s.subtipo from investdb.tipo_invest t, investdb.sub_tipo_invest s where t.idsubtipo = s.idsubtipo;";
								
								//Execute query
								$qry_result = mysqli_query($db,$query) or die(mysql_error());
								
								//Build Result String
								$display_string = "<optgroup label='Tipo de Investimento'>";
								
								// Insert a new row in the table for each person returned
								while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
									$display_string .= '<option value="'. $row[idtipoinvest] . '">'. $row[tipo] .'-'.$row[subtipo] .'</option>';
								}
								echo $display_string;
							?>
						</select>
						<!-- <input class="form-control" id="tipoinv" name="tipoinv" placeholder="Tipo de investimento" type="text" required>
						-->
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<input class="form-control" id="detalhe" name="detalhe" placeholder="Detalhes do investimento" type="text" >
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class="btn-group pull-right" >
							<a href="cadastr-tipo.php" class="btn btn-info btn-sm">
								<span class="glyphicon glyphicon-plus-sign"></span> Novo Tipo Investimento
							</a>
							<a href="cadastr-entidade.php" class="btn btn-info btn-sm">
								<span class="glyphicon glyphicon-plus-sign"></span> Nova Entidade Gestora
							</a>
							<button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
							<button class="btn btn-default btn-sm" type="submit">Cadastrar</button>
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<?php
					if ($error!=""){
						echo '<div class="col-xs-12 form-group alert alert-danger">';
						echo $error;
						echo '</div>';
					}
				?>
			</div>
			<!-- Listagem de investimentos existentes -->
			<div class="row">
				<div class="col-xs-3" style="background-color:gray">Nome</div>
				<div class="col-xs-3" style="background-color:gray">Entidade</div>
				<div class="col-xs-3" style="background-color:gray">Tipo</div>
				<div class="col-xs-3" style="background-color:gray">SubTipo</div>
			</div>
			<?php
				include "get-invest.php";
			?>
			<br>
		</div>
	</div>
</body>
 
 
 
 <!-- Footer -->
 <footer class="container-fluid">
     <div class="container">
         <div class="media-container-row content text-white">
             <div class="col-12 col-xs-3">
                 <div class="media-wrap">
                     <a href="https://xxxxxxxxxx.com">
                         <img src="assets/images/logo24.png" alt="Mobirise">
                     </a>
                 </div>
             </div>
             <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                 <h5 class="pb-3">
                     Address
                 </h5>
                 <p class="mbr-text">
                     1234 Street Name
                     <br>City, AA 99999
                 </p>
             </div>
             <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                 <h5 class="pb-3">
                     Contacts
                 </h5>
                 <p class="mbr-text">
                     Email: support@mobirise.com
                     <br>Phone: +1 (0) 000 0000 001
                     <br>Fax: +1 (0) 000 0000 002
                 </p>
             </div>
             <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                 <h5 class="pb-3">
                     Links
                 </h5>
                 <p class="mbr-text">
                     <a class="text-primary" href="https://xxxxxxxxxx.com">Website builder</a>
                     <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-win.zip">Download for Windows</a>
                     <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-mac.zip">Download for Mac</a>
                 </p>
             </div>
         </div>
   
   <p>© Copyright 2018 Blablabla - All Rights Reserved</p>
 </footer>
 
 </html>