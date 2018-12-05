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
		<!-- Ajuda -->
          <div class="row justify-content-center">
			<button type="button" class="btn btn-xs pull-right" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</button>
			
			<div id="myModal" class="modal fade" role="dialog">
			  <div class="modal-dialog">
			
			    <div class="modal-content">
				 <div class="modal-header">
				   <button type="button" class="close" data-dismiss="modal">&times;</button>
				   <h4 class="modal-title">Ajuda - Cadastro de Investimento</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página temos o cadastro de investimentos, onde é possivel montar um novo Investimento para ser adicionado à carteira, o investimento é composto pelo tipo e subtipo de investimento e entidade gestora.
				   <br>Dados necessários para o cadastro:
				   <br> - Nome do Investimento*
				   <br> - Entigade Gestora* -> ex: Banco Itaú, XP Investimentos.
				   <br> - Tipo de investimento* -> ex: CDB, LCA, Fundo de Investimento, Título do tesouro
				   <br> - Detalhes do investimento -> Descrição sobre o investimento, esta informação é para seu uso apenas
				   <br> * Informação Obrigatória.
				   <br><br> Os botões disponíveis são:
				   <br> - Novo Tipo de Investimento -> Este botão deve ser usado quando não existe o tipo de investimento, do investimento que está sendo criado.
				   <br> - Nova Entidade Gestora -> Este botão deve ser usado quando não existe a entidade gestora do investimento, imaginemos que o investimento que está sendo criado é o fundo: Alaska Black Fic Fia (que é operado por várias corretoras), mas não temos a corretora cadastrada no sistema, este botão permite-nos cadastrar uma corretora para este investimento.</p>
				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
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