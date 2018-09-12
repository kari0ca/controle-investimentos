<?php
   include("config.php");   
   session_start();

   //echo "Entrou no get-carteira.php   ";
   // Retrieve data from Query String
   $age = $_GET['age'];
   $sex = $_GET['sex'];
   $wpm = $_GET['wpm'];
   
   // Escape User Input to help prevent SQL Injection

   $age = mysqli_real_escape_string($db,$age);
   //$sex = mysql_real_escape_string($sex);
   //$wpm = mysql_real_escape_string($wpm);
   
   //build query
   $query = "select idinvest, nome, tipo from investdb.invest";
   
   //Execute query
   $qry_result = mysqli_query($db,$query) or die(mysql_error());
   
   //Build Result String
   $display_string = "<table class='table table-hover'>";
   $display_string .= "<thead>";
   $display_string .= "	<tr>";
   $display_string .= "		<th>Id</th>";
   $display_string .= "		<th>Nome</th>";
   $display_string .= "		<th>Tipo</th>";
   $display_string .= "	</tr>";
   $display_string .= "</thead>";
   
   
   // Insert a new row in the table for each person returned
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
      
      $display_string .= '<thead>';
      $display_string .= '	<tr>';
      $display_string .= '		<td>' . $row[idinvest] . '</td>';
      $display_string .= '		<td>' . $row[nome] . '</td>';
      $display_string .= '		<td>' . $row[tipo] . '</td>';
      $display_string .= '	</tr>';
      $display_string .= '</thead>';
      
      //echo "<br> Id = " . $row[idinvest] . ",";
   }
   //echo "Query: " . $query . "<br/>";
   
   $display_string .= "</table>";
   echo $display_string;

?>