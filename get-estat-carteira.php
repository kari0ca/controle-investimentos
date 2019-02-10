<?php
   include("config.php");   
   session_start();

   //echo "Entrou no get-carteira.php   ";
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
   //$query = "select i.idinvest, i.nome, t.tipo, s.subtipo from investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.idtipo=t.idtipoinvest and t.idsubtipo=s.idsubtipo";
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
   
   // Nome Entidade Tipo Subtipo DataIni ValIni RendMes RendMes% RendAno%
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
   $display_string .= "		<th>Data Ult Leitura</th>";
   $display_string .= "		<th>Val Ult Leitura</th>";
   $display_string .= "		<th>RentMês $</th>";
   $display_string .= "		<th>RentMês %</th>";
   $display_string .= "		<th>RentAno %</th>";
   $display_string .= "	</tr>";
   $display_string .= "</thead>";
   
   $idcarteira=$ult_data=$ult_val=0;
   $qry_result_ult_data=array();
   $row_ult_data=array();
   
   // Insert a new row in the table for each person returned
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
	 
	 $idcarteira = $row[idcarteira];
	 
	 // Tratamento para pegar a ultima leitura, meses de duração
	 $qry_ult_data = 'select data_fato, val_invest from inv_fato where idcarteira='. $idcarteira .' and data_fato = (select max(data_fato) from inv_fato where idcarteira='. $idcarteira .')';
	 $qry_result_ult_data = mysqli_query($db,$qry_ult_data) or die(mysql_error());
	 $row_ult_data = mysqli_fetch_array($qry_result_ult_data,MYSQLI_ASSOC);
	 
	 $ult_data = $row_ult_data[data_fato];
	 $ult_val = $row_ult_data[val_invest];
	 
	 // Obtedo a diferença em meses, para o calculo da rentabilidade por mes
	 $ini_data_dt = strtotime(substr($row[data_ini], 0, 4).'-'.substr($row[data_ini], 4, 2).'-'.substr($row[data_ini], -2));
	 $ult_data_dt = strtotime(substr($ult_data,0,4).'-'.substr($ult_data, 4, 2).'-'.substr($ult_data, -2));
	 
	 $diff_ano = (date('Y',$ult_data_dt)-date('Y',$ini_data_dt));
	 if (date('m',$ult_data_dt) < date('m',$ini_data_dt)) {
		$diff_ano -= 1;
	 }
	 // echo '<br> '.date('m',$ult_data_dt).' >= '.date('m',$ini_data_dt);
	 $diff_mes =  (date('m',$ult_data_dt)-date('m',$ini_data_dt));
	 if ($diff_mes < 0) {
		$diff_mes = 12 + $diff_mes;
	 }
	 //$diff_mes =  $diff_ano*12;
	 //$diff_mes += date('m',$ult_data_dt)-date('m',$ini_data_dt);
	 if (date('d',$ult_data_dt)<date('d',$ini_data_dt)){
		$diff_mes -= 1;
		//echo '<br>'.date('d',$ult_data_dt).'<'.date('m',$ini_data_dt);
	 }
	 // Calculo da diferença de tempo em meses: ano * 12 + diff_mes
	 $diff = ($diff_ano * 12) + $diff_mes;
	 // echo '<br>'.$row[nome].' Ult Data '.$ult_data.' - Data Ini'.$row[data_ini].' = '.$diff.' meses,     anos= '.$diff_ano.'*12, + meses='.$diff_mes.' mes_ult '.date('m',$ult_data_dt).' - mes_ini '.date('m',$ini_data_dt);
	 
	
	 // Calculo da rentabilidade por mes $$
	 $rent= $ult_val - $row[val_ini];
	 $rent_mes = round($rent/$diff,2);
	 
	 // Calculo da rentabilidade por mes %%
	 $rent_mes_per = round(($row[rent_perc]-100)/$diff,2);
	 
	 // Calculo da rentabilidade por ano %%
	 if ($diff_ano <= 1){
		$rent_ano_per = round($row[rent_perc],2);
		
		//$rent_ano_per = (round($row[rent_perc]-100,2)/$diff)*12;
		$rent_ano_per = round(((($row[rent_perc]-100)/$diff)*12),2);
	 } else {
		$rent_ano_per = round((pow(($row[rent_perc]/100),(1/($diff/12)))*100),2);
		
	 }
	 
	 
	 // echo '<br>Data inicial = '.date('Y-m-d', $ini_data_dt).' Data Final = '.date('Y-m-d', $ult_data_dt).'    diff_mes = '.$diff_mes.' diff_ano = '.$diff_ano;
	 // echo '<br>Rent = '.$rent.' valor atual = '. $ult_val .' valor inicial = '.$row[val_ini].' Rentabilidade por mes $$ = '.$rent_mes.' ('.$rent.' / '.$diff.' )';
	 // echo '<br>Rent % mes = '.($row[rent_perc]/100).' / '.$diff.' = '.$rent_mes_per;
	 // echo '<br>Rent % ano = '.($row[rent_perc]/100).' ^ '.(1/($diff/12)).' = '.$rent_ano_per;
	 
	 //Formatação da cor da letra
	 $cor = '000000';
	 if ($rent_ano_per > 0){
		$cor = '009933';
	 } else {
		$cor = 'cc0000';
	 }
		
      //Manipulação da data para o formato DD/MM/YYYY
	 $trat_data_ini = substr($row[data_ini], -2)."/".substr($row[data_ini], 4, 2)."/".substr($row[data_ini],0,4) ;
      $display_string .= "<thead>";
      $display_string .= " 	<tr>";
      $display_string .= "		<td>" . $row[nome] . "</td>";
      $display_string .= "		<td>" . $row[entidade] . "</td>";
      $display_string .= "		<td>" . $row[tipo] . "</td>";
      $display_string .= "		<td>" . $row[subtipo] . "</td>";
      $display_string .= "		<td>" . $trat_data_ini . "</td>";
	 $display_string .= "		<td>" . $row[val_ini] . "</td>";
	 $display_string .= "		<td>" . date('d/m/Y', $ult_data_dt) . "</td>";
	 $display_string .= "		<td>" . $ult_val . "</td>";
      $display_string .= "		<td>" . $rent_mes . "</td>";
      $display_string .= "		<td>" . $rent_mes_per . "%</td>";
	 $display_string .= "		<td>" . $rent_ano_per . "%</td>";
      $display_string .= "	</tr>";
      $display_string .= "</thead>";
      
      //echo "<br> Id = " . $row[idinvest] . ",";
   }
   
   $display_string .= "</table>";
   echo $display_string;
   
   
   
   
   
   

?>