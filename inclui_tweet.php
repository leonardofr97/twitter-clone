<?php

// para que possamos recuperar os dados de session
session_start();

// verificação de controle
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

// recuperando a informação do campo tweet
$texto_tweet = $_POST['texto_tweet'];

// recuperando o id do usuário da sessão (logado)
$id_usuario = $_SESSION['id_usuario'];

// outra verificação ;b
if ($texto_tweet == '' or $id_usuario == '') {
    die();
}

$objDb = new db();
$link  = $objDb->conecta_mysql();

// preparando a query de insert do tweet no BD
$sql = " INSERT INTO tweet(id_usuario, tweet) values ('$id_usuario', '$texto_tweet') ";

// por fim, executamos a query
mysqli_query($link, $sql);

?>