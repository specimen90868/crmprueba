<?php
//Inicio la sesi�n
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTICADO
if ($_SESSION["Autenticado"]=="Si") {
}
else{
//si el usuario no est� autenticado
//redirigirlo a la p�gina de inicio de sesi�n
header("Location:http://crm.premo.mx/login.php");
//salimos de este script
exit();
}
?>
