<?php

// para que possamos recuperar os dados de session
session_start();

// verificação de controle
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

// recuperando o id do usuário da sessão (logado)
$id_usuario = $_SESSION['id_usuario'];

// recuperando o id do tweet que queremos excluir
$id_tweet = $_POST['id_tweet'];

// outra verificação ;b
if ($id_tweet == '' or $id_usuario == '') {
    die();
}

$objDb = new db();
$link  = $objDb->conecta_mysql();

// preparando a query de insert do registro na tabela usuarios_seguidores
$sql = " DELETE FROM tweet WHERE id_tweet = $id_tweet ";

// por fim, executamos a query
mysqli_query($link, $sql);

?>