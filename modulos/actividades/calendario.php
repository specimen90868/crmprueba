<?php
include ("../../seguridad.php");
include ("../../config/config.php");

$claveagente=$_SESSION[Claveagente];
$numeroagente=$claveagente;
$nivel=2;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link href="../../css/agenda.css" rel="stylesheet" type="text/css" />
<link type='text/css' href='../../css/thickbox.css' rel='stylesheet' media='screen' />
<link type='text/css' href='../../css/contact.css' rel='stylesheet' media='screen' />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/cmxform.js"></script>
<link rel="icon" href="images/icon.ico" />

<!--jCarousel library
<script type="text/javascript" src="../../js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.2.min.js"></script>-->

</head>
<body>
      <?php include('../../header.php'); ?>
      <div id="titulo">Calendario de Actividades</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="calendario.php?organizacion=<?php echo $claveorganizacion;?>">Calendario</a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades</a></li>
            </ul> 
        </div>
      </div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt">
        
        <?php

/* Select our database (there is more than one in my server) */
mysql_select_db($basedatos, $db);

$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
	
/* draws a calendar */
function draw_calendar($month,$year,$events = array()){
 	/* día actual */
	$date=date("Y-m-d");
    /* draw table */
    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
    /* table headings */
    $headings = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
 
    /* days and weeks vars now ... */
    $running_day = date('w',mktime(0,0,0,$month,1,$year));
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();
 
    /* row for week one */
    $calendar.= '<tr class="calendar-row">';
 
    /* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++):
        $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
        $days_in_this_week++;
    endfor;
 
    /* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++):
    $calendar.= '';
/* add leading zero in the day number */
    if($list_day < 10) {
         $list_day = str_pad($list_day, 2, '0', STR_PAD_LEFT);
         }
/* add leading zero in the month number */
    if($month < 10) {
         $month = str_pad($month, 2, '0', STR_PAD_LEFT);
         }
 
    $event_day = $year.'-'.$month.'-'.$list_day;
     
    $calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
 
    /* add in the day number */
            $calendar.= '<div class="day-number"><div id="contact-form"><a href="?dia='.$event_day.'"><img src="../../images/calendar_info.png"></a> '; if($event_day<$date){$calendar.=$list_day;} else {$calendar.='<a href="forminsert.php?o=I&a=C&fecha='.$event_day.'">'.$list_day.'</a>';}$calendar.='</div></div>';
             
            $event_day = $year.'-'.$month.'-'.$list_day;
            //echo $event_day;
            //echo "<br />";
            if(isset($events[$event_day])) {
                foreach($events[$event_day] as $event) {
					if($event["completa"]==1){$color="#444444";}else{if(strtotime($event["fecha"])<strtotime($hoy)){$color="#FF7F7F";}else{$color="#88C97A";}}
					$time=explode(":",$event["hora"]);
					$hora=$time[0].":".$time[1];
					$calendar.='
                            <ul><li id="" class="draggable" style="list-style-type:none;" draggable="true"><span class="highlight" style="display:inline-block;font-size:9px;background-color:'.$color.';">'.htmlentities($event["tipo"]).'</span><p><strong><span class="nowraptext">'.$hora.'</span></strong><a href="forminsert.php?id='.$event["id_actividad"].'&o=U&a=C&fecha='.$event_day.'&organizacion='.$event["clave_organizacion"].'" rel="Modificar actividad"> '.$event["subtipo"].'</a><span class="nodrag hover" style="position:absolute;right:5px;top:5px;"></span><span class="subtext"><br />'; if($event["id_oportunidad"]!=0){$calendar.='para <a href="../oportunidades/forminsert.php?id='.$event["id_oportunidad"].'&o=U&a=C&fecha='.$event_day.'&organizacion='.$event["clave_organizacion"].'" class="nodrag">'.$event["oportunidad"].'</a></span></p>';} else {$calendar.='</li></ul>';}
					
                }
            }
            else {
                $calendar.= str_repeat('<p>&nbsp;</p>',2);
            }
        if($running_day == 6):
            $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month):
                $calendar.= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++; $running_day++; $day_counter++;
    endfor;
 
    /* finish the rest of the days in the week */
    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
        endfor;
    endif;
 
    /* final row */
    $calendar.= '</tr>';
     
 
    /* end the table */
    $calendar.= '</table>';
 
    /** DEBUG **/
    $calendar = str_replace('</td>','</td>'."\n",$calendar);
    $calendar = str_replace('</tr>','</tr>'."\n",$calendar);
     
    /* all done, return result */
    return $calendar;
}//Fin de función draw_calendar

