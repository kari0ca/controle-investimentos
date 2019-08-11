<?php
   include("config.php");   
   session_start();

   //echo "Entrou no get-carteira.php   ";
   // Retrieve data from Query String
   $nome = $_GET['nome'];
   $entidade = $_GET['entidade'];
   $tipo = $_GET['tipo'];
   $subtipo = $_GET['subtipo'];
   $estado = $_GET['estado'];
   
   // Escape User Input to help prevent SQL Injection
   $nome = mysqli_real_escape_string($db,$nome);   
   $tipo = mysqli_real_escape_string($db,$tipo);   
   $subtipo = mysqli_real_escape_string($db,$subtipo);
   $estado = mysqli_real_escape_string($db,$estado);   
	
   //build query
   //$query = "select i.idinvest, i.nome, t.tipo, s.subtipo from investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.idtipo=t.idtipoinvest and t.idsubtipo=s.idsubtipo";
	$query = "select c.idcarteira, c.idinvest, i.nome, e.identidade, e.entidade, c.data_ini, c.rent_val, c.val_ini, c.rent_perc, c.ativo, t.tipo, s.subtipo from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo";
	$query .= " and iduser=".$_SESSION['iduser'];
   if ($nome  != ''){
      $query .= " and i.nome='".$nome."'";
   }
   if ($entidade  != ''){
      $query .= " and e.identidade='".$entidade."'";
   }
   if ($tipo  != ''){
      $query .= " and t.tipo='".$tipo."'";
   }
   if ($subtipo  != ''){
      $query .= " and s.subtipo='".$subtipo."'";
   }
   if ($estado  != ''){
      $query .= " and c.ativo='".$estado."'";
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
   
   $idcarteira=$ult_data=$ult_val=$max_diff_mes=$rent_mes_cart=$rent_mes_per_cart=$rent_ano_per_cart=0;
   $qry_result_ult_data=array();
   $row_ult_data=array(); //ultima data lida na tabela de fatos (por investimento da carteira) para calcular duração
   $total_val_cart=$total_val_ini=$total_tp_ano=$tp_ano=$max_data=0; // variaveis para calcular a estatistica da carteira toda
   $min_data=99999999;
   
   // Insert a new row in the table for each person returned
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
	 
	 $idcarteira = $row[idcarteira];
	 
	 // Tratamento para pegar a ultima leitura, meses de duração
	 $qry_ult_data = 'select data_fato, val_invest from inv_fato where idcarteira='. $idcarteira .' and data_fato = (select max(data_fato) from inv_fato where idcarteira='. $idcarteira .')';
	 $qry_result_ult_data = mysqli_query($db,$qry_ult_data) or die(mysql_error());
	 $row_ult_data = mysqli_fetch_array($qry_result_ult_data,MYSQLI_ASSOC);
	 $ult_data = $row_ult_data[data_fato];
	 $ult_val = $row_ult_data[val_invest];
	 //echo "<br> ult_data = ".$ult_data." ult_data[data_fato]=".$row_ult_data[data_fato];
	 
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
	 //echo '<br>'.$row[nome].' Ult Data '.$ult_data.' - Data Ini'.$row[data_ini].' = '.$diff.' meses,     anos= '.$diff_ano.'*12, + meses='.$diff_mes.' mes_ult '.date('m',$ult_data_dt).' - mes_ini '.date('m',$ini_data_dt);
	 
	
	 // Calculo da rentabilidade por mes $$, (rendimento/numero de meses)
	 $rent= $ult_val - $row[val_ini];
	 $rent_mes = round($rent/$diff,2);
	 
	 // Calculo da rentabilidade por mes %% (percentual de rendimento/numero de meses)
	 $rent_mes_per = round(($row[rent_perc]-100)/$diff,2);
	 //echo '<br>('.$row[rent_perc].'-100)'.' / '.$diff.' = '.$rent_mes_per;
	 
	 // Calculo da rentabilidade por ano %%
	 $tp_ano = $diff/12; // Tempo em anos
	 $rent_ano_per = round(pow(($ult_val/$row[val_ini]),(1/$tp_ano)),4)*100;
	 
	 
	 // Calculo de dados estatíticos da carteira
	 if ($row[data_ini] < $min_data) 
	 {
		$min_data = $row[data_ini];
	 }
	 if ($ult_data > $max_data) 
	 {
		$max_data = $ult_data;
	 }
	 
	 if ($diff > $max_diff_mes)
	 {
		$max_diff_mes = $diff;
	 }
	 $total_val_cart += $ult_val;
	 $total_val_ini += $row[val_ini];
	 $total_tp_ano = $max_diff_mes/12;
	 $rent_mes_cart += $rent_mes;
	 //$rent_mes_cart = round(($total_val_cart - $total_val_ini)/$max_diff_mes,2);
	 $rent_mes_per_cart = ($total_val_cart/$total_val_ini);
	 //echo '<br> rent_mes_per_cart';
	 $rent_mes_per_cart = ((($total_val_cart/$total_val_ini)*100)-100)/$max_diff_mes;
	 //echo '<br> rent_mes_per_cart  '.$rent_mes_per_cart.' = ((('.$total_val_cart.'/'.$total_val_ini.')*100)-100)/'.$max_diff_mes.' = '.round($rent_mes_per_cart,2);
	 //$rent_ano_per_cart = round(pow(($total_val_cart/$total_val_ini),(1/$total_tp_ano)),4)*100;
	 $rent_ano_per_cart = round((((($total_val_cart/$total_val_ini)*100)-100)/floor($total_tp_ano))+100,2);
	 //echo '<br> floor('.$total_tp_ano.') = '.floor($total_tp_ano);
	 //echo '<br> $rent_ano_per_cart '.$rent_ano_per_cart.' = (((('.$total_val_cart.'/'.$total_val_ini.')*100)-100)/'.floor($total_tp_ano).')+100';
	 //echo "<brTotal Cart = ".$total_val_cart." - Cart Ini = ".$total_val_ini." / Meses = ".$max_diff_mes;


	 
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
	 
	 //echo "<br> UltData = ".$ult_data_dt." format date = ".date('d/m/Y', $ult_data_dt);
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
   $trat_min_data = substr($min_data, -2)."/".substr($min_data, 4, 2)."/".substr($min_data,0,4) ;
   $trat_max_data = substr($max_data, -2)."/".substr($max_data, 4, 2)."/".substr($max_data,0,4) ;
   
   $display_string .= " 	<tr>";
   $display_string .= "		<td>TOTAL</td>";
   $display_string .= "		<td>CARTEIRA</td>";
   $display_string .= "		<td></td>";
   $display_string .= "		<td></td>";
   $display_string .= "		<td>" . $trat_min_data ."</td>";
   $display_string .= "		<td>" . $total_val_ini . "</td>";
   $display_string .= "		<td>" . $trat_max_data ."</td>";
   $display_string .= "		<td>" . $total_val_cart . "</td>";
   $display_string .= "		<td>" . round($rent_mes_cart,2) . "</td>";
   $display_string .= "		<td>" . round($rent_mes_per_cart,2) ."%</td>";
   $display_string .= "		<td>" . $rent_ano_per_cart ."%</td>";
   $display_string .= "	</tr>";
   
   $display_string .= "</table>";
   echo $display_string;
?>