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
	   $result = mysqli_query($db,$sql) or die(mysql_error());
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
	$page = "Login";
	$title = "Página de Login";
	$metaD = "Página de entrada do site";
	include 'header.php';
?>
	<!-- Conteúdo -->
	<div class="container">
		<div class="row justify-content-center">
			<form action = "" method = "post" name = "FormLogin">
				<p><h3>Login de Usuário</h3></p>
				<div class="row">
					<div class="col-xs-2 form-group">
					</div>	
					<div class="col-xs-4 form-group">
						<input class="form-control" id="login" name="username" placeholder="Login" type="text" required>
					</div>
					<div class="col-xs-4 form-group">
						<input class="form-control" id="pass" name="password" placeholder="Senha" type="password" required>
					</div>
					<div class="col-xs-2 form-group">
					</div>	
				</div>
				<div class="row">
					<div class="col-xs-2 form-group">
					</div>	
					<div class="col-xs-8 form-group">
						<div class="btn-group pull-right">
							<a href="cadastr-usuario.php" class="btn btn-info btn-sm">
								<span class="glyphicon glyphicon-plus-sign"></span> Cadastrar Usuário
							</a>
							<button class="btn btn-default btn-sm" type="submit">Entrar</button>
						</div>
					</div>					
					<div class="col-xs-2 form-group">
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
</body>

<!-- Footer -->
<?php
include 'footer.php';
?>
</html>