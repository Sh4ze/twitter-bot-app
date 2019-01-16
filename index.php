<?php

# INICIA A SESSION DOS COOKIES
session_start();
# REQUERE A CLASSE AUTOLOAD.PHP
require "vendor/autoload.php";
# API DO TWITTER
use Abraham\TwitterOAuth\TwitterOAuth;
# INCLUE AS CONFIGURACOES SETADAS NO ARQUIVO config.php
include("config.php");

# AO CLICAR NO BOTAO PARA LOGAR COM O TWITTER
if(isset($_POST['login']) !== false){

    # INICIA UMA CONEXAO COM O TWITTER, UTILIZANDO AS SUAS KEYS
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    # RETORNA PARA A CALLBACK URL APOS O TERMINO DA AUTORIZAÇAO
    $callback = CALLBACK_URL;
    $request_token =$connection->oauth('oauth/request_token', array('oauth_callback' => $callback));
    
    # GUARDA O TOKEN NOS COOKIES DO NAVEGADOR DO CARA E REDIRECIONA PRA PAGINA DE AUTORIZAÇÃO
    $_SESSION['oauth_token']        = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    header("Location: $url");

}
?>

<html>
    <body>
        <form action="" method="post">
            <button type="submit" name="login">Login</button>
        </form>
    </body>
</html>
