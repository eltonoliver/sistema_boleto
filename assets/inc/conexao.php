<?php
/**
* Este arquivo faz a conexao com o banco de dados.
*/


$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "clientec_boletos";

$conexao = mysql_connect($servidor, $usuario, $senha);
mysql_select_db($banco, $conexao) or die(mysql_error());

?>