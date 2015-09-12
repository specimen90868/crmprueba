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

$sqlorganizacion="SELECT * FROM organizaciones WHERE clave_organizacion='$_POST[organizacion]'";
$resultorg= mysql_query ($sqlorganizacion,$db);	
while($myroworg=mysql_fetch_array($resultorg))
{
	$nombreorg=$myroworg[organizacion];
	$claveunica=$myroworg[clave_unica];
	$tipoorg=$myroworg[tipo_organizacion];
}


switch($_POST[tipo])
{
	case 'L':
		if($_POST[operacion]=='U')
		{
			//update
		}
		elseif($_POST[operacion]=='I')
		{
			if($_FILES['archivo']['name']!="")
			{
			  //echo "Hay archivo";
			  $extension = explode(".",$_FILES['archivo']['name']); 
				$num = count($extension)-1; 
				if($extension[$num]=="jpg"||$extension[$num]=="gif"||$extension[$num]=="png"||$extension[$num]=="swf"||$extension[$num]=="JPG"||$extension[$num]=="GIF"||$extension[$num]=="PNG"||$extension[$num]=="SWF") 
					{ 
						//echo "Formato correcto";
						if($_FILES['archivo']['size'] < 800000) 
						{
							//echo "Tamaño permitido";
							$Foto="A".time()."D".rand(100,999).rand(10,99).".".$extension[$num];
							$nombrearchivo="../../logos/".$Foto;
							//echo $nombrearchivo;
							if (move_uploaded_file($_FILES['archivo']['tmp_name'], $nombrearchivo))
							{
							   $enviada=1;
							   $sql="INSERT INTO `archivos` VALUES ('' , 'O', '$_POST[organizacion]', 'Logotipo', 'Logotipo', 'Logotipo', '$Foto', '$extension[$num]', NOW( ));";
							   //echo $sqlarchivo;
							   mysql_query ($sql,$db);
							}
							else
							{
							   echo "Ocurrió algún error al subir el fichero. No pudo guardarse.<br>";
							} 			        	
						} 
						else 
						{ 
							echo "<b>La imagen no fue enviada.</b><br>El archivo supera los 320kb"; 
						} 
					} 
					else 
					{ 
						echo "<b>La imagen no fue enviada.</b><br>El formato de archivo no es valido, solo <b>.jpg</b> y <b>.gif</b>"; 
					} 
			}
		}
		break;
	case 'O':
		//////////////////////ACTUALIZACIÓN DE ORGANIZACION////////////////////
		if($_POST[operacion]=='U')
		{
			$sqlorg="UPDATE `organizaciones` SET `organizacion`='$_POST[ORGorganizacion]',`clave_unica`='$_POST[ORGclave]',`fecha_fundacion`='$_POST[ORGfundacion]',`procedencia`='$_POST[ORGprocedencia]',`tipo_organizacion`='$_POST[ORGtipo_organizacion]',`modifico`='$claveagente',`fecha_modificacion`=NOW(),`hora_modificiacion`=NOW(),`tipo_persona`='$_POST[ORGtipo_persona]',`clave_web`='$_POST[ORGclave_web]',`forma_contacto`='$_POST[ORGforma_contacto]' WHERE `id_organizacion` = $_POST[id]";
		}
		//echo $sqlorg;
		mysql_query ($sqlorg,$db);
		break;
	case 'C':
		/////////////////////ACTUALIZACIÓN DE UN NUEVO CONTACTO/////////////////
		if($_POST[operacion]=='U')
		{
			$sqlcon="SELECT * FROM contactos WHERE id_contacto=$_POST[id]"; //Sacar la clave del contacto
			$resultcon= mysql_query ($sqlcon,$db);
			while($myrowcon=mysql_fetch_array($resultcon))
			{
				$clavecontacto=$myrowcon[clave_contacto];
			}
			$roles=$_POST[CONrep_legal];
			$rol="";
			$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_contacto`, `clave_relacion`,`id_rol`, `rol`, `usuario_captura`, `fecha_captura`) VALUES ";
			for ($i=0;$i<count($roles);$i++)    
			{     
				$sqlrol="SELECT * FROM roles WHERE id_rol = '".$roles[$i]."'";
				$resultrol= mysql_query ($sqlrol,$db);
				$myrowrol=mysql_fetch_array($resultrol);
				$rol.= $roles[$i];
				$sqlrelacion.="(NULL,'$_POST[organizacion]','$clavecontacto','$_POST[organizacion]','$roles[$i]','$myrowrol[rol]','$claveagente',NOW())";
				if($i<count($roles)-1){$sqlrelacion.= ",";}  
			}
			$nombrecompleto= $_POST[CONapellido]." ".$_POST[CONnombre];
			$fechanacimiento= $Anio."-".$_POST[CONdianac]."-".$_POST[CONmesnac];
			$sql = "UPDATE `contactos` SET `apellidos` = '$_POST[CONapellido]', `nombre` = '$_POST[CONnombre]', `nombre_completo` = '$nombrecompleto', `rep_legal`='$_POST[CONrep_legal]', `tipo_persona`='$_POST[tipo_persona]', `fecha_nacimiento` = '$fechanacimiento', `dia_cumpleanios` = '$_POST[CONdianac]', `mes_cumpleanios` = '$_POST[CONmesnac]', `puesto` = '$_POST[CONpuesto]', `telefono_oficina` = '$_POST[CONdirecto]', `telefono_celular` = '$_POST[CONcelular]', `titulo`='$_POST[CONtitulo]', `modifico` = '$claveagente', `fecha_modificacion` = NOW(), `hora_modificacion` = NOW() WHERE `id_contacto` = $_POST[id]";
			//echo $sql;

			//Actualizar Email
			if($_POST[e]) //Ya había un email capturado, entonces hago un update usando el id
			{
				$sqlmail="UPDATE `correos` SET `correo` = '$_POST[CONemail]' WHERE `id_correo` = $_POST[e]";
			}
			else
			{
				if($_POST[CONemail])//Hay un correo capturado en el textbox
				{
					$sqlmail="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'C', '$clavecontacto', 'Trabajo', '$_POST[CONemail]', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
				}
				else
				{
				}
			}
		}
		/////////////////////INSERCIÓN DE UN NUEVO CONTACTO/////////////////
		elseif($_POST[operacion]=='I')
		{
			$clavecontacto = generateKey();
			$nombrecompleto= $_POST[CONapellido]." ".$_POST[CONnombre];
			$fechanacimiento= $Anio."-".$_POST[CONdianac]."-".$_POST[CONmesnac];
			
			$roles=$_POST[CONrep_legal];
			$rol="";
			$sqlrelacion="INSERT INTO `relaciones`(`id_relacion`, `clave_organizacion`, `clave_relacion`, `clave_contacto`, `id_rol`, `rol`, `usuario_captura`, `fecha_captura`) VALUES ";
			
			for ($i=0;$i<count($roles);$i++)    
			{     
				$sqlrol="SELECT * FROM roles WHERE id_rol = '".$roles[$i]."'";
				$resultrol= mysql_query ($sqlrol,$db);
				$myrowrol=mysql_fetch_array($resultrol);
				$rol.= $roles[$i];
				$sqlrelacion.="(NULL,'$_POST[organizacion]','$clavecontacto','$_POST[organizacion]','$roles[$i]','$myrowrol[rol]','$claveagente',NOW())";
				if($i<count($roles)-1){$sqlrelacion.= ",";}  
			}
			$sql="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '$claveunica', '$_POST[organizacion]', '$nombreorg','$_POST[CONapellido]', '$_POST[CONnombre]', '$nombrecompleto', '$_POST[CONrep_legal]', '$_POST[tipo_persona]', '', '', '$fechanacimiento', '$_POST[CONdianac]', '$_POST[CONmesnac]', '','$_POST[CONpuesto]', '', '$_POST[CONdirecto]', '$_POST[CONcelular]', '', '', '$_POST[CONtitulo]', '', '', '', '', '', '', '$tipoorg', '$claveagente', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', '', '', '')";
			if($_POST[CONemail])//Hay un correo capturado en el textbox
			{
				$sqlmail="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'C', '$clavecontacto', 'Trabajo', '$_POST[CONemail]', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			}
		}
		//mysql_query ($sql,$db);	//Contacto
		if( mysql_query ($sql,$db) ) { 
		   // ok! 
		} else { 
		   echo "Has tenido el siguiente error:<br />".mysql_error(); 
		}  
		mysql_query ($sqlrelacion,$db); //Relaciones
		mysql_query ($sqltel,$db); //Teléfonos
		mysql_query ($sqlmail,$db);	//Correos
		echo "Actualizado con éxito";	
		break;
	case 'T':
		//////////////////////ACTUALIZACIÓN DE TELEFONOS////////////////////
		if($_POST[operacion]=='U')
		{
			$sqltel="UPDATE `telefonos` SET `telefono` = '$_POST[TELtelefono]', `tipo_telefono` = '$_POST[TELtipo_telefono]' WHERE `id_telefono` = $_POST[id]";
		}
		//////////////////////INSERCIÓN DE TELEFONOS////////////////////
		elseif($_POST[operacion]=='I')
		{
			if($_POST[TELtelefono])
			{
				$sqltel="INSERT INTO `telefonos` (`id_telefono`, `tipo_registro`, `clave_registro`, `tipo_telefono`, `telefono`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$_POST[organizacion]', '$_POST[TELtipo_telefono]', '$_POST[TELtelefono]', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			}
		}
		mysql_query ($sqltel,$db);
		break;
	case 'E':
		//////////////////////ACTUALIZACIÓN DE CORREOS////////////////////
		if($_POST[operacion]=='U')
		{
			$sqlmail="UPDATE `correos` SET `correo` = '$_POST[CORemailo]', `tipo_correo` = '$_POST[CORtipoo]' WHERE `id_correo` = $_POST[id]";
		}
		//////////////////////INSERCIÓN DE CORREOS////////////////////
		elseif($_POST[operacion]=='I')
		{
			if($_POST[CORemailo])
			{
				$sqlmail="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$_POST[organizacion]', '$_POST[CORtipoo]', '$_POST[CORemailo]', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
				echo $sqlmail;
			}
		}
		//mysql_query ($sqlmail,$db);
		
		if( mysql_query ($sqlmail,$db) ) { 
		   // ok! 
		} else { 
		   echo "Has tenido el siguiente error:<br />".mysql_error(); 
		}  		
		break;
	
	case 'W':
		//////////////////////ACTUALIZACIÓN DE DIRECCIONES WEB////////////////////
		if($_POST[operacion]=='U')
		{
			$sqlweb="UPDATE `direccionesweb` SET `direccion` = '$_POST[WEBdireccion_web]', `tipo_direccion` = '$_POST[WEBtipo_direccion_web]' WHERE `id_direccionweb` = $_POST[id]";
		}
		//////////////////////INSERCIÓN DE DIRECCIONES WEB////////////////////
		elseif($_POST[operacion]=='I')
		{
			if($_POST[WEBdireccion_web])
			{
				$sqlweb="INSERT INTO `direccionesweb` (`id_direccionweb`, `tipo_registro`, `clave_registro`, `tipo_direccion`, `direccion`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$_POST[organizacion]', '$_POST[WEBtipo_direccion_web]', '$_POST[WEBdireccion_web]', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			}
		}
		mysql_query ($sqlweb,$db);
		break;
	
	case 'D':
		//////////////////////ACTUALIZACIÓN DE DOMICILIOS////////////////////
		if($_POST[operacion]=='U')
		{
			$sqldom="UPDATE `domicilios` SET `tipo_domicilio` = '$_POST[DOMtipo_domicilio]', `domicilio` = '$_POST[DOMdomicilio]', `ciudad` = '$_POST[DOMciudad]', `estado` = '$_POST[DOMestado]', `cp` = '$_POST[DOMcp]'  WHERE `id_domicilio` = $_POST[id]";
		}
		//////////////////////INSERCIÓN DE DOMICILIOS////////////////////
		elseif($_POST[operacion]=='I')
		{
			if($_POST[DOMdomicilio])
			{
				$sqldom="INSERT INTO `domicilios` (`id_domicilio`, `tipo_registro`, `clave_registro`, `tipo_domicilio`, `domicilio`, `ciudad`, `estado`, `pais`, `cp`, `latitud`, `longitud`, `mapa`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$_POST[organizacion]', '$_POST[DOMtipo_domicilio]', '$_POST[DOMdomicilio]', '$_POST[DOMciudad]', '$_POST[DOMestado]', 'México', '$_POST[DOMcp]', '', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			}
		}
		//echo $sqldom;
		mysql_query ($sqldom,$db);
		break;
	case 'R':
		//////////////////////ACTUALIZACIÓN DE RAZONES SOCIALES////////////////////
		if($_POST[operacion]=='U')
		{
			if($_POST[quickfind])
			{
				if($_POST[RFCrfc]){$rfc=$_POST[RFCrfc];}else{
				$sqlcrfc="SELECT * FROM ventas WHERE Anunciante='".$_POST[quickfind]."' LIMIT 1";
				$resultcrfc=mysql_query ($sqlcrfc,$db);
				$myrowcrfc=mysql_fetch_array($resultcrfc);
				$rfc=$myrowcrfc[Rfc];}
				$sqlrfc="UPDATE `razonessociales` SET `razon_social` = '$_POST[quickfind]', `rfc` = '$rfc' WHERE `id_razonsocial` = $_POST[id]";
			}
		}
		//////////////////////INSERCIÓN DE RAZONES SOCIALES////////////////////
		elseif($_POST[operacion]=='I')
		{
			//if($_POST[RFCrazon_social])
			if($_POST[quickfind])
			{
				if($_POST[RFCrfc]){$rfc=$_POST[RFCrfc];}else{
				$sqlcrfc="SELECT * FROM ventas WHERE Anunciante='".$_POST[quickfind]."' LIMIT 1";
				$resultcrfc=mysql_query ($sqlcrfc,$db);
				$myrowcrfc=mysql_fetch_array($resultcrfc);
				$rfc=$myrowcrfc[Rfc];}
				$sqlrfc="INSERT INTO `razonessociales` (`id_razonsocial`, `tipo_registro`, `clave_registro`, `razon_social`, `rfc`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$_POST[organizacion]', '$_POST[quickfind]', '$rfc', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			}
		}
		mysql_query ($sqlrfc,$db);
		echo "Actualizado con éxito";
		break;
}

//header("Location: http://crm.premo.mx/modulos/organizaciones/editarregistro.php?organizacion=".urlencode($claveorganizacion)); 
//exit;
?>
