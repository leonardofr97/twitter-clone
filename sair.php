<?php

session_start();

// eliminando os indices da variavel session, ou seja, encerrando a sessão com o unset que eliminada indices de um array
unset($_SESSION['usuario']);
unset($_SESSION['email']);

echo "Esperamos você de volta em breve !!!";

?>