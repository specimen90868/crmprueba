<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
if($_POST[min_actividad]){$minutos=$_POST[min_actividad];}else{$minutos="00";}
$hora=$_POST[hora_actividad].":".$minutos.":00";
$claveagente=$_SESSION[Claveagente];
if($_POST[organizacion])
{
	$claveorganizacion=$_POST[organizacion];
}
else
{
	if($_POST[oportunidad])
	{
		$sqlopt="SELECT * FROM `oportunidades` WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
		$resultopt= mysql_query($sqlopt,$db);
		while($myrowopt=mysql_fetch_array($resultopt))
		{
			$claveorganizacion=$myrowopt[clave_organizacion];
			$oportunidad=$myrowopt[nombre_oportunidad];
		}
	}
	else
	{
		$claveorganizacion=$_POST[selOrganizacion];
	}
}

$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` = '".$claveorganizacion."'";
$resultorg= mysql_query($sqlorg,$db);
while($myroworg=mysql_fetch_array($resultorg))
{
	$organizacion=$myroworg[organizacion];
}

//Definir Location
$i_header="Location: http://crm.premo.mx/";
switch($_POST[a])
{
	case 'D'://Se accedio desde el Dashboard
		$i_header.="index.php";
		break;
	case 'O'://Se accedio desde el detalle de Organización
		$i_header.="modulos/organizaciones/detalles.php";
		break;
	case 'C'://Se accedio desde el Calendario
		$i_header.="modulos/actividades/calendario.php";
		break;
	case 'A'://Se accedio desde la lista de Actividades
		$i_header.="modulos/actividades/actividades.php";
		break;
	case 'oA'://Se accedio desde la lista de Actividades
		$i_header.="modulos/organizaciones/actividades.php";
		break;		
}
switch ($_POST[o])
{
	case 'I':
		$claveoportunidad = generateKey();
		if($_POST[promotor]=='0'||$_POST[promotor]=="")
		{
			$promotor=$claveagente; 
			if($_SESSION["Tipo"]=="Promotor")
			{
				$asignado=1;
			} 
			else
			{
				$asignado=0;
			}
		}
		else
		{
			$promotor=$_POST[promotor]; $asignado=1;
			$sqlupdateorg= "UPDATE `organizaciones` SET `clave_agente`='$promotor', `modifico`='$claveagente',`fecha_modificacion`=NOW(),`hora_modificiacion`=NOW(),`asignado`='1' WHERE clave_organizacion = '".$claveorganizacion."'";
			mysql_query($sqlupdateorg,$db);//Asignar contacto si se asigna el proceso de crédito (oportunidad) a un promotor
			//Enviar mail
			$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$promotor."'";
			$rspromotor= mysql_query($sqlpromotor,$db);
			$rwpromotor=mysql_fetch_array($rspromotor);
			$headers = "MIME-Version: 1.1\n";
			$headers .= "Content-type: text/plain; charset=UTF-8\n";
			$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
			$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
			$cuerpo = "Hola ".$rwpromotor[nombre]." el usuario ".$claveagente." te ha asignado ".$contactos." nuevo(s) contacto(s), no olvides darles seguimiento \n\n";
			$cuerpo .= "\nAdministrador del CRM";
			$asunto = $rwpromotor[nombre]." Tienes nuevos contactos asignados en el sistema";
			//mail("dmedina@am.com.mx",$asunto,$cuerpo,$headers);
			mail($rwpromotor[email],$asunto,$cuerpo,$headers);				
		}
		
		if (isset($_POST[completar]))
		{
			$sqlact="INSERT INTO `actividades` 
(`id_actividad`, `tipo_registro`, `id_oportunidad`, `oportunidad`, `clave_organizacion`, `organizacion`, `id_contacto`, `contacto`, `fecha`, `hora`, `duracion`, `fecha_final`, `alarma`, `fecha_alarma`, `hora_alarma`, `tipo`, `subtipo`, `descripcion`, `color`, `resultado`, `usuario`, `fecha_realizada`, `hora_realizada`, `duracion_realizada`, `usuario_realizo`, `usuario_capturo`, `fecha_captura`, `hora_captura`, `completa`, `estatus`) VALUES 
(NULL, 'A', '$_POST[oportunidad]', '$oportunidad', '$claveorganizacion', '$organizacion', '', '', '$_POST[date]', '$hora', '', '', '', '', '', '$_POST[tipo_actividad]', '$_POST[actividad]', '$_POST[descripcion]', '$_POST[color1]', '$_POST[resultado]', '$promotor', NOW(), NOW(), '', '$claveagente', '$claveagente', NOW(), NOW(), '1', '')";
			$sqlupdateorg="UPDATE `organizaciones` SET `fecha_ultimo_contacto` = NOW(), `hora_ultimo_contacto`= NOW() WHERE `clave_organizacion` = '$claveorganizacion'";
		}
		else
		{
			$sqlact="INSERT INTO `actividades` 
(`id_actividad`, `tipo_registro`, `id_oportunidad`, `oportunidad`, `clave_organizacion`, `organizacion`, `id_contacto`, `contacto`, `fecha`, `hora`, `duracion`, `fecha_final`, `alarma`, `fecha_alarma`, `hora_alarma`, `tipo`, `subtipo`, `descripcion`, `color`, `resultado`, `usuario`, `fecha_realizada`, `hora_realizada`, `duracion_realizada`, `usuario_realizo`, `usuario_capturo`, `fecha_captura`, `hora_captura`, `completa`, `estatus`) VALUES 
(NULL, 'A', '$_POST[oportunidad]', '$oportunidad', '$claveorganizacion', '$organizacion', '', '', '$_POST[date]', '$hora', '', '', '', '', '', '$_POST[tipo_actividad]', '$_POST[actividad]', '$_POST[descripcion]', '$_POST[color1]', '', '$promotor', '', '', '', '', '$claveagente', NOW(), NOW(), '2', '')";
			//Enviar mail
			if($promotor!=$claveagente)
			{
				$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$promotor."'";
				$rspromotor= mysql_query($sqlpromotor,$db);
				$rwpromotor=mysql_fetch_array($rspromotor);
				$headers = "MIME-Version: 1.1\n";
				$headers .= "Content-type: text/plain; charset=UTF-8\n";
				$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
				$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
				$cuerpo = "Hola ".$rwpromotor[nombre]." el usuario ".$claveagente." te ha asignado actividades, no olvides darles seguimiento \n\n";
				$cuerpo .= "\nAdministrador del CRM";
				$asunto = $rwpromotor[nombre]." Tienes nuevas actividades asignadas en el sistema";
				//mail("dmedina@am.com.mx",$asunto,$cuerpo,$headers);
				mail($rwpromotor[email],$asunto,$cuerpo,$headers);
			}
			//echo $sqlact;
		}
		mysql_query($sqlact,$db);
		mysql_query($sqlupdateorg,$db);
		header($i_header."?organizacion=".urlencode($claveorganizacion)); 
		exit;
		break;
	case 'U':
		if (isset($_POST[completar]))
		{
			$sqlact = "UPDATE `actividades` SET `id_oportunidad` = '$_POST[oportunidad]', `oportunidad` = '$oportunidad', `fecha` = '$_POST[date]', `hora` = '$hora', `tipo` = '$_POST[tipo_actividad]', `subtipo` = '$_POST[nombre_actividad]', `descripcion` = '$_POST[descripcion]', `color` = '$_POST[color1]', `usuario` = '$claveagente', `usuario_realizo` = '$claveagente', `usuario_capturo` = '$claveagente', `completa`='1', `resultado`='$_POST[resultado]', `fecha_realizada`= NOW(), `hora_realizada`=NOW(), `usuario_realizo`='$claveagente' WHERE `id_actividad` = '".$_POST[actividad]."'";
			$sqlupdateorg="UPDATE `organizaciones` SET `fecha_ultimo_contacto` = NOW(), `hora_ultimo_contacto`= NOW() WHERE `clave_organizacion` = '$claveorganizacion'";
		}
		else
		{
			$sqlact = "UPDATE `actividades` SET `id_oportunidad` = '$_POST[oportunidad]', `oportunidad` = '$oportunidad', `fecha` = '$_POST[date]', `hora` = '$hora', `tipo` = '$_POST[tipo_actividad]', `subtipo` = '$_POST[nombre_actividad]', `descripcion` = '$_POST[descripcion]', `color` = '$_POST[color1]', `usuario` = '$claveagente', `usuario_realizo` = '$claveagente', `usuario_capturo` = '$claveagente' WHERE `id_actividad` = '".$_POST[actividad]."'";
		}
		mysql_query($sqlact,$db);
		mysql_query($sqlupdateorg,$db);
		header($i_header."?organizacion=".urlencode($claveorganizacion)); 
		exit;
		break;
	case 'C':
		foreach($_POST['Seleccionados'] as $actividad)
		{
			$sqlact="UPDATE `actividades` SET `completa`='1' WHERE `id_actividad`='".$actividad."'";
			mysql_query($sqlact,$db);
		}
		header($i_header."?organizacion=".urlencode($claveorganizacion)); 
		exit;
		break;
}
?>