function draw_daycalendar($dia,$events = array())//VISTA DIA DEL CALENDARIO
{
	/*$host="minia001.mysql.guardedhost.com";
	$user="minia001_crm";
	$pass="8W3Uf8ve";
	$basedatos="minia001_crm";
	$db=mysql_connect($host, $user, $pass);*/
	
	global $db;
	mysql_query("SET NAMES 'utf8'");
	setlocale(LC_ALL,"es_ES");
	mysql_select_db ($basedatos,$db);

	$date=$_GET[dia];
	$hoy=date("Y-m-d");
	$daycalendar = '<table class="recordList"><tbody>';
	$hora_local  = mktime(date("H")+6,date("i")); 
	$hora=getdate($hora_local); 
	$daycalendar .= '<tr><td colspan="2" class="list-column-actions" style="background-color:#eeeeee;"></td><td class=" list-column-left" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %e %B, %Y", strtotime($dia)))).'</b></td></tr>';
	$horaagenda="";
	for($h=0;$h<=23;$h++)
	{
		if($h < 10) {$horaagenda = str_pad($h, 2, '0', STR_PAD_LEFT);}else{$horaagenda=$h;}
		//Total de eventos para cada hora para sacar el rowspan
		$e=0;
		$event_day = $dia;
		if(isset($events[$event_day]))
		{
			foreach($events[$event_day] as $event)
			{
				$hora=explode(":",$event["hora"]);
				if($hora[0]==$horaagenda){$e++;}
			}
		}
		$daycalendar .= '<tr class="odd-row">
		<td '; if($e!=0&&$e!=1){$daycalendar .='rowspan="'.$e.'" class="list-column-actions" style="background-color:#eeeeee;"><b>';} else {$daycalendar .='class="list-column-actions" style="background-color:#eeeeee;"><b>';}$daycalendar.=$horaagenda; if($h<12){$daycalendar.="<sup>am</sup>";}else{$daycalendar.="<sup>pm</sup>";}
		$daycalendar.= '</b></td>';
		$i=0;
		$event_day = $dia;
		if(isset($events[$event_day]))
		{
			$agente="";
			foreach($events[$event_day] as $event)
			{
				$hora=explode(":",$event["hora"]);
				if($hora[0]==$horaagenda)//El evento en el array pertenece a la hora de la agenda
				{
					if($event["completa"]==1){$color="#444444";}else{if(strtotime($event["fecha"])<strtotime($hoy)){$color="#FF7F7F";}else{$color="#88C97A";}}
					$time=explode(":",$event["hora"]);
					$hora=$time[0].":".$time[1];
					$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion LIKE '".$event["clave_organizacion"]."'";
					$resultorg= mysql_query($sqlorg,$db);
					$myroworg=mysql_fetch_array($resultorg);//Datos de la organización
					$organizacion=$myroworg[clave_organizacion];
					$empresa=$myroworg[organizacion];
					//Datos del Agente
					$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$event[usuario]."'";
					$resultagente= mysql_query ($sqlagente,$db);
					$myrowagente=mysql_fetch_array($resultagente);
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
					$i++;
					if($i!=1){$daycalendar.='<tr class="odd-row"><td class=" list-column-center"><img src="../../images/imghour.png" /><b>'.$hora.'</b></td><td class=" list-column-left">';}
					else{$daycalendar.='<td class=" list-column-center"><img src="../../images/imghour.png" /><b>'.$hora.'</b></td><td class=" list-column-left">';}
					if(strtotime($event["fecha"]) < strtotime($hoy)&&$event["completa"]==2){$daycalendar.= '<span class="label-overdue">Atrasado</span>';}
					$daycalendar.= '<span id="">
            <span class="task-title">';if($_SESSION["Tipo"]!="Promotor"){$daycalendar.='<span class="highlight" style="background-color:#C1C1C1;">'.$event[usuario].' </span> ';}$daycalendar.='<span class="highlight" style="background-color:'.$color.';">'.$event["tipo"].' </span> <a href="forminsert.php?id='.$event[id_actividad].'&o=U&a=A&fecha='.$event[fecha].'&organizacion='.$event[clave_organizacion].'">'.$event[subtipo].'</a></span></span><span class="subtext">'; $daycalendar.=' para <a href="../organizaciones/detalles.php?organizacion='.$event[clave_organizacion].'">'.$event[organizacion].'</a></span>'; $daycalendar.='<span class="more-detail">'.$event[descripcion].'</span></span></td></tr>';
				}//FIN DE IF QUE REVISA SI LA HORA DE LA ACTIVIDAD ES IGUAL A LA HORA DE LA TABLA
			}//FIN DE FOREACH
			if($i==0){$daycalendar.='<td class=" list-column-actions"></td><td class=" list-column-left"></td></tr>';}
		}//FIN DE IF ISSET
		else{$daycalendar.='<td class=" list-column-actions"></td><td class=" list-column-left"></td></tr>';}
	}//FIN DE FOR
	$daycalendar .= '</tbody></table>';
	/* all done, return result */
    return $daycalendar;
}// End function draw_daycalendar

