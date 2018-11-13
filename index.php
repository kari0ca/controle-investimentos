<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
  
	$page = "Entrada";
	$title = "Página Principal";
	$metaD = "Página de entrada do site";
	include 'header.php';
?>
<!-- Conteúdo -->
		<div id="myCarousel" class="carousel slide" data-ride="carousel" style="background-color: #FFF;">
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
<?php
include 'footer.php';
?>
