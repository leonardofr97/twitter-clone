<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

$id_usuario = $_SESSION['id_usuario'];

$objDb = new db();
$link  = $objDb->conecta_mysql();

// consulta que irá retornar os tweets do usuario da sessão ordenados de forma decrescente (DESC), ou seja, do post mais atual para o mais antigo, e alterando do formato de data padrão para o do BR :), e tbm os tweets dos usuários ao qual o usuario da sessão está seguindo
$sql = " SELECT DATE_FORMAT(t.data_inclusao, '%d %b %Y %T') AS data_inclusao_format, t.tweet, t.id_tweet, t.id_usuario, u.usuario ";
$sql .= " FROM tweet AS t JOIN usuarios AS u ON (t.id_usuario = u.id) ";
$sql .= " WHERE id_usuario = $id_usuario ";

// com a sub-query pegamos todos os usuarios que o user da sessão esta seguindo
$sql .= " OR id_usuario IN ( SELECT seguindo_id_usuario FROM usuarios_seguidores WHERE id_usuario = $id_usuario ) ";
$sql .= " ORDER BY data_inclusao DESC ";

$resultado_id = mysqli_query($link, $sql);

if ($resultado_id) {

    // é usada a forma de guardar vários registros do BD em array com a função mysqli
    while ($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)) {

        // verifica se o registro (tweet) da iteração pertence ao usuario logado, se sim, será incluido o botão excluir tweet
        if ($registro['id_usuario'] == $id_usuario) {

            // para cada registro(tweet) é criado um item do list-group da div
            echo "<div class='list-group-item'>";

            // contendo um cabeçalho onde terá nome do usuário, a data de inclusão do tweet e um botão excluir tweet para os do usuario logado
            echo "<h4 class='list-group-item-heading'>" . $registro['usuario'] . "<small> - " . $registro['data_inclusao_format'] . "</small><button type='button' class='btn btn-default btn-xs btn_del_tweet pull-right' data-id_tweet='" . $registro['id_tweet'] . "'>Excluir Tweet</button></h4>";

            // por fim, um paragrafo onde conterá o tweet em si
            echo "<p class='list-group-item-text' style='font-size: 17px; word-wrap: break-word;'>" . $registro['tweet'] . "</p>";
            echo "</div><br>";

        } else {

            echo "<div class='list-group-item'>";

            echo "<h4 class='list-group-item-heading'>" . $registro['usuario'] . "<small> - " . $registro['data_inclusao_format'] . "</small></h4>";

            echo "<p class='list-group-item-text' style='font-size: 17px;'>" . $registro['tweet'] . "</p>";
            echo "</div><br>";
        }
    }

} else {
    echo "Erro na consulta de tweets no banco de dados !";
}

?>