<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
	
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}

	$page = "acomp-valorcarteira";
	$title = "[MI] - Acompanhamento de valores da Carteira";
	$metaD = "Acompanhamento de valores da Carteira";
	include 'header.php';

	$count = $_SESSION['count_inv'];
	$error="";
	if($_SERVER["REQUEST_METHOD"] == "GET") {
		$dataini = mysqli_real_escape_string($db,$_POST['dataini']);
		$datafim = mysqli_real_escape_string($db,$_POST['datafim']);
		//echo "<br>Dataini sem tratamento=".$dataini;
		$val=array();
		$idcarteira=array();
		for ($i = 0; $i <= $count; $i++) {
			array_push($val,mysqli_real_escape_string($db,$_POST['val'.$i]));
			array_push($idcarteira,mysqli_real_escape_string($db,$_POST['idcarteira'.$i]));
			//echo "<br>Inseriu o valor:".mysqli_real_escape_string($db,$_POST['val'.$i])." do IDcarteira:". mysqli_real_escape_string($db,$_POST['idcarteira'.$i]) .", na posição ".$i;
		}

		//Datas
		$arraydata = explode('/', $dataini);
		$day = $arraydata[0];
		$month = $arraydata[1];
		$year  = $arraydata[2];
		if(!checkdate ($month, $day , $year)){
			$error = "Data de inicio Inválida";
			echo '<br>Data de inicio Inválida';
		}
		else {
			$dataini=$year.$month.$day;
			echo "<br>Dataini tratada=".$dataini;
		}

		$arraydata = explode('/', $datafim);
		$day = $arraydata[0];
		$month = $arraydata[1];
		$year  = $arraydata[2];
		if(!checkdate ($month, $day , $year)){
			$error .= " Data final Inválida";
		}
		else {
			$datafim=$year.$month.$day;
			echo "<br>Datafim tratada=".$datafim;
		}
		if($dataini > $datafim){
			$error .= " Data final deve ser maior ou igual a data inicial";
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
			<!-- <form action = "" method = "post" name = "FormValorCarteira"> -->
			<form name = "FormValorCarteira" >
				<p><h3>Acompanhamento de valores da Carteira de Investimento</h3></p>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class='col-xs-3'><span class="pull-right">Data de inicio</span>
						</div>	
						<div class='col-xs-3'>
							<input class="form-control" id="dataini" name="dataini" placeholder="DD/MM/AAAA" type="text" required>
						</div>
						<div class='col-xs-3'><span class="pull-right">Data final</span>
						</div>	
						<div class='col-xs-3'>
							<input class="form-control" id="datafim" name="datafim" placeholder="DD/MM/AAAA" type="text" required>
						</div>
					</div>
				</div>
                    <div class="row">
                         <div class="col-xs-3 form-group">
						<label for="nome">Nome:</label>
                              <select class="form-control" name="nome">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct(nome) from investdb.carteira where iduser=".$_SESSION['iduser'];
                                        
                                        //Execute query
                                        $qry_result = mysqli_query($db,$query) or die(mysql_error());
                                        $display_string = "";
                                        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
                                             $display_string .= '<option value="'. $row[nome] . '">'. $row[nome] .'</option>';
                                        }
                                        echo $display_string;
                                   ?>
                              </select>
                         </div>
                         <div class="col-xs-3 form-group">
						<label for="entidade">Entidade:</label>
                              <select class="form-control" name="entidade">
                                   <option value=""></option>
                                   <?php
                                        $query = "select e.identidade, e.entidade from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest = i.idinvest and i.identidade = e.identidade and c.iduser=".$_SESSION['iduser'];
                                        $query .= " group by e.identidade, e.entidade";
                                        
                                        //Execute query
                                        $qry_result = mysqli_query($db,$query) or die(mysql_error());
                                        $display_string = "";
                                        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
                                             $display_string .= '<option value="'. $row[identidade] . '">'. $row[entidade] .'</option>';
                                        }
                                        echo $display_string;
                                   ?>
                              </select>
                         </div>  
                         <div class="col-xs-2 form-group">
						<label for="tipo">Tipo:</label>
                              <select class="form-control" name="tipo">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct t.tipo from investdb.carteira c, investdb.invest i, investdb.tipo_invest t where c.idinvest = i.idinvest and i.idtipo = t.idtipoinvest and c.iduser=".$_SESSION['iduser'];
                                        
                                        //Execute query
                                        $qry_result = mysqli_query($db,$query) or die(mysql_error());
                                        
                                        //Build Result String
                                        $display_string = "";
                                        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
                                             $display_string .= '<option value="'. $row[tipo] . '">'. $row[tipo] .'</option>';
                                        }
                                        echo $display_string;
                                   ?>
                              </select>
                         </div>
                         <div class="col-xs-2 form-group">
						<label for="subtipo">SubTipo:</label>
                              <select class="form-control" name="subtipo">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct s.subtipo from investdb.carteira c, investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo and c.iduser=".$_SESSION['iduser'];
                                        
                                        //Execute query
                                        $qry_result = mysqli_query($db,$query) or die(mysql_error());
                                        //Build Result String
                                        $display_string = "";
                                        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
                                             $display_string .= '<option value="'. $row[subtipo] . '">'. $row[subtipo] .'</option>';
                                        }
                                        echo $display_string;
                                   ?>
                              </select>
                         </div>
					<div class="col-xs-2 form-group">
						<label for="subtipo">Estado:</label>
                              <select class="form-control" name="estado">
                                   <option value=""></option>
							<option selected value="1">Ativo</option>
							<option value="0">Inativo</option>
                              </select>
                         </div>
                    </div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<div class="btn-group pull-right" >
							<!-- <button class="btn btn-danger btn-sm" type="reset">Cancelar</button> -->
							<button class="btn btn-default btn-sm" type="submit">Mostrar Dados</button>
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
			<p><h4>Valores registrados da Carteira de Investimento</h4></p>
			<!-- Listagem das ultimas leituras -->
			<?php
				include "get-acompvalorcarteira.php";
			?>   
		</div>
	</div>
</body>
 
<!-- Footer -->
<?php
include 'footer.php';
?>