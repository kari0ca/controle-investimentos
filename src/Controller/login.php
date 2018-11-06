<!DOCTYPE html>
<?php
   include("config.php");
   session_start();
   
   $error="";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);
      
      $param_password = password_hash($mypassword, PASSWORD_DEFAULT);
      
      $sql = "SELECT iduser, pass FROM investdb.user WHERE login = '$myusername'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $senha_hash = $row[pass];
      $active = $row['active'];
      $iduser = $row[iduser];
      
      //compara senha com hash
      if (password_verify ($mypassword, $senha_hash)){
        //echo "<br>Senha verificada com sucesso";
      } else {
        //echo "<br>Senha não verificada";
      }
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         $_SESSION["login_user"] = $myusername;
         $_SESSION["iduser"] = $iduser;
         header("location:carteira.php"); die('Não ignore meu cabeçalho...');
      }else {
         $error = "Login ou Senha invalidos";
      }
   }
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
      </ul>
    </div>
  </div>
</nav>
    

<!-- Conteúdo -->
<p></p>
<span></span>
<span></span>


<div class="container">
	<div class="row justify-content-center">
		<div class="col-xs-12">
			<form action = "" method = "post">
        <div class="row">
          <div class="col-xs-4 form-group">
            <input class="form-control" id="login" name="username" placeholder="Login" type="text" required>
          </div>
          <div class="col-xs-4 form-group">
            <input class="form-control" id="pass" name="password" placeholder="Senha" type="password" required>
          </div>

        </div>
        <div class="row">
          <div class="col-xs-4 form-group text-align:center"><a href="cadastr-usuario.php">Cadastrar usuário</a></div>
          <div class="col-xs-4 form-group">
            <button class="btn pull-right align:right" type="submit">Entrar</button>
          </div>
        </div>
      </form>
          <div class="row">
            <?php
              if ($error!=""){
                echo '<div class="col-xs-8 form-group alert alert-danger">';
                echo $error;
                echo '</div>';
              }
            ?>
          </div>
      
		</div>
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