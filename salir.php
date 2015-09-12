<?php

include("config/config.php");

//Cerrar la Sesión
session_start();
$_SESSION = array();
session_destroy();

?>

<html>

<head>
<meta http-equiv="Content-Language" content="es-mx">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>crm</title>
</head>

<body topmargin="20" leftmargin="20" rightmargin="20" bottommargin="20" marginwidth="20" marginheight="20" style="text-align: center">

					<table border="0" cellpadding="0" cellspacing="0" width="97%">
						<tr>
							<td>
							<p align="center">
							<b>
							<font face="Microsoft Sans Serif" style="font-size: 16pt" color="#DA251D">
							Sesión Finalizada</font></b></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>
																	
							<p align="center">
							<font face="Microsoft Sans Serif" size="5" color="#808080">
							Has elegido salir de la aplicación</font></p>
							<p align="center"><font face="Microsoft Sans Serif">
							<a href="login.php">Entrar nuevamente</a></font></td>
						</tr>
					</table>
					
</body>

</html>