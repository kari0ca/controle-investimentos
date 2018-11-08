<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
	
	$count = $_SESSION['count_inv'];
	$error="";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$dataref = mysqli_real_escape_string($db,$_POST['dataref']);
		//echo "<br>Dataref sem tratamento=".$dataref;
		$val=array();
		$idcarteira=array();
		for ($i = 0; $i <= $count; $i++) {
			array_push($val,mysqli_real_escape_string($db,$_POST['val'.$i]));
			array_push($idcarteira,mysqli_real_escape_string($db,$_POST['idcarteira'.$i]));
			//echo "<br>Inseriu o valor:".mysqli_real_escape_string($db,$_POST['val'.$i])." do IDcarteira:". mysqli_real_escape_string($db,$_POST['idcarteira'.$i]) .", na posição ".$i;
		}
		
		//echo "<br>Valores: Val1=".$val1." Val2=".$val2." Val3=".$val3;
		
		//echo "<br> valor formatado=".number_format($val[2],2,",",".");
		//tratamento das variaveis
      
		//Datas
		
		$arraydata = explode('/', $dataref);
		$day = $arraydata[0];
		$month = $arraydata[1];
		$year  = $arraydata[2];
		if(!checkdate ($month, $day , $year)){
			$error ="Data de Referência Inválida";
		}
		else {
			$dataref=$year.$month.$day;
			//echo "<br>Dataref tratada=".$dataref;
		}
		
		// Procura pela chave da carteira (idinvest, iduser, dataini)
		for ($i = 0; $i <= $count; $i++) {

			//$sql = "SELECT idcarteira, data_fato FROM investdb.inv_fato WHERE idcarteira= ".$idcarteira[$i]." and data_fato= ".$dataref;
			if (!empty($val[$i])){
				$sql_insert = "INSERT INTO investdb.inv_fato VALUES (".$idcarteira[$i].",".$dataref.",'".$val[$i]."') ON DUPLICATE KEY UPDATE val_invest='".$val[$i]."'";
				//echo "<br>SQL = ".$sql;
				if (!mysqli_query($db, $sql_insert)) {
					echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
				}				
			}
			else {
				//echo "<br>Valor vazio no idcarteira ".$idcarteira[$i];
				
			}
		}
		
		/*
		$sql = "SELECT idcarteira FROM investdb.carteira WHERE idinvest= $idinv and iduser= $user and data_ini= $dataini";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$active = $row['active'];
		
		//Verifica se existe algum investimento com o mesmo nome
		$count = mysqli_num_rows($result);
		if($count >= 1) {
			$error="Já existe um Investimento deste tipo iniciado na mesma data na carteira do usuário";
		}
		else { //Se não existe nenhuma chave reetida, insere o investimento na carteira
			// Obtem o maior idsubtipo
			$sql = "SELECT max(idcarteira) as idcarteira FROM investdb.carteira";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$idcart = $row[idcarteira];
			$idcart = $idcart + 1;
			
			//obtem o nome do investimento
			$sql = "SELECT nome FROM investdb.invest where idinvest =".$idinv;
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$nome = $row[nome];
			
			// campos: idcarteira, idinvest, iduser,data_ini, nome, notas, data_fim, ativo, val_ini
			$sql_insert = "INSERT INTO investdb.carteira (idcarteira, idinvest, iduser, data_ini, nome, notas, data_fim, ativo, val_ini) values(".$idcart.",".$idinv.",".$user.",".$dataini.",'".$nome."','".$notas."',".$datafinal.",".$ativo.",".$val.")";
			
			if (!mysqli_query($db, $sql_insert)) {
				echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
			}
			
		}
		*/
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
			<form action = "" method = "post" name = "FormValorCarteira">
				<p><h3>Leitura de valores da Carteira de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class='col-xs-9'><span class="pull-right">Data de Referência</span>
						</div>	
						<div class='col-xs-3'>
							<input class="form-control" id="dataref" name="dataref" placeholder="DD/MM/AAAA" type="text" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<?php
							$query = "select c.idcarteira, c.nome, e.entidade, c.data_ini from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest=i.idinvest and i.identidade=e.identidade  ";
							$query .= " and iduser=".$_SESSION['iduser'];
								
							//Execute query
							$qry_result = mysqli_query($db,$query) or die(mysql_error());
							
							//guarda quantidade de linhas
							$count = mysqli_num_rows($qry_result);
							$_SESSION["count_inv"]= $count;
							
							//Build Result String
							$i=1;
							$display_string  = "<div class='row'>";
							$display_string .= "	<div class='col-xs-4' style='background-color:gray'>Nome</div>";
							$display_string .= "	<div class='col-xs-3' style='background-color:gray'>Entidade</div>";
							$display_string .= "	<div class='col-xs-2' style='background-color:gray'>Data Inicial</div>";
							$display_string .= "	<div class='col-xs-3' style='background-color:gray'>Valor</div>";
							$display_string .= "</div>";
							
							while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
								$trat_data_ini = substr($row[data_ini], -2)."/".substr($row[data_ini], 4, 2)."/".substr($row[data_ini],0,4);
								$display_string .= "    <div class='row'>";
								$display_string .= "       <div class='col-xs-4'>". $row[nome] ."</div>";
								$display_string .= "       <div class='col-xs-3'>". $row[entidade] ."</div>";
								$display_string .= "       <div class='col-xs-2'>". $trat_data_ini ."</div>";
								$display_string .= "       <div class='col-xs-3'><input class='form-control' id='val".$i."' name='val".$i."' placeholder='Valor' type='text'></div>";
								$display_string .= "       <input type='hidden' id='idcarteira".$i."' name='idcarteira".$i."' value=". $row[idcarteira] .">";
								$display_string .= "    </div>";
								$i = $i+1;
							}							
							echo $display_string;
							
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class="btn-group pull-right" >
							<button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
							<button class="btn btn-default btn-sm" type="submit">Gravar Dados</button>
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