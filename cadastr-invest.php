<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}

	$page = "Cadastr-Invest";
	$title = "[MI] - Cadastro de Investimento";
	$metaD = "Cadastro de Investimento";
	include 'header.php';
	
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
<?php
include 'footer.php';
?>