<?php 
$claveagente="001";
$Filename = "/calendarios/ACT".$claveagente.".ics"; 
header("Content-Type: text/x-vCalendar"); 
header("Content-Disposition: inline; filename=$Filename");

include ("../../seguridad.php");
include ("../../config/config.php");

$sqlact = "SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' ORDER BY `fecha` ASC";
$resultact = mysql_query($sqlact,$db);

$act="BEGIN:VCALENDAR\n
	PRODID: - //Microsoft Corporation//Outlook 12.0 MIMEDIR//EN\n
	VERSION:2.0\n
	METHOD:PUBLISH\n
	X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";

while($myrowact=mysql_fetch_array($resultact))
{
	$fecha = str_replace("-", "", $myrowact[fecha]);
	$hora = str_replace(":", "", $myrowact[hora]);
	
	$act.= "BEGIN:VEVENT\n";
	$act.= "CLASS:PUBLIC\n";
	$act.= "CREATED:".$fecha."T".$hora."T\n";
	$act.= "DESCRIPTION:".$myrowact[oportunidad]." - ".$myrowact[descripcion]."\n";
	$act.= "DTEND:".$fecha."T".$hora."T\n";
	$act.= "DTSTAMP:".$fecha."T".$hora."T\n";
	$act.= "DTSTART:".$fecha."T".$hora."T\n";
	$act.= "LAST-MODIFIED:".$fecha."T".$hora."T\n";
	$act.= "LOCATION:$event_query_row[location]\n";
	$act.= "PRIORITY:5\n";
	$act.= "SEQUENCE:0\n";
	$act.= "SUMMARY:".$myrowact[subtipo]." - ".$myrowact[organizacion]."\n";
	$act.= "TRANSP:OPAQUE\n";
	$act.= "UID:".$myrowact[id_actividad]."\n";
	$act.= "X-MICROSOFT-CDO-BUSYSTATUS:BUSY\n";
	$act.= "X-MICROSOFT-CDO-IMPORTANCE:1\n";
	$act.= "X-MICROSOFT-DISALLOW-COUNTER:FALSE\n";
	$act.= "X-MS-OLK-ALLOWEXTERNCHECK:TRUE\n";
	$act.= "X-MS-OLK-AUTOFILLLOCATION:FALSE\n";
	$act.= "X-MS-OLK-CONFTYPE:0\n";
	//Here is to set the reminder for the event.
	$act.= "BEGIN:VALARM\n";
	$act.= "TRIGGER:-PT1440M\n";
	$act.= "ACTION:DISPLAY\n";
	$act.= "DESCRIPTION:Reminder\n";
	$act.= "END:VALARM\n";
	$act.= "END:VEVENT\n";
}
$act.= "END:VCALENDAR\n";

$ruta = "calendarios/";
$name_file = $claveagente.".ics";
$file = fopen($ruta.$name_file,"w+");
fwrite ($file,$act);
fclose($file);

?>
