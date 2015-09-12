<?php

$hostpremo="just114.justhost.com";
$userpremo="premomx1_crm";
$passpremo="xWvG5uCuf_dA";
$basedatospremo="premomx1_drupal";
$dbpremo=mysql_connect($hostpremo, $userpremo, $passpremo);
if (!$dbpremo) 
{ 
die("No se ha podido conectar a la BD: " . mysql_error()); 
} 

date_default_timezone_set("America/Mexico_City");
mysql_query("SET NAMES UTF8");
setlocale(LC_ALL,"es_ES");
mysql_select_db ($basedatospremo,$dbpremo);

?>