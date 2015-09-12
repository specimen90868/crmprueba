<?php
include ("../../seguridad.php");
include('../../config/config.php');
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

$estatus=$_POST['estatus'];
$organizacion=$_POST['claveorganizacion'];

$numeroagente=$claveagente;

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

//SI SOLO HAY UNA PALABRA DE BUSQUEDA SE ESTABLECE UNA INSTRUCCION CON LIKE

switch($estatus)
{
	case ''://Todas las oportunidades
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM oportunidades WHERE usuario ='".$claveagente."' AND clave_organizacion ='".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE clave_organizacion ='".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
		
	case 'Abiertas'://Todas las abiertas
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='6' AND id_etapa!='7') AND usuario ='".$claveagente."' AND clave_organizacion ='".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='6' AND id_etapa!='7') AND clave_organizacion ='".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
		
	case 'Cerradas'://Todas las cerradas
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='6' OR id_etapa='7') AND usuario ='".$claveagente."' AND clave_organizacion ='$organizacion' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='6' OR id_etapa='7') AND clave_organizacion ='$organizacion' ORDER BY fecha_cierre_esperado DESC";}
		include("paginator.inc.php");
		break;
		
	case 'MesCerradas'://Cerradas en el mes actual
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND (id_etapa='6' OR id_etapa='7') AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND (id_etapa='6' OR id_etapa='7') AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
		
	case 'MesCaptura'://Capturadas en el mes actual
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
		
	case 'MesCierre'://Próximas a cerrar en el mes actual
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_esperado) = MONTH(CURRENT_DATE) AND (id_etapa!='6' AND id_etapa!='7') AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_esperado) = MONTH(CURRENT_DATE) AND (id_etapa!='6' AND id_etapa!='7') AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
		
	case 'MesProximoCierre'://Próximas a cerrar el mes siguiente al actual
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM oportunidades WHERE MONTH(fecha_cierre_esperado) = MONTH(DATE_ADD(NOW(), INTERVAL 1 MONTH)) AND (id_etapa!='6' AND id_etapa!='7') AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql = "SELECT * FROM oportunidades WHERE MONTH(fecha_cierre_esperado) = MONTH(DATE_ADD(NOW(), INTERVAL 1 MONTH)) AND (id_etapa!='6' AND id_etapa!='7') AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
		
	case 'SemanaCierre'://Próximas a cerrar durante esta semana
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM oportunidades AS o WHERE YEARweek(o.fecha_cierre_esperado) = YEARweek(CURRENT_date) AND (id_etapa!='6' AND id_etapa!='7') AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql = "SELECT * FROM oportunidades AS o WHERE YEARweek(o.fecha_cierre_esperado) = YEARweek(CURRENT_date) AND (id_etapa!='6' AND id_etapa!='7') AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
		
	case 'SemanaProximaCierre'://Próximas a cerrar la semana siguiente a la actual
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM oportunidades AS o WHERE YEARweek(o.fecha_cierre_esperado) = YEARweek(CURRENT_date + INTERVAL 7 DAY) AND (id_etapa!='6' AND id_etapa!='7') AND usuario = '".$claveagente."' AND clave_organizacion ='".$organizacion."'";}else{$_pagi_sql="SELECT * FROM oportunidades AS o WHERE YEARweek(o.fecha_cierre_esperado) = YEARweek(CURRENT_date + INTERVAL 7 DAY) AND (id_etapa!='6' AND id_etapa!='7') AND clave_organizacion ='".$organizacion."'";}
		include("paginator.inc.php");
		break;
	case '7':
	case '6':
	case '5':
	case '4':
	case '3':
	case '2':
	case '1':
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$estatus."' AND usuario LIKE '".$claveagente."' AND clave_organizacion LIKE '".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$estatus."' AND clave_organizacion LIKE '".$organizacion."' ORDER BY fecha_cierre_esperado DESC";}
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
}

?>
<link href="../../style.css" rel="stylesheet" type="text/css" />

	<fieldset class="fieldsethistorial">
    <legend>Mostrando <?php echo $_pagi_info; ?> oportunidades</legend>
    <table class="recordList" style="margin-top: 12px;">
<thead>
<tr>
<th class="list-column-left" scope="col">Oportunidad</th>
<th class="list-column-left" scope="col">Etapa</th>
<th class="list-column-right" scope="col">Valor</th>
<th class="list-column-left" scope="col">Abierta</th>
<th class="list-column-left" scope="col">Esperado</th>
<th class="list-column-left" scope="col">Real</th>
</tr>
</thead> 
	<tbody>
<?php
$i=0;
while($row = mysql_fetch_array($_pagi_result))
{
	$nombre_oportunidad = $row[nombre_oportunidad];
	$descripcion_oportunidad = $row[descripcion_oportunidad];
	$monto = $row[monto];
	$id_etapa = $row[id_etapa];
	$fecha_cierre_esperado = $row[fecha_cierre_esperado];
	$recurrencia=$row[recurrencia];
	//$dias = diferencia_dias($fecha_cierre_esperado,$date);
	
	//Definir fecha 1 
	$minuendo=explode("-",$fecha_cierre_esperado);
	$ano1 = $minuendo[0]; 
	$mes1 = $minuendo[1]; 
	$dia1 = $minuendo[2]; 
	//Definir fecha 2 
	$sustraendo=explode("-",$date);
	$ano2 = $sustraendo[0]; 
	$mes2 = $sustraendo[1]; 
	$dia2 = $sustraendo[2];
	//Calcular timestamp de las dos fechas 
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
	$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
	//Restar a una fecha la otra 
	$segundos_diferencia = $timestamp1 - $timestamp2; 
	//Convertir segundos en días 
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	//Obtener el valor absoulto de los días (quito el posible signo negativo) 
	//$dias_diferencia = abs($dias_diferencia); 
	//Quitar los decimales a los días de diferencia 
	$dias_diferencia = floor($dias_diferencia);
	
	//Semaforización de oportunidades
	if($dias_diferencia<=0){$resaltado="#FF7F7F";}
	elseif($dias_diferencia<=7){$resaltado="#FFCC00";}
	else{$resaltado="#86CE79";}
	
	////////aqui me quedeee//////////////
	
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	while($myrowetp=mysql_fetch_array($resultetp))
	{
		$etapa = $myrowetp[etapa];
		$probabilidad = $myrowetp[probabilidad];
	}
	
$fecha=explode("-",$row[fecha]);
$time=explode(":",$row['hora']);
$hora=$time[0].":".$time[1];

$res="<tr class='odd-row'>
<td class='list-column-left'>
<a href='forminsert.php?id=".$row[id_oportunidad]."&o=U'>".$nombre_oportunidad."</a><br />".$descripcion_oportunidad."</td>
<td class='list-column-left'>".$etapa."(".$probabilidad."%)</td>
<td class='list-column-right'>".number_format($monto)."</td>
<td class='list-column-left'><span class='highlight' style='background-color:#C1C1C1;'>".htmlentities(strftime('%a, %b, %d', strtotime($row[fecha_captura])))."</span></td>
<td class='list-column-left'><span class='highlight' style='background-color:"; if($id_etapa!=6&&$id_etapa!=7){$res.=$resaltado;} else {$res.="#C1C1C1";} $res.="'>".htmlentities(strftime('%a, %b, %d', strtotime($fecha_cierre_esperado)))."</span></td>
<td class='list-column-left'>";if($id_etapa==6||$id_etapa==7){$res.="<span class='highlight' style='background-color:#C1C1C1;'>".htmlentities(strftime('%a, %b, %d', strtotime($row[fecha_cierre_real])))."</span>";} $res.="</td>
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