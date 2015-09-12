<?php
include("config/config.php");

session_start();

//generando la consulta sobre el usuario y su contrasena
$qr = "SELECT * FROM `usuarios` WHERE `claveagente` = '".$_POST[Usuario]."' AND `contrasenia` = '".$_POST[Password]."' AND `estatus` ='1'";
//echo $qr;

//ejecutando la consulta
$rs = mysql_query($qr);
$row = mysql_fetch_object($rs);

//verificando si hay un usuario con ese password mediante numrows
$nr = mysql_num_rows($rs);
if($nr){
//usuario y contraseña válidos
//se define una sesion y se guarda el dato session_start();
$_SESSION[Autenticado] = "Si";
$_SESSION[Usuario] = $_POST[Usuario];
$_SESSION["Nombre"] = $row->nombre." ".$row->apellidopaterno." ".$row->apellidomaterno;
$_SESSION["Claveagente"] = $row->claveagente;
$_SESSION["Rol"] = $row->id_responsable;
$_SESSION["Tipo"] = $row->tipo;
/*if($_SESSION["Tipo"]=="Usuario"){
header("Location:http://www.anabiosis.com.mx/crm/index.php");}
else{header("Location:http://www.anabiosis.com.mx/crm/admin/index.php");}*/
header("Location:http://crm.premo.mx/index.php");
}
else {
//si no existe se va a login.php y pone el valor de error a SI
header("Location:http://crm.premo.mx/login.php?errorusuario=si");
} ?>
