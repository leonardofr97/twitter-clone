<?php

// permite que o script tenha acesso as variáveis de sessão. recomendasse que esteja no inicio do script php
session_start();

require_once 'db.class.php';

// recuperando os dados do form. de login
$usuario = $_POST['usuario'];

// eh necessário a criptografia da senha digitada pelo usuario no login, pois senão seria comparada a senha pura digitada com o hash armazenado no banco de dados e então não seria encontrado o usuário
$senha = md5($_POST['senha']);

// query de consulta o usuário no banco de dados
$sql = " SELECT id, usuario, email FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' ";

$objDb = new db();
$link  = $objDb->conecta_mysql();

// como o retorno de um select é referente á algo externo ao php, eh preciso chamar a função mysqli_fetch_array que irá transformar em array esses dados

$resultado_id = mysqli_query($link, $sql);

// verificando se houve algum erro de sintaxe ou instrução na consulta
if ($resultado_id) {
    $dados_usuario = mysqli_fetch_array($resultado_id);

    // verifica se o usuário existe no bd
    if (isset($dados_usuario['usuario'])) {
        //echo "Usuário existe";

        // atribuí as variaveis de sessão dados do usuário recuperados
        $_SESSION['id_usuario'] = $dados_usuario['id'];
        $_SESSION['usuario']    = $dados_usuario['usuario'];
        $_SESSION['email']      = $dados_usuario['email'];

        // caso exista, redirecionamos o usuário para home.php
        header('Location: home.php');

    } else {
        //echo "Usuário não existe !";

        // caso não exista, será forçado um redirecionamento para a index.php passando um erro por parametro
        header('Location: index.php?erro=1');
    }

    // var_dump($dados_usuario);
} else {
    echo "Erro na execução da consulta, favor entrar em contato com o admin do site";
}

?>