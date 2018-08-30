<!DOCTYPE html>
<html lang="en">
<head>
  <title>Controle de investimentos</title>
  <meta charset="utf-8">
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
<!-- Depois trocar pela chamada da função que mostra a barra--> 
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
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="./images/porq.jpg" alt="New York">
      <div class="carousel-caption">
        <h1>Gerencie seus investimentos</h1>
        <h3>Use a nossa plataforma para controlar melhor os seus investimentos</h3>
      </div> 
    </div>

    <div class="item">
      <img src="./images/invest.jpg" alt="Chicago">
      <div class="carousel-caption">
        <h1>Acompanhe o seu investimento</h1>
        <h3>Saiba de forma simples e direta o rendimento e performance dos seus investimentos</h3>
      </div> 
    </div>

    <div class="item">
      <img src="./images/din.jpg" alt="Los Angeles">
      <div class="carousel-caption">
        <h1>Fique de olho no seu dinheiro</h1>
        <h3>Veja as melhores oportunidades para aplicar o seu dinheiro</h3>
      </div> 
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!-- Footer -->
<!-- Depois trocar pela chamada da função que mostra o footer --> 
<footer class="container-fluid">
    <div class="container">
        <div class="media-container-row content text-white">
            <div class="col-12 col-md-3">
                <div class="media-wrap">
                    <a href="https://xxxxxxxxxx.com">
                        <img src="assets/images/logo24.png" alt="Mobirise">
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-3 mbr-fonts-style display-7">
                <h5 class="pb-3">
                    Address
                </h5>
                <p class="mbr-text">
                    1234 Street Name
                    <br>City, AA 99999
                </p>
            </div>
            <div class="col-12 col-md-3 mbr-fonts-style display-7">
                <h5 class="pb-3">
                    Contacts
                </h5>
                <p class="mbr-text">
                    Email: support@mobirise.com
                    <br>Phone: +1 (0) 000 0000 001
                    <br>Fax: +1 (0) 000 0000 002
                </p>
            </div>
            <div class="col-12 col-md-3 mbr-fonts-style display-7">
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

  
</body>
</html>