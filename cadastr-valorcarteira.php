<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}

	$page = "Cadastr-valorcarteira";
	$title = "[MI] - Leitura de valores da Carteira";
	$metaD = "Leitura de valores da Carteira";
	include 'header.php';

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
				if (!mysqli_query($db, $sql_insert)) {
					echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
				}
				// Depois que insere o valor, atualiza a carteira com o rendimento até o momento
				
				$sql_fato = 'select a.data_fato, i.val_invest from (select max(data_fato) as data_fato from investdb.inv_fato where idcarteira='.$idcarteira[$i].') as a, investdb.inv_fato i where idcarteira='.$idcarteira[$i].' and i.data_fato = a.data_fato';
				$qry_result_fato = mysqli_query($db,$sql_fato) or die(mysql_error());
				$row_fato = mysqli_fetch_array($qry_result_fato,MYSQLI_ASSOC);
				$max_data = $row_fato[data_fato];
				$val_max = $row_fato[val_invest];
				
				$sql_cart = 'select data_ini, val_ini from investdb.carteira where idcarteira='.$idcarteira[$i];
				$qry_result_cart = mysqli_query($db,$sql_cart) or die(mysql_error());
				$row_cart = mysqli_fetch_array($qry_result_cart,MYSQLI_ASSOC);
				$data_ini = $row_cart[data_ini];
				$val_ini = $row_cart[val_ini];
				
				
				//calculos de rentabilidade
				$rent_per = (($val_max*100)/$val_ini);
				$rent_val = $val_max - $val_ini;
				$sql_insert = 'update investdb.carteira set rent_val='.$rent_val.', rent_perc='.$rent_per.' where idcarteira='.$idcarteira[$i];
				if (!mysqli_query($db, $sql_insert)) {
					echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
				}
				header("location:carteira.php"); die('Não ignore meu cabeçalho...');
			}
			else {
				//echo "<br>Valor vazio no idcarteira ".$idcarteira[$i];
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
				   <h4 class="modal-title">Ajuda - Leitura de valores da Carteira de Investimento</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página temos a leitura de valores da Carteira de Investimento, aqui é possível inserir a leitura com os valores (recomendavel que seja liquido) dos investimentos
				   <br>Esta leitura pode ser feita para qualquer data, inclusive para leituras antigas ou anteriores a última leitura existente.
				   <br>A frequência de atualização fica a cargo do usuário, quão mais frequente, melhores serão as informações estatísticas sobre os investimentos.
				   <br>Dados necessários para a leitura de valores: 
				   <br> - Data de referência* -> Data da leitura dos valores
				   <br> - Valor* -> Valor (recomendavel liquido) do investimento
				   <br> É possível realizar a leitura para apenas alguns dos investimentos por vez, também é permitido atualizar valores, para tal, basta realizar a leitura novamente, ex: Foi realizada a leitura para o 1º investimento apenas, e em um segundo momento, é realizada a leitura dos valores dos demais investimentos para a mesma data de referência.</p>

				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
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
			<br><br>
			<p><h4>Últimos valores da Carteira de Investimento</h4></p>
			<!-- Listagem das ultimas leituras -->
			<?php
				include "get-valorcarteira.php";
			?>   
		</div>
	</div>
</body>
 
<!-- Footer -->
<?php
include 'footer.php';
?>