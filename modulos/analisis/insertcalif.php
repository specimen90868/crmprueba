<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_POST[organizacion];

$sqlanalisis="SELECT * FROM analisis WHERE id_analisis='".$_POST[an]."'";
$rsanalisis= mysql_query($sqlanalisis,$db);
$rwanalisis=mysql_fetch_array($rsanalisis);

$sqlcalificacion="INSERT INTO `calficacionanalisis`(`id_calificacionanalisis`, `id_analisis`, `clave_analisis`, `id_oportunidad`, `clave_organizacion`, `calificacion_poderes`, `calificacion_historial`, `calificacion_flujo`, `calificacion_garantia`, `calificacion_listas`, `calificacion_final`, `usuario`, `fecha_evaluacion`, `usuario_modificacion`, `fecha_modificacion`) VALUES (NULL,'$_POST[an]','$rwanalisis[clave_analisis]','$_POST[id]','$claveorganizacion','$_POST[poderes]','$_POST[historial]','$_POST[flujo]','$_POST[garantia]','$_POST[listas]','$_POST[final]','$claveagente','NOW()','$claveagente',NOW())";
mysql_query ($sqlcalificacion,$db);

//header("Location: http://www.anabiosiscrm.com.mx/premo/modulos/organizaciones/detalles.php?organizacion=".urlencode($claveorganizacion));
header("Location: http://crm.premo.mx/index.php"); 
exit;
?>
