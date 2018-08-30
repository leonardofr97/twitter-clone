<?php

// trabalhando com o retorno de n registros do BD com o msqli_fetch_array

require_once 'db.class.php';

$sql = " SELECT * FROM usuarios ";

$objDb = new db();
$link  = $objDb->conecta_mysql();

$resultado_id = mysqli_query($link, $sql);

if ($resultado_id) {
    $dados_usuario = array();

    // usando o while para armazenar cada registro no array dados_usuario, MYSQLI_ASSOC é um parametro que recupera somente com indices associativos (no caso, o nome dos campos), não indices numericos
    while ($linha = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)) {
        $dados_usuario[] = $linha;
    }

    // para exibir o conteúdo do array
    foreach ($dados_usuario as $usuario) {
        //var_dump($usuario);
        //var_dump($usuario['email']);
        echo $usuario['email'];
        echo "<br><br>";
    }
} else {
    echo "Erro na execução da consulta, favor entrar em contato com o admin do site";
}

?>