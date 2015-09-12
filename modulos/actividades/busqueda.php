<?php
include ("../../seguridad.php");
include('../../config/config.php');
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

$estatus=$_POST['estatus'];
$tipo=$_POST['tiporegistro'];


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
			if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM actividades WHERE completa='$estatus' AND tipo='$tipo' AND usuario ='$claveagente' ORDER BY fecha ASC";}
			else{$_pagi_sql="SELECT * FROM actividades WHERE completa='$estatus' AND tipo='$tipo' ORDER BY fecha, usuario ASC";}
			$_pagi_cuantos = 10;
			include("../../paginator.inc.php");
		}
		else //Tipo de actividad vacío, entonces Todas
		{
			if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM actividades WHERE completa='$estatus' AND usuario ='$claveagente' ORDER BY fecha ASC";}
			else{$_pagi_sql="SELECT * FROM actividades WHERE completa='$estatus' ORDER BY fecha, usuario ASC";}
			$_pagi_cuantos = 10;
			include("../../paginator.inc.php");
		}
	}
	else //Estatus vacío, entonces todos
	{
		if($tipo)
		{
			if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM actividades WHERE tipo='$tipo' AND usuario ='$claveagente' ORDER BY fecha ASC";}
			else{$_pagi_sql="SELECT * FROM actividades WHERE tipo='$tipo' ORDER BY fecha, usuario ASC";}
			$_pagi_cuantos = 10;
			include("../../paginator.inc.php");
		}
		else //Tipo de actividad vacío, entonces Todas
		{
			if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM actividades WHERE usuario ='$claveagente' ORDER BY fecha ASC";}
			else{$_pagi_sql="SELECT * FROM actividades ORDER BY fecha,usuario ASC";}
			$_pagi_cuantos = 10;
			include("../../paginator.inc.php");
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

	<fieldset class="fieldsetgde">
    <legend>Mostrando <?php echo $_pagi_info; ?> actividades</legend>
    <form action="update.php" method="post">
    <table class="recordList" > 
	<tbody>
<?php
	//$result=mysql_query($_pagi_sql, $db);
	$i=1;
	$c=0;
	while ($row = mysql_fetch_array($_pagi_result))
	{
		$fecha=explode("-",$row[fecha]);
		$time=explode(":",$row['hora']);
		$hora=$time[0].":".$time[1];
		
		//Datos del Agente
		$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$row[usuario]."'";
		$resultagente= mysql_query ($sqlagente,$db);
		while($myrowagente=mysql_fetch_array($resultagente))
		{
			$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
		}

		$res="<tr class='odd-row'>
		<td class='list-column-left'>";
		
		if ($row[completa]==2)
		{
			$res.="<img src='../../images/incompleta.png' />";
			//$res.="<input type='checkbox' onclick='' name='Seleccionados[]' value='".$row[id_actividad]."'/>";
			$c++;
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
		
		$res.="<span id=''>";
		if($_SESSION["Tipo"]!="Usuario"){$res.='<span class="highlight" style="background-color:#C1C1C1;">'.$agente.' </span> ';}
		$res.="<span class='task-title'><span class='highlight' style='background-color:".$row[color].";'>".$row[tipo]." </span> <a href='forminsert.php?id=".$row[id_actividad]."&o=U&a=A&fecha=".$row[fecha]."'>".$row[subtipo]."</a></span></span><span class='subtext'>"; if($row["id_oportunidad"]!=0){ $res.=" para <a href='forminsert.php?idorganizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a></span>";} $res.="<span class='more-detail'>".$row[descripcion]."
</span></td><td class=' list-column-left'><span class='nowraptext'>".htmlentities(strftime('%a, %b, %e', strtotime($row[fecha])))."</span><br />a las <span class='nowraptext subtext'>".$hora."</span><br /><span class='subtext'><span class='nowraptext'>".$row[oportunidad]."</span></span>
		</td>
		</tr>";
		echo $res; 
		$i++;
	}

?>
	</tbody>
	</table>
    <?php
/*if($c!=0)
{
	?>
    <p align="center"><input type="submit" name="submit" class="" value="Completar" /></p>
    <?php
	
}*/
    //Incluimos la barra de navegación 
	echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>
    <input type="hidden" name="o" id="o" value="C" />
	<input type="hidden" name="a" id="a" value="A" />
    </form>
    </fieldset>
    