<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_POST[organizacion];
$date=date("Ymd");
//Definir Location
$i_header="Location: http://crm.premo.mx/";

switch($_POST[a])
{
	case 'D'://Se accedio desde el Dashboard
		$i_header.="index.php";
		break;
	case 'oP'://Se accedio desde Oportunidades de Organización
		$i_header.="modulos/organizaciones/oportunidades.php";
		break;
	case 'P'://Se accedio desde la lista de Oportunidades
		$i_header.="modulos/oportunidades/oportunidades.php";
		break;		
}

$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
$resultorg= mysql_query ($sqlorg,$db);
$myroworg=mysql_fetch_array($resultorg);

$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_POST[oportunidad]."'";
$resultopt= mysql_query ($sqlopt,$db);
$myrowopt=mysql_fetch_array($resultopt);

//Datos de la etapa en la que está
$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowopt[id_etapa]."'";
$resultetp= mysql_query($sqletp,$db);
$myrowetp=mysql_fetch_array($resultetp);

//Etapa de actualización
$sqletpu="SELECT * FROM `etapas` WHERE `id_etapa` = '".$_POST[id_etapa]."'";
$resultetpu= mysql_query($sqletpu,$db);
$myrowetpu=mysql_fetch_array($resultetpu);

//Actualizar el registro de la oportunidad para AVANZAR
switch ($_POST[id_etapa])
{
	case 13: //SE PREAUTORIZÓ EL CRÉDITO
		//Preparar el texto para el archivo de Términos y Condiciones
		$content = "<page backcolor='#FFFFFF' backimg='../../images/pie.jpg' backimgx='center' backimgy='bottom' backimgw='100%' backtop='0' backbottom='30mm' backleft='10mm' backright='10mm' footer='date;heure;page' style='font-size: 12pt'>
		<bookmark title='Términos y condiciones' level='0' ></bookmark>
		<table cellspacing='0' style='width: 100%; text-align: center; font-size: 14px'>
		<tr>
			<td style='width: 25%;'>
				<img style='width: 100%;' src='../../images/logo_color.jpg' alt='Logo'></td>
		  <td style='width: 75%; color: #444444;'>
			</td>
		</tr>
		</table>
		<br>
		<table cellspacing='0' style='width: 100%; text-align: left;'>
		<tr>
			<td style='width:50%;'></td>
			<td style='width:50%; text-align: right;'>".$_POST[TER_fecha]."</td>
		</tr>
		</table>
		<br>
		<br>
		<b>".$_POST[TER_destinatario]."</b><br>
		".$_POST[TER_empresa]."<br>
		PRESENTE<br>
		<br>
		<br>
		".$_POST[TER_textolibre]." 
		<br>
		<br>
		
		<b><u>TERMINOS Y CONDICIONES</u></b>
		<br>
		<br>    
		
		<b><u>Tipo de crédito:</u></b> ".$_POST[TER_tipocredito]."<br>
		<b><u>Monto:</u></b> ".$_POST[TER_monto]."<br>
		<b><u>Tasa de interés:</u></b> ".$_POST[TER_interes]."<br>
		<b><u>Garantía:</u></b> ".$_POST[TER_garantia]."<br>
		<b><u>Aforo:</u></b> ".$_POST[TER_aforo]."<br>
		<b><u>Comisión por apertura:</u></b> ".$_POST[TER_comision]."<br>
		<b><u>Gastos de formalización:</u></b> ".$_POST[TER_formalizacion]."<br>
		<b><u>Estimación del valor de la garantía:</u></b> ".$_POST[TER_preciogarantia]."<br>
		<b><u>Vigencia:</u></b> ".$_POST[TER_vigencia]."<br>
		<br>";
		
		//Documentos de solicitante y garante
		$p=0;
		if($myroworg[tipo_persona]=="Moral")//Si el acreditado es moral, obtener datos de accionista y rep. legal
		{
		$personas[$p] ['rol_persona'] = 'Acreditado';
		$personas[$p] ['tipo_persona'] = 'Moral';
		$personas[$p] ['nombre'] = $myroworg[organizacion];
		
		$sqlrelacr="SELECT * FROM `relaciones` WHERE `clave_organizacion` = '".$claveorganizacion."' AND rol != 'Garante'";
		$rsrelacr= mysql_query ($sqlrelacr,$db);
		while($rwrelacr=mysql_fetch_array($rsrelacr))
		{
			$sqlconacr="SELECT * FROM contactos WHERE clave_contacto = '".$rwrelacr[clave_contacto]."'";
			$rsconacr= mysql_query ($sqlconacr,$db);
			$rwconacr=mysql_fetch_array($rsconacr);
			$p++;
			$personas[$p] ['rol_persona'] = 'Acreditado';
			$personas[$p] ['tipo_persona'] = $rwrelacr[rol];
			$personas[$p] ['nombre'] = $rwconacr[nombre_completo];
			}
		}
		else
		{
			$personas[$p] ['rol_persona'] = 'Acreditado';
			$personas[$p] ['tipo_persona'] = 'Física';
			$personas[$p] ['nombre'] = $myroworg[organizacion];
		}
		
		//Obtener Garante
		$sqlrelacion="SELECT * FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$myrowopt[clave_oportunidad]."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC" ;
		$rsrelacion= mysql_query($sqlrelacion,$db);
		$rwrelacion=mysql_fetch_array($rsrelacion);
		if($rwrelacion)//Hay Garante
		{
		$p++;
		$personas[$p] ['rol_persona'] = $rwrelacion[rol];
		$personas[$p] ['tipo_persona'] = $rwrelacion[tipo_persona];
		$personas[$p] ['nombre'] = $rwrelacion[organizacion];
		
		if($rwrelacion[tipo_persona]=="Moral")//Si el garante es moral, obtener datos de accionista y rep. legal
		{
			$sqlrel="SELECT * FROM relaciones WHERE clave_organizacion = '".$rwrelacion[clave_relacion]."'";
			$rsrel= mysql_query ($sqlrel,$db);
			while($rwrel=mysql_fetch_array($rsrel))
			{
				$sqlcon="SELECT * FROM contactos WHERE clave_contacto = '".$rwrel[clave_contacto]."'";
				$rscon= mysql_query ($sqlcon,$db);
				$p++;
				while($rwcon=mysql_fetch_array($rscon))
				{
					$personas[$p] ['rol_persona'] = 'Garante';
					$personas[$p] ['tipo_persona'] = $rwrel[rol];
					$personas[$p] ['nombre'] = $rwcon[nombre_completo];
				}
			}
		}
		}//FIN DE IF GARANTE
		$width=round((100/($p+2)),0);
		
		$content.="<table cellspacing='0' style='width: 100%; border: 1px solid #E3E3E3; font-size: 8pt;  table-layout:fixed; overflow:auto;'>
		<thead>
			<tr>
				<th scope='col' style='width:".$width."%; text-align:left;   white-space:normal;'>Documentación Requerida</th>";
				foreach($personas as $persona)
				{
				$rol="";
				if($persona['tipo_persona']!='Moral'&&$persona['tipo_persona']!='Física'){$rol=" (".$persona['tipo_persona'].")";}
				$content.="<th scope='col' style='width:".$width."%; text-align:center; white-space:normal;'>".$persona['rol_persona'].$rol."<br />".$persona['nombre']."</th>";
				}
			$content.="</tr>
		</thead>
		<tbody>";
			$sqldocumentos="SELECT DISTINCT(tipo_archivo) FROM `tiposarchivos` WHERE id_expediente='2' ORDER BY tipo_archivo ASC";
			$rsdocumentos= mysql_query($sqldocumentos,$db);
			while($rwdocumentos=mysql_fetch_array($rsdocumentos))
			{
				$content.="<tr>
				<td style='width:14%; text-align:left; border:1px solid #E3E3E3; white-space:normal;'>".$rwdocumentos[tipo_archivo]."</td>";
				foreach($personas as $persona)
				{
					$sqldoc="SELECT * FROM `tiposarchivos` WHERE `tipo_archivo` LIKE '".$rwdocumentos[tipo_archivo]."' AND `id_expediente` = 2 AND `tipo_persona` LIKE '".$persona[tipo_persona]."' AND `rol_persona` LIKE '".$persona[rol_persona]."'";
					
					$rsdoc= mysql_query($sqldoc,$db);
					$rwdoc=mysql_fetch_array($rsdoc);
					if($rwdoc)
					{
					$content.="<td style='width:".$width."%; text-align:center; border:1px solid #E3E3E3;'><img src='../../images/aprovedgray.png' /></td>";
					}
					else
					{
					$content.="<td style='width:".$width."%; text-align:center; border:1px solid #E3E3E3;'></td>";	
					}
				}
			$content.="</tr>";
		}
		$content.="</tbody>
		</table>
		<br>
		<br>
		<b><u>Proceso de revisión:</u></b> ".$_POST[TER_procesorevision]."<br>
		<b><u>Fecha estimada de firma:</u></b> ".$_POST[TER_fechafirma]."<br>
		<b><u>Depósito de seriedad:</u></b> ".$_POST[TER_deposito]."<br>
		<b><u>Referencia:</u></b> Puede realizar el depósito de seriedad con la referencia: ".$_POST[TER_referencia].", a la cuenta de Banorte: 0806433934, CLABE: 072225008064339344, a nombre de: Préstamo Empresarial Oportuno S.A. de C.V. SOFOM ENR.<br>
		<br>
		
		<nobreak>
		<table cellspacing='0' style='width: 100%; text-align: left;'>
			<tr>
				<td style='width:50%;'>
					Atentamente<br><br>
					".$_POST[TER_remitente]."<br>
					".$_POST[TER_puesto]."<br>
					Préstamo Empresarial Oportuno, S.A. de C.V., SOFOM, E.N.R.<br>
				</td>
				<td style='width:50%;'>
				</td>
			</tr>
		</table>
		</nobreak>
		</page>";
		
		// convert to PDF
		require_once('../../html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'Letter', 'es');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			//$html2pdf->pdf->SetProtection(array('print'), 'spipu');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$ruta="../../expediente/";
			$nombreoriginal="TC".$date."-".strtoupper($myroworg[organizacion]);
			$nombre="T".time()."C".rand(100,999).rand(10,99).".pdf";
			$html2pdf->Output($ruta.$nombre, 'F');
			$clavearchivo = generateKey();
			
			//Verificar si ya hay archivo de TERMINOS Y CONDICIONES generado
			$sqlfile="SELECT * FROM archivos WHERE id_tipoarchivo='10' AND id_oportunidad='".$_POST[oportunidad]."'";
			$rsfile= mysql_query ($sqlfile,$db);
			$rwfile=mysql_fetch_array($rsfile);
			$archivoanterior="../../expediente/".$rwfile[nombre];
			
			if($rwfile)//Ya hay un archivo, será reemplazado
			{
				//Obtener referencia anterior
				$sqref="SELECT * FROM `referencias` WHERE asignada=1 AND descartado=0 AND clave_oportunidad='".$myrowopt[clave_oportunidad]."' ORDER BY fecha_asignacion ASC LIMIT 1";
				$rsref= mysql_query ($sqlref,$db);
				$rwref=mysql_fetch_array($rsref);
				
				unlink($archivoanterior);//Borrar archivo anterior
				$sqlarchivo="UPDATE `archivos` SET `nombre`='$nombre', `fecha_modificacion`=NOW(), `aprobado`='0' WHERE `id_archivo` = '".$rwfile[id_archivo]."'";//Actualizar registro
				$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL, '$rwfile[clave_archivo]', '$_POST[oportunidad]','3','Reemplazado', '', NOW(),'$claveagente')";//Actualizar historial
				$sqldescartar="UPDATE `referencias` SET `descartada`='1' WHERE `referencia`='$rwref[referencia]'";//La referencia usada será descartada
				$sqlref="UPDATE `referencias` SET `clave_cliente`='$claveorganizacion',`clave_oportunidad`='$myrowopt[clave_oportunidad]',`asignada`='1',`fecha_asignacion`=NOW() WHERE `referencia`='$_POST[TER_referencia]'";
				
				//Actualizar el crédito preautorizado
				$sqlcredito="UPDATE `creditos` SET `estatus`='Pre autorizado' WHERE clave_oportunidad='".$myrowopt[clave_oportunidad]."'";
			}
			else
			{
				$sqlarchivo="INSERT INTO `archivos`(`id_archivo`, `clave_archivo`, `id_tipoarchivo`, `id_oportunidad`, `clave_oportunidad`, `nombre_original`, `nombre`, `extension`, `fecha_captura`, `fecha_modificacion`, `aprobado`, `usuario_captura`, `usuario_aprobacion`, `fecha_aprobacion`, `id_expediente`) VALUES (NULL,'$clavearchivo','10','$_POST[oportunidad]','$myrowopt[clave_oportunidad]','$nombreoriginal','$nombre','pdf',NOW(),NOW(),'0','$claveagente','','','3')";
				$sqlhistorial="INSERT INTO `historialarchivos`(`id_historialarchivo`, `clave_archivo`, `id_oportunidad`, `id_expediente`, `actividad`, `motivo`, `fecha_actividad`, `usuario`) VALUES (NULL,'$clavearchivo','$_POST[oportunidad]','3','Generado', '', NOW(),'$claveagente')";
				$sqlref="UPDATE `referencias` SET `clave_cliente`='$claveorganizacion',`clave_oportunidad`='$myrowopt[clave_oportunidad]',`asignada`='1',`fecha_asignacion`=NOW() WHERE `referencia`='$_POST[TER_referencia]'";
			
			}	
			//Actualizar el registro de la oportunidad para AVANZAR
			$sqloportunidades="UPDATE `oportunidades` SET `marcado`='0', `motivo`='', `id_etapa`='$_POST[id_etapa]', `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
			
			//Registrar avance de la oportunidad
			$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$_POST[id_etapa]', '$myrowetpu[etapa]', NOW(), '$claveagente', NOW())";
			
			//Registrar crédito preautorizado
			$sqlcredito="INSERT INTO `creditos`(`id_credito`, `clave_organizacion`, `clave_oportunidad`, `tipo_credito`, `monto_credito`, `tasa_interes`, `garantia`, `aforo`, `comision_apertura`, `gastos_formalizacion`, `valor_garantia`, `estatus`, `motivo_rechazo`, `fecha_firma`, `usuario`) VALUES (NULL,'$claveorganizacion','$myrowopt[clave_oportunidad]','$_POST[TER_tipocredito]','$_POST[TER_monto]','$_POST[TER_interes]','$_POST[TER_garantia]','$_POST[TER_aforo]','$_POST[TER_comision]','$_POST[TER_formalizacion]','$_POST[TER_preciogarantia]','Pre autorizado','','','$claveagente')";
			
			//Enviar mail
			$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
			$rspromotor= mysql_query($sqlpromotor,$db);
			$rwpromotor=mysql_fetch_array($rspromotor);
			$headers = "MIME-Version: 1.1\n";
			$headers .= "Content-type: text/plain; charset=UTF-8\n";
			$headers .= "From: alarmas@crm.premo.mx\n"; // remitente
			$headers .= "Return-Path: alarmas@crm.premo.mx\n"; // return-path
			$cuerpo = "Hola ".$rwpromotor[nombre].", el usuario ".$claveagente." te ha enviado un proceso a la etapa: ".$myrowetpu[etapa].", no olvides darles seguimiento \n\n";
			$cuerpo .= "\nAdministrador del CRM";
			$asunto = $rwpromotor[nombre]." Tienes nuevos procesos asignados en el sistema";
			
			mysql_query($sqlarchivo,$db);
			mysql_query($sqlhistorial,$db);
			//mysql_query($sqldescartar,$db);
			mysql_query($sqlref,$db);
			mysql_query($sqlcredito,$db);
		}
		catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
		}
		break;
	case 11: //SE RECHAZÓ EL CRÉDITO
		$sqloportunidades="UPDATE `oportunidades` SET `marcado` = '1', `motivo` = 'Rechazo',  `id_rechazo` = '$_POST[motivo_rechazo]', `id_etapa` = '$_POST[id_etapa]', `fecha_cierre_real`= NOW(), `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
		//Registrar avance de la oportunidad
		$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$_POST[id_etapa]', '$myrowetpu[etapa]', NOW(), '$claveagente', NOW())";
		//Enviar mail
		$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
		$rspromotor= mysql_query($sqlpromotor,$db);
		$rwpromotor=mysql_fetch_array($rspromotor);
		$headers = "MIME-Version: 1.1\n";
		$headers .= "Content-type: text/plain; charset=UTF-8\n";
		$headers .= "From: alarmas@crm.premo.mx\n"; // remitente
		$headers .= "Return-Path: alarmas@crm.premo.mx\n"; // return-path
		$cuerpo = "Hola ".$rwpromotor[nombre].", el usuario ".$claveagente." ha rechazado procesos en el sistema. \n\n";
		$cuerpo .= "\nAdministrador del CRM";
		$asunto = $rwpromotor[nombre]." Tienes nuevos procesos rechazados en el sistema";
		mail("denmed2210@gmail.com",$asunto,$cuerpo,$headers);
		//mail($rwpromotor[email],$asunto,$cuerpo,$headers);
		break;
		
	case 4: //SE RETROCEDE EL PROCESO A RECABACIÓN DE EXPEDIENTE PRELIMINAR
		$sqloportunidades="UPDATE `oportunidades` SET `marcado`='1', `motivo`='Retroceso de etapa', `id_etapa`='$_POST[id_etapa]', `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$_POST[oportunidad]."'";
		//Registrar avance de la oportunidad
		$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$_POST[id_etapa]', '$myrowetpu[etapa]', NOW(), '$claveagente', NOW())";
		//Enviar mail
		$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
		$rspromotor= mysql_query($sqlpromotor,$db);
		$rwpromotor=mysql_fetch_array($rspromotor);
		$headers = "MIME-Version: 1.1\n";
		$headers .= "Content-type: text/plain; charset=UTF-8\n";
		$headers .= "From: alarmas@crm.premo.mx\n"; // remitente
		$headers .= "Return-Path: alarmas@crm.premo.mx\n"; // return-path
		$cuerpo = "Hola ".$rwpromotor[nombre].", el usuario ".$claveagente." te ha devuelto un proceso a la etapa: ".$myrowetpu[etapa].", no olvides darles seguimiento \n\n";
		$cuerpo .= "\nAdministrador del CRM";
		$asunto = $rwpromotor[nombre]." Tienes nuevos procesos asignados en el sistema";
		//mail("dmedina@am.com.mx",$asunto,$cuerpo,$headers);
		mail($rwpromotor[email],$asunto,$cuerpo,$headers);
		break;
}//Fin switch etapa de actualización

mysql_query($sqloportunidades,$db);
mysql_query($sqletapaoportun,$db);
header($i_header."?organizacion=".urlencode($claveorganizacion));

?>
