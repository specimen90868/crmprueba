<?php
include("config/config.php");
?>

<html>

<head>
<meta http-equiv="Content-Language" content="es-mx">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>crm</title>
</head>

<body topmargin="20" leftmargin="20" rightmargin="20" bottommargin="20" marginwidth="20" marginheight="20" style="text-align: center">

					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
																	
							<p align="center">
												
												<?php
												if ($_GET["errorusuario"]=="si"){
												?>
												<font face="Microsoft Sans Serif" size="2">
												<font color="red"><b>Datos incorrectos</b></font>
												Introduce nuevamente tu nombre de usuario y contrase�a<?php
												}
												else{
												?></font><?php
												}
												?></p>
							<div align="center">
							<table border="0" cellpadding="0" cellspacing="0" width="400">
								<tr>
									<td>
									<p align="center">
									<img border="0" src="images/loginheader.jpg" width="401" height="48"></td>
								</tr>
								<tr>
									<td>
									<div align="center">
										<table border="1" cellpadding="5" width="400" style="border-collapse: collapse; border-top-width: 0px" bordercolor="#C0C0C0">
											<tr>
												<td style="border-top-style: none; border-top-width: medium">
												
												<form method=POST action="autenticacion.php" onSubmit="" language="JavaScript" name="FrontPage_Form1">
													<p>
													<font face="Verdana" style="font-size: 8pt">
													Introduzca el nombre de 
													usuario en el campo &quot;Usuario&quot; y la contrase�a 
													en &quot;Contrase�a&quot;. Despu�s 
													haga clic en &quot;Iniciar 
													Sesi�n&quot;</font><!--webbot bot="SaveResults" i-checksum="43374" endspan --></p>
													<div align="center">
													<table border="0" cellpadding="5" cellspacing="0" width="100%">
														<tr>
															<td>
															<p align="right">
															<font face="Microsoft Sans Serif" style="font-size: 9pt">
															Usuario</font></td>
															<td>
															<input type="text" name="Usuario" size="33" style="border: 1px solid #C0C0C0"></td>
														</tr>
														<tr>
															<td>
															<p align="right">
															<font face="Microsoft Sans Serif" style="font-size: 9pt">
															Contrase�a</font></td>
															<td>
															<input type="password" name="Password" size="33" style="border: 1px solid #C0C0C0"></td>
														</tr>
														</table>
													</div>
													<p align="center">
													<input type="submit" value="Iniciar Sesi�n" name="B1" style="font-family: Microsoft Sans Serif; font-size: 8pt; border: 1px solid #C0C0C0; background-color: #FFFFFF"></p>
													<input TYPE="hidden" NAME="VTI-GROUP" VALUE="0">
												</form>												
												</td>
											</tr>
										</table>
									</div>
									</td>
								</tr>
								</table>
							</div>
					
							</td>
						</tr>
					</table>
					
</body>

</html>