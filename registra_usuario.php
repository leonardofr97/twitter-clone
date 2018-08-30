<?php

// importando a classe de conexão com o MySQL
require_once 'db.class.php';

// recuperando os dados do formulário de cadastro
$usuario = $_POST['usuario'];
$email   = $_POST['email'];

// será feita a criptografia MD5 da senha, converte em um hash de 32 caracteres que não poderam ser descriptografados (mão única)
$senha = md5($_POST['senha']);

// atribuindo uma instância da classe db
$objDb = new db();

// variavel recebe o retorno da função de conexão
$link = $objDb->conecta_mysql();

// variavel de controle
$usuario_existe = false;
$email_existe   = false;

// verifica se o usuário já existe
$sql = " select * from usuarios where usuario = '$usuario' ";

// não há problema algum em atribuir um valor à uma variavel dentro da condição do if
if ($resultado_id = mysqli_query($link, $sql)) {

    // armazenando o retorno da consulta em array
    $dados_usuario = mysqli_fetch_array($resultado_id);

    //var_dump($dados_usuario);

    // verifica se o indice usuário existe
    if (isset($dados_usuario['usuario'])) {
        //echo "Usuário já cadastrado !";

        $usuario_existe = true;
    }
    /*
else {

//
echo "Usuário não cadastrado, ok, pode cadastrar :)";
} */

} else {
    echo "Erro ao tentar localizar o registro de usuário";
}

// verifica se o email já existe
$sql = " select * from usuarios where email = '$email' ";

// não há problema algum em atribuir um valor à uma variavel dentro da condição do if
if ($resultado_id = mysqli_query($link, $sql)) {

    // armazenando o retorno da consulta em array
    $dados_usuario = mysqli_fetch_array($resultado_id);

    //var_dump($dados_usuario);

    // verifica se o indice email existe
    if (isset($dados_usuario['email'])) {
        //echo "Email já cadastrado !";

        $email_existe = true;
    }
    /*
else {

//
echo "Email não cadastrado, ok, pode cadastrar :)";
} */

} else {
    echo "Erro ao tentar localizar o registro de email";
}

// caso já exista usuario ou email, será impedido avançar com o cadastro, forçando o redirecionamento pra própria inscrevase.php retornando erro(s)
if ($usuario_existe or $email_existe) {
    $retorno_get = '';

    // passa os erros pela url
    if ($usuario_existe) {

        // '&' é um delimitador que separa as variaveis e os valores passados pela url
        $retorno_get .= "erro_usuario=1&";
    }

    if ($email_existe) {
        $retorno_get .= "erro_email=1&";
    }

    // por fim redireciona passando erro(s)
    header('Location: inscrevase.php?' . $retorno_get);

    die();
}

// interrompe a execução do script a partir desse ponto
//die();

// criando a query de insert de usuários no BD
$sql = " insert into usuarios (usuario, email, senha) values ('$usuario', '$email', '$senha') ";

// executar a query ( mysqli_query(o link de conexão com o bd, a query em si) );
if (mysqli_query($link, $sql)) {
    echo "Usuário cadastrado com sucesso !";
} else {
    echo "Erro ao registrar o usuário !";
}

?>