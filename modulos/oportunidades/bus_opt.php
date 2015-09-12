<?php
include("../../seguridad.php");
include('../../config/config.php');
include("../../util.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$organizacion=$_POST['claveorganizacion'];

$_SESSION['Estatus'] = $_POST['estatus'];
$_SESSION['Agente'] = $_POST['agente'];
$estatus=$_SESSION['Estatus'];
$agente=$_SESSION['Agente'];

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;
$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

switch($estatus)
{
    case ''://Todas las oportunidades
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado DESC";}
        else{if($agente){$_pagi_sql="SELECT * FROM oportunidades WHERE usuario ='".$agente."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades ORDER BY fecha_cierre_esperado DESC";}}
        break;

    case 'Abiertas'://Todas las abiertas
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado DESC";}
        else{if($agente){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') AND usuario ='".$agente."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') ORDER BY fecha_cierre_esperado DESC";}}
        break;

    case 'Cerradas'://Todas las cerradas
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado DESC";}
        else{if($agente){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') AND usuario ='".$agente."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') ORDER BY fecha_cierre_esperado DESC";}}
        break;

    case 'MesCerradas'://Cerradas en el mes actual
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND
        YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11') AND usuario = '".$claveagente."'";}
        else{if($agente){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11') AND usuario = '".$agente."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11')";}}
        break;

    case 'MesCaptura'://Capturadas en el mes actual
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND usuario = '".$claveagente."'";}
        else{if($agente){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND usuario = '".$agente."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE)";}}
        break;
    
    case '11':
    case '10':	
    case '9':
    case '8':
    case '7':
    case '13':
    case '12':
    case '5':
    case '4':
    case '3':
    case '2':
    case '1':
        if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$estatus."' AND usuario LIKE '".$claveagente."' ORDER BY `fecha_captura` DESC";}
        else{if($agente){$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$estatus."' AND usuario LIKE '".$agente."' ORDER BY `fecha_captura` DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$estatus."' ORDER BY `fecha_captura`,`usuario` DESC";}}
        break;
}

$_pagi_nav_num_enlaces=20;
$_pagi_cuantos = 10;
$_pagi_propagar = array("estatus","agente");
include("paginator.inc.php");
?>
<link href="../../style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

	<fieldset class="fieldsetgde">
    <legend>Mostrando <?php echo $_pagi_info; ?> oportunidades</legend>
    <table class="recordList" style="margin-top: 12px;">
<thead>
<tr>
<th class="list-column-left" scope="col">Oportunidad</th>
<th class="list-column-left" scope="col">Etapa</th>
<th class="list-column-left" scope="col">Antigüedad</th>
<th class="list-column-left" scope="col">Real</th>
</tr>
</thead> 
	<tbody>
<?php
$i=0;
while($row = mysql_fetch_array($_pagi_result))
{
	$nombre_oportunidad = $row[productos];
	$descripcion_oportunidad = $row[descripcion_oportunidad];
	if($row[tipo_credito]){$tipo = $row[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
	if($row[monto]){$monto = " por ".number_format($row[monto]);}else{$monto=" Monto: sin especificar, ";}
	if($row[plazo_credito]){$plazo = " a ".$row[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
	$id_etapa = $row[id_etapa];
	
	list($dias, $meses) = diferencia_dias($date,$row[fecha_captura]);
			
	//Semaforización de oportunidades
	if($dias>90){$antiguedad= $meses." meses"; $resaltado="#FF7F7F";}
	elseif($dias>=31&&$dias<=90){$antiguedad= $meses." meses"; $resaltado="#FFCC00";}
	else{if($meses==1){$antiguedad=$meses. "mes";}else{$antiguedad= $dias." días"; $resaltado="#86CE79";}}
	
	//Datos del Agente
	$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$row[usuario]."'";
	$resultagente= mysql_query ($sqlagente,$db);
	while($myrowagente=mysql_fetch_array($resultagente))
	{
		$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
	}
	//Datos de la organización
	$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$row[clave_organizacion]."'";
	$resultorg= mysql_query ($sqlorg,$db);
	while($myroworg=mysql_fetch_array($resultorg))
	{
		$organizacion=$myroworg[organizacion];
		$claveorg=$myroworg[clave_organizacion];
		$tipoorg=$myroworg[tipo_persona];
	}
	
	$fecha=explode("-",$row[fecha]);
	$time=explode(":",$row['hora']);
	$hora=$time[0].":".$time[1];
	
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	while($myrowetp=mysql_fetch_array($resultetp))
	{
		$etapa = $myrowetp[etapa];
		$anterior= $myrowetp[etapa_anterior];
		$siguiente= $myrowetp[etapa_siguiente];
		$probabilidad = $myrowetp[probabilidad];
		$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
		$resultcolor=mysql_query($sqlcolor,$db);
		$myrowcolor=mysql_fetch_array($resultcolor);
		
		//Color de celda y vínculos
		if($myrowetp[id_responsable]==$responsable||$responsable==3)
		{
			$celda="#FFFFFF";
			$vinculo=1;
		}
		else
		{
			$celda="#F0F0F0";
			$vinculo=0;
		}
	}
	
	//Verificar si se solicita algún expediente en la etapa de la oportunidad
	$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$id_etapa."' OR id_etapa='".$anterior."'";
	$resultexp= mysql_query ($sqlexp,$db);
	$myrowexp=mysql_fetch_array($resultexp);
	if($myrowexp)//Si hay expediente asociado a la etapa de la oportunidad, obtener los datos necesarios.
	{			
		//Obtener cuántos tipos de archivos tiene el expediente solicitado
		$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
		$resulttipos= mysql_query ($sqltipos,$db);
		$totarch=mysql_num_rows($resulttipos);//archivos totales del expediente
		$myrowtipos=mysql_fetch_array($resulttipos);
		
		//Numeros para expediente
		$sqlarchivo="SELECT count(`id_archivo`)as total, aprobado FROM `archivos` WHERE id_oportunidad = '".$row[id_oportunidad]."' GROUP BY (`aprobado`)";
		$resultarchivo= mysql_query ($sqlarchivo,$db);
		$aprobados=0;$nrevisado=0;$rechazados=0;$cargados=0;
		while($myrowarchivo=mysql_fetch_array($resultarchivo))
		{						
			if($myrowarchivo[aprobado]==0){$nrevisado=$myrowarchivo[total];}elseif($myrowarchivo[aprobado]==1){$aprobados=$myrowarchivo[total];}else{$rechazados=$myrowarchivo[total];}
		}
		$cargados=$aprobados+$rechazados+$nrevisado;
	}

$res="<tr class='odd-row'>
<td class='list-column-left'><span class='task-title'>";
if($row[marcado]==1){$res.="<span class='label-overdue'>".$row[motivo]."</span>";}
$res.="<a href='forminsert.php?id=".$row[id_oportunidad]."&o=U&a=P&organizacion=".$claveorg."'>".$nombre_oportunidad." ".$tipo.$monto.$plazo."</a></span> para "; 

if($tipoorg){ $res.="<span class='highlight' title='".$tipoorg."'>".$tipoorg[0]." </span>"; }

$res.="<a href='../organizaciones/detalles.php?organizacion=".$row[clave_organizacion]."'>".$organizacion."</a> "; if($_SESSION[Tipo]!="Promotor"){$res.="<span class='highlight' style='background-color:#9FC733'>".$agente."</span>"; }
$res.="<br /><span class='subtext'>Destino del crédito: "; if($row[destino_credito]){$res.= $myrowopt[destino_credito];}else{$res.="Sin especificar";} $res.="</span>"; list ($link,$autorizacion)= vinculos($row[clave_organizacion],$row[id_oportunidad],$row[id_etapa],P,2,$responsable); $res.=$link;
list ($barra, $campos) = barra($row[clave_organizacion],$row[clave_oportunidad],2); $res.=" ".$barra."<span class='subtext'>".number_format($campos,0)."%</span>"; $res.="</td>
<td class='list-column-left'><span class='highlight' style='background-color:"; if($id_etapa!=10&&$id_etapa!=11){$res.="#".$myrowcolor[color];} else {$res.="#C1C1C1";} $res.="'>".$etapa."</span></td>
<td class='list-column-center'><span class='highlight' style='background-color:"; if($id_etapa!=10&&$id_etapa!=11){$res.=$resaltado;} else {$res.="#C1C1C1";} $res.="' title='".htmlentities(strftime('%A, %d de %B', strtotime($row[fecha_captura])))."'>".$antiguedad."</span></td>
<td class='list-column-left'>";if($id_etapa==10||$id_etapa==11){$res.="<span class='highlight' style='background-color:#C1C1C1;'>".htmlentities(strftime('%a, %b, %d', strtotime($row[fecha_cierre_real])))."</span>";} $res.="</td>
</tr>";
		echo $res; 
		$i++;
	}

?>
	</tbody>
	</table>
    <?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>
    </fieldset>