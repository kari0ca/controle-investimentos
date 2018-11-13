<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
	
	$page = "Cadastr-Tipo";
	$title = "[MI] - Cadastro de Tipo de Investimento";
	$metaD = "Cadastro de Tipo de Investimento";
	include 'header.php';
	
	$idsubtipo = $_POST["subtipo"];
	$error="";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$mytipo = mysqli_real_escape_string($db,$_POST['tipo']);
		// Procura por outros tipos de investimento com o mesmo nome
		$sql = "SELECT tipo FROM investdb.tipo_invest WHERE tipo = '$mytipo'";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$active = $row['active'];
		
		$count = mysqli_num_rows($result);
		if($count >= 1) {
			$error="Já existe um Tipo de Investimento com este nome";
		}
		else {
			
			// Obtem o maior id_tipo
			$sql = "SELECT max(idtipoinvest) as idtipo FROM investdb.tipo_invest";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$idtipo = $row[idtipo];
			$idtipo = $idtipo + 1;
			
			
			//echo "<br>Final das validações";
			//echo "<br>dados de validação: Idtipo:".$idtipo." Tipo:".$mytipo." SubTipo:".$idsubtipo;
			//INSERT INTO `investdb`.`user` (`iduser`, `nome`, `login`, `pass`, `aux_senha`, `email`) VALUES ('', 'afdasfd ', 'asdas sa', '123', '123', 'wg rwg wrg');
			$sql_insert = "INSERT INTO investdb.tipo_invest values(".$idtipo.",'".$mytipo."',".$idsubtipo.")";
			//echo "<br>SQL=".$sql_insert;
	    
			if (!mysqli_query($db, $sql_insert)) {
				echo "<br><br>Error: " . $sql_insert . "<br>" . mysqli_error($db);
			}
		}
	}
?>

	<!-- Conteúdo -->
	<div class="container">
		<div class="row justify-content-center"> 
			<form action = "" method = "post" name = "FormCadastroTipo">
				<p><h3>Cadastro de Tipo de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-5 form-group">
						<input class="form-control" id="tipo" name="tipo" placeholder="Tipo de Investimento" type="text" required>
					</div>
					<div class="col-xs-3 form-group text-align:center"> <p><b>Sub-Tipo de Investimento:</b></p>
					</div>
					<div class="col-xs-4 form-group">
						<select class="form-control" name="subtipo">
							<?php
							  $query = "SELECT idsubtipo, subtipo FROM investdb.sub_tipo_invest";
							  
							  //Execute query
							  $qry_result = mysqli_query($db,$query) or die(mysql_error());
							  
							  //Build Result String
							  $display_string = "";
							  
							  // Insert a new row in the table for each person returned
							  while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
								$display_string .= '<option value="'. $row[idsubtipo] . '">'. $row[subtipo] .'</option>';
							  }
							  echo $display_string;
							?>
						</select>
					</div>      
				</div>				
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class="btn-group pull-right">
							<a href="cadastr-subtipo.php" class="btn btn-info btn-sm">
							  <span class="glyphicon glyphicon-plus-sign"></span> Novo SubTipo de investimento
							</a>
							<button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
							<button class="btn btn-default btn-sm" type="submit">Cadastrar</button>
						</div>  
					</div>
				</div>
			</form>
			
			<!-- Mensagem de erro -->
			<div class="row">
				<?php
					if ($error!=""){
						echo '<div class="col-xs-10 form-group alert alert-danger">';
						echo $error;
						echo '</div>';
					}
				?>
			</div>
			
			<!-- Listagem de tipos existentes -->
			<div class="row">
				<div class="col-xs-6" style="background-color:gray">Tipo</div>
				<div class="col-xs-6" style="background-color:gray">SubTipo</div>
			</div>
			<?php
				include "get-tipo.php";
			?>
		</div>
	</div>
	<br>
</body>
 
<!-- Footer -->
<?php
include 'footer.php';
?>