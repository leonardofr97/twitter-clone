<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

$id_usuario = $_SESSION['id_usuario'];

$objDb = new db();
$link  = $objDb->conecta_mysql();

// consulta que retorna os seguidores do usuario logado
$sql = " SELECT u.*, us.* FROM usuarios AS u
         LEFT JOIN usuarios_seguidores AS us
         ON ( u.id = us.id_usuario )
         WHERE us.seguindo_id_usuario = $id_usuario ";

$resultado_id = mysqli_query($link, $sql);

if ($resultado_id) {

    echo "<br><h4>Seguidores</h4><br>";
    // é usada a forma de guardar vários registros do BD em array com a função mysqli
    while ($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)) {

        // para cada registro(usuário) é criado um item do list-group da div
        echo "<a href='#' class='list-group-item'>";

        // será exibido o nome e o email do usuario
        echo "<strong>" . $registro['usuario'] . "</strong><br> <small> " . $registro['email'] . "</small>";

        // clearfix para corrigir o espaçamento do botão
        echo "<div class='clearfix'></div>";
        echo "</a><br>";
    }

} else {
    echo "Erro na consulta de usuários no banco de dados !";
}

?>