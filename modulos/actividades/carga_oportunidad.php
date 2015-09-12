<?php
//Creado por Cesar Walter Gerez en Micodigobeta.com.ar
//A manera de ejemplo solo lo realizo con array, pero para que realmente sea dinamico se debería traer las opciones de una base de datos.
include("../../config/config.php");

$Organizacion= $_REQUEST["id"];
//realizamos la consulta
$SQL = "SELECT * FROM oportunidades WHERE clave_organizacion='".$Organizacion."'";

$rsCons = mysql_query($SQL, $db) or die(mysql_error());
$cantReg = mysql_num_rows($rsCons);

if ($cantReg > 0)
{
	echo "<option selected value='0'>[Elige]</option>";
	//el bucle para cargar las opciones
	while ($rsReg = mysql_fetch_assoc($rsCons))
	{
		echo "<option value=".$rsReg["id_oportunidad"].">".$rsReg["nombre_oportunidad"] ."</option>";
	}
}


?>