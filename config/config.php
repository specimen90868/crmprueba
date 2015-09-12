<?php

//Conexión a omnis
/*$hostanabiosis="minia001.mysql.guardedhost.com";
$useranabiosis="minia001_premo";
$passanabiosis="3kX96tQd";*/

$hostanabiosis="minia001.mysql.guardedhost.com";
$useranabiosis="minia001_redwolf";
$passanabiosis="N@m8yf2b4E";
$basedatosanabiosis="minia001_premo";
$dbanabiosis=mysql_connect($hostanabiosis, $useranabiosis, $passanabiosis);
if (!$dbanabiosis) 
{ 
die("No se ha podido conectar a la BD: " . mysql_error()); 
}
$dbanabiosis=mysql_connect($hostanabiosis, $useranabiosis, $passanabiosis);
date_default_timezone_set("America/Mexico_City");
date_default_timezone_set("America/Los_Angeles");
mysql_query("SET NAMES UTF8");
mysql_query("SET SESSION time_zone = '-6:00'"); 
setlocale(LC_ALL,"es_ES");
mysql_select_db ($basedatosanabiosis,$dbanabiosis);

//Conexión a justhost
$host="localhost";
$user="premomx1_crm";
$pass="xWvG5uCuf_dA";
$basedatos="premomx1_crm";

$db=mysql_connect($host, $user, $pass);
if (!$db) 
{ 
die("No se ha podido conectar a la BD: " . mysql_error()); 
} 


$db=mysql_connect($host, $user, $pass);
date_default_timezone_set("America/Mexico_City");
date_default_timezone_set("America/Los_Angeles");
mysql_query("SET NAMES UTF8");
mysql_query("SET SESSION time_zone = '-6:00'"); 
setlocale(LC_ALL,"es_ES");
mysql_select_db ($basedatos,$db);

?>