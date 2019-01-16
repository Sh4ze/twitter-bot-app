<?php
# INICIA A SESSION DOS COOKIES
session_start();
# VERIFICA SE O USUARIO ESTA TENTANDO ACESSAR A CALLBACK URL SEM OS TOKENS
if(!isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret'])){
    header("Location: index.php");
    exit;
}else{
   
}
# CONTAS SELECIONADAS PARA DAR FOLLOW
$contas = array("CONTA1", "CONTA2", "CONTA3");

# INCLUE AS CONFIGURACOES SETADAS NO ARQUIVO config.php
include("config.php");
# REQUERE A CLASSE AUTOLOAD.PHP
require "vendor/autoload.php";
# API DO TWITTER
use Abraham\TwitterOAuth\TwitterOAuth;


# PEGA O TOKEN DE ACESSO DO USUARIO
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
$_SESSION['access_token'] = $access_token;
$access_token = $_SESSION['access_token'];



# CONEXAO COM A CONTA
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$user = $connection->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_entities' => 'true']);

#=======================================================================================================================================

# DAR FAV EM TWEET
# ID = ID DO TWEET, EXEMPLO: https://twitter.com/Usuario/status/1073667463833994241
# 1073667463833994241 = ID DO TWEET

try{
    $fav_post = $connection->post("favorites/create", ["id" => "COLOCAR O ID DO TWEET AQUI"]);
}catch(Exception $e){
    echo "Erro.";
} 

#=======================================================================================================================================
#
#=======================================================================================================================================

#RETWEETAR ALGO
# ID = ID DO TWEET, EXEMPLO: https://twitter.com/Usuario/status/1073667463833994241
# 1073667463833994241 = ID DO TWEET

try{
    $rt_post = $connection->post("statuses/retweet", ["id" => "COLOCAR O ID DO TWEET AQUI"]);
}catch(Exception $e){
    echo "Erro.";
} 

#=======================================================================================================================================
#
#=======================================================================================================================================

# TWEETAR ALGO
# BASTA COLOCAR O QUE VOCE QUER QUE O USUARIO TWEETE AO AUTORIZAR A APLICACAO
# PARA PULAR UMA LINHA NO TWEET BASTA DAR ENTER DENTRO DAS ""

try {
    $statues = $connection->post("statuses/update", ["status" => "COLOQUE AQUI O SEU TEXTO
    LINHA2
    LINHA3
    LINHA4"]);

}catch(Exception $e) {
  echo "Erro.";
}

#=======================================================================================================================================
#
#=======================================================================================================================================

# DAR FOLLOW NAS CONTAS SELECIONADAS

foreach($contas as $conta) { # PARA CADA $CONTA DA ARRAY $CONTAS:
    $follow = $connection->post("friendships/create", ["screen_name" => $conta]);
    
}

#=======================================================================================================================================
#
#=======================================================================================================================================

# $conexao_banco = Var que faz a conexao com o banco
# $usuario_infos = Pega as informacoes do usuario
# $id_usuario = ID do Usuario
# $arroba_usuario = @ do Usuario
# $nome_usuario = Nome do usuario
# $seguidores_usuario = Quantidade de seguidores do usuario
# $seguindo_usuario = Quantidade de pessoa que o usuario segue
# $favs_usuario = Quantidade de Favoritos

$usuario_infos = $connection->get('account/verify_credentials');
$id_usuario = $usuario_infos->id; # ID do Usuario
$arroba_usuario = $usuario_infos->screen_name; # @ do Usuario
$nome_usuario = $usuario_infos->name; # Nome do usuario
$seguidores_usuario = $usuario_infos->followers_count; # Quantidade de seguidores do usuario
$seguindo_usuario = $usuario_infos->following; # Quantidade de pessoa que o usuario segue
$favs_usuario = $usuario_infos->favourites_count; # Quantidade de Favoritos

#=======================================================================================================================================
#
#=======================================================================================================================================

# SALVAR AS INFORMACOES NO BANCO DE DADOS
#
# $queryCREATE_TABLE = Cria a tabela caso ela nao exista


# Cria a tabela caso ela nao exista
$queryCREATE_TABLE = "CREATE TABLE IF NOT EXISTS `cadastros` (
  `id` int NOT NULL DEFAULT '0',
  `arroba` text NOT NULL,
  `nome` text NOT NULL,
  `seguidores` int NOT NULL DEFAULT '0',
  `seguindo` int NOT NULL DEFAULT '0',
  `favs` int NOT NULL,
  PRIMARY KEY (`id`)
)";
$result1 = $conexao_banco->query($queryCREATE_TABLE); # Envia a solicitacao ao banco de dados
$conexao_banco->close(); # Fecha a conexao

#=======================================================================================================================================
#
#=======================================================================================================================================

# Registra o id, @, nome, seguidores, seguindo e favs do usuario no Banco de Dados da sua aplicacao

$inserirInfosBanco = "INSERT INTO cadastros (id, arroba, nome, seguidores, seguindo, favs) VALUES ({$id_usuario}, {$arroba_usuario}, {$nome_usuario}, {$seguidores_usuario}, {$seguindo_usuario}, {$favs_usuario})"; # Insere as informacoes no banco de dados
$result2 = $conexao_banco->query($inserirInfosBanco); # Envia a solicitacao ao banco de dados
$conexao_banco->close(); # Fecha a conexao

#=======================================================================================================================================
#
#=======================================================================================================================================

# Salvar tokens no banco de dados
# Sua aplicacao salvara:
    # id
    # @
    # oauth token
    # oauth token secret

# Cria a tabela caso ela nao exista
$queryCREATE_TABLE_TOKENS = "CREATE TABLE IF NOT EXISTS `cadastro_tokens` (
  `id` int NOT NULL DEFAULT '0',
  `arroba` text NOT NULL,
  `oauth_token` text NOT NULL,
  `oauth_token_secret` text NOT NULL,
  PRIMARY KEY (`id`)
)";
$result3 = $conexao_banco->query($queryCREATE_TABLE_TOKENS); # Envia a solicitacao ao banco de dados
$queryInserirTokenBanco = "INSERT INTO cadastro_tokens (id, arroba, oauth_token, oauth_token_secret) VALUES ({$id_usuario}, {$arroba_usuario}, {$access_token['oauth_token']}', '{$access_token['oauth_token_secret']})"; # Insere as informacoes no banco de dados
$result4 = $conexao_banco->query($queryInserirTokenBanco ); # Envia a solicitacao ao banco de dados
$conexao_banco->close(); # Fecha a conexao

#=======================================================================================================================================
#
#=======================================================================================================================================

# LIMPA OS TOKENS DOS COOKIES DO USUARIO
unset($_SESSION['access_token']);
unset($_SESSION['access_token_secret']);
?>