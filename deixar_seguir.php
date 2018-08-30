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

// recuperando o id do usuario que queremos deixar de seguir
$deixar_seguir_id_usuario = $_POST['deixar_seguir_id_usuario'];

// outra verificação ;b
if ($deixar_seguir_id_usuario == '' or $id_usuario == '') {
    die();
}

$objDb = new db();
$link  = $objDb->conecta_mysql();

// preparando a query de delete do registro na tabela usuarios_seguidores, onde na coluna id_usuario seja igual ao id do usuario da sessão e que o id da coluna seguindo_id_usuario seja igual ao id que recuperamos via post do botão
$sql = " DELETE FROM usuarios_seguidores WHERE id_usuario = $id_usuario AND seguindo_id_usuario = $deixar_seguir_id_usuario ";

// por fim, executamos a query
mysqli_query($link, $sql);

?>