<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_POST[organizacion];

//Para usuarios
$sqlagt="SELECT * FROM `usuarios` WHERE `idagente` LIKE '".$_POST[agente]."'";
$resultagt= mysql_query ($sqlagt,$db);
$myrowagt=mysql_fetch_array($resultagt);
$agente=$myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre];
$claveagt=$myrowagt[claveagente];
$ingreso=$myrowagt[ingreso];
$trimestre=trimestre(substr($ingreso,5,2));
$anioingreso=substr($ingreso,0,4);

switch ($_POST[o])
{
	case 'I':
		//Insertar minimos y metas
		for($i=1;$i<=4;$i++)
		{
			$min="min_trim_".$trimestre; $anio_min="min_anio_".$anioingreso; $meta="meta_trim_".$trimestre; $anio_meta="meta_anio_".$anioingreso;
			if($_POST[$meta]&&$_POST[$min])
			{
				$sqlmeta="INSERT INTO `cuotas`(`id_cuota`, `clave_agente`, `trimestre`, `anio`, `meta`, `minimo`, `fecha`, `usuario`) VALUES 
		(NULL,'$claveagt','$trimestre','$_POST[$anio_min]','$_POST[$meta]','$_POST[$min]',NOW(),'$claveagente')";
				//Ejecutar las consultas
				mysql_query("SET NAMES 'utf8'");
				mysql_query($sqlmeta,$db);
				//echo $sqlmeta."<br/>";
			}
			$trimestre++;
			if($trimestre>4)
			{
				$anioingreso++;
				$trimestre=1;
			}
		}
		header("Location: http://www.anabiosiscrm.com.mx/premo/modulos/agentes/cuotas.php"); 
		exit;
		break;
	case 'U':
		$sqlupdate="UPDATE `usuarios` SET `tipo`='$_POST[tipo]',`numeroagente`='$_POST[claveagente]',`claveagente`='$_POST[claveagente]',`contrasenia`='$_POST[contrasenia]',`nombre`='$_POST[nombre]',`apellidopaterno`='$_POST[apellidopaterno]',`id_grupofacturacion`='$_POST[id_grupofacturacion]',`id_grupoproducto`='$_POST[id_grupoproducto]',`apellidomaterno`='$_POST[apellidomaterno]',`titulo`='$_POST[titulo]',`puesto`='$_POST[puesto]',`fechanacimiento`='$_POST[date]',`idgrupo`='$grupo',`estatus`='$_POST[estatus]',`telcasa`='$_POST[telcasa]',`teloficina`='$_POST[teloficina]',`extoficina`='$_POST[extoficina]',`nextel`='$_POST[nextel]',`idnextel`='$_POST[idnextel]',`teldirecto`='$_POST[teldirecto]',`email`='$_POST[email]',`emailotro`='$_POST[emailotro]',`claves`='$_POST[claves]' WHERE `idagente` = '".$_POST[usuario]."'";
		//echo $sqlupdate;
		//Ejecutar las consultas
		mysql_query("SET NAMES 'utf8'");
		mysql_query($sqlupdate,$db);
		//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
		header("Location: http://www.anabiosis.com.mx/crm/modulos/agentes/detalles.php?usuario=".urlencode($_POST[usuario])); 
		exit;
		break;
}
?>
