<?php
   include("config.php");   
   session_start();

	$query = "select c.idcarteira, c.idinvest, c.val_ini, i.nome, e.identidade, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo, t.tipo, s.subtipo from investdb.carteira c, investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo and c.ativo=1";
	$query .= " and iduser=".$_SESSION['iduser'];
	
	
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
   $display_string .= "		<th>Valor Investido</th>";
   $display_string .= "		<th>Ativo</th>";
   $display_string .= "	</tr>";
   $display_string .= "</thead>";
   
   
   // Insert a new row in the table for each person returned
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
      $trat_data_ini = substr($row[data_ini], -2)."/".substr($row[data_ini], 4, 2)."/".substr($row[data_ini],0,4);
      $display_string .= "<thead>";
      $display_string .= "	<tr>";
      $display_string .= "		<td>" . $row[nome] . "</td>";
      $display_string .= "		<td>" . $row[entidade] . "</td>";
      $display_string .= "		<td>" . $row[tipo] . "</td>";
      $display_string .= "		<td>" . $row[subtipo] . "</td>";
      $display_string .= "		<td>" . $trat_data_ini . "</td>";
      $display_string .= "		<td>" . $row[val_ini] . "</td>";
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
   $display_string .= "</table>";
   echo $display_string;

?>