<?php

$localhost="mysql.omnis.com";
$username="minia001_agendai";
$password="46z6hUkY";
$database="minia001_agendaicl";

$db_link=mysql_connect($localhost, $username, $password);
	mysql_select_db ($database,$db_link);


?>