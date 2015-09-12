<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

//print_r ($_POST);
$campos=0; $con=0; $tel=0; $cor=0; $web=0; $dom=0; $rfc=0;

foreach($_POST as $nombre_campo => $valor)
{ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion);
   $tipo=substr($nombre_campo,0,3);
   switch ($tipo)
   {
	case 'CON':
			$con++;
			break;
	case 'TEL':
			$tel++;
			break;
	case 'COR':
			$cor++;
			break;
	case 'WEB':
			$web++;
			break;	
	case 'DOM':
			$dom++;
			break;
	case 'RFC':
			$rfc++;
			break;					
   }
   $campos++;
	
}

$con=$con/9; $tel=$tel/2; $cor=$cor/1; $web=$web/2; $dom=$dom/5; $rfc=$rfc/2;

//Construir las sentencias sql para la inserción de registros de ORGANIZACIONES
$claveorganizacion = generateKey();

/*if($_POST[ORGpromotor]=='0')
{
	$promotor=$claveagente; 
	$asignado=0;
}
else
{*/
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
	$promotor=$_POST[ORGpromotor];
	$asignado=1;
	if($_SESSION["Tipo"]!="Promotor")
	{
		$asignado=0;
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
}

$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
(NULL , '$claveorganizacion', 'O', '$_POST[ORGorganizacion]', '$_POST[ORGclave]', '$_POST[ORGfundacion]', '$_POST[ORGprocedencia]', '$_POST[ORGtipo_organizacion]', '$promotor', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', '$_POST[ORGtipo_persona]', '$_POST[ORGclave_web]', '$_POST[ORGforma_contacto]', '$asignado')";
//echo $sqlorganizaciones;

$sqlcontactos="";
$sqlcontactos='INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES ';

$sqltelcontactos="";
$sqltelcontactos='INSERT INTO `telefonos` (`id_telefono`, `tipo_registro`, `clave_registro`, `tipo_telefono`, `telefono`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

$sqlemailcontactos="";
$sqlemailcontactos='INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

//Construir las sentencias sql para la inserción de registros de CONTACTOS
for($c=1;$c<=$con;$c++)
{
	$clavecontacto = generateKey();
	//Contactos
	$vCONtitulo="$"."CONtitulo".$c;
	eval("\$vCONtitulo = \"$vCONtitulo\";");
	$vCONnombre="$"."CONnombre".$c."$"."CONapellido".$c."$"."CONpuesto".$c;
	eval("\$vCONnombre = \"$vCONnombre\";");
	//Teléfonos de contactos
	$vCONcelular="$"."CONcelular".$c;
	eval("\$vCONcelular = \"$vCONcelular\";");
	$vCONrep_legal="$"."CONrep_legal".$c;
	eval("\$vCONrep_legal = \"$vCONrep_legal\";");
	$vCONdianac="$"."CONdianac".$c;
	eval("\$vCONdianac = \"$vCONdianac\";");
	$vCONmesnac="$"."CONmesnac".$c;
	eval("\$vCONmesnac = \"$vCONmesnac\";");
	if($vCONnombre!="")
	{
	$sqlcontactos .= "(NULL, '$clavecontacto', 'C', '$ORGclave', '$claveorganizacion', '$ORGorganizacion','$"."CONapellido".$c."', '$"."CONnombre".$c."', '', '$"."CONrep_legal".$c."', '', '', '', '', '$"."CONdianac".$c."', '$"."CONmesnac".$c."', '','$"."CONpuesto".$c."', '', '$"."CONdirecto".$c."', '$"."CONcelular".$c."', '', '', '$"."CONtitulo".$c."', '', '', '', '', '', '', '$ORGtipo_organizacion', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($c<$con) $sqlcontactos .= ",";
	//echo $sqlcontactos;
	}
	else
	{
		$sqlcontactos="";
	}
	//Correos de contactos
	$vCONemail="$"."CONemail".$c;
	eval("\$vCONemail = \"$vCONemail\";");
	if($vCONemail)
	{
	$sqlemailcontactos .= "(NULL, 'C', '$clavecontacto', 'Trabajo', '$"."CONemail".$c."', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($c<$con) $sqlemailcontactos .= ",";
	}
}

$sqltelorganizaciones="";
$sqltelorganizaciones='INSERT INTO `telefonos` (`id_telefono`, `tipo_registro`, `clave_registro`, `tipo_telefono`, `telefono`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

$sqlemailorganizaciones="";
$sqlemailorganizaciones='INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

$sqlweborganizaciones="";
$sqlweborganizaciones='INSERT INTO `direccionesweb` (`id_direccionweb`, `tipo_registro`, `clave_registro`, `tipo_direccion`, `direccion`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

$sqldomorganizaciones="";
$sqldomorganizaciones='INSERT INTO `domicilios` (`id_domicilio`, `tipo_registro`, `clave_registro`, `tipo_domicilio`, `domicilio`, `ciudad`, `estado`, `pais`, `cp`, `latitud`, `longitud`, `mapa`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

$sqlrfcorganizaciones="";
$sqlrfcorganizaciones='INSERT INTO `razonessociales` (`id_razonsocial`, `tipo_registro`, `clave_registro`, `razon_social`, `rfc`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES ';

//Construir las sentencias sql para la inserción de registros de TELEFONOS DE ORGANIZACIONES
for($t=1;$t<=$tel;$t++)
{
	$vTELtelefono="$"."TELtelefono".$t;
	eval("\$vTELtelefono = \"$vTELtelefono\";");
	if($vTELtelefono)
	{
	$sqltelorganizaciones .= "(NULL, 'O', '$claveorganizacion', '$"."TELtipo_telefono".$t."', '$"."TELtelefono".$t."', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($t<$tel) $sqltelorganizaciones .= ",";
	}
}
//Construir las sentencias sql para la inserción de registros de CORREOS DE ORGANIZACIONES
for($e=1;$e<=$cor;$e++)
{
	$vCORemailo="$"."CORemailo".$e;
	eval("\$vCORemailo = \"$vCORemailo\";");
	if($vCORemailo)
	{
	$sqlemailorganizaciones .= "(NULL, 'O', '$clavecontacto', 'Trabajo', '$"."CORemailo".$e."', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($e<$cor) $sqlemailorganizaciones .= ",";
	}
}
//Construir las sentencias sql para la inserción de registros de DIRECCIONES WEB DE ORGANIZACIONES
for($w=1;$w<=$web;$w++)
{
	$vWEBdireccion_web="$"."WEBdireccion_web".$w;
	eval("\$vWEBdireccion_web = \"$vWEBdireccion_web\";");
	if($vWEBdireccion_web)
	{
	$sqlweborganizaciones .= "(NULL, 'O', '$claveorganizacion', '$"."WEBtipo_direccion_web".$w."', '$"."WEBdireccion_web".$w."', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($w<$web) $sqlweborganizaciones .= ",";
	}
}

//Construir las sentencias sql para la inserción de registros de DOMICILIOS DE ORGANIZACIONES
for($d=1;$d<=$dom;$d++)
{
	$vDOMdomicilio="$"."DOMdomicilio".$d;
	eval("\$vDOMdomicilio = \"$vDOMdomicilio\";");
	if($vDOMdomicilio!="Calle, Número, Colonia")
	{
	$sqldomorganizaciones .= "(NULL, 'O', '$claveorganizacion', '$"."DOMtipo_domicilio".$d."', '$"."DOMdomicilio".$d."', '$"."DOMciudad".$d."', '$"."DOMestado".$d."', 'México', '$"."DOMcp".$d."', '', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($d<$dom) $sqldomorganizaciones .= ",";
	}
}
//Construir las sentencias sql para la inserción de registros de RAZONES SOCIALES DE ORGANIZACIONES
for($r=1;$r<=$rfc;$r++)
{
	$vRFCrazon_social="$"."RFCrazon_social".$r;
	eval("\$vRFCrazon_social = \"$vRFCrazon_social\";");
	if($vRFCrazon_social)
	{
	$sqlrfcorganizaciones .= "(NULL, 'O', '$claveorganizacion', '$"."RFCrazon_social".$r."', '$"."RFCrfc".$r."', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')"; if($w<$web) $sqlweborganizaciones .= ",";
	}
}

eval("\$sqlcontactos = \"$sqlcontactos\";");
eval("\$sqltelcontactos = \"$sqltelcontactos\";");
eval("\$sqlemailcontactos = \"$sqlemailcontactos\";");
eval("\$sqltelorganizaciones = \"$sqltelorganizaciones\";");
eval("\$sqlemailorganizaciones = \"$sqlemailorganizaciones\";");
eval("\$sqlweborganizaciones = \"$sqlweborganizaciones\";");
eval("\$sqldomorganizaciones = \"$sqldomorganizaciones\";");
eval("\$sqlrfcorganizaciones = \"$sqlrfcorganizaciones\";");
/*echo $sqlorganizaciones."<br><br>";
echo $sqlcontactos."<br><br>";
echo $sqltelcontactos."<br><br>";
echo $sqlemailcontactos."<br><br>";
echo $sqltelorganizaciones."<br><br>";
echo $sqlemailorganizaciones."<br><br>";
echo $sqlweborganizaciones."<br><br>";
echo $sqldomorganizaciones."<br><br>";
echo $sqlrfcorganizaciones."<br><br>";*/
mysql_query ($sqlorganizaciones,$db);
mysql_query ($sqlcontactos,$db);
mysql_query ($sqltelcontactos,$db);
mysql_query ($sqlemailcontactos,$db);
mysql_query ($sqltelorganizaciones,$db);
mysql_query ($sqlemailorganizaciones,$db);
mysql_query ($sqlweborganizaciones,$db);
mysql_query ($sqldomorganizaciones,$db);
mysql_query ($sqlrfcorganizaciones,$db);

//header("Location: http://www.anabiosiscrm.com.mx/premo/modulos/organizaciones/detalles.php?organizacion=".urlencode($claveorganizacion));
header("Location: http://crm.premo.mx/modulos/organizaciones/detalles.php?organizacion=".urlencode($claveorganizacion)); 
exit;
?>
