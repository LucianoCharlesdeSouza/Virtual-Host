<?php 
if(!isset($_SESSION)):
	session_start();
endif;	
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Painel Virtual Host</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<h1>Crie aqui seus Virtuais Hosts</h1>
	<div class="page">
		<div class="form">
			<form method="POST" action="vhost.php" class="login-form">
				<input type="text" name="virtual_host" placeholder="Digite o nome do Virtual Host"/>
				<button type="submit">Criar Virtual Host</button>
			</form>
		</div>
	</div>
	<?php if(!empty($_SESSION['msg'])):?>
	
		<div class="page">
			<div class="msg">
				<h5>
					<?php 
						echo $_SESSION['msg'];
						session_destroy();
					?>	
				</h5>
			</div>	
	    </div>
	<?php endif; ?>

</body>
</html>