<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
//echo $_GET[organizacion];

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];

$numeroagente=$claveagente;

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
    
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard"></a></li>
          <li><a href="../../modulos/organizaciones/index.php" class="contactos"></a></li>
          <li><a href="calendario.php" class="actividades"></a></li>
          <li><a href="" class="ventas"></a></li>
          <li><a href="" class="casos"></a></li>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>) | <a href="" class="sesionlinks">Inicio</a> | <a href="" class="sesionlinks">Mi cuenta</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <div id="titulo">Nombre de la Organización</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="calendario.php?organizacion=<?php echo $claveorganizacion;?>">Calendario</a></li>
                <li class="selected"><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades</a></li>
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
            <form name="frmbusqueda" action="" onsubmit="buscarDato(); return false">
              <div><input  type="hidden" id="dato" name="dato" onkeyup=";" onblur="" />
                <label>Estatus: </label>
                <select name="estatus" size="1" onchange="">
                    <option value="" selected="selected">Selecciona</option>
                    <option value="1">Completas</option>
                    <option value="2">Pendientes</option>
				</select> 
                <label>Tipo de actividad: </label>
                <select name="tipo_actividad" size="1" onchange="">
                    <option value="" selected="selected">Selecciona</option>
                    <option value="Llamada">Llamada</option>
                    <option value="Email">Email</option>
                    <option value="Seguimiento" >Seguimiento</option>
                    <option value="Cita">Cita</option>
				</select> 
                <input type="submit" value="Buscar">
              </div>
              
            </form>
        </fieldset>
        
        <div id="resultado">
        <fieldset class="fieldsetgde">
        <legend>Próximas <?php echo $_pagi_info; ?> Actividades</legend>
        <form action="modulos/actividades/update.php" method="post">
        </span></span>
        <table class="recordList">
		<tbody>
		
		<?php
		//Consulta inicial
		if($claveorganizacion)
		{
			 $sqlact="SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha` ASC";
		}
		else
		{
			$sqlact="SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' ORDER BY `fecha` ASC";
		}
        
        $resultact= mysql_query ($sqlact,$db);
		
		while($myrowact=mysql_fetch_array($resultact))
		{
			$fecha=explode("-",$myrowact[fecha]);
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			?>
        	<tr class="odd-row">
            <td class="list-column-left"><?php if ($myrowact[completa]==2){?><input type="checkbox" onclick="" /><?php } else {?> <input type="checkbox" onclick="" disabled="disabled" checked="checked"/> <?php }?></td>
			<td class=" list-column-left">
            <?php if(strtotime($myrowact[fecha]) < strtotime($date)&&$myrowact[completa]==2){echo "<span class='label-overdue'>Atrasado</span>";}?>
            <span id="taskDescription6429310">
            <span class="task-title"><span class="highlight" style="background-color:<?php echo $myrowact[color]; ?>;"><?php echo $myrowact[tipo]; ?> </span> <a href="forminsert.php?id=<?php echo $myrowact[id_actividad]; ?>"><?php echo $myrowact[subtipo]; ?></a></span></span><span class="subtext"> para <a href="../organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[organizacion]; ?></a></span><span class="more-detail"><?php echo $myrowact[descripcion]; ?>
</span></td><td class=" list-column-left"><span class="nowraptext"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?></span><br />a las <span class="nowraptext subtext"><?php echo $hora; ?></span><br /><span class="subtext"><span class="nowraptext"><?php echo $myrowact[oportunidad]; ?></span></span>
            </td>
            </tr>
		<?php
		}
		?>
</tbody>
</table>
<p align="center">
<input type="submit" name="submit" class="" value="Completar" /></p>
<input type="hidden" name="o" id="o" value="C" />
<input type="hidden" name="a" id="a" value="A" />
</form>
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

</body>
