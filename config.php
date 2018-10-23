<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'higor');
   define('DB_PASSWORD', 'sp120c');
   define('DB_DATABASE', 'investdb');

   try
   {
      if ($db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE))
      {
         //echo "<br>Conectou!";
      }
      else
      {
         throw new Exception('Unable to connect. '.mysqli_connect_error());
      }
   }
   catch(Exception $e)
   {
      echo $e->getMessage();
   }

?>
