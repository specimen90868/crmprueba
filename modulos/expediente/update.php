<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

/***VARIABLES POR POST ***/

foreach($_POST as $nombre_campo => $valor)
{ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   eval($asignacion);
}
//print_r ($_POST);

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_POST[organizacion];

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
	case 'oP'://Se accedio desde la lista de Oportunidades en Organizaciones
		$i_header.="modulos/organizaciones/oportunidades.php";
		break;	
	case 'P'://Se accedio desde la lista de Oportunidades
		$i_header.="modulos/oportunidades/oportunidades.php";
		break;
}

switch ($_POST[o])
{
	case 'I':
		//echo "Se cargarán archivos al expediente";
		$msj="";
		$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$_POST[e]."'";
		$resulttipos= mysql_query ($sqltipos,$db);
		$file_log = fopen("Data/log.txt", "a");//Archivo éxito
		//$file_err = fopen("Data/err.txt", "a");//Archivo con errores
		while($myrowtipos=mysql_fetch_array($resulttipos))
		{
			$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_POST[id]."'";
			$resultopt= mysql_query ($sqlopt,$db);
			$myrowopt=mysql_fetch_array($resultopt);
			
			//Verificar si hay archivo cargado
			$sqlfile="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."' AND id_oportunidad='".$_POST[id]."'";
			$rsfile= mysql_query ($sqlfile,$db);
			$rwfile=mysql_fetch_array($rsfile);
							
			$nombrecampo="archivo".$myrowtipos[id_tipoarchivo];
			if($_FILES[$nombrecampo]['name']!="")
			{
				$nombreoriginal=$_FILES[$nombrecampo]['name'];
				$extension = explode(".",$_FILES[$nombrecampo]['name']);
				//echo "Se subió archivo: ".$nombreoriginal." en ".$myrowtipos[tipo_archivo]; 
				$num = count($extension)-1; 
				if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF"||$extension[$num]=="pdf"||$extension[$num]=="PDF") 
				{ 
					if($_FILES[$nombrecampo]['size'] < 4194304)//4MB Permitidos 
					{
						//echo "Tamaño permitido";
						$nombre="E".time()."X".rand(100,999).rand(10,99).".".$extension[$num];
						$archivoanterior="../../expediente/".$rwfile[nombre];
						$nombrearchivo="../../expediente/".$nombre;
						if (move_uploaded_file($_FILES[$nombrecampo]['tmp_name'], $nombrearchivo))
						{
							$clavearchivo = generateKey();
							$enviada=1;
							if($rwfile)//Ya hay un archivo, será reemplazado
							{
								//echo "<b>Ya hay archivo, se reemplazara por ".$nombreoriginal."</b></br>";
								unlink($archivoanterior);//Borrar archivo anterior
						   		$sql="UPDATE `archivos` SET `nombre`='$nombre', `nombre_original`='$nombreoriginal', `fecha_modificacion`=NOW(), `aprobado`='0' WHERE `id_archivo` = '".$rwfile[id_archivo]."'";//Actualizar registro
								$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL, '$rwfile[clave_archivo]', '$_POST[id]','$_POST[e]','Reemplazado', '', NOW(),'$claveagente')";//Actualizar historial
							}
							else
							{
								$sql="INSERT INTO `archivos`(`id_archivo`, `clave_archivo`, `id_tipoarchivo`, `id_oportunidad`, `clave_oportunidad`, `nombre_original`, `nombre`, `extension`, `fecha_captura`, `fecha_modificacion`, `aprobado`, `usuario_captura`, `usuario_aprobacion`, `fecha_aprobacion`,`id_expediente`) VALUES (NULL, '$clavearchivo', '$myrowtipos[id_tipoarchivo]','$_POST[id]','$myrowopt[clave_oportunidad]','$nombreoriginal','$nombre','$extension[$num]',NOW(),NOW(),'0', '$claveagente', '', '','$_POST[e]')";
								$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL, '$clavearchivo', '$_POST[id]','$_POST[e]','Cargado', '', NOW(),'$claveagente')";
							}
							$msj='El archivo. '.$nombreoriginal.' fué cargado con éxito';
						    fwrite($file_log, $msj . PHP_EOL);
							mysql_query($sql,$db);
							mysql_query($sqlhistorial,$db);
						}
						else
						{
						   $msj='Ocurrió algún error al subir el archivo. '.$nombreoriginal.' no pudo guardarse';
						   fwrite($file_log, $msj . PHP_EOL);
						} 			        	
					} 
					else 
					{ 
						$msj='El archivo '.$nombreoriginal.' no fue enviado, el tamaño supera los 400kb';
						fwrite($file_log, $msj . PHP_EOL);  
					} 
				} 
				else 
				{ 
					$msj='El archivo '.$nombreoriginal.' no fue enviado, el formato no es válido';
					fwrite($file_log, $msj . PHP_EOL); 
				}
			}//FIN DE IF HAY ARCHIVO
		}//FIN DE WHILE
		fclose($file_log);
		
		//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
		//header($i_header."?organizacion=".urlencode($claveorganizacion));
		//$i_header.="modulos/expediente/forminsert.php";
		header($i_header."?id=".urlencode($_POST[id])."&o=".urlencode($_POST[o])."&a=".urlencode($_POST[a])."&e=".urlencode($_POST[e])."&organizacion=".urlencode($claveorganizacion));
		exit;
		break;
	case 'U':
		$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_POST[id]."'";
		$resultopt= mysql_query ($sqlopt,$db);
		$myrowopt=mysql_fetch_array($resultopt);
		
		$sqlarchivos="SELECT archivos.* , tiposarchivos.id_tipoarchivo, tiposarchivos.id_expediente, tiposarchivos.tipo_archivo FROM archivos INNER JOIN tiposarchivos ON archivos.id_tipoarchivo = tiposarchivos.id_tipoarchivo WHERE tiposarchivos.id_expediente='".$_POST[e]."' AND archivos.id_oportunidad='".$_POST[id]."' order by archivos.id_tipoarchivo ASC";
		$rsarchivos=mysql_query($sqlarchivos,$db);
		while($rwarchivos=mysql_fetch_array($rsarchivos))
		{
			//echo $rwarchivos[clave_archivo].": ";
			if($rwarchivos[aprobado]!=($_POST[aprobados.$rwarchivos[id_archivo]]))
			{
				//echo "Hubo cambio ";
				if(($_POST[aprobados.$rwarchivos[id_archivo]])==1)//Aprobado
				{
					//echo "Aprobado </br>";
					$sqlaprobar = "UPDATE `archivos` SET `aprobado`='1', `usuario_aprobacion`='$claveagente',`fecha_aprobacion`= NOW() WHERE id_archivo = '".$rwarchivos[id_archivo]."'";
					$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL,'$rwarchivos[clave_archivo]','$_POST[id]','$_POST[e]','Aprobado', '', NOW(),'$claveagente')";
					//echo $sqlaprobar;
					mysql_query($sqlaprobar,$db);
					mysql_query($sqlhistorial,$db);
					//$i_header.="modulos/organizaciones/oportunidades.php";
					//header($i_header."?organizacion=".urlencode($claveorganizacion));
				}
				else if(($_POST[aprobados.$rwarchivos[id_archivo]])==2)//Reprobado
				{
					$motivo=$_POST[motivo.$rwarchivos[id_archivo]];
					//echo $motivo;
					//echo "Reprobado, Motivo: ".$_POST[motivo.$rwarchivos[id_archivo]]."</br>";
					$sqlaprobar = "UPDATE `archivos` SET `aprobado`='2', `usuario_aprobacion`='$claveagente',`fecha_aprobacion`= NOW() WHERE id_archivo = '".$rwarchivos[id_archivo]."'";
					$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL,'$rwarchivos[clave_archivo]','$_POST[id]','$_POST[e]','Rechazado', '$motivo', NOW(),'$claveagente')";
					$sqltipo="SELECT * FROM archivos WHERE id_archivo='".$rwarchivos[id_archivo]."'";
					$rstipo=mysql_query($sqltipo,$db);
					$rwtipo=mysql_fetch_array($rstipo);
					if($rwtipo[id_tipoarchivo]==10)//Verificar si un archivo de términos y condiciones fue rechazado
					{
						$sqlcredito="UPDATE `creditos` SET `estatus`='Rechazado',`motivo_rechazo`='Desaprobación de Términos y Condiciones' WHERE clave_oportunidad='".$rwarchivos[clave_oportunidad]."'";
						$sqref="SELECT * FROM `referencias` WHERE asignada=1 AND descartado=0 AND clave_oportunidad='".$rwarchivos[clave_oportunidad]."' ORDER BY fecha_asignacion DESC LIMIT 1";
						$rsref= mysql_query ($sqref,$db);
						$rwref=mysql_fetch_array($rsref);
						$sqldescartar="UPDATE `referencias` SET `descartado`='1',`motivo`='Desaprobación de Términos y Condiciones' WHERE `id_referencia`='".$rwref[id_referencia]."'";//La referencia usada será descartada
					}
					mysql_query($sqlaprobar,$db);
					mysql_query($sqlhistorial,$db);
					mysql_query($sqlcredito,$db);
					mysql_query($sqldescartar,$db);
				}
				else//Sin revisar
				{
					//echo "No revisado </br>";
				}
			}
			else
			{
				//echo "No hubo cambio ";
			}
			
		}//Fin de while arhivos

		//Regresar al archivo desde el cual se abrio la pantalla de inserción/modificación
		//$i_header.="modulos/expediente/forminsert.php";$i_header.="index.php";
		header($i_header."?id=".urlencode($_POST[id])."&o=".urlencode($_POST[o])."&a=".urlencode($_POST[a])."&e=".urlencode($_POST[e])."&organizacion=".urlencode($claveorganizacion));
		//header($i_header."?organizacion=".urlencode($claveorganizacion));	
		
		exit;
		break;
}
?>
