<?php

$host="173.254.28.114";
$user="premomx1_crm";
$pass="xWvG5uCuf_dA";
$basedatos="premomx1_crm";
$dbpremo=mysql_connect($host, $user, $pass);
if (!$dbpremo) 
{ 
die("No se ha podido conectar a la BD: " . mysql_error()); 
} 

date_default_timezone_set("America/Mexico_City");
mysql_query("SET NAMES UTF8");
setlocale(LC_ALL,"es_ES");
mysql_select_db ($basedatospremo,$dbpremo);

?>