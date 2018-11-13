<!DOCTYPE html>
<?php
	include("config.php");
	session_start();

	$page = "Cadastr-usuario";
	$title = "[MI] - Cadastro de Usuário";
	$metaD = "Cadastro de Usuário";
	include 'header.php';
	
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
?>

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
			  <?php
			    if ($error!=""){
				 echo '<div class="col-xs-12 form-group alert alert-danger">';
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