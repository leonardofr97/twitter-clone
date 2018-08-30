<?php

session_start();

// verifica se o indice usuário da variavel session existe, ou seja, se o usuário foi autenticado, isso evita que a página seja acessada sem um login (autenticação)
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

$objDb = new db();
$link  = $objDb->conecta_mysql();

$id_usuario = $_SESSION['id_usuario'];

// exibir qtde de tweets do usuário logado

// é necessário recuperar a quantidade de registros (tweets) e isso eh feito com o COUNT()
$sql = " SELECT COUNT(*) AS qtde_tweets FROM tweet WHERE id_usuario = $id_usuario ";

$resultado_id = mysqli_query($link, $sql);

$qtde_tweets = 0;

if ($resultado_id) {
    $registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);

    // é atribuido à variavel o indice de $registro que corresponde ao alias da query
    $qtde_tweets = $registro['qtde_tweets'];
} else {
    echo "Erro ao executar a query";
}

// exibir qtde de seguidores

$sql = " SELECT COUNT(*) AS qtde_seguidores FROM usuarios_seguidores WHERE seguindo_id_usuario = $id_usuario ";

$resultado_id = mysqli_query($link, $sql);

$qtde_seguidores = 0;

if ($resultado_id) {
    $registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);

    // é atribuido à variavel o indice de $registro que corresponde ao alias da query
    $qtde_seguidores = $registro['qtde_seguidores'];
} else {
    echo "Erro ao executar a query";
}

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
		<script type="text/javascript">

			$(document).ready( function(){

				// associar o evento de click ao botão
				$('#btn_procurar_pessoa').click( function(){

					// verificando se o campo nome_pessoa não está vazio
					if ($('#nome_pessoa').val().length > 0) {

						// será enviado a informação do campo nome_pessoa para um script php através de uma requisição ajax
						$.ajax({
							url: 'get_pessoas.php',
							method: 'post',
							// para formulário com vários campos, podemos simplificar a captura de todos com a função jquery serialize
							data: $('#form_procurar_pessoa').serialize(),
							success: function(data) {

								// inclui na div pessoas o retorno da requisição, ou seja a lista dos usuarios de acordo com a pesquisa
								$('#pessoas').html(data);

								// botões Seguir
								$('.btn_seguir').click( function(){

									// com o 'data' podemos recuperar os dados do atributo customizado que definimos no botão em get_pessoas.php
									var id_usuario = $(this).data('id_usuario');

									// caso o botão de seguir seja clicado, o mesmo será ocultado e o botão deixar de seguir será exibido no lugar dele
									$('#btn_seguir_'+id_usuario).hide();
									$('#btn_deixar_seguir_'+id_usuario).show();

									//alert(id_usuario);

									$.ajax({
										url: 'seguir.php',
										method: 'post',

										// será enviado o id do usuário recuperado do botão através de um JSON ({})
										data: { seguir_id_usuario: id_usuario },
										success: function(data){
											//alert('registro efetuado com sucesso');
										}
									})
								});

								// botões Deixar de Seguir
								$('.btn_deixar_seguir').click( function(){

									// funciona quase da msm forma que no botão seguir :D

									var id_usuario = $(this).data('id_usuario');

									// caso o botão deixar de seguir seja clicado, o mesmo será ocultado e o botão de seguir será exibido no lugar dele
									$('#btn_deixar_seguir_'+id_usuario).hide();
									$('#btn_seguir_'+id_usuario).show();

									//alert(id_usuario);

									$.ajax({
										url: 'deixar_seguir.php',
										method: 'post',

										//
										data: { deixar_seguir_id_usuario: id_usuario },
										success: function(data){
											//alert('registro removido com sucesso');
										}
									})
								})

							}
						});
					}

				});


			});

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

	            	<li><a href="home.php">Home</a></li>
	            	<!-- botão que irá encerrar a sessão -->
	            	<li><a href="sair.php">Sair</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>


	    <div class="container">

	    	<!-- barras laterais e timeline com bootstrap -->

	    	<div class="col-md-3">
	    		<!-- barra da esquerda -->
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<!-- será exibido o nome do usuário -->
	    				<h4><?=$_SESSION['usuario']?></h4>

	    				<hr>

	    				<div class="col-md-6">
	    					TWEETS <br> <?=$qtde_tweets?>
	    				</div>
	    				<div class="col-md-6">
	    					SEGUIDORES <br> <?=$qtde_seguidores?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    	<!-- painel central (onde fica timeline) -->
	    	<div class="col-md-6">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<!-- campo para procurar outros usuários agrupado à um botão de procurar com input-group -->
	    				<form id="form_procurar_pessoa" class="input-group">

	    					<!-- criando um name para uso na função serialize -->
	    					<input type="text" id="nome_pessoa" name="nome_pessoa" class="form-control" placeholder="Quem você está procurando ?" maxlength="140">

	    					<span class="input-group-btn">
	    						<button class="btn btn-default" type="button" id="btn_procurar_pessoa">Procurar</button>
	    					</span>
	    				</form>
	    			</div>
	    		</div>

	    		<!-- div que conterá a lista de exibição de usuários de acordo com a pesquisa -->
	    		<div id="pessoas" class="list-group">

	    		</div>
			</div>

			<!-- barra da direita -->
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">

					</div>
				</div>
			</div>



		</div>


	    </div>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	</body>
</html>