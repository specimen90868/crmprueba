<?php
include ("../../seguridad.php");
include('../../config/config.php');
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

$estatus=$_POST['estatus'];
$tipo=$_POST['tiporegistro'];
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

	if($estatus)
	{
		if($tipo)
		{
			$_pagi_sql="SELECT * FROM actividades WHERE completa='".$estatus."' AND tipo='".$tipo."' AND usuario ='".$claveagente."' AND clave_organizacion ='$organizacion' ORDER BY fecha ASC";
			$_pagi_cuantos = 10;
			include("paginator.inc.php");
		}
		else //Tipo de actividad vacío, entonces Todas
		{
			$_pagi_sql="SELECT * FROM actividades WHERE completa='".$estatus."' AND usuario ='".$claveagente."' AND clave_organizacion ='".$organizacion."' ORDER BY fecha ASC";
			$_pagi_cuantos = 10;
			include("paginator.inc.php");
		}
	}
	else //Estatus vacío, entonces todos
	{
		if($tipo)
		{
			$_pagi_sql="SELECT * FROM actividades WHERE tipo='".$tipo."' AND usuario ='".$claveagente."' AND clave_organizacion ='".$organizacion."' ORDER BY fecha ASC";
			$_pagi_cuantos = 10;
			include("paginator.inc.php");
		}
		else //Tipo de actividad vacío, entonces Todas
		{
			$_pagi_sql="SELECT * FROM actividades WHERE usuario ='".$claveagente."' AND clave_organizacion ='".$organizacion."' ORDER BY fecha ASC";
			$_pagi_cuantos = 10;
			include("paginator.inc.php");
		}
	}
	function limitarPalabras($cadena, $longitud, $elipsis = "...")
	{
		$palabras = explode(' ', $cadena);
		if (count($palabras) > $longitud)
			return implode(' ', array_slice($palabras, 0, $longitud)) . $elipsis;
		else
			return $cadena;
	}
?>
<link href="../../style.css" rel="stylesheet" type="text/css" />

	<fieldset class="fieldsethistorial">
    <legend>Mostrando <?php echo $_pagi_info; ?> actividades</legend>
    <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="../actividades/forminsert.php?o=I&a=O&organizacion=<?php echo $claveorganizacion; ?>">agregar actividad</a></div>
    <table class="recordList" > 
	<tbody>
<?php
	//$result=mysql_query($_pagi_sql, $db);
	$i=1;
	while ($row = mysql_fetch_array($_pagi_result))
	{
		$fecha=explode("-",$row[fecha]);
		$time=explode(":",$row['hora']);
		$hora=$time[0].":".$time[1];

		$res="<tr class='odd-row'>
		<td class='list-column-left'>";
		
		if ($row[completa]==2)
		{
			$res.="<img src='../../images/incompleta.png' />";
			//$res.="<input type='checkbox' onclick='' />";
		} 
		else 
		{
			$res.="<img src='../../images/completa.png' />";
			//$res.="<input type='checkbox' onclick='' disabled='disabled' checked='checked'/>";
		}
		$res.="</td><td class=' list-column-left'>";
		
		if(strtotime($row[fecha]) < strtotime($date)&&$row[completa]<>1)
		{
			$res.="<span class='label-overdue'>Atrasado</span>";
		}
		$res.="<span id=''>
		<span class='task-title'><span class='highlight' style='background-color:".$row[color].";'>".$row[tipo]." </span> <a href='../actividades/forminsert.php?id=".$row[id_actividad]."&o=U&a=oA&fecha=".$row[fecha]."'>".$row[subtipo]."</a></span></span><span class='subtext'> para <a href='../organizaciones/detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a></span><span class='more-detail'>".$row[descripcion]."
</span></td><td class=' list-column-left'><span class='nowraptext'>".htmlentities(strftime('%a, %b, %e', strtotime($row[fecha])))."</span><br />a las <span class='nowraptext subtext'>".$hora."</span><br /><span class='subtext'><span class='nowraptext'>".$row[oportunidad]."</span></span>
		</td>
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