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

// recuperando o id do usuario que queremos seguir
$seguir_id_usuario = $_POST['seguir_id_usuario'];

// outra verificação ;b
if ($seguir_id_usuario == '' or $id_usuario == '') {
    die();
}

$objDb = new db();
$link  = $objDb->conecta_mysql();

// preparando a query de insert do registro na tabela usuarios_seguidores
$sql = " INSERT INTO usuarios_seguidores(id_usuario, seguindo_id_usuario) values ($id_usuario, $seguir_id_usuario) ";

// por fim, executamos a query
mysqli_query($link, $sql);

?>