<!DOCTYPE html>
<?php
	include("config.php");
	session_start();

	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
	
	$page = "Cadastr-Carteira";
	$title = "[MI] - Cadastro de Carteira";
	$metaD = "Configuração da sua carteira";
	include 'header.php';	
	$error="";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$mysubtipo = mysqli_real_escape_string($db,$_POST['subtipo']);
		
		// Procura por subtipos de investimento com o mesmo nome
		$sql = "SELECT subtipo FROM investdb.sub_tipo_invest WHERE subtipo = '$mysubtipo'";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$active = $row['active'];
		
		$count = mysqli_num_rows($result);
		if($count >= 1) {
		  $error="Já existe um Sub Tipo de Investimento com este nome";
		}
		else {
			
			// Obtem o maior idsubtipo
			$sql = "SELECT max(idsubtipo) as idsubtipo FROM investdb.sub_tipo_invest";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$idsubtipo = $row[idsubtipo];
			$idsubtipo = $idsubtipo + 1;
			
			$sql_insert = "INSERT INTO investdb.sub_tipo_invest values(".$idsubtipo.",'".$mysubtipo."')";
			if (!mysqli_query($db, $sql_insert)) {
				echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
			} 
		}
	}
?>

	<!-- Conteúdo -->
  
	<div class="container">
		<div class="row justify-content-center"> 
			<form action = "" method = "post" name = "FormCadastroSubtipo">
				<p><h3>Cadastro de Subtipo de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-12 form-group">
						<input class="form-control" id="subtipo" name="subtipo" placeholder="Sub Tipo de Investimento" type="text" required>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class="btn-group pull-right">
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
			
			<!-- Listagem de subtipos -->
			<div class="row justify-content-center">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-12" style="background-color:gray">Subtipo(s) de investimento existente
						</div>
					</div>
						<?php
							include "get-subtipo.php";
						?>          
				</div>
			</div>
		</div>
	</div>
	<br>

</body>

<!-- Footer -->
<?php
include 'footer.php';
?>