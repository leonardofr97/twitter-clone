<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?erro=1');
}

require_once 'db.class.php';

// recupera a informação digitada no campo de pesquisa nome_pessoa
$nome_pessoa = $_POST['nome_pessoa'];
$id_usuario  = $_SESSION['id_usuario'];

$objDb = new db();
$link  = $objDb->conecta_mysql();

// consulta que irá retornar os usuarios de acordo, que sejam parecido ou que contenham parte do nome com o digitado no campo de pesquisa, isso eh feito com a ajuda do 'like' e o coringa '%', e que o id seja diferente do id do usuario da sessão, isso evita que seja exibido o próprio usuário da sessão no resultado da procura e com um join iremos consultar se o usuario esta sendo seguido por alguem
$sql = " SELECT u.*, us.*
FROM usuarios AS u
LEFT JOIN usuarios_seguidores AS us
ON ( us.id_usuario = $id_usuario AND u.id = us.seguindo_id_usuario )
WHERE u.usuario like '%$nome_pessoa%' AND u.id <> $id_usuario ";

$resultado_id = mysqli_query($link, $sql);

if ($resultado_id) {

    // é usada a forma de guardar vários registros do BD em array com a função mysqli
    while ($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)) {

        // para cada registro(usuário) é criado um item do list-group da div
        echo "<a href='#' class='list-group-item'>";

        // será exibido o nome e o email do usuario
        echo "<strong>" . $registro['usuario'] . "</strong> <small> - " . $registro['email'] . "</small>";

        // cria um botão de seguir que flutuará a direita com o pull-right
        echo "<p class='list-group-item-text pull-right'>";

        // verificamos se o usuario contém o campo id_usuario_seguidor, ou seja se esta sendo seguido
        $esta_seguindo_usuario_sn = isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor']) ? 'S' : 'N';

        // variáveis auxiliares
        $btn_seguir_display        = 'block';
        $btn_deixar_seguir_display = 'block';

        // caso não estiver seguindo o botão deixar seguir recebe display:none (eh ocultado)
        if ($esta_seguindo_usuario_sn == 'N') {
            $btn_deixar_seguir_display = 'none';
        }

        // caso contrário, se for 'S', o botão seguir que será ocultado
        else {
            $btn_seguir_display = 'none';
        }

        // precisamos de um id pros botões que seja único para cada botao de cada usuário, podemos então concatenar o id do botão com o id do usuário já que o msm é uma informação única

        // criando um atributo personalizado em html com o prefixo 'data-' que irá recuperar o id do usuário que deseja seguir
        echo "<button type='button' id='btn_seguir_" . $registro['id'] . "' style='display: " . $btn_seguir_display . "' class='btn btn-default btn_seguir' data-id_usuario='" . $registro['id'] . "'>Seguir</button>";

        // os botões serão exibidos ou não de acordo com o valor da variavel $btn_seguir_display e $btn_deixar_seguir_display
        echo "<button type='button' id='btn_deixar_seguir_" . $registro['id'] . "' style='display: " . $btn_deixar_seguir_display . "' class='btn btn-primary btn_deixar_seguir' data-id_usuario='" . $registro['id'] . "'>Deixar de Seguir</button>";
        echo "</p>";

        // clearfix para corrigir o espaçamento do botão
        echo "<div class='clearfix'></div>";
        echo "</a>";
    }

} else {
    echo "Erro na consulta de usuários no banco de dados !";
}

?>