function draw_weekcalendar($week, $year, $events = array())//VISTA SEMANA DEL CALENDARIO
{
	$dates=array();
	$dates[0] = date("Y-m-d", strtotime("{$year}-W{$week}-0")); //Returns the date of Sunday in week
	$dates[1] = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of Monday in week
	$dates[2] = date("Y-m-d", strtotime("{$year}-W{$week}-2"));	//Returns the date of Tuesday in week
	$dates[3] = date("Y-m-d", strtotime("{$year}-W{$week}-3"));	//Returns the date of Wednesday in week
	$dates[4] = date("Y-m-d", strtotime("{$year}-W{$week}-4"));	//Returns the date of Thursday in week
	$dates[5] = date("Y-m-d", strtotime("{$year}-W{$week}-5"));	//Returns the date of Friday in week
	$dates[6] = date("Y-m-d", strtotime("{$year}-W{$week}-6")); //Returns the date of Saturday in week
	//echo $dates[0]." ".$dates[1]." ".$dates[2]." ".$dates[3]." ".$dates[4]." ".$dates[5]." ".$dates[6];
	
	$weekcalendar = '<table class="recordList"><tbody>';
	$weekcalendar .= '<tr class="odd-row"><td colspan="8" class="list-column-left" style="background-color:#eeeeee;"><b>'.htmlentities(strftime("%d de %B de %Y", strtotime($dates[0]))).' - '.htmlentities(strftime("%d de %B de %Y", strtotime($dates[6]))).'</b></td></tr>
	<tr class="odd-row"><td class="list-column-actions" style="background-color:#eeeeee;"></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[0])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[1])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[2])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[3])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[4])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[5])))).'</b></td><td class=" list-column-left-fw" style="background-color:#eeeeee;"><b>'.ucwords(htmlentities(strftime("%A, %d", strtotime($dates[6])))).'</b></td></tr>';
	$horaagenda="";
	for($h=0;$h<=23;$h++)
	{
		if($h < 10) {$horaagenda = str_pad($h, 2, '0', STR_PAD_LEFT);}else{$horaagenda=$h;}
		$weekcalendar .= '<tr class="odd-row">
		<td class="list-column-actions" style="background-color:#eeeeee;"><b>'; $weekcalendar.=$horaagenda; if($h<12){$weekcalendar.="<sup>am</sup>";}else{$weekcalendar.="<sup>pm</sup>";}
		$weekcalendar.= '</b></td>';
		$i=0;
		for($d=0;$d<=6;$d++)
		{
			$weekcalendar.='<td class="list-column-left-fw">';
			$event_day = $dates[$d];
			if(isset($events[$event_day]))
			{
				$weekcalendar.='<ul>';
				foreach($events[$event_day] as $event)
				{
					$hora=explode(":",$event["hora"]);
					if($hora[0]==$horaagenda)//El evento en el array pertenece a la hora de la agenda
					{
						if($event["completa"]==1){$color="#444444";}else{if(strtotime($event["fecha"])<strtotime($hoy)){$color="#FF7F7F";}else{$color="#88C97A";}}
						$time=explode(":",$event["hora"]);
						$hora=$time[0].":".$time[1];
						$i++;
						if(strtotime($event["fecha"]) < strtotime($hoy)&&$event["completa"]==2){$weekcalendar.= '<span class="label-overdue">Atrasado</span>';}
						/*$weekcalendar.= '<span id="">
				<span class="task-title"><span class="highlight" style="background-color:'.$color.';">'.$event["tipo"].' </span> <a href="forminsert.php?id='.$event[id_actividad].'&o=U&a=A&fecha='.$event[fecha].'">'.$event[subtipo].'</a></span></span>'; */
						
						$weekcalendar.='<li id="" class="draggable" style="list-style-type:none;" draggable="true"><span class="highlight" style="background-color:'.$color.';">'.htmlentities($event["tipo"]).'</span><strong><span class="nowraptext"> '.$hora.'</span></strong><a href="forminsert.php?id='.$event["id_actividad"].'&o=U&a=A&fecha='.$event_day.'" rel="Modificar actividad"> '.$event["subtipo"].'</a><span class="nodrag hover" style="position:absolute;right:5px;top:5px;"></span><span class="subtext"><br />';
						//$weekcalendar.='<br>';
					}//FIN DE IF QUE REVISA SI LA HORA DE LA ACTIVIDAD ES IGUAL A LA HORA DE LA TABLA
				}//FIN DE FOREACH
				$weekcalendar.='</ul></td>';
			}//FIN DE IF ISSET
			else{$weekcalendar.='</td>';}
		}//FIN DE FOR DE DÍA
		$weekcalendar.='</tr>';
	}//FIN DE FOR DE HORA
	$weekcalendar .= '</tbody></table>';
	/* all done, return result */
    return $weekcalendar;
}// End function draw_daycalendar
 
function random_number() {
    srand(time());
    return (rand() % 7);
}
 
/* date settings */
$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));

if($_GET['dia'])//Estoy en vista día
{
	$month = (int)date('m', strtotime($_GET[dia]));
	$year = (int)date('Y', strtotime($_GET[dia]));
	$semana=(int)date('W', strtotime($_GET[dia]));
	$date=$_GET[dia];
}
else if($_GET['semana'])//Estoy en vista semana
{
	$sem=($_GET['semana']<10 ? "0".$_GET['semana'] : $_GET['semana']);
	$dias=array();
	$dias[0] = date("Y-m-d", strtotime("{$year}-W{$sem}-0"));
	$dias[6] = date("Y-m-d", strtotime("{$year}-W{$sem}-6"));
	if((int)date('Y', strtotime($dias[0]))!=(int)date('Y', strtotime($dias[6]))){$date=$dias[6];}
	else{$date=$dias[0];}
	$month = (int)date('m', strtotime($date));
	$year = (int)date('Y', strtotime($date));
	$semana=$_GET[semana];
}
else
{
	$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));
	$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
	$date  = mktime(0, 0, 0, $_GET['month'], "01", $_GET['year']);
	$date = $year."-".$month."-01";
	$semana=(int)date('W', strtotime($date));
}
//echo $year."-".$month."-".$semana."-".$date;

/* select month control */
$select_month_control = '<select name="month" id="month">';
for($x = 1; $x <= 12; $x++)
{
	$select_month_control.= '<option value="'.$x.'"'.($x != $month ? '' : ' selected="selected"').'>'.$meses[$x-1].'</option>';
}
$select_month_control.= '</select> ';
 
