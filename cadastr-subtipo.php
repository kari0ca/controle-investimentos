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
		<!-- Ajuda -->
          <div class="row justify-content-center">
			<button type="button" class="btn btn-xs pull-right" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</button>
			
			<div id="myModal" class="modal fade" role="dialog">
			  <div class="modal-dialog">
			
			    <div class="modal-content">
				 <div class="modal-header">
				   <button type="button" class="close" data-dismiss="modal">&times;</button>
				   <h4 class="modal-title">Ajuda - Cadastro de Subtipo de Investimento</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página temos o cadastro de subtipos de investimentos, o subtipo de investimento está associado, a um tipo. ex: Tipo CDB, pode ter os subtipos Prefixado‎, DI Pósfixado, o tipo Fundo de Investimento, pode ter os subtipos: Fundo de Ação, Fundo Cambial, etc.
				   <br>Dados necessários para o cadastro:
				   <br> - SubTipo de Investimento* -> Nome do SubTipo, ex: Prefixado‎, DI Pósfixado (no caso de um CDB). Ação, Cambial, Multimercado, no caso de um fundo de investimento.
				   <br> * Informação Obrigatória.</p>
				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
			<form action = "" method = "post" name = "FormCadastroSubtipo">
				<p><h3>Cadastro de Subtipo de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-12 form-group">
						<input class="form-control" id="subtipo" name="subtipo" placeholder="SubTipo de Investimento" type="text" required>
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