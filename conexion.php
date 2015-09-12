<?php

$dbuser="minia001_premo";
$dbpass="3kX96tQd";
$dbname="minia001_premo";
$chandle = mysql_connect("67.212.178.162", $dbuser, $dbpass) or die("Error conectando a la BBDD");
echo "Conectado correctamente";
mysql_select_db($dbname, $chandle) or die ($dbname . " Base de datos no encontrada." . $dbuser);
echo "Base de datos " . $database . " seleccionada";
mysql_close($chandle);

?>