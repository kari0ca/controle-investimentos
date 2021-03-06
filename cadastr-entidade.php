<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
  
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
   
	$page = "Cadastr-Entidade";
	$title = "[MI] - Cadastro de Entidade";
	$metaD = "Cadastre Entidade Gestora";
	include 'header.php';

	$error="";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$entidade = mysqli_real_escape_string($db,$_POST['nome']);
		// Procura por Entidade com o mesmo nome
		$sql = "SELECT entidade FROM investdb.entidade WHERE entidade = '$entidade'";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$active = $row['active'];
		
		//Verifica se existe algum investimento com o mesmo nome
		$count = mysqli_num_rows($result);
		if($count >= 1) {
			$error="Já existe uma Entidade Gestora com este nome";
		}
		else { //Se não existe um investimento com o mesmo nome, pode inserir no banco
			// Obtem o maior idsubtipo
			$sql = "SELECT max(identidade) as identidade FROM investdb.entidade";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$ident = $row[identidade];
			$ident = $ident + 1;
			
			$sql_insert = "INSERT INTO investdb.entidade values(".$ident.",'".$entidade."')";
			
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
				   <h4 class="modal-title">Ajuda - Cadastro de Entidade Gestora de Investimento</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página temos o cadastro de entidade gestora de investimentos, A entidade nada mais é do que a corretora do investimento.
				   <br>Dados necessários para o cadastro:
				   <br> - Entidade Gestora* -> Nome da entidade gestora, ex: Banco Itaú, XP Investimentos, Órama, etc.
				   <br> * Informação Obrigatória.</p>
				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
			<form action = "" method = "post" name = "FormCadastroEntidade">
				<p><h3>Cadastro de Entidade Gestora de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-8 form-group">
						<input class="form-control" id="nome" name="nome" placeholder="Entidade Gestora" type="text" required>
					</div>
					<div class="col-xs-4 form-group">
						<div class="btn-group pull-right" >
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
			<!-- Listagem de Entidade gestoras existentes -->
			<div class="row">
				<div class="col-xs-12" style="background-color:gray">Entidade Gestora</div>
			</div>
			<?php
				include "get-entidade.php";
			?>   
		</div>
	</div>
	<br>
</body>

 
<!-- Footer -->
<?php
include 'footer.php';
?>