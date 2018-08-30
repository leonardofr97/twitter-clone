<?php

// criando a conexão entre o php e o banco de dados
class db {

    // eh preciso os seguintes dados pra criar a conexão

    // host (no caso é o servidor local, usa-se então localhost)
    private $host = 'localhost';

    // usuário de acesso do banco de dados (no caso o root)
    private $usuario = 'root';

    // senha (por padrão a instalação do mysql do phpmyadmin, a senha do root está em branco)
    private $senha = '';

    // banco de dados que será usado
    private $database = 'twitter_clone';

    public function conecta_mysql() {

        // criar a conexão através da função nativa mysqli_connect(localização do bd, usuario de acesso, senha, banco de dados)
        $conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->database);

        // ajustar o charset de comunicação entre a aplicação e o banco de dados
        mysqli_set_charset($conexao, 'utf8');

        // verificar se houve erro de conexão
        if (mysqli_connect_errno()) {
            echo "Erro ao tentar se conectar com o banco de dados MySQL: " . mysqli_connect_error();
            // mysqli_connect_error() retorna a descrição do erro
        }

        //
        return $conexao;

    }

}

?>