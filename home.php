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

				$('#texto_tweet').click( function(){
					// ao clicar no campo, a msg de sucesso de um post anterior eh ocultado
					$('#msg_tweet_post').html('');

					if( $(this).val() == '' ){
        				// retonando ao height padrão de 34px
       					$(this).css('height', '34px');
    				}
				});

				// aumenta a altura automaticamente conforme digita ou apaga
				$('#texto_tweet').on('keyup change onpaste', function () {
    				var alturaScroll = this.scrollHeight;
    				var alturaCaixa = $(this).height();

    				if (alturaScroll > (alturaCaixa + 10)) {
        				if (alturaScroll > 500) return;
        				$(this).css('height', alturaScroll);
    				}

    				if( $(this).val() == '' ){
        				// retonando ao height padrão de 34px
       					$(this).css('height', '34px');
    				}
				});

				// associar o evento de click ao botão
				$('#btn_tweet').click( function(){

					// verificando se o campo tweet não está vazio
					if ($('#texto_tweet').val().length > 0) {

						// será enviado a informação do campo tweet para um script php através de uma requisição ajax
						$.ajax({
							url: 'inclui_tweet.php',
							method: 'post',
							// para formulário com vários campos, podemos simplificar a captura de todos com a função jquery serialize
							data: $('#form_tweet').serialize(),
							success: function(data) {

								// limpando o campo tweet caso haja sucesso na requisição
								$('#texto_tweet').val('');

								//alert('Tweet postado com sucesso !');

								// exibirá uma msg de sucesso abaixo do campo
								$('#msg_tweet_post').html('<br>Tweet postado');

								// para que o tweet recém-postado já apareça na timeline sem precisar dar refresh, podemos chamar a função aqui
								atualizaTweet();

								atualizaQtdeTweet();
							}
						});
					}

				});



				function atualizaTweet(){

					// carregar os tweets
					$.ajax({
						url: 'get_tweet.php',
						success: function(data) {

							// caso requisição foi sucesso, será inserido o retorno da requisição na div tweets
							$('#tweets').html(data);

							$('.btn_del_tweet').click( function(){

								// recuperando o valor do atributo customizado
								var id_tweet = $(this).data('id_tweet');

								// enviando para del_tweet.php via requisição
								$.ajax({
									url: 'del_tweet.php',
									method: 'post',

									// será enviado o id do tweet recuperado do botão através de um JSON ({})
									data: { id_tweet: id_tweet },
									success: function(data){
										alert('Tweet removido com sucesso !');
									}
								});

								atualizaTweet();
								atualizaQtdeTweet();

							});
						}
					});


				}

				// atualiza qtde tweets
				function atualizaQtdeTweet() {

					$.ajax({

						url: 'qtde_tweets.php',
						success: function(data) {

							$('#qtde_tweets').html(data);
						}
					})
				}

				// executa a função
				atualizaTweet();
				atualizaQtdeTweet();

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
	            <li>
	            	<!-- botão que irá encerrar a sessão -->
	            	<a href="sair.php">Sair</a></li>
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

	    				<div class="col-md-6" id="qtde_tweets">
	    					TWEETS <br> <!-- é impresso o valor da variavel qtde_tweets -->
	    					<?=$qtde_tweets?>

	    				</div>
	    				<div class="col-md-6">
	    					SEGUIDORES <br> <!-- é impresso o valor da variavel qtde_seguidores -->
	    					<?=$qtde_seguidores?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    	<!-- painel central (onde fica timeline) -->
	    	<div class="col-md-6">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<!-- campo para um post de até 140 caracteres agrupado à um botão de envio com input-group -->
	    				<form id="form_tweet" class="input-group">

	    					<!-- criando um name para uso na função serialize -->
	    					<textarea id="texto_tweet" name="texto_tweet" class="form-control" placeholder="O que está acontecendo agora ?" maxlength="140" style="resize: none; height: 34px; overflow-y: hidden;"></textarea>

	    					<span class="input-group-btn">
	    						<button class="btn btn-default" type="button" id="btn_tweet">Tweet</button>
	    					</span>

	    				</form>
	    				<!-- msg de sucesso -->
	    				<strong id="msg_tweet_post" style="color: green"></strong>
	    			</div>
	    		</div>

	    		<!-- div que conterá a timeline em si, ou seja, a lista de exibição de tweets -->
	    		<div id="tweets" class="list-group">

	    		</div>
			</div>

			<!-- barra da direita -->
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4><a href="procurar_pessoas.php">Procurar por pessoas</a></h4>
					</div>
				</div>
			</div>



		</div>


	    </div>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	</body>
</html>