<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_POST[organizacion];

//print_r ($_POST);

//Para organizaciones
$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."'";
$resultorg= mysql_query ($sqlorg,$db);
while($myroworg=mysql_fetch_array($resultorg))
{
	$idorganizacion=$myroworg[id_organizacion];
	$empresa=$myroworg[organizacion];
	$clave=$myroworg[clave_unica];
}

//Definir Location
$i_header="Location: http://crm.premo.mx/";



if($_GET[id])//Se llamo al update sin pasar por el forminsert para actualizar la etapa
{
	switch($_GET[a])
	{
		case 'D'://Se accedio desde el Dashboard
			$i_header.="index.php";
			break;
		case 'O'://Se accedio desde el detalle de Organización
			$i_header.="modulos/organizaciones/detalles.php";
			break;
		case 'oP'://Se accedio desde Oportunidades de Organización
			$i_header.="modulos/organizaciones/oportunidades.php";
			break;
		case 'C'://Se accedio desde el Calendario
			$i_header.="modulos/actividades/calendario.php";
			break;
		case 'P'://Se accedio desde la lista de Actividades
			$i_header.="modulos/oportunidades/oportunidades.php";
			break;		
		}//Fin de switch
	$claveorganizacion=$_GET[organizacion];
	//Datos de la oportunidad
	$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
	$resultopt= mysql_query ($sqlopt,$db);
	$myrowopt=mysql_fetch_array($resultopt);
	
	//Datos de la etapa en la que está
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowopt[id_etapa]."'";
	$resultetp= mysql_query($sqletp,$db);
	$myrowetp=mysql_fetch_array($resultetp);
	//Siguiente etapa
	$sqletps="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowetp[etapa_siguiente]."'";
	$resultetps= mysql_query($sqletps,$db);
	$myrowetps=mysql_fetch_array($resultetps);
	//Etapa anterior
	$sqletpa="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowetp[etapa_anterior]."'";
	$resultetpa= mysql_query($sqletpa,$db);
	$myrowetpa=mysql_fetch_array($resultetpa);
	
	if($_GET[av])
	{
		//Actualizar el registro de la oportunidad para AVANZAR
		$sqlupdate="UPDATE `oportunidades` SET `marcado`='0', `motivo`='', `id_etapa`='$myrowetp[etapa_siguiente]', `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_GET[id]."'";
		//Registrar avance de la oportunidad
	$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$myrowetp[etapa_siguiente]', '$myrowetps[etapa]', NOW(), '$claveagente', NOW())";
		//Enviar mail
		$sqlusuario="SELECT * FROM usuarios WHERE id_responsable='".$myrowetps[id_responsable]."'";
        $rsusuario= mysql_query($sqlusuario,$db);
		$rwusuario=mysql_fetch_array($rsusuario);
        
        /*$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
		$rspromotor= mysql_query($sqlpromotor,$db);
		$rwpromotor=mysql_fetch_array($rspromotor);*/
        
		$headers = "MIME-Version: 1.1\n";
		$headers .= "Content-type: text/plain; charset=UTF-8\n";
		$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
		$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
		$cuerpo = "Hola ".$rwusuario[nombre].", el usuario ".$claveagente." te ha enviado un proceso a la etapa: ".$myrowetps[etapa].", no olvides darle seguimiento \n\n";
		$cuerpo .= "\nAdministrador del CRM";
		$asunto = $rwusuario[nombre]." Tienes nuevos procesos asignados en el sistema";
        //mail("denmed2210@gmail.com",$asunto,$cuerpo,$headers);
		mail($rwusuario[email],$asunto,$cuerpo,$headers);
	}
	else
	{
		//Actualizar el registro de la oportunidad para RETROCEDER
		/*switch ($_GET[re])
		{
			case 5:*/
				$sqlupdate="UPDATE `oportunidades` SET `marcado`='1', `motivo`='Retroceso de etapa', `id_etapa`='$myrowetp[etapa_anterior]', `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_GET[id]."'";
				//Registrar avance de la oportunidad
	$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$myrowetp[etapa_anterior]', '$myrowetpa[etapa]', NOW(), '$claveagente', NOW())";
				//Enviar mail
				$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
				$rspromotor= mysql_query($sqlpromotor,$db);
				$rwpromotor=mysql_fetch_array($rspromotor);
				$headers = "MIME-Version: 1.1\n";
				$headers .= "Content-type: text/plain; charset=UTF-8\n";
				$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
				$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
				$cuerpo = "Hola ".$rwpromotor[nombre].", el usuario ".$claveagente." te ha devuelto un proceso a la etapa: ".$myrowetpa[etapa].", no olvides darle seguimiento \n\n";
				$cuerpo .= "\nAdministrador del CRM";
				$asunto = $rwpromotor[nombre]." Tienes nuevos procesos asignados en el sistema";
				//mail("dmedina@am.com.mx",$asunto,$cuerpo,$headers);
				mail($rwpromotor[email],$asunto,$cuerpo,$headers);
				
				/*break;
			case 6:
				break;
		}*/
	}
	
	//Realizar consultas
	mysql_query($sqlupdate,$db);
	mysql_query($sqletapaoportun,$db);
	
	//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
	header($i_header."?organizacion=".urlencode($claveorganizacion)); 
}//Fin de if para avanzar o retroceder
else
{
	switch($_POST[a])
	{
		case 'D'://Se accedio desde el Dashboard
			$i_header.="index.php";
			break;
		case 'O'://Se accedio desde el detalle de Organización
			$i_header.="modulos/organizaciones/detalles.php";
			break;
		case 'oP'://Se accedio desde Oportunidades de Organización
			$i_header.="modulos/organizaciones/oportunidades.php";
			break;
		case 'C'://Se accedio desde el Calendario
			$i_header.="modulos/actividades/calendario.php";
			break;
		case 'P'://Se accedio desde la lista de Actividades
			$i_header.="modulos/oportunidades/oportunidades.php";
			break;		
	}//Fin de switch para definir location
	$claveorganizacion=$_POST[organizacion];
	switch ($_POST[o])
	{
		case 'I':
			//Insertar la oportunidad
			$claveoportunidad = generateKey();
			if($_POST[promotor]=='0'||$_POST[promotor]==""){$promotor=$claveagente; if($_SESSION["Tipo"]=="Promotor"){$asignado=1;} else {$asignado=0;}}
			else
			{
				$promotor=$_POST[promotor]; $asignado=1;
				$sqlupdateorg= "UPDATE `organizaciones` SET `clave_agente`='$promotor', `modifico`='$claveagente',`fecha_modificacion`=NOW(),`hora_modificiacion`=NOW(),`asignado`='1' WHERE clave_organizacion = '".$claveorganizacion."'";
				mysql_query($sqlupdateorg,$db);//Asignar contacto si se asigna el proceso de crédito (oportunidad) a un promotor			
			}
			$sqloportunidades="INSERT INTO `oportunidades` (`id_oportunidad`, `clave_oportunidad`, `id_organizacion`, `clave_organizacion`, `marcado`, `motivo`, `monto`, `id_rechazo`, `id_etapa`, `productos`, `usuario`, `capturo`, `fecha_captura`, `fecha_cierre_esperado`, `fecha_cierre_real`, `fecha_modificacion`, `tipo_credito`, `plazo_credito`, `destino_credito`, `asignado`, `interes`) VALUES 
	(NULL , '$claveoportunidad','$idorganizacion', '$claveorganizacion', '$_POST[nombre_oportunidad]', '$_POST[descripcion_oportunidad]', '$_POST[monto_credito]', '', '$_POST[id_etapa]', 'Crédito', '$promotor', '$claveagente', NOW(), '', '', NOW(), '$_POST[tipo_credito]', '$_POST[plazo_credito]', '$_POST[destino_credito]', '$asignado','$_POST[interes_credito]')";
	
	
			//Etapa de la oportunidad
			$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$_POST[id_etapa]."'";
			$resultetp= mysql_query($sqletp,$db);
			while($myrowetp=mysql_fetch_array($resultetp))
			{
				$etapa=$myrowetp[etapa];
			}
			//Insertar un registro para la etapa de la oportunidad
			$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$claveoportunidad', '$_POST[id_etapa]', '$etapa', NOW(), '$claveagente', NOW())";
			
			/*******VERIFICAR SI HAY RELACION DE GARANTE CAPTURADA*******/
			if($_POST[tipo_garante]==Física)
			{
				if($_POST[sel_garante_fisico]!='Nuevo'&&$_POST[sel_garante_fisico]!='')//Se seleccionó un Garante de la lista (inserción de relación de garante)
				{
					$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$_POST[sel_garante_fisico]','3','Garante','$claveagente',NOW(),'$claveoportunidad')";
				}
				else//Se dará de alta un garante nuevo (inserción de nuevo contacto y de relación de garante)
				{
					if($_POST[nombre_garante_fisico]||$_POST[apellido_garante_fisico])//Hay algo en los campos
					{
						$clavecontacto = generateKey();
						$claverelacion = generateKey();
						$organizacion = strtoupper($_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico]);
						$nombrecompleto = $_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico];
						
						$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
	(NULL , '$claverelacion', 'O', '$organizacion', '', '', '', 'Garante', '$claveagente', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', 'Física', '', '', '1')";
						
						$sqlcontactos="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_garante_fisico]', '$_POST[nombre_garante_fisico]', '$nombrecompleto', '', 'Física', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
						
						$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`, `clave_oportunidad`) VALUES (NULL,'$claveorganizacion','$clavecontacto','$claverelacion','3','Garante','$claveagente',NOW(),'$clave_oportunidad')";
						
						mysql_query("SET NAMES 'utf8'");
						mysql_query($sqlorganizaciones,$db);
						mysql_query($sqlcontactos,$db);
					}//Fin de if campos llenos
				}//Fin de else: garante nuevo
				mysql_query($sqlrelacion,$db);
			}//Fin de if: GARANTE FÍSICO
			else
			{
				if($_POST[sel_garante_moral]!='Nuevo'&&$_POST[sel_garante_moral]!='')//Se seleccionó un Garante de la lista (inserción de relación de garante)
				{
					$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$_POST[sel_garante_moral]','3','Garante','$claveagente',NOW(),'$claveoportunidad')";
				}
				else//Se dará de alta un garante nuevo (inserción de nuevo contacto y relación de garante)
				{
					if($_POST[garante_moral]||$_POST[nombre_moral_legal]||$_POST[apellido_moral_legal]||$_POST[nombre_moral_accionista]||$_POST[apellido_moral_accionista])//Si hay texto en cualquiera de los campos
					{
						$claverelacion = generateKey();//clave de la organización garante
						if($_POST[garante_moral]){$organizacion=$_POST[garante_moral];}else{$organizacion="NO ESPECIFICADA";}
						$nombrecompleto = $_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico];
						
						$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto`, `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
(NULL , '$claverelacion', 'O', '$organizacion', '', '', '', 'Garante', '$claveagente', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', 'Moral', '', '', '1')";

						$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$claverelacion','3','Garante','$claveagente',NOW(),'$claveoportunidad')";
						
						if($_POST[nombre_moral_legal]||$_POST[apellido_moral_legal])//representante legal del garante que se insertarán
						{
							$clavelegal = generateKey();
							$nombrecompletolegal = $_POST[nombre_moral_legal]." ".$_POST[apellido_moral_legal];
							
							$sqllegal="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavelegal', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_moral_legal]', '$_POST[nombre_moral_legal]', '$nombrecompletolegal', '', '', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
						
							$sqlrelacionlegal="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claverelacion','$clavelegal','$claverelacion','2','Representante Legal','$claveagente',NOW(),'')";
							
							//Ejecutar consultas: representante legal y relación
							mysql_query("SET NAMES 'utf8'");
							mysql_query($sqllegal,$db);
							mysql_query($sqlrelacionlegal,$db);
						}//Fin de if representante legal
						
						if($_POST[nombre_moral_accionista]||$_POST[apellido_moral_accionista])//accionista del garante que se insertarán
						{
							$claveaccionista = generateKey();
							$nombrecompletoaccionista = $_POST[nombre_moral_accionista]." ".$_POST[apellido_moral_accionista];
							
							$sqlaccionista="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$claveaccionista', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_moral_accionista]', '$_POST[nombre_moral_accionista]', '$nombrecompletolegal', '', '', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
							
							$sqlrelacionaccionista="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claverelacion','$claveaccionista','$claverelacion','4','Accionista Principal','$claveagente',NOW(),'')";
							
							//Ejecutar consultas: accionista principal y relación
							mysql_query("SET NAMES 'utf8'");
							mysql_query($sqlaccionista,$db);
							mysql_query($sqlrelacionaccionista,$db);
						}//Fin de if: principal accionista
					}//Fin de if: hay texto en cualquiera de los campos
				}//Fin de else: garante nuevo
				mysql_query("SET NAMES 'utf8'");
				mysql_query($sqlorganizaciones,$db);
				mysql_query($sqlrelacion,$db);
			}//Fin de else: GARANTE MORAL
			
			//Ejecutar las consultas de oportunidades
			mysql_query("SET NAMES 'utf8'");
			mysql_query($sqloportunidades,$db);
			mysql_query($sqletapaoportun,$db);
			//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
			header($i_header."?organizacion=".urlencode($claveorganizacion)); 
			exit;
			break;
		case 'U':
			$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_POST[oportunidad]."'";
			$resultopt= mysql_query($sqlopt,$db);
			$myrowopt=mysql_fetch_array($resultopt);
			$claveoportunidad=$myrowopt[clave_oportunidad];
			if($_POST[id_etapa]){$etapa=$_POST[id_etapa];}else{$etapa=$_POST[etapa];}
			if($etapa==$myrowopt[id_etapa])//No se modificó la etapa de la oportunidad
			{
				if($_POST[promotor]=='0'||$_POST[promotor]==""){$promotor=$claveagente; if($_SESSION["Tipo"]=="Promotor"){$asignado=1;} else {$asignado=0;}}
				else{$promotor=$_POST[promotor]; $asignado=1;}
					
				$sqloportunidades="UPDATE `oportunidades` SET `monto` = '$_POST[monto_credito]', `usuario`='$promotor', `fecha_modificacion`= NOW(), `tipo_credito` = '$_POST[tipo_credito]', `plazo_credito` = '$_POST[plazo_credito]', `destino_credito` = '$_POST[destino_credito]', `asignado` = '$asignado' WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
				//echo $sqloportunidades;
			}
			else//Se modificó la etapa de la oportunidad
			{
				/*******CIERRE DE LA OPORTUNIDAD*******/
				if($_POST[id_etapa]=='10'||$_POST[id_etapa]=='11')//Verificar si se cerró la oportunidad para actualizar la fecha de cierre real
				{
					$sqloportunidades="UPDATE `oportunidades` SET `monto` = '$_POST[monto]', `id_rechazo` = '$_POST[motivo_rechazo]', `id_etapa` = '$_POST[id_etapa]', `productos` = '$_POST[productos]', `fecha_cierre_esperado` = '$_POST[date]', `fecha_cierre_real`= NOW(), `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
				}
				else//No se cerró la oportunidad
				{
					if($_POST[promotor]=='0'||$_POST[promotor]==""){$promotor=$claveagente; if($_SESSION["Tipo"]=="Promotor"){$asignado=1;} else {$asignado=0;}}
					else{$promotor=$_POST[promotor]; $asignado=1;}
					$sqloportunidades="UPDATE `oportunidades` SET `monto` = '$_POST[monto_credito]', `id_etapa`='$_POST[id_etapa]', `usuario`='$promotor', `fecha_modificacion`= NOW(), `tipo_credito` = '$_POST[tipo_credito]', `plazo_credito` = '$_POST[plazo_credito]', `destino_credito` = '$_POST[destino_credito]', `asignado` = '$asignado' , `interes` = '$_POST[interes_credito]' WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
				}
				//Se agregará una cita si la etapa que se asigna a la oportunidad es 2-Cita
				if($etapa==2)
				{
					$hora=$_POST[hora_actividad].":".$_POST[min_actividad].":00";
					$sqlactividades="INSERT INTO `actividades`(`id_actividad`, `tipo_registro`, `id_oportunidad`, `oportunidad`, `clave_organizacion`, `organizacion`, `id_contacto`, `contacto`, `fecha`, `hora`, `duracion`, `fecha_final`, `alarma`, `fecha_alarma`, `hora_alarma`, `tipo`, `subtipo`, `descripcion`, `color`, `resultado`, `usuario`, `fecha_realizada`, `hora_realizada`, `duracion_realizada`, `usuario_realizo`, `usuario_capturo`, `fecha_captura`, `hora_captura`, `completa`, `estatus`) VALUES (NULL,'A','$_POST[oportunidad]','','$claveorganizacion','$empresa','','','$_POST[date]','$hora','','','','','','Cita','$_POST[actividad]','$_POST[descripcion]','','','$claveagente','','','','','$claveagente',NOW(),NOW(),'2','')";	
					mysql_query("SET NAMES 'utf8'");
					mysql_query($sqlactividades,$db);
				}

				//Etapa de la oportunidad
				$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$_POST[id_etapa]."'";
				$resultetp= mysql_query($sqletp,$db);
				while($myrowetp=mysql_fetch_array($resultetp))
				{
					$etapa=$myrowetp[etapa];
				}
				//En cualquier caso se inserta un registro para el AVANCE DE LA ETAPA DE LA OPORTUNIDAD
				$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$claveoportunidad', '$_POST[id_etapa]', '$etapa', NOW(), '$claveagente', NOW())";
			}//Fin de else (se modifico la etapa de la oportunidad)
			
			if($_POST[cambiar])
			{
		 		//Información de garante
				$sqlrelacion="SELECT * FROM `relaciones` WHERE `clave_oportunidad`='".$myrowopt[clave_oportunidad]."' AND `rol`='Garante'";
				$rsrelacion= mysql_query ($sqlrelacion,$db);
				$rwrelacion=mysql_fetch_array($rsrelacion);
				//echo "se modificará garante";
				/*******VERIFICAR SI HAY RELACION DE GARANTE CAPTURADA*******/
				if($_POST[tipo_garante]==Física)
				{
					if($_POST[sel_garante_fisico]!='Nuevo'&&$_POST[sel_garante_fisico]!='')//Se seleccionó un Garante de la lista (inserción de relación de garante)
					{
						$sqlgarante="DELETE FROM `relaciones` WHERE `id_relacion`='".$rwrelacion[id_relacion]."'";
						$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$_POST[sel_garante_fisico]','3','Garante','$claveagente',NOW(),'$myrowopt[clave_oportunidad]')";
					}
					else//Se dará de alta un garante nuevo (inserción de nuevo contacto y de relación de garante)
					{
						if($_POST[nombre_garante_fisico]||$_POST[apellido_garante_fisico])//Hay algo en los campos
						{
							$clavecontacto = generateKey();
							$claverelacion = generateKey();
							$organizacion = strtoupper($_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico]);
							$nombrecompleto = $_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico];
							
							$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
		(NULL , '$claverelacion', 'O', '$organizacion', '', '', '', 'Garante', '$claveagente', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', 'Física', '', '', '1')";
							
							$sqlcontactos="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_garante_fisico]', '$_POST[nombre_garante_fisico]', '$nombrecompleto', '', 'Física', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
							
							$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','$clavecontacto','$claverelacion','3','Garante','$claveagente',NOW(),'$myrowopt[clave_oportunidad]')";
							
							$sqlgarante="DELETE FROM `relaciones` WHERE `id_relacion`='".$rwrelacion[id_relacion]."'";
							
							mysql_query("SET NAMES 'utf8'");
							mysql_query($sqlorganizaciones,$db);
							mysql_query($sqlcontactos,$db);
						}//Fin de if campos llenos
					}//Fin de else: garante nuevo
					mysql_query($sqlrelacion,$db);
					mysql_query($sqlgarante,$db);
				}//Fin de if: GARANTE FÍSICO
				else
				{
					if($_POST[sel_garante_moral]!='Nuevo'&&$_POST[sel_garante_moral]!='')//Se seleccionó un Garante de la lista (inserción de relación de garante)
					{
						$sqlgarante="DELETE FROM `relaciones` WHERE `id_relacion`='".$rwrelacion[id_relacion]."'";
						$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$_POST[sel_garante_moral]','3','Garante','$claveagente',NOW(),'$myrowopt[clave_oportunidad]')";
					}
					else//Se dará de alta un garante nuevo (inserción de nuevo contacto y relación de garante)
					{
						if($_POST[garante_moral]||$_POST[nombre_moral_legal]||$_POST[apellido_moral_legal]||$_POST[nombre_moral_accionista]||$_POST[apellido_moral_accionista])//Si hay texto en cualquiera de los campos
						{
							$claverelacion = generateKey();//clave de la organización garante
							if($_POST[garante_moral]){$organizacion=$_POST[garante_moral];}else{$organizacion="NO ESPECIFICADA";}
							$nombrecompleto = $_POST[nombre_garante_fisico]." ".$_POST[apellido_garante_fisico];
							
							$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto`, `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
	(NULL , '$claverelacion', 'O', '$organizacion', '', '', '', 'Garante', '$claveagente', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', 'Moral', '', '', '1')";
	
							$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claveorganizacion','','$claverelacion','3','Garante','$claveagente',NOW(),'$myrowopt[clave_oportunidad]')";
							
							$sqlgarante="DELETE FROM `relaciones` WHERE `id_relacion`='".$rwrelacion[id_relacion]."'";
							
							if($_POST[nombre_moral_legal]||$_POST[apellido_moral_legal])//representante legal del garante que se insertarán
							{
								$clavelegal = generateKey();
								$nombrecompletolegal = $_POST[nombre_moral_legal]." ".$_POST[apellido_moral_legal];
								
								$sqllegal="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavelegal', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_moral_legal]', '$_POST[nombre_moral_legal]', '$nombrecompletolegal', '', '', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
							
								$sqlrelacionlegal="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claverelacion','$clavelegal','$claverelacion','2','Representante Legal','$claveagente',NOW(),'')";
								
								//Ejecutar consultas: representante legal y relación
								mysql_query("SET NAMES 'utf8'");
								mysql_query($sqllegal,$db);
								mysql_query($sqlrelacionlegal,$db);
							}//Fin de if representante legal
							
							if($_POST[nombre_moral_accionista]||$_POST[apellido_moral_accionista])//accionista del garante que se insertarán
							{
								$claveaccionista = generateKey();
								$nombrecompletoaccionista = $_POST[nombre_moral_accionista]." ".$_POST[apellido_moral_accionista];
								
								$sqlaccionista="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$claveaccionista', 'C', '', '$claverelacion', '$organizacion','$_POST[apellido_moral_accionista]', '$_POST[nombre_moral_accionista]', '$nombrecompletolegal', '', '', '', '', '', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', 'Garante', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
								
								$sqlrelacionaccionista="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`,`clave_oportunidad`) VALUES (NULL,'$claverelacion','$claveaccionista','$claverelacion','4','Accionista Principal','$claveagente',NOW(),'')";
								
								//Ejecutar consultas: accionista principal y relación
								mysql_query("SET NAMES 'utf8'");
								mysql_query($sqlaccionista,$db);
								mysql_query($sqlrelacionaccionista,$db);
							}//Fin de if: principal accionista
						}//Fin de if: hay texto en cualquiera de los campos
					}//Fin de else: garante nuevo
					mysql_query("SET NAMES 'utf8'");
					mysql_query($sqlorganizaciones,$db);
					mysql_query($sqlrelacion,$db);
					mysql_query($sqlgarante,$db);
				}//Fin de else: GARANTE MORAL
				
			}//Fin de if cambiar garante

			//Ejecutar las consultas de oportunidades
			mysql_query("SET NAMES 'utf8'");
			mysql_query($sqloportunidades,$db);
			mysql_query($sqletapaoportun,$db);
			//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
			header($i_header."?organizacion=".urlencode($claveorganizacion)); 
			exit;
			break;
		}
}


?>
