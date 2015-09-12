<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$nivel=2;
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

if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql = "SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' ORDER BY `fecha` ASC";}
else{$_pagi_sql = "SELECT * FROM `actividades` ORDER BY `fecha`,`usuario` ASC";}
$_pagi_nav_num_enlaces=20;
$_pagi_cuantos = 10;
include("paginator.inc.php");

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
    <?php include('../../header.php'); ?>
      
      <div id="titulo">Lista de Actividades</div>
      
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
                <select name="estatus" size="1" onchange="buscarDato(); return false">
                    <option value="" selected="selected">Todos</option>
                    <option value="1">Completas</option>
                    <option value="2">Pendientes</option>
				</select> 
                <label>Tipo de actividad: </label>
                <select name="tipo_actividad" size="1" onchange="buscarDato(); return false">
                    <option value="" selected="selected">Todas</option>
                    <option value="Llamada">Llamada</option>
                    <option value="Email">Email</option>
                    <option value="Seguimiento" >Seguimiento</option>
                    <option value="Cita">Cita</option>
				</select> 
              </div>
              
            </form>
        </fieldset>
        
        <div id="resultado">
        <fieldset class="fieldsetgde">
        <legend>Mostrando <?php echo $_pagi_info; ?> Actividades</legend>
        <form action="update.php" method="post">
        </span></span>
        <table class="recordList">
		<tbody>
		
		<?php
		$c=0;
		while($myrowact=mysql_fetch_array($_pagi_result))
		{
			$fecha=explode("-",$myrowact["fecha"]);
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			//Datos del Agente
			$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myrowact[usuario]."'";
			$resultagente= mysql_query ($sqlagente,$db);
			while($myrowagente=mysql_fetch_array($resultagente))
			{
				$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
			}
			?>
        	<tr class="odd-row">
            <td class="list-column-image"><?php if ($myrowact[completa]==2){$c++;?><img src="../../images/incompleta.png" /><!--<input name="Seleccionados[]" type="checkbox" value="<?php echo $myrowact[id_actividad]; ?>" onclick="" />--> <?php } else {?> <img src="../../images/completa.png" /><!--<input name="Seleccionados[]" type="checkbox" onclick="" disabled="disabled" checked="checked"/> --><?php }?></td>
			<td class=" list-column-left">
            <?php
			if(strtotime($myrowact[fecha]) < strtotime($date)&&$myrowact[completa]==2){echo "<span class='label-overdue'>Atrasado</span>";}
			if($_SESSION["Tipo"]!="Promotor"){echo "<span class='highlight' style='background-color:#C1C1C1;'>".$agente." </span> ";}
			?>
            <span id="">
            <span class="task-title"><span class="highlight" style="background-color:<?php echo $myrowact[color]; ?>;"><?php echo $myrowact[tipo]; ?> </span> <a href="forminsert.php?id=<?php echo $myrowact[id_actividad]; ?>&o=U&a=A&fecha=<?php echo $myrowact[fecha]; ?>&organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[subtipo]; ?></a></span></span><span class="subtext"> para <a href="../organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[organizacion]; ?></a></span> <span class="more-detail"><?php echo $myrowact[descripcion]; ?>
</span></td><td class=" list-column-left"><span class="nowraptext"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?></span><br />a las <span class="nowraptext subtext"><?php echo $hora; ?></span><br /><span class="subtext"><span class="nowraptext"><?php echo $myrowact[oportunidad]; ?></span></span>
            </td>
            </tr>
		<?php
		}
		?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; 

/*if($c!=0)
{
	?>
    <p align="center"><input type="submit" name="submit" class="" value="Completar" /></p>
    <?php
}*/
?>
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
