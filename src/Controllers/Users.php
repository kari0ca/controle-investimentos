<?php

namespace App\Controllers;

class Users extends Controller
{
	//Adição Higor (como foi feito na Types)
    public function add()
    {
		$error="";
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			$myusername = mysqli_real_escape_string($db,$_POST['nome']);
			$mylogin = mysqli_real_escape_string($db,$_POST['username']);
			$myemail = mysqli_real_escape_string($db,$_POST['email']);
			$mypassword1 = mysqli_real_escape_string($db,$_POST['password1']);
			$mypassword2 = mysqli_real_escape_string($db,$_POST['password2']); 
			$mylembr = mysqli_real_escape_string($db,$_POST['lembrete']); 
			
			// Procura por usuarios com o mesmo login
			$sql = "SELECT iduser FROM investdb.user WHERE login = '$mylogin'";
			$result = mysqli_query($db,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$active = $row['active'];
			
			$count = mysqli_num_rows($result);
			//echo "<br> count=".$count;
			if($count >= 1) {
				$error="Já existe um usuário com este nome, ";
				//echo "<br> Usuário existente";
			}
			if($mypassword1!=$mypassword2) {
				$error.="A confirmação da senha não é igual, ";
			}
			//remover 2 ultimos caracteres da string de erro
			
			// Obtem o maior id_user
			$sql = "SELECT max(iduser) as iduser FROM investdb.user";
			$result = mysqli_query($db,$sql) or die(mysql_error());
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$iduser = $row[iduser];
			$param_password = password_hash($mypassword1, PASSWORD_DEFAULT);
			$iduser = $iduser + 1;
			$sql_insert = "INSERT INTO investdb.user values('".$iduser."','".$myusername."','".$mylogin."','".$param_password."','".$mylembr."','".$myemail."')";
			
			if (mysqli_query($db, $sql_insert)) {
				//echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
    
		echo '<html lang="en">
				 <head>
				   <title>Controle de investimentos</title>
				   <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
				   <meta name="viewport" content="width=device-width, initial-scale=1">
				   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
				   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
				   <style>
					/* Remove the navbars default margin-bottom and rounded borders */ 
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
					
					/* On small screens, set height to "auto" for sidenav and grid */
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
						  </ul>
						</div>
					   </div>
					 </nav>
						
					 
					 <!-- Conteúdo -->
					 <div class="container">
						 <div class="row justify-content-center">
							 <form action = "" method = "post" name = "FormCadastroUsuario">
								 <p><h3>Cadastro de Usuário</h3></p>
								 <div class="row">
									 <div class="col-xs-6 form-group">
										 <input class="form-control" id="nome" name="nome" placeholder="Nome" type="text" required>
									 </div>
									 <div class="col-xs-6 form-group">
										 <input class="form-control" id="login" name="username" placeholder="Login" type="text" required>
									 </div>
								 </div>
								 <div class="row">
									 <div class="col-xs-12 form-group">
										 <input class="form-control" id="email" name="email" placeholder="E-mail" type="email" required>
									 </div>
								 </div>
								 <div class="row">
									 <div class="col-xs-6 form-group">
										 <input class="form-control" id="pass1" name="password1" placeholder="Senha" type="password" required>
									 </div>
									 <div class="col-xs-6 form-group">
										 <input class="form-control" id="pass2" name="password2" placeholder="Repita a Senha" type="password" required>
									 </div>
								 </div>
								 <div class="row">
									 <div class="col-xs-8 form-group">
										 <input class="form-control" id="lembrete" name="lembrete" placeholder="Lembrete da senha" type="text" required>
									 </div>
									 <div class="col-xs-4 form-group">
										 <div class="btn-group pull-right">
											 <button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
											 <button class="btn btn-default btn-sm" type="submit">Cadastrar</button>
										 </div>						
									 </div>
								 </div>
								 
							 </form>
							 <div class="row">
		';
		 if ($error != "") {
			 echo '<div class="col-xs-10 form-group alert alert-danger">';
			 echo $error;
			 echo '</div>';
		 }
	 
		 echo '			</div>
					 </div>
				 </div>
			 </body>
			 
			 
			 
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
		 ';
    }
}