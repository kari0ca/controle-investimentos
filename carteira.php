<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
  
	if(!isset($_SESSION['login_user'])){
	   header("location:login.php"); die('Não ignore meu cabeçalho...');
	}
	
	//$idsubtipo = $_POST["subtipo"];
	$error="";
?>


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
     
     <div class="container">
          <div class="row justify-content-center"> 
               <form name = "FormCarteira" >
                    <p><h3>Carteira de Investimentos</h3></p>
                    <div class="row">
                         <div class="col-xs-2 form-group"><h4>Filtros:</h4>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xs-3 form-group">
						<label for="nome">Nome:</label>
                              <select class="form-control" name="nome">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct(nome) from investdb.carteira where iduser=".$_SESSION['iduser'];
                                        
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
                         <div class="col-xs-3 form-group">
						<label for="entidade">Entidade:</label>
                              <select class="form-control" name="entidade">
                                   <option value=""></option>
                                   <?php
                                        $query = "select e.identidade, e.entidade from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest = i.idinvest and i.identidade = e.identidade and c.iduser=".$_SESSION['iduser'];
                                        $query .= " group by e.identidade, e.entidade";
                                        
                                        //Execute query
                                        $qry_result = mysqli_query($db,$query) or die(mysql_error());
                                        $display_string = "";
                                        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {
                                             $display_string .= '<option value="'. $row[identidade] . '">'. $row[entidade] .'</option>';
                                        }
                                        echo $display_string;
                                   ?>
                              </select>
                         </div>  
                         <div class="col-xs-3 form-group">
						<label for="tipo">Tipo:</label>
                              <select class="form-control" name="tipo">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct t.tipo from investdb.carteira c, investdb.invest i, investdb.tipo_invest t where c.idinvest = i.idinvest and i.idtipo = t.idtipoinvest and c.iduser=".$_SESSION['iduser'];
                                        
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
                         <div class="col-xs-3 form-group">
						<label for="subtipo">SubTipo:</label>
                              <select class="form-control" name="subtipo">
                                   <option value=""></option>
                                   <?php
                                        $query = "select distinct s.subtipo from investdb.carteira c, investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where c.idinvest = i.idinvest and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo and c.iduser=".$_SESSION['iduser'];
                                        
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
                    </div>
                    <div class="row">
                         <div class="col-xs-12 form-group">
                              <div class="btn-group pull-right" >
                                   <a href="cadastr-invest.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Novo Investimento
                                   </a>
                                   <a href="cadastr-carteira.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Gerenciar Carteira
                                   </a>
                                   <a href="cadastr-valorcarteira.php" class="btn btn-info btn-sm">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Atualizar Valores
                                   </a>
                                   <button class="btn btn-default btn-sm" type="submit">Filtrar</button>
                              </div>
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
</body>

<!-- --------------------------------------------------------------------------------------- -->
<!-- Footer -->
<footer class="container-fluid">
    <div class="container">
	   <div class="media-container-row content text-white">
		  <div class="col-12 col-xs-3">
			 <div class="media-wrap">
				<a href="https://xxxxxxxxxx.com">
				    <img src="assets/images/logo24.png" alt="Mobirise">
				</a>
			 </div>
		  </div>
		  <div class="col-12 col-xs-3 mbr-fonts-style display-7">
			 <h5 class="pb-3">
				Address
			 </h5>
			 <p class="mbr-text">
				1234 Street Name
				<br>City, AA 99999
			 </p>
		  </div>
		  <div class="col-12 col-xs-3 mbr-fonts-style display-7">
			 <h5 class="pb-3">
				Contacts
			 </h5>
			 <p class="mbr-text">
				Email: support@mobirise.com
				<br>Phone: +1 (0) 000 0000 001
				<br>Fax: +1 (0) 000 0000 002
			 </p>
		  </div>
		  <div class="col-12 col-xs-3 mbr-fonts-style display-7">
			 <h5 class="pb-3">
				Links
			 </h5>
			 <p class="mbr-text">
				<a class="text-primary" href="https://xxxxxxxxxx.com">Website builder</a>
				<br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-win.zip">Download for Windows</a>
				<br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-mac.zip">Download for Mac</a>
			 </p>
		  </div>
	   </div>
  
  <p>© Copyright 2018 Blablabla - All Rights Reserved</p>
</footer>

</html>