/* select year control */
$year_range = 7;
$select_year_control = '<select name="year" id="year">';
for($x = ($year-floor($year_range/2)); $x <= ($year+floor($year_range/2)); $x++) {
    $select_year_control.= '<option value="'.$x.'"'.($x != $year ? '' : ' selected="selected"').'>'.$x.'</option>';
}
$select_year_control.= '</select>';
 
/* "next month" control */
$next_month_link = '<a href="?month='.($month != 12 ? $month + 1 : 1).'&year='.($month != 12 ? $year : $year + 1).'" class="control">Siguiente Mes &gt;&gt;</a>';
 
/* "previous month" control */
$previous_month_link = '<a href="?month='.($month != 1 ? $month - 1 : 12).'&year='.($month != 1 ? $year : $year - 1).'" class="control">&lt;&lt;  Mes Anterior</a>';

/* "next day" control */
$next_day_link = '<a href="?dia='.(date('Y-m-d', strtotime($_GET[dia] .' +1 day'))).'" class="control">Siguiente Día &gt;&gt;</a>';

/* "previous day" control */
$previous_day_link = '<a href="?dia='.(date('Y-m-d', strtotime($_GET[dia]) - 3600)).'" class="control">&lt;&lt;  Día Anterior</a>';

/* "next week" control */
$next_week_link = '<a href="?semana='.($semana != 52 ? $semana + 1 : 1).'&year='.($semana != 52 ? $year : $year + 1).'" class="control">Siguiente Semana &gt;&gt;</a>';
 
/* "previous week" control */
$previous_week_link = '<a href="?semana='.($semana != 1 ? $semana - 1 : 52).'&year='.($semana != 01 ? $year : $year - 1).'" class="control">&lt;&lt;  Semana Anterior</a>';

/* "vista mes" control */
$month_link = '<img src="../../images/week16.png"/><a href="?month='.$month.'&year='.$year.'" class="control">  Mes</a>';

/* "vista semana" control */
$week_link = '<img src="../../images/week16.png"/><a href="?semana='.$semana.'&year='.$year.'" class="control">  Semana</a>';

/* "vista dia" control */
$day_link = '<img src="../../images/day16.png"/><a href="?dia='.$date.'" class="control">  Día</a>';

/* "reporte" control */
$reporte_link = '<img src="../../images/report.png"/><a href="../reportes/actividades.php?dia='.$date.'" class="control">  Ver reporte</a>';
 
/* get all events for the given month. I had to rewrite this query to get anything usable out of the mysql database we already had. */
 
$events = array();

