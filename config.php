<?php 
//CONFIGURAÇÃO DO BOT
define('HOST', ""); // HOST DO SEU BANCO DE DADOS
define('USER', ""); // USUARIO DO BANCO DE DADOS
define('PASS', ""); // SENHA DO BANCO DE DADOS
define('DB', ""); // BANCO DE DADOS QUE IRÁ SALVAR TUDO
define("CONSUMER_KEY", ""); // KEY DO SEU APP
define("CONSUMER_SECRET", "");// OUTRA KEY DO SEU APP
define("CALLBACK_URL", ""); // CALLBACK URL QUE VOCE SETOU NO TWITTER

#CONEXAO COM O BANCO DE DADOS
$conexao_banco = mysqli_connect(HOST, USER, PASS, DB) or die("A conexao com o Banco de Dados falhou!");

?>