<?php
	include("config.php");   
	session_start();
  
	// Retrieve data from Query String
	$nome = $_GET['nome'];
	$entidade = $_GET['entidade'];
	$tipo = $_GET['tipo'];
	$subtipo = $_GET['subtipo'];
	
	// Escape User Input to help prevent SQL Injection
	$nome = mysqli_real_escape_string($db,$nome);   
	$tipo = mysqli_real_escape_string($db,$tipo);   
	$subtipo = mysqli_real_escape_string($db,$subtipo);   
  
	// Query de investimentos da carteira do usuário
	$query_carteira = 'select idcarteira, nome from investdb.carteira where iduser = '.$_SESSION['iduser'].' and ativo=1 order by idcarteira';
	$qry_cart_result = mysqli_query($db,$query_carteira) or die(mysql_error());
	
	$idcarteiras=array();
	$nomecarteiras=array();
	while($row = mysqli_fetch_array($qry_cart_result,MYSQLI_ASSOC)){
		array_push($idcarteiras, $row[idcarteira]);
		array_push($nomecarteiras, $row[nome]);
	}
	$cont_carteira = count($idcarteiras);
	
	//Query datas
	$query_data = 'select data_fato from investdb.inv_fato where idcarteira in (select idcarteira from investdb.carteira where iduser = '.$_SESSION['iduser'].' and ativo=1) group by data_fato order by data_fato desc limit 5';
	// echo '<br>Query DATA '.$query_data . '<br>carteiras = '. $cont_carteira;
	$qry_data_result = mysqli_query($db,$query_data) or die(mysql_error());
	$datas=array();
	while($row = mysqli_fetch_array($qry_data_result,MYSQLI_ASSOC)){
		array_push($datas, $row[data_fato]);
	}
	$cont_data = count($datas);
	// echo '<br>Data inicial = '.$datas[0].' e data final = '.$datas[$cont_data-1].' quantidade de datas='.count($datas);
	
	
	//Query valores
	$query_val = 'select data_fato, idcarteira, val_invest from investdb.inv_fato where idcarteira in (select idcarteira from investdb.carteira where iduser = '.$_SESSION['iduser'].' and ativo=1) and data_fato >= '.$datas[$cont_data-1].' and data_fato <= '.$datas[0].' group by data_fato, idcarteira order by data_fato desc;';
	// echo '<br>Query de dados '.$query;
	
	// echo '<br> arrays: datas='; print_r($datas);
	// echo ' <br> idcarteiras='; print_r($idcarteiras);
	$qry_val_result = mysqli_query($db,$query_val) or die(mysql_error());
	
	$cont_val=0;
	$it = $linha = $col = 1;
	$val_data = $old_val_data = $val_id_cart = $old_val_id_cart = $val_invest = $old_val_invest = 0;
	// echo 'It='.$it.' linha='.$linha;
	$fim = 0;
	$le_val = 1;
	while($fim == 0){
		if ($le_val == 1){
			$row = mysqli_fetch_array($qry_val_result,MYSQLI_ASSOC);	
		}
		
		
		//array_push($valores, $row[data_fato]);
		$val_data = $row[data_fato];
		$val_id_cart = $row[idcarteira];
		$val_invest = $row[val_invest];
		//echo '<br>val_data='.$val_data.' id_carteira='.$val_id_cart.' val_invest='.$val_invest;
		
		// Construção do cabeçalho da tabela 
		if ($linha==1 && $col==1){
			$display_string = '<table class="table table-hover">';
			$display_string .= '<thead>';
			$display_string .= '	<tr>';
			$display_string .= "		<th>Data</th>";
			for ($i = 0; $i < $cont_carteira; $i++) {
			$display_string .= '		<th>'. $nomecarteiras[$i] .'</th>';
			}
			$display_string .= '	</tr>';
			$display_string .= '</thead>';
		}
		if ($col==1){
			$display_string .= '	<tr>';
			$display_string .= '	<td>'.$val_data.'</td>';
		}
		
		// Tratamento dos valores
		if ($val_id_cart==$idcarteiras[$col-1]){
			$display_string .= '	<td>'.$val_invest.'</td>';
			$le_val=1;
		} else {
			$display_string .= '	<td> -- </td>';
			$le_val=0;
		}
		
		// Verificação da última coluna
		if ($col==$cont_carteira){
			$display_string .= '	</tr>';
		}

		// echo '<br> Final do ciclo, it='.$it.', linha='.$linha.', coluna='.$col;

		if ($linha>=$cont_data && $col>=$cont_carteira){
			$fim =1;
		}

		// Final do ciclo
		$it+=1;
		$col+=1;
		
		if ($col > $cont_carteira){
			$col=1;
			$linha+=1;	
		}
	}

	$display_string .= "</table>";
	echo $display_string;

?>