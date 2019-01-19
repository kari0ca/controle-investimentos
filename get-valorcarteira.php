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
	  
	//build query
  
	// Query de investimentos da carteira do usuário
	$query_carteira = 'select idcarteira, nome from investdb.carteira where iduser = '.$_SESSION['iduser'].' order by idcarteira';
	$qry_cart_result = mysqli_query($db,$query_carteira) or die(mysql_error());
	
	$idcarteiras=array();
	$nomecarteiras=array();
	$cont_carteira=0;
	while($row = mysqli_fetch_array($qry_cart_result,MYSQLI_ASSOC)){
		array_push($idcarteiras, $row[idcarteira]);
		array_push($nomecarteiras, $row[nome]);
		$cont_carteira+=1;
	}
	
	// Construção do cabeçalho da tabela 
	$display_string = '<table class="table table-hover">';
	$display_string .= '<thead>';
	$display_string .= '	<tr>';
	$display_string .= "		<th>Data</th>";
	for ($i = 0; $i < $cont_carteira; $i++) {
	$display_string .= '		<th>'. $nomecarteiras[$i] .'</th>';
	}
	$display_string .= '	</tr>';
	$display_string .= '</thead>';
	
	//Query datas
	$query_data = 'select data_fato from investdb.inv_fato where idcarteira in (select idcarteira from investdb.carteira where iduser = '.$_SESSION['iduser'].') group by data_fato order by data_fato desc limit 5';
	echo '<br>Query DATA '.$query_data;
	$qry_data_result = mysqli_query($db,$query_data) or die(mysql_error());
	$datas=array();
	$cont_data=0;
	while($row = mysqli_fetch_array($qry_data_result,MYSQLI_ASSOC)){
		array_push($datas, $row[data_fato]);
		$cont_data+=1;
	}
	echo '<br>Data inicial = '.$datas[0].' e data final = '.$datas[$cont_data-1];
	
	//Query valores
	$query = 'select data_fato, idcarteira, val_invest from investdb.inv_fato where idcarteira in (select idcarteira from investdb.carteira where iduser = '.$_SESSION['iduser'].') and data_fato >= '.$datas[$cont_data-1].' and data_fato <= '.$datas[0].' group by data_fato, idcarteira order by data_fato desc;';
	echo '<br>Query de dados '.$query;

	/*
	
	//Query valores
	$query = 'select a.data_fato, a.idcarteira, val_invest from (select * from investdb.inv_fato where idcarteira in (select idcarteira from investdb.carteira where iduser = '.-1.') group by data_fato, idcarteira order by data_fato desc) as a';
	$query = "select c.idcarteira, c.idinvest, i.nome, e.identidade, e.entidade, c.data_ini, c.rent_val, c.val_ini, c.rent_perc, c.ativo, t.tipo, s.subtipo from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo";
	$query .= " and iduser=".$_SESSION['iduser'];
	if ($nome  != ''){
		$query .= " and i.nome='".$nome."'";
	}
	if ($entidade  != ''){
		echo " Entidade=".$entidade;
		$query .= " and e.identidade='".$entidade."'";
	}
	if ($tipo  != ''){
		echo " Tipo=".$tipo;
		$query .= " and t.tipo='".$tipo."'";
	}
	if ($subtipo  != ''){
		echo " SubTipo=".$subtipo;
		$query .= " and s.subtipo='".$subtipo."'";
	}
	  
	  
	//Execute query
	$qry_result = mysqli_query($db,$query) or die(mysql_error());
	
	//Build Result String
	$display_string = "<table class='table table-hover'>";
	$display_string .= "<thead>";
	$display_string .= "	<tr>";
	$display_string .= "		<th>Nome</th>";
	$display_string .= "		<th>Entidade</th>";
	$display_string .= "		<th>Tipo</th>";
	$display_string .= "		<th>SubTipo</th>";
	$display_string .= "		<th>Data Ini</th>";
	$display_string .= "		<th>Val Ini</th>";
	$display_string .= "		<th>Rent $</th>";
	$display_string .= "		<th>Rent %</th>";
	$display_string .= "		<th>Ativo</th>";
	$display_string .= "	</tr>";
	$display_string .= "</thead>";
	
	
	// Insert a new row in the table for each person returned
	while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
		//Manipulação da data para o formato DD/MM/YYYY
		$trat_data_ini = substr($row[data_ini], -2)."/".substr($row[data_ini], 4, 2)."/".substr($row[data_ini],0,4) ;
		//echo "<br>Data Orig = ".$row[data_ini].", Data Tratada=".$trat_data_ini;
		$display_string .= "<thead>";
		$display_string .= "	<tr>";
		$display_string .= "		<td>" . $row[nome] . "</td>";
		$display_string .= "		<td>" . $row[entidade] . "</td>";
		$display_string .= "		<td>" . $row[tipo] . "</td>";
		$display_string .= "		<td>" . $row[subtipo] . "</td>";
		$display_string .= "		<td>" . $trat_data_ini . "</td>";
		$display_string .= "		<td>" . $row[val_ini] . "</td>";
		$display_string .= "		<td>" . $row[rent_val] . "</td>";
		$display_string .= "		<td>" . $row[rent_perc] . "</td>";
		if ($row[ativo]==1){
			$display_string .= "		<td><input type='checkbox' value='' checked disabled></td>";
		}
		else {
			$display_string .= "		<td><input type='checkbox' value='' disabled></td>";
		}
		$display_string .= "	</tr>";
		$display_string .= "</thead>";

		//echo "<br> Id = " . $row[idinvest] . ",";
	}
	*/
	$display_string .= "</table>";
	echo $display_string;

?>