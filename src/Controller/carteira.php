<!DOCTYPE html>
<?php
   include("config.php");
   session_start();

   $idsubtipo = $_POST["subtipo"];
   $error="";
?>
<!-- AJAX -->
<script language = "javascript" type = "text/javascript">
	function ajaxFunction(){ //Browser Support Code
		var ajaxRequest;  // The variable that makes Ajax possible!
		
		try {
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		}catch (e) {
			// Internet Explorer Browsers
			try {
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
         
		// Create a function that will receive data 
		// sent from the server and will update
		// div section in the same page.
 
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay = document.getElementById('ajaxDiv');
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			}
		}
		
		// Now get the value from user and pass it to
		// server script.
 
		var nome = document.getElementById('nome').value;
		var tipo = document.getElementById('tipo').value;
		var subtipo = document.getElementById('subtipo').value;
		var queryString = "?nome=" + nome ;
	
		queryString +=  "&tipo=" + tipo + "&subtipo=" + subtipo;
		ajaxRequest.open("GET", "get-carteira.php" + queryString, true);
		ajaxRequest.send(null); 
	}
</script>

<html lang="en">
<head>
  <title>Controle de investimentos</title>
  <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
  </style>
</head>
<body>

  <!-- Barra de navegação -->
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
        </button>
        <a class="navbar-brand" href="#">Logo</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li class="active"><a href="./index.php">Início</a></li>
          <li><a href="./sobre.php">Sobre</a></li>
          <li><a href="./contato.php">Contato</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
      

  <!-- Conteúdo -->
  <p></p>
  <span></span>
  <span></span>
  
  
  <div class="container">
  
    <!-- Listagem de investimentos -->
    <div class="row justify-content-center">
      <div class="col-xs-12">
			<div class="row">
				 <p><h2>Carteira de Investimentos</h2></p>
			</div>
			<form name = 'myForm'>
				<div class="row">
					<div class="col-xs-1"><h4>Filtros</h4>
					</div>
					<div class="col-xs-3">Nome:
						<select class="form-control" name="nome">
							<option value=""></option>
							<?php
								$query = "SELECT distinct(nome) as nome FROM investdb.invest";
								
								//Execute query
								$qry_result = mysqli_query($db,$query) or die(mysql_error());
								$display_string = "";
								while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
									$display_string .= '<option value="'. $row[nome] . '">'. $row[nome] .'</option>';
								}
								echo $display_string;
							?>
						</select>
					</div>
					<div class="col-xs-3">Tipo:
						<select class="form-control" name="tipo">
							<option value=""></option>
							<?php
								$query = "SELECT distinct(tipo) as tipo FROM investdb.tipo_invest";
								
								//Execute query
								$qry_result = mysqli_query($db,$query) or die(mysql_error());
								
								//Build Result String
								$display_string = "";
								while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
									$display_string .= '<option value="'. $row[tipo] . '">'. $row[tipo] .'</option>';
								}
								echo $display_string;
							?>
					  </select>
					</div>
					<div class="col-xs-3">SubTipo: 
						<select class="form-control" name="subtipo">
							<option value=""></option>
							<?php
								$query = "SELECT distinct(subtipo) as subtipo FROM investdb.sub_tipo_invest";
								
								//Execute query
								$qry_result = mysqli_query($db,$query) or die(mysql_error());
								//Build Result String
								$display_string = "";
								while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
									$display_string .= '<option value="'. $row[subtipo] . '">'. $row[subtipo] .'</option>';
								}
								echo $display_string;
							?>
					  </select>
					</div>
					<div class="col-xs-1">
						<button class="btn btn-default" type="submit">Filtrar</button>
					</div>
				</div>
			</form>
			<br><br>
			<div id = 'ajaxDiv'>
			  <?php
				 include "get-carteira.php";
			  ?>
			</div>
      </div>
   </div>
</div>
<p></p>
</body>
