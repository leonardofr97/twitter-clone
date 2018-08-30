<?php

// recuperando o erro na index.php
// verifica se existe o erro através de um if ternário
$erro = isset($_GET['erro']) ? $_GET['erro'] : 0;

// verifica se o usuário não estava vindo de um logoff
$logoff = isset($_GET['logoff']) ? $_GET['logoff'] : 0;
?>


<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">

		<title>Twitter clone</title>

		<!-- jquery - link cdn -->
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

		<!-- bootstrap - link cdn -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

		<!-- código javascript -->
		<script>
			$(document).ready( function(){

				// verificar se os campos de usuário e senha foram devidamente preenchidos
				$('#btn_login').click( function(){

					// variável de controle
					var campo_vazio = false;

					if ($('#campo_usuario').val() == '') {

						// caso o campo esteja vazio, iremos alterar a cor das bordas para vermelho como um alerta
						$('#campo_usuario').css({'border-color': '#A94442'});
						campo_vazio = true;
					} else {

						// se o campo for preenchido ele volta pra cor padrão
						$('#campo_usuario').css({'border-color': '#CCC'});
					}

					if ($('#campo_senha').val() == '') {
						$('#campo_senha').css({'border-color': '#A94442'});
						campo_vazio = true;
					} else {
						$('#campo_usuario').css({'border-color': '#CCC'});
					}

					// caso o campo_vazio for true, será interrompido o envio do formulário com o return false, pois sem isso o form. era enviado antes de alterar a cor da borda ;-;
					if (campo_vazio) {
						return false;
					}
				})

			})

		</script>
	</head>

	<body>

		<!-- Static navbar -->
	    <nav class="navbar navbar-default navbar-static-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <img src="imagens/icone_twitter.png" />
	        </div>

	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav navbar-right">
	            <li><a href="inscrevase.php">Inscrever-se</a></li>
	            <!-- eh necessário forçar a exibição da janela form. login quando houver o erro de usuario inválido atribuindo 'open' a class dele -->
	            <li class=" <?=$erro == 1 ? 'open' : ''?> ">
	            	<a id="entrar" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Entrar</a>
					<ul class="dropdown-menu" aria-labelledby="entrar">
						<div class="col-md-12">
				    		<p>Você possui uma conta?</h3>
				    		<br />
							<form method="post" action="validar_acesso.php" id="formLogin">
								<div class="form-group">
									<input type="text" class="form-control" id="campo_usuario" name="usuario" placeholder="Usuário" />
								</div>

								<div class="form-group">
									<input type="password" class="form-control red" id="campo_senha" name="senha" placeholder="Senha" />
								</div>

								<button type="buttom" class="btn btn-primary" id="btn_login">Entrar</button>

								<br /><br />

							</form>

							<?php

// exibindo a mensagem de erro abaixo do form. login
if ($erro == 1) {
    echo "<font color='#FF0000'>Usuário e ou senha inválido(s) !</font>";
}

?>
						</form>
				  	</ul>
	            </li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav> <br><br><br>

	    <!--verifica se o usuário não estava vindo de um logoff-->
	    <?php
if ($logoff == 1) {
    echo "<div class='container'><div class='jumbotron'><h3>Esperamos você de volta em breve !!!</h3></div></div>";
}

?>
		<br><br><br>
	    <div class="container">

	      <!-- Main component for a primary marketing message or call to action -->
	      <div class="jumbotron">
	        <h1>Bem vindo ao twitter clone</h1>
	        <p>Veja o que está acontecendo agora...</p>
	      </div>

	      <div class="clearfix"></div>
		</div>


	    </div>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	</body>
</html>