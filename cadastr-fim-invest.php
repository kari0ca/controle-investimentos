<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}

	$page = "Cadastr-fiminvest";
	$title = "[MI] - Finalização de investimento da Carteira";
	$metaD = "Finalização de investimento da Carteira";
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
				$sql_insert = 'update investdb.carteira set rent_val='.$rent_val.', rent_perc='.$rent_per.', ativo=0, data_fim='.$dataref.' where idcarteira='.$idcarteira[$i];
				if (!mysqli_query($db, $sql_insert)) {
					echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
				}
				
				// Obtem valor minimo do mês, para calcular agg_mes
				$sql_mes = 'select f.val_invest min_val from investdb.inv_fato f, (select min(data_fato) min_data, idcarteira from investdb.inv_fato where data_fato>'.$year.$month.'00 and data_fato<'.$year.$month.'32 group by idcarteira) fmin where f.idcarteira=fmin.idcarteira and f.data_fato=fmin.min_data  and f.idcarteira='.$idcarteira[$i];
				$qry_result_mes = mysqli_query($db,$sql_mes) or die(mysql_error());
				$row_mes = mysqli_fetch_array($qry_result_mes,MYSQLI_ASSOC);
				$val_ini_mes = $row_mes[min_val];

				$sql_mes = 'select f.val_invest max_val from investdb.inv_fato f, (select max(data_fato) max_data, idcarteira from investdb.inv_fato where data_fato>'.$year.$month.'00 and data_fato<'.$year.$month.'32 group by idcarteira) fmax where f.idcarteira=fmax.idcarteira and f.data_fato=fmax.max_data  and f.idcarteira='.$idcarteira[$i];
				$qry_result_mes = mysqli_query($db,$sql_mes) or die(mysql_error());
				$row_mes = mysqli_fetch_array($qry_result_mes,MYSQLI_ASSOC);
				$val_max_mes = $row_mes[max_val];
				//echo '<br>Carteira='.$idcarteira[$i].', valor inicio do mês='.$val_ini_mes.', ultimo valor do mês='.$val_max_mes;
				
				$val_agg_mes=round((($val_max_mes*100)/$val_ini_mes),2);
				
				$sql_mes = 'INSERT INTO investdb.inv_agg_mes VALUES ('.$idcarteira[$i].','.$year.$month.','.$val_agg_mes.') ON DUPLICATE KEY UPDATE rend_mes_perc='.$val_agg_mes;
				//echo '<br>Insert AGG= '.$sql_mes.'<br>Calculo val_agg_mes= (($val_max_mes*100)/$val_min_mes) -->(('.$val_max_mes.'*100)/'.$val_min_mes.')= '.$val_agg_mes;
				if (!mysqli_query($db, $sql_mes)) {
					echo "Error: " . $sql_mes . "<br>" . mysqli_error($db);
				}

				// Obtem valor minimo do ano, para calcular agg_ano
				$sql_ano = 'select f.val_invest min_val from investdb.inv_fato f, (select min(data_fato) min_data, idcarteira from investdb.inv_fato where data_fato>'.$year.'0000 and data_fato<'.$year.'1232 group by idcarteira) fmin where f.idcarteira=fmin.idcarteira and f.data_fato=fmin.min_data  and f.idcarteira='.$idcarteira[$i];
				$qry_result_ano = mysqli_query($db,$sql_ano) or die(mysql_error());
				$row_ano = mysqli_fetch_array($qry_result_ano,MYSQLI_ASSOC);
				$val_ini_ano = $row_ano[min_val];

				$sql_ano = 'select f.val_invest max_val from investdb.inv_fato f, (select max(data_fato) max_data, idcarteira from investdb.inv_fato where data_fato>'.$year.'0000 and data_fato<'.$year.'1232 group by idcarteira) fmax where f.idcarteira=fmax.idcarteira and f.data_fato=fmax.max_data  and f.idcarteira='.$idcarteira[$i];
				$qry_result_ano = mysqli_query($db,$sql_ano) or die(mysql_error());
				$row_ano = mysqli_fetch_array($qry_result_ano,MYSQLI_ASSOC);
				$val_max_ano = $row_ano[max_val];
				
				$val_agg_ano=round((($val_max_ano*100)/$val_ini_ano),2);
				
				$sql_ano = 'INSERT INTO investdb.inv_agg_ano VALUES ('.$idcarteira[$i].','.$year.','.$val_agg_ano.') ON DUPLICATE KEY UPDATE rend_ano_perc='.$val_agg_ano;
				if (!mysqli_query($db, $sql_ano)) {
					echo "Error: " . $sql_ano . "<br>" . mysqli_error($db);
				}


			}
			else {
				//echo "<br>Valor vazio no idcarteira ".$idcarteira[$i];
			}

		}
		
		header("location:carteira.php"); die('Não ignore meu cabeçalho...');
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
				   <h4 class="modal-title">Ajuda - Finalização de investimento da Carteira</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página podemos realizar a o encerramento de um investimento, onde é possível inserir a última leitura com os valores (recomendavel que seja liquido) dos investimentos
				   <br>A data usada, deve ser a data em que o investimento foi liquidado, caso pretenda informar a finalização de vários investimentos com datas diferentes, deverá executar esta ação uma vez para cada data.
				   <br>
				   <br>Dados necessários para a leitura de valores: 
				   <br> - Data de fim do investimento* -> Data em que encerrou o investimento
				   <br> - Valor* -> Valor (recomendavel liquido) do investimento</p>

				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
			<form action = "" method = "post" name = "FormValorCarteira">
				<p><h3>Finalização de investimento da Carteira de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class='col-xs-9'><span class="pull-right">Data de fim do investimento</span>
						</div>	
						<div class='col-xs-3'>
							<input class="form-control" id="dataref" name="dataref" placeholder="DD/MM/AAAA" type="text" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<?php
							$query = "select c.idcarteira, c.nome, e.entidade, c.data_ini from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest=i.idinvest and i.identidade=e.identidade and c.ativo=1 ";
							$query .= " and iduser=".$_SESSION['iduser'];
								
							//Execute query
							$qry_result = mysqli_query($db,$query) or die(mysql_error());
							
							//guarda quantidade de linhas
							$count = mysqli_num_rows($qry_result);
							$_SESSION["count_inv"]= $count;
							
							//Build Result String
							$i=1;
							$display_string  = "<div class='row'>";
							$display_string .= "	<div class='col-xs-4'><b>Nome</b></div>";
							$display_string .= "	<div class='col-xs-3'><b>Entidade</b></div>";
							$display_string .= "	<div class='col-xs-2'><b>Data Inicial</b></div>";
							$display_string .= "	<div class='col-xs-3'><b>Valor final do Investimento</b></div>";
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
<?php
include 'footer.php';
?>