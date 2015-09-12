<?php
include ("../config/config.php");
include ("../util.php");
include ("../includes/FusionCharts.php");

mysql_query("SET NAMES 'utf8'");

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];

if($date==$Anio."-".$month."-01")
{
	$Mes=$Mes-1;
	$ultimodia= $Anio."-".$Mes."-".ultimo_dia($Mes,$Anio)." 00:00:00";
	$primerdia= $Anio."-".$Mes."-01 00:00:00";
}
else
{
	//$Mes=1;
	$ultimodia= $Anio."-".$Mes."-".ultimo_dia($Mes,$Anio)." 00:00:00";
	$primerdia= $Anio."-".$Mes."-01 00:00:00";
}

//Agentes Activos
$sqlagt="SELECT * FROM usuarios WHERE estatus='1' AND tipo='Promotor' ORDER BY claveagente";
$resultagt= mysql_query ($sqlagt,$db);
while($myrowagt=mysql_fetch_array($resultagt))
{
	//Oportunidades en etapa de entrega de propuesta a promotor
	$sqloportunidades="SELECT * FROM `oportunidades` WHERE `usuario` = '".$myrowagt[claveagente]."' AND id_etapa=7 ORDER BY `fecha_modificacion` DESC";
	$resultopt = mysql_query($sqloportunidades, $db);
	$myrowopt=mysql_fetch_array($resultopt);
	$opt = mysql_num_rows($resultopt);
	if($opt)
	{
		//Datos del proceso
		$sqlproceso="SELECT * FROM `etapasoportunidades` WHERE `clave_oportunidad` = '".$myrowopt[clave_oportunidad]."' AND id_etapa='7'";
		$rsproceso= mysql_query($sqlproceso,$db);
		$rwproceso=mysql_fetch_array($rsproceso);
		
		//Datos de organización
		$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$myrowopt[clave_organizacion]."'";
		$rsorg= mysql_query($sqlorg,$db);
		$rworg=mysql_fetch_array($rsorg);

		//Verificar fecha en que se asignó a la etapa de entrega de propuesta a promotor
		list($dias, $meses) = diferencia_dias($rwproceso[fecha],$date);
		$vigencia=30-$dias;
		echo "Promotor: ".$myrowagt[nombre]." Vigencia: ".$vigencia."días \n";
		if($dias<30)//No han pasado 30 días desde que la propuesta fue enviada al promotor
		{
			if($vigencia==5)//Enviar correo de alerta a promotor
			{
				$empresas.= $rworg[organizacion].", Vigencia de la propuesta: ".$vigencia." días";
				$empresas.= "\n";
				$headers = "MIME-Version: 1.1\n";
				$headers .= "Content-type: text/plain; charset=UTF-8\n";
				$headers .= "From: crm@anabiosiscrm.com.mx\n"; // remetente
				$headers .= "Return-Path: crm@anabiosiscrm.com.mx\n"; // return-path
				$cuerpo = "Hola ".$myrowagt[nombre].": \n\n";
				$cuerpo .= "La vigencia de las siguiente(s) propuesta(s) está por vencer:\n\n";
				$cuerpo .= $empresas;
				$cuerpo .= "\n\nRecuerda contactar a tus prospectos y clientes, y actualizar los resultados en el sistema \n\n";
				$cuerpo .= "Administrador del CRM";
				$asunto = "Tienes ".$opt." propuesta(s) en riesgo de vencer";
				mail($myrowagt[email],$asunto,$cuerpo,$headers);
			}
		}
		else//Ya han pasado más de 30 días desde que la propuesta fue enviada al promotor
		{
			$sqlupdate="UPDATE `oportunidades` SET `id_rechazo` = '9', `id_etapa` = '11', `fecha_cierre_real`= NOW(), `fecha_modificacion` = NOW() WHERE `id_oportunidad` = '".$myrowopt[id_oportunidad]."'";
			$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '11', 'Cierre (Crédito Rechazado)', NOW(), 'proceso', NOW())";
			$sqlcredito="UPDATE `creditos` SET `estatus`='Rechazado',`motivo_rechazo`='Expiración de Propuesta' WHERE clave_oportunidad='".$myrowopt[clave_oportunidad]."'";
			$sqref="SELECT * FROM `referencias` WHERE asignada=1 AND descartado=0 AND clave_oportunidad='".$myrowopt[clave_oportunidad]."' ORDER BY fecha_asignacion DESC LIMIT 1";
			$rsref= mysql_query ($sqref,$db);
			$rwref=mysql_fetch_array($rsref);
			$sqldescartar="UPDATE `referencias` SET `descartado`='1',`motivo`='Desaprobación de Términos y Condiciones' WHERE `id_referencia`='".$rwref[id_referencia]."'";//La referencia usada será descartada
			
			$empresas.= $rworg[organizacion].", Vigencia de la propuesta: ".$vigencia." días";
			mysql_query ($sqlupdate,$db);
			mysql_query ($sqletapaoportun,$db);
			mysql_query ($sqlcredito,$db);
			mysql_query ($sqldescartar,$db);
			//Enviar correo avisando que se cerró el proceso
			$empresas.= "\n";
			$headers = "MIME-Version: 1.1\n";
			$headers .= "Content-type: text/plain; charset=UTF-8\n";
			$headers .= "From: crm@anabiosiscrm.com.mx\n"; // remetente
			$headers .= "Return-Path: crm@anabiosiscrm.com.mx\n"; // return-path
			$cuerpo = "Hola ".$myrowagt[nombre].": \n\n";
			$cuerpo .= "La(s) siguiente(s) propuesta(s) ha(n) vencido:\n\n";
			$cuerpo .= $empresas;
			$cuerpo .= "\n\nRecuerda contactar a tus prospectos y clientes, y actualizar los resultados en el sistema \n\n";
			$cuerpo .= "Administrador del CRM";
			$asunto = "Tienes ".$opt." propuesta(s) vencida(s)";
			mail($myrowagt[email],$asunto,$cuerpo,$headers);
		}
	}
}

?>