<?php
include ("config/config.php");
if($nivel==2){$ruta="../../";}else{$ruta="";}
?>

<div id="headerbg">
  <div id="headerblank">
    <div id="header">
        <div id="logo"></div>
        <div id="menu">
        <ul>
        <?php
		  switch($_SESSION["Tipo"])
		  {
			 case 'Promotor':
			 	?>
                  <li><a href="<?php echo $ruta; ?>index.php" class="dashboard" title="Mi Tablero"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/actividades/calendario.php" class="actividades" title="Actividades"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
                <?php
			 	break;
			 case 'Administrador':
             case 'Sistema':
			 	?>
                  <li><a href="<?php echo $ruta; ?>index.php" class="dashboard" title="Mi Tablero"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/actividades/calendario.php" class="actividades" title="Actividades"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/reportes/index.php" class="reportes" title="Reportes"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/exportaciones/index.php" class="exportaciones" title="Exportar datos"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/referencias/index.php" class="referencias" title="Referencias Bancarias"></a></li>
                  <li><a href="<?php echo $ruta; ?>modulos/configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
                 <?php
			 	break;
          }
		  ?>
        </ul>
      </div>
      
		<?php 
        $sqlagt="SELECT * FROM usuarios WHERE claveagente='".$claveagente."'";
        $rsagt= mysql_query ($sqlagt,$db);
        while($rwagt=mysql_fetch_array($rsagt))
        {
			if($rwagt[foto])
			{
                $imagen="<img src='".$ruta."images/".$rwagt[foto]."' width='20' height='20' alt='' />";
			}
			else
			{
				$imagen="<img src='".$ruta."images/person_avatar_20.png' width='20' height='20' alt='' />";
			}
        }

	  ?>
      <div id="sesionlinks"> <a href="<?php echo $ruta; ?>modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="<?php echo $ruta; ?>salir.php" class="sesionlinks">Cerrar sesión</a></div>
      <div id="sesionimage"><?php echo $imagen; ?></div>
      
    