if($_GET[dia])//Consulta por día
{
	if($_SESSION["Tipo"]!="Promotor"){$query = "SELECT id_actividad, id_oportunidad, organizacion, clave_organizacion, tipo, subtipo, hora, oportunidad, descripcion, color, completa, usuario, DATE_FORMAT(fecha,'%Y-%m-%d') AS fecha FROM actividades WHERE fecha LIKE '".$_GET[dia]."' ORDER BY hora ASC";}
	else{$query = "SELECT id_actividad, id_oportunidad, organizacion, clave_organizacion, tipo, subtipo, hora, oportunidad, descripcion, color, completa, usuario, DATE_FORMAT(fecha,'%Y-%m-%d') AS fecha FROM actividades WHERE fecha LIKE '".$_GET[dia]."' AND usuario = '$claveagente' ORDER BY hora ASC";
	}
	
	$result = mysql_query($query,$db) or die('cannot get results!');       
	$result = mysql_query($query,$db) or die('error 2');
	while($row = mysql_fetch_assoc($result))
	{
		$events[$row['fecha']][] = $row;
	}
	
	//Controles
	$controls = '<form method="get">'.$select_month_control.$select_year_control.'&nbsp;<input type="submit" name="submit" value="Ir" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$previous_day_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_day_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$week_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$day_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$reporte_link.' </form>';
	
	echo '<h2 style="float:left; padding-right:30px;">'.ucwords(htmlentities(strftime("%B %Y", strtotime($_GET[dia])))).'</h2>';
	echo '<div style="float:left;">'.$controls.'</div>';
	echo '<div style="clear:both;"></div>';
	echo draw_daycalendar($_GET[dia],$events);
	
}
else if($_GET[semana])
{
	$anio=$_GET[year];
	$sem=($_GET['semana']<10 ? "0".$_GET['semana'] : $_GET['semana']);

	$dias=array();
	$dias[0] = date("Y-m-d", strtotime("{$anio}-W{$sem}-0")); //Returns the date of Sunday in week
	
	if($_SESSION["Tipo"]!="Promotor"){$query = "SELECT * FROM actividades WHERE YEARweek(fecha) = YEARweek('".$dias[0]."') ORDER BY fecha,hora ASC";}
	else{$query = "SELECT * FROM actividades WHERE YEARweek(fecha) = YEARweek('".$dias[0]."') AND usuario = '".$claveagente."' ORDER BY fecha,hora ASC";
	}
	
	$result = mysql_query($query,$db) or die('cannot get results!');       
	$result = mysql_query($query,$db) or die('error 2');
	while($row = mysql_fetch_assoc($result))
	{
		$events[$row['fecha']][] = $row;
	}
	
	/* bringing the controls together */
	$controls = '<form method="get">'.$select_month_control.$select_year_control.'&nbsp;<input type="submit" name="submit" value="Ir" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$previous_week_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_week_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$week_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$day_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$reporte_link.' </form>';
	
	echo '<h2 style="float:left; padding-right:30px;">'.ucwords(htmlentities(strftime("%B %Y", strtotime($dias[0])))).'</h2>';
	echo '<div style="float:left;">'.$controls.'</div>';
	echo '<div style="clear:both;"></div>';
	echo draw_weekcalendar($sem,$anio,$events);
}
else //Consulta por mes
{
	if($_SESSION["Tipo"]!="Promotor"){
	$query = "SELECT id_actividad, id_oportunidad, tipo, subtipo, hora, oportunidad, clave_organizacion, descripcion, color, completa, usuario, DATE_FORMAT(fecha,'%Y-%m-%d') AS fecha FROM actividades WHERE fecha LIKE '$year-%$month-%'";}
	else{
	$query = "SELECT id_actividad, id_oportunidad, tipo, subtipo, hora, oportunidad, clave_organizacion, descripcion, color, completa, usuario, DATE_FORMAT(fecha,'%Y-%m-%d') AS fecha FROM actividades WHERE fecha LIKE '$year-%$month-%' AND usuario = '$claveagente'";}
	$result = mysql_query($query,$db) or die('cannot get results!');       
	$result = mysql_query($query,$db) or die('error 2');
	while($row = mysql_fetch_assoc($result))
	{
		$events[$row['fecha']][] = $row;
	}
	
	//Controles
	$controls = '<form method="get">'.$select_month_control.$select_year_control.'&nbsp;<input type="submit" name="submit" value="Ir" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$previous_month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$week_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$day_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$reporte_link.' </form>';

	echo '<h2 style="float:left; padding-right:30px;">'.$meses[$month-1].' '.$year.'</h2>';
	echo '<div style="float:left;">'.$controls.'</div>';
	echo '<div style="clear:both;"></div>';
	echo draw_calendar($month,$year,$events);

}
echo '<br /><br />';
?>
        
        </div>
      </div>
    </div>
  </div>
</div>
<div id="footerbg">
  <div id="footerblank">
    <div id="footer">
      <div id="footerlinks">
      	<a href="" class="footerlinks">Dashboard</a> | 
        <a href="" class="footerlinks">Contactos</a> | 
        <a href="" class="footerlinks">Actividades</a> | 
        <a href="" class="footerlinks">Ventas</a> | 
        <a href="" class="footerlinks">Casos</a>
      </div>
      <div id="copyrights">© anabiosis. Todos los derechos reservados.</div>
    </div>
  </div>
</div>
<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../js/ventanas-modales.js"></script>

</body>
