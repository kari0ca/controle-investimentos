<?php
   include("config.php");   
   session_start();

   /*// Retrieve data from Query String
   $age = $_GET['age'];
   $sex = $_GET['sex'];
   $wpm = $_GET['wpm'];
   // Escape User Input to help prevent SQL Injection
   $age = mysqli_real_escape_string($db,$age);*/
   
   //build query
   $query = "select i.idinvest, i.nome, t.tipo, s.subtipo from investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.idtipo=t.idtipoinvest and t.idsubtipo=s.idsubtipo";
   
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
      $display_string .= '	</tr>';
      $display_string .= '</thead>';
      
      //echo "<br> Id = " . $row[idinvest] . ",";
   }
   //echo "Query: " . $query . "<br/>";
   
   $display_string .= "</table>";
   echo $display_string;

?>