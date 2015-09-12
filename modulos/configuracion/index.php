<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$numeroagente=number_format($claveagente);

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];

//Obtener la venta actual
$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

$sqlgrupopto="SELECT * FROM `gruposproducto` ORDER BY id_grupoproducto ASC";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="../../css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" href="../../css/agenda.css"type="text/css" />
<link type='text/css' href='../../css/thickbox.css' rel='stylesheet' media='screen' />
<link type='text/css' href='../../css/contact.css' rel='stylesheet' media='screen' />
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/cmxform.js"></script>
<script language="JavaScript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>
<script src="../../js/jquery-1.1.3.1.pack.js" type="text/javascript"></script>
<script src="../../js/thickbox.js" type="text/javascript"></script>
<link rel="icon" href="images/icon.ico" />

<script>
$(document).ready(function(){
	$("#selRubros").change(function(){
		$.post("../combos/carga_rubros.php",{ id:$(this).val() },function(data){$("#selSecciones").html(data);})
	});
})
</script>

</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard"></a></li>
          <li><a href="../organizaciones/index.php" class="contactos"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="" class="ventas" title="Acumulado Anual"></a></li>
          <li><a href="../evaluaciones/evaluacion.php" class="evaluaciones" title="Evaluaciones Mensuales"></a></li>
          <li><a href="" class="casos" title="Archivos"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"){
		  ?>
          <li><a href="modulos/configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <div id="titulo">Configuración</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
            	<li class="selected"><a href="#">General</a></li>
                <li class=""><a href="../agentes/index.php">Agentes</a></li>
                <li class=""><a href="../metas/index.php">Metas</a></li>
                <li class=""><a href="../expediente/index.php">Expedientes</a></li>
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
        <form action="insert.php" method="post">

    	</form>
    
    <div class="wrapper">
    <div class="toggle"><img src="../../images/agenda.png" /></div>
    <div class="cart">
    	<table id="" class="recordList">
            <tbody>
			<?php 
            $sqlagente="SELECT * FROM `usuarios` ORDER BY `apellidopaterno`";
			$resultagente= mysql_query ($sqlagente,$db);
			while($myrowagente=mysql_fetch_array($resultagente))
            {
				
				$agente=$myrowagente[apellidopaterno]." ".$myrowagente[apellidomaterno]." ".$myrowagente[nombre];
				?>
				<tr class="even-row">
                <td class="list-column-picture">
                	
                <?php
				if($myrowagente[foto]){?><img class="picture-thumbnail" src="../../fotos/<?php echo $myrowagente[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                </td>
                <td class=" list-column-left">
                    <a class="keytext" href="" style="text-transform:none; font-size: 12px;color: #000;"><?php echo $agente; ?></a> <span class="highlight" style="background-color: #00cccc;"><?php echo $myrowagente[extoficina]; ?></span><br /><a target="" href="mailto:<?php echo $myrowagente[email]; ?>"><?php echo $myrowagente[email]; ?></a><br />
                </td>
                </tr>
				<?php
            }
			?>
</tbody>
</table>
    
    </div><!-- FIN DE DIV DIRECTORIO -->
</div>
    
    
        
        </div><!-- Fin de midtxt -->
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
