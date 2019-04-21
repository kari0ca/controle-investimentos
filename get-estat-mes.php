<?php
	include("config.php");   
	session_start();
  
	// Retrieve data from Query String
	$nome = $_GET['nome'];
	$entidade = $_GET['entidade'];
	$tipo = $_GET['tipo'];
	$subtipo = $_GET['subtipo'];
	$ativo = $_GET['estado'];
	
	// Escape User Input to help prevent SQL Injection
	$nome = mysqli_real_escape_string($db,$nome);   
	$tipo = mysqli_real_escape_string($db,$tipo);   
	$subtipo = mysqli_real_escape_string($db,$subtipo);   
	
	
	// Query de investimentos da carteira do usuário
	//$query_carteira = 'select idcarteira, nome from investdb.carteira where iduser = '.$_SESSION['iduser'];
	$query_carteira = 'select c.idcarteira, c.idinvest, i.nome, e.identidade, e.entidade, c.data_ini, c.rent_val, c.val_ini, c.rent_perc, c.ativo, t.tipo, s.subtipo from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo';
	$query_carteira .= " and iduser=".$_SESSION['iduser'];
	if ($nome  != ''){
	   $query_carteira .= " and i.nome='".$nome."'";
	}
	if ($entidade  != ''){
	   $query_carteira .= " and e.identidade='".$entidade."'";
	}
	if ($tipo  != ''){
	   $query_carteira .= " and t.tipo='".$tipo."'";
	}
	if ($subtipo  != ''){
	   $query_carteira .= " and s.subtipo='".$subtipo."'";
	}
	if ($ativo  != ''){
	   $query_carteira .= ' and ativo="'.$ativo.'"';
	}
	$query_carteira .= ' order by idcarteira';
	//echo 'query_carteira= '.$query_carteira;
	$qry_cart_result = mysqli_query($db,$query_carteira) or die(mysql_error());
	
	$idcarteiras=array();
	$nomecarteiras=array();
	while($row = mysqli_fetch_array($qry_cart_result,MYSQLI_ASSOC)){
		array_push($idcarteiras, $row[idcarteira]);
		array_push($nomecarteiras, $row[nome]);
	}
	$cont_carteira = count($idcarteiras);
	
	//Query datas
	$query_data = 'select mes_ref from investdb.inv_agg_mes where idcarteira in (';
	$query_data .= 'select c.idcarteira from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo';
	if ($nome  != ''){
	   $query_data .= " and i.nome='".$nome."'";
	}
	if ($entidade  != ''){
	   $query_data .= " and e.identidade='".$entidade."'";
	}
	if ($tipo  != ''){
	   $query_data .= " and t.tipo='".$tipo."'";
	}
	if ($subtipo  != ''){
	   $query_data .= " and s.subtipo='".$subtipo."'";
	}
	if ($ativo  != ''){
	   $query_data .= ' and ativo="'.$ativo.'"';
	}
	$query_data .= " and iduser=".$_SESSION['iduser'];
	$query_data .= ')';
	$query_data .= ' group by mes_ref order by mes_ref desc limit 12';	
	$qry_data_result = mysqli_query($db,$query_data) or die(mysql_error());
	$datas=array();
	while($row = mysqli_fetch_array($qry_data_result,MYSQLI_ASSOC)){
		array_push($datas, $row[mes_ref]);
	}
	$cont_data = count($datas);
	// echo '<br>Data inicial = '.$datas[0].' e data final = '.$datas[$cont_data-1].' quantidade de datas='.count($datas);
	
	
	//Query valores
	$query_val = 'select mes_ref, idcarteira, rend_mes_perc from investdb.inv_agg_mes where idcarteira in (';
	$query_val .= 'select c.idcarteira from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo';
	if ($nome  != ''){
	   $query_val .= " and i.nome='".$nome."'";
	}
	if ($entidade  != ''){
	   $query_val .= " and e.identidade='".$entidade."'";
	}
	if ($tipo  != ''){
	   $query_val .= " and t.tipo='".$tipo."'";
	}
	if ($subtipo  != ''){
	   $query_val .= " and s.subtipo='".$subtipo."'";
	}
	if ($ativo  != ''){
	   $query_val .= ' and ativo="'.$ativo.'"';
	}
	$query_val .= " and iduser=".$_SESSION['iduser'];
	$query_val .= ')';
	$query_val .= ' and mes_ref >= '.$datas[$cont_data-1].' and mes_ref <= '.$datas[0].' group by mes_ref, idcarteira order by mes_ref desc;';
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
		$val_data = $row[mes_ref];
		$val_id_cart = $row[idcarteira];
		$val_invest = $row[rend_mes_perc];
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