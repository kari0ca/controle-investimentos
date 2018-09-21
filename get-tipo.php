<?php
   include("config.php");   
   session_start();

   //build query
   $query = "select t.tipo, s.subtipo from investdb.tipo_invest t, investdb.sub_tipo_invest s where t.idsubtipo=s.idsubtipo";
   //echo "<br>Query=".$query;
   
   //Execute query
   if (!$qry_result = mysqli_query($db,$query))
   {
      echo("<br><br>Error description: " . mysqli_error($db))."<br><br>";
   }
   
   $row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC);
   $active = $row['active'];
   $count = mysqli_num_rows($qry_result);
   
   //Build Result String
   $display_string = "";
   $i = 1;
   
   while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
      $display_string .= '    <div class="row">';
      if ($i % 2 == 0){
         $display_string .= '       <div class="col-sm-5" style="background-color:lightgray">'. $row[tipo] . '</div>';
         $display_string .= '       <div class="col-sm-5" style="background-color:lightgray">'. $row[subtipo] . '</div>';
      }
      else {
         $display_string .= '       <div class="col-sm-5">'. $row[tipo] . '</div>';
         $display_string .= '       <div class="col-sm-5">'. $row[subtipo] . '</div>';
      }      
      $display_string .= '    </div>';
      $i = $i+1;
   }
   //$display_string .= ' </div>';
   echo $display_string;
   
?>