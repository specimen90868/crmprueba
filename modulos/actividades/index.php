<?php
include ("../../seguridad.php");
include ("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    
        <div id="menu">
        <ul>
          <li><a href="../index.php" class="dashboard"></a></li>
          <li><a href="" class="contactos"></a></li>
          <li><a href="" class="actividades"></a></li>
          <li><a href="" class="ventas"></a></li>
          <li><a href="" class="casos"></a></li>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>) | <a href="" class="sesionlinks">Inicio</a> | <a href="" class="sesionlinks">Mi cuenta</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <div id="submenu">
        <ul>
          <li><a href="forminsert.php" class="submenu">nueva organización</a></li>
          <li><a href="" class="submenu">nuevo contacto</a></li>

        </ul>
      </div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt"></div>
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
