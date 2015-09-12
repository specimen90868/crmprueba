<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];

$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

$claveagente=$_SESSION[Claveagente];

$sqlnota="SELECT * FROM notas WHERE id_nota='$_GET[nota]'";
$rsnota= mysql_query ($sqlnota,$db);	
while($rwnota=mysql_fetch_array($rsnota))
{
	$nota=$rwnota[nota];
	$usuario=$rwnota[usuario_captura];
	$fecha=$rwnota[fecha_captura];
}
//ACTUALIZACIÓN DE ORGANIZACION//
if($_POST[operacion]=='U')
{
	$sqlupdate="UPDATE `notas` SET `nota`='$_POST[texto_nota]' WHERE `id_nota`='$_POST[nota]'";
}
else
{
	$sqlupdate="INSERT INTO `notas`(`id_nota`, `nota`, `id_oportunidad`, `fecha_captura`, `usuario_captura`) VALUES (NULL,'$_POST[texto_nota]','$_POST[oportunidad]',NOW(),'$claveagente')";
}
//echo $sqlorg;
mysql_query ($sqlupdate,$db);
echo "<p align='center' style='font-family:Arial;'>La nota ha sido actualizada</p>";
header("Location: http://crm.premo.mx/modulos/oportunidades/editarregistro.php?organizacion=".urlencode($claveorganizacion)); 
exit;
?>
