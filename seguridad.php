<?php
//Inicio la sesión
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTICADO
if ($_SESSION["Autenticado"]=="Si") {
}
else{
//si el usuario no está autenticado
//redirigirlo a la página de inicio de sesión
header("Location:http://crm.premo.mx/login.php");
//salimos de este script
exit();
}
?>
