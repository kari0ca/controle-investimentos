<!DOCTYPE html>
<?php
	include("config.php");
	session_start();
  
	$page = "Sobre";
	$title = "[MI] - Sobre a Plataforma";
	$metaD = "Página de explicação da plataforma";
	include 'header.php';
?>
<!-- Conteúdo -->
	<div class="container">
		<div class="row justify-content-center">
			<p><h3>Meu Investimento</h3></p>
			<div class="row">
				<div class="col-xs-1 form-group">
					
				</div>
				<div class="col-xs-10 form-group">
					<p><h4>Plataforma open source para controle de investimento e colaboração sobre o acompanhamento do mercado</h5></p>
					<p><h5>Objetivo</h5></p>
					<p><h6>
					Nosso objetivo é permitir que você use a plataforma para controlar os seus investimentos,
					ou seja, acompanhar a rentabilidade, saber o quanto o seu dinheiro está rendendo,
					e se possivel ter uma previsão de quanto irá render em determinado período. Tudo isto,
					usando alguns modelos estatísticos para gerar essas previsões.</h6></p>
					<p><h5>Como vou poder acompanhar o mercado?</h5></p>
					<p><h6>A parte colaborativa, vem com o compatilhamento de algumas inforações dos usuários da plataforma,
					não se preocupe, os seus dados sensiveis estão seguros!
					<br>
					Apenas será compartilhada a informação dos rendimentos (valores percentuais) dos seus investimentos.
					Os seus dados e informação financeira, não são compartilhados.
					Com o compartilhamento destas informações, você também se benefeciará, terá acesso a informação dos
					investimentos com melhores rendimentos na carteira de todos os usuários!</h6></p>
				</div>
				<div class="col-xs-1 form-group">
					
				</div>
			</div>
		</div>
	</div>
<!-- Footer -->
<?php
include 'footer.php';
?>
