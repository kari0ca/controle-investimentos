<?php
   include("config.php");   
   session_start();

   //echo "Entrou no get-carteira.php   ";
   // Retrieve data from Query String
   $nome = $_GET['nome'];
   $tipo = $_GET['tipo'];
   $subtipo = $_GET['subtipo'];
   
   // Escape User Input to help prevent SQL Injection
   $nome = mysqli_real_escape_string($db,$nome);   
   $tipo = mysqli_real_escape_string($db,$tipo);   
   $subtipo = mysqli_real_escape_string($db,$subtipo);   
   
	echo "userid=".$_SESSION['iduser'];
	
	/*
	select c.idcarteira, c.idinvest, i.nome, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo
from investdb.carteira c, investdb.invest i, investdb.entidade e
where c.idinvest = i.idinvest and i.identidade = e.identidade; 
	*/
	
	
   //build query
   //$query = "select i.idinvest, i.nome, t.tipo, s.subtipo from investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.idtipo=t.idtipoinvest and t.idsubtipo=s.idsubtipo";
	$query = "select c.idcarteira, c.idinvest, i.nome, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest = i.idinvest and i.identidade = e.identidade";
	$query .= " and iduser=".$_SESSION['iduser'];
   if ($nome  != ''){
      $query .= " and i.nome='".$nome."'";
   }
	
	/*  comentario previo de tipo e subtipo
   if ($tipo  != ''){
      $query .= " and t.tipo='".$tipo."'";
   }
   if ($subtipo  != ''){
      $query .= " and s.subtipo='".$subtipo."'";
   }
	*/
	
	
   //Execute query
   $qry_result = mysqli_query($db,$query) or die(mysql_error());
   
   //Build Result String
   $display_string = "<table class='table table-hover'>";
   $display_string .= "<thead>";
   $display_string .= "	<tr>";
   $display_string .= "		<th>Id</th>";
   $display_string .= "		<th>Nome</th>";
   $display_string .= "		<th>Tipo</th>";
   $display_string .= "		<th>SubTipo</th>";
   $display_string .= "		<th>Rent Total $</th>";
   $display_string .= "		<th>Rent Mês %</th>";
   $display_string .= "		<th>Rent Mês %</th>";
   $display_string .= "		<th>Ativo</th>";
   $display_string .= "	</tr>";
   $display_string .= "</thead>";
   
   
   // Insert a new row in the table for each person returned
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
      
      $display_string .= '<thead>';
      $display_string .= '	<tr>';
      $display_string .= '		<td>' . $row[idinvest] . '</td>';
      $display_string .= '		<td>' . $row[nome] . '</td>';
      $display_string .= '		<td>' . $row[tipo] . '</td>';
      $display_string .= '		<td>' . $row[subtipo] . '</td>';
      $display_string .= '		<td></td>';
      $display_string .= '		<td></td>';
      $display_string .= '		<td></td>';
      $display_string .= '		<td></td>';
      $display_string .= '	</tr>';
      $display_string .= '</thead>';
      
      //echo "<br> Id = " . $row[idinvest] . ",";
   }
   
   $display_string .= "</table>";
   echo $display_string;

?>