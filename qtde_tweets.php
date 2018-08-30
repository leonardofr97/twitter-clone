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

    // exibe na div qtde_tweets
    echo "TWEETS <br>$qtde_tweets";

} else {
    echo "Erro ao executar a query";
}

?>