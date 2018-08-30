<?php

<!-- Conxeзгo ao banco de dados -->
$servername = "localhost";
$username = "higor";
$password = "sp120c";


// Create connection
//$conn = new mysqli($servername, $username, $password);

// Check connection
/*if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} */
  
  function connectdb(){ 
	$conexao = mysql_connect($servername, $username, $password);
	if (!$conexao) {
		die('Not connected : ' . mysql_error());
	}

	$db_selected = mysql_select_db('investdb', $conexao);
	if (!$db_selected) {
		die ('Can\'t use foo : ' . mysql_error());
	}	
  }



  function login($user, $pass){ //function parameters, two variables.
	connectdb();
	$sql = "select iduser, pass from investdb.user where upper(login)="+strtoupper(trim($user))+";";
	$result = $conn->query($sql);

    return $valid;  //returns the second argument passed into the function
  }
?>