<?php
//Configuracion de la conexion a base de datos
include ("../../config/config.php");
mysql_select_db($bd_base, $con);
//consulta todos los empleados
$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$_POST[organizacion]."'";
$resultorg= mysql_query ($sqlorg,$db);
$myroworg=mysql_fetch_array($resultorg);

$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
$resultagente= mysql_query ($sqlagente,$db);
$myrowagente=mysql_fetch_array($resultagente);

$agente=$myrowagente[apellidopaterno]." ".$myrowagente[apellidomaterno]." ".$myrowagente[nombre];
$rol=$myrowagente[tipo];

if($myroworg[asignado]==1&&$rol=='Promotor'){$sqlpromotor="SELECT * FROM usuarios WHERE tipo='Promotor' AND claveagente= '".$myroworg[clave_agente]."' AND estatus='1' ORDER BY claveagente ASC";}else{$sqlpromotor="SELECT * FROM usuarios WHERE tipo='Promotor' AND estatus='1' ORDER BY claveagente ASC";}

$tabla="<table class='recordList' style='margin-left:0px;'><tbody>";
$rspromotor= mysql_query ($sqlpromotor,$db);
while($rwpromotor = mysql_fetch_array($rspromotor))
{
	$sqlact="SELECT * FROM actividades WHERE fecha='".$_POST[date]."' AND usuario='".$rwpromotor[claveagente]."' ORDER BY hora ASC";
	$rsact= mysql_query ($sqlact,$db);
	while($rwact = mysql_fetch_array($rsact))
	{
		$events[$rwact[fecha]][] = $rwact;	
	}
	$horaagenda="";
	$disponible=0;
	$tabla.="<tr class='odd-row'><td class='list-column-picture' rowspan='2'>";
                if($rwpromotor[foto]){$tabla.="<img class='picture-thumbnail' src='../../fotos/".$rwpromotor[foto]."' width='32' alt='' />"; } else {$tabla.="<img class='picture-thumbnail' src='../../images/person_avatar_32.png' width='32' height='32' alt=''/>";} $tabla.="</td><td class='list-column-left' colspan='11'><b>".$rwpromotor[nombre]." ".$rwpromotor[apellidopaterno]."</b></td></tr><tr class='odd-row'>";
	for($h=9;$h<=19;$h++)
	{
		if($h < 10) {$horaagenda = str_pad($h, 2, '0', STR_PAD_LEFT);}else{$horaagenda=$h;}
		$event_day = $dia;
		if(isset($events[$_POST[date]]))
		{
			foreach($events[$_POST[date]] as $event)
			{
				$hora=explode(":",$event["hora"]);
				if($hora[0]==$horaagenda)//El evento en el array pertenece a la hora de la agenda
				{
					$color="#CECECE";
					$disponible=0;
					$texto=$event[tipo]."-".$event[subtipo]." para ".$event[organizacion];
					break;
				}
				else{$color="#88C97A";$disponible=1; $texto="";
				}
			}
			$tabla.="<td class='list-column-left'><span class='highlight' style='background-color:".$color.";'title='".$texto."'>".$horaagenda.":00</span></td>";
		}
		else
		{
			$color="#88C97A";$tabla.="<td class='list-column-left'><span class='highlight' style='background-color:".$color.";'title=''>".$horaagenda.":00</span></td>";
		}
	}
	$events="";
	$tabla.="</tr>";
}
$tabla.="</tbody></table>";
echo $tabla;



?>