<?php
$claveagente="001";
$Filename = "act".$claveagente.".ics"; 
header("Content-Type: text/x-vCalendar"); 
header("Content-Disposition: inline; filename=$Filename");

include ("../../seguridad.php");
include ("../../config/config.php");

$claveagente="001";
$sqlact = "SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' ORDER BY `fecha` ASC";
$resultact = mysql_query($sqlact,$db);

echo "BEGIN:VCALENDAR\n";
echo "PRODID:-//Microsoft Corporation//Outlook 12.0 MIMEDIR//EN\n";
echo "VERSION:2.0\n";
echo "METHOD:PUBLISH\n";
echo "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";
while($myrowact=mysql_fetch_array($resultact))
{
	$fecha = str_replace("-", "", $myrowact[fecha]);
	$hora = str_replace(":", "", $myrowact[hora]);
	
	echo "BEGIN:VEVENT\n";
	echo "CLASS:PUBLIC\n";
	echo "CREATED:".$fecha."T".$hora."T\n";
	echo "DESCRIPTION:".$myrowact[oportunidad]." - ".$myrowact[descripcion]."\n";
	echo "DTEND:".$fecha."T".$hora."T\n";
	echo "DTSTAMP:".$fecha."T".$hora."T\n";
	echo "DTSTART:".$fecha."T".$hora."T\n";
	echo "LAST-MODIFIED:".$fecha."T".$hora."T\n";
	echo "LOCATION:$event_query_row[location]\n";
	echo "PRIORITY:5\n";
	echo "SEQUENCE:0\n";
	echo "SUMMARY:".$myrowact[subtipo]." - ".$myrowact[organizacion]."\n";
	echo "TRANSP:OPAQUE\n";
	echo "UID:".$myrowact[id_actividad]."\n";
	echo "X-MICROSOFT-CDO-BUSYSTATUS:BUSY\n";
	echo "X-MICROSOFT-CDO-IMPORTANCE:1\n";
	echo "X-MICROSOFT-DISALLOW-COUNTER:FALSE\n";
	echo "X-MS-OLK-ALLOWEXTERNCHECK:TRUE\n";
	echo "X-MS-OLK-AUTOFILLLOCATION:FALSE\n";
	echo "X-MS-OLK-CONFTYPE:0\n";
	//Here is to set the reminder for the event.
	echo "BEGIN:VALARM\n";
	echo "TRIGGER:-PT1440M\n";
	echo "ACTION:DISPLAY\n";
	echo "DESCRIPTION:Reminder\n";
	echo "END:VALARM\n";
	echo "END:VEVENT\n";
}

$act.= "END:VCALENDAR\n";

?>