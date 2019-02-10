<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
  
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
	
	//$idsubtipo = $_POST["subtipo"];
	$error="";
	$page = "Estatísticas da Carteira";
	$title = "[MI] - Estatísticas da Carteira";
	$metaD = "Estatísticas da sua carteira";
	include 'header.php';
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
				   <h4 class="modal-title">Ajuda - Estatísticas da Carteira de Investimentos</h4>
				 </div>
				 <div class="modal-body">
				   <p>Nesta página temos a estatística da sua carteira, onde é possivel filtrar a carteira.
				   <br>Existem filtros para:
				   <br> - Nome do Investimento
				   <br> - Entigade Gestora
				   <br> - Tipo de investimento
				   <br> - Subtipo de investimento
				   <br><br> Sobre as informações mostradas, temos:
				   <br> - Rendimento total do investimento, em valor monetário
				   <br> - Rendimento total do investimento, em percentual comparado com o investimento inicial, ex: se o percentual é de 112%, significa que hoje tenho 112% do meu investimento inicial, houve um ganho de 12%, por outro lado, se o percentual for de 87%, significa que houve uma perda de 13%
				   <br><br> Os botões disponíveis são:
				   <br> - Novo Investimento -> Para cadastrar um novo investimento, este botão deve ser usado quando o investimento a ser adicionado à carteira não existe, caso outro usuário tenha este investimento na carteira, basta adicionar à carteira, no botão: Gerenciar Carteira
				   <br> - Gerenciar Carteira -> Deve ser usado para adicionar um investimento existente à carteira
				   <br> - Atualizar Valores -> Esta é a pagina mais usada, deve ser usada para informar o valor do investimento, para que seja calculada a rentabilidade e estatística.</p>
				 </div>
				 <div class="modal-footer">
				   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				 </div>
			    </div>
			
			  </div>
			</div>
			<!-- Formulário -->
               <form name = "FormCarteira" >
                    <p><h3>Estatísticas da Carteira de Investimentos</h3></p>
                    <div class="row">
                         <div class="col-xs-2 form-group"><h4>Filtros:</h4>
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
                         <div class="col-xs-3 form-group">
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
                         <div class="col-xs-3 form-group">
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
                    </div>
                    <div class="row">
                         <div class="col-xs-12 form-group">
                              <div class="btn-group pull-right" >
                                   <a href="cadastr-invest.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Novo Investimento
                                   </a>
                                   <a href="cadastr-carteira.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Gerenciar Carteira
                                   </a>
                                   <a href="cadastr-valorcarteira.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Atualizar Valores
                                   </a>
                                   <button class="btn btn-default btn-sm" type="submit">Filtrar</button>
                              </div>
                         </div>
                    </div>
               </form>
               <br><br>
               <div id = 'ajaxDiv'>
                 <?php
                     include "get-estat-carteira.php";
                 ?>
               </div>
          </div> 
     </div>
</body>

<!-- Footer -->
<?php
include 'footer.php';
?>