<?php
//ini_set('display_errors', true); 
//error_reporting(E_ALL);

//Aqui colocamos o servidor em que está o nosso banco de dados, no nosso exemplo é a conexão com um servidor local, portanto localhost
$server = "localhost";
//Aqui é o nome de usuário do seu banco de dados, root é o servidor inicial e básico de todo servidor, mas recomenda-se não usar o usuario root e sim criar um novo usuário
$userDB = "arm_user";
//Aqui colocamos a senha do usuário, por padrão o usuário root vem sem senha, mas é altamente recomenável criar uma senha para o usuário root, visto que ele é o que tem mais privilégios no servidor
$passwd ="@ttikFsadDS123Bd";
// DB name
$LANG_NAMEDB="attikgrc"; // attikgrc
//$LANG_NAMEDB="attik_grc";
// port of Postgresql
$port="5432";

//String of connection.
$conn = pg_connect("dbname=$LANG_NAMEDB port=$port host=$server user=$userDB password=$passwd");
?>
