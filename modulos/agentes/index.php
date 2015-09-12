<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];

if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql = "SELECT * FROM usuarios WHERE claveagente ='".$claveagente."'";}
else{$_pagi_sql = "SELECT * FROM usuarios ORDER BY apellidopaterno ASC";}
$_pagi_cuantos = 10;
$_pagi_nav_num_enlaces= 30;
include("paginator.inc.php");

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;

$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00"; 

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
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard" title="Mi Tablero"></a></li>
          <li><a href="../organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      

  <div id="titulo">Configuraciones</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class=""><a href="../configuracion/index.php">General</a></li>
            <li class="selected"><a href="">Agentes</a></li>
            <li class=""><a href="../metas/index.php">Metas</a></li>
            <li class=""><a href="../expediente/index.php">Expedientes</a></li>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="forminsert.php?o=I">Nuevo Usuario</a></li>  
        </ul>
        </div>
      </div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt">
        
        
        
        <fieldset class="fieldsetgde">
            <legend>Filtar lista por</legend>
            <form name="frmbusqueda" action="" onsubmit="buscarorg(); return false">
              <div>Termino a buscar:
                <input type="text" id="dato" name="dato" onkeyup=";" onblur="" />
                <select name="tipo_registro" size="1" onchange="">
                    <option value="">Seleccionar</option>
                    <option value="Persona">Persona</option>
                    <option value="Organizacion" selected="selected">Organización</option>
                    <option value="Email">Email</option>
                    <option value="Teléfono">Teléfono</option>
                    <option value="Direccion">Dirección</option>
                    <option value="Social">Razón Social</option>
                    <option value="RFC">RFC</option>
				</select> 
                <input type="submit" value="Buscar">
                
                <?php if($_SESSION["Tipo"]!="Usuario")
				{
				?>
                <label>Agente: </label>
                <select name="agente" size="1" onchange="buscarorg(); return false">
                    <option value="" selected="selected">Todos</option>
                    <?php
					$sqlagt="SELECT * FROM usuarios ORDER BY claveagente";
                    $resultagt= mysql_query ($sqlagt,$db);
                    while($myrowagt=mysql_fetch_array($resultagt))
                    {
					?>
                        <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                    <?php
					}
					?>
                </select>
				<?php
                }
				?>
                
              </div>
              
            </form>
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> usuarios</legend>
            <table id="" class="recordList">
            <tbody>
			<?php
            while($myrowusu=mysql_fetch_array($_pagi_result))
            {
				$agente=$myrowusu[apellidopaterno]." ".$myrowusu[apellidomaterno]." ".$myrowusu[nombre];
				?>
				<tr class="even-row">
                <td class="list-column-picture">
                <?php if($myrowusu[foto]){?><img class="picture-thumbnail" src="../../fotos/<?php echo $myrowusu[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                </td>
                <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?usuario=<?php echo $myrowusu[idagente]; ?>"><?php echo $agente; ?></a> <span class="highlight"><?php echo $myrowusu[claves]; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span><br />
                    <?php echo $domicilio; ?>
                    <br />
                    <span class="subtext">Etiquetado como:
                        <span class="nobreaktext"><?php echo $myrowusu[tipo]; ?></span>
                    </span>
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $myrowusu[email]; ?>"><?php echo $myrowusu[email]; ?></a><br /><?php if($myrowusu[teldirecto]){echo format_Telefono($myrowusu[teldirecto])." (".$tipotelorg.")"; }?><br />
                </td>
                </tr>
				<?php
            }
			?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>

        </fieldset>
        </div>
        </div>
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
