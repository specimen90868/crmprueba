<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];

//$numeroagente=number_format($claveagente);
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

$meses_espanol = array(
    '1' => 'Enero',
    '2' => 'Febrero',
    '3' => 'Marzo',
    '4' => 'Abril',
    '5' => 'Mayo',
    '6' => 'Junio',
    '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
    ); 

//Oportunidades atrasadas cierre anterior a la fecha actual y abiertas
$sqloverdueopt="SELECT * FROM `oportunidades` WHERE `fecha_cierre_esperado` < '".$date."' AND (`id_etapa`!=6 AND `id_etapa`!=7) AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoopt = mysql_query($sqloverdueopt, $db);
$overdueopt = mysql_num_rows($resultadoopt);

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE `fecha` < '".$date."' AND `completa`!=1 AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="../../css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>

<link rel="StyleSheet" href="estilos.css" type="text/css">
<link rel="icon" href="images/icon.ico" />

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy",
		changeMonth: true,
      	changeYear: true
	});
});
</script>

<style>
.myform{
margin:0 auto;
width:660px;
padding:10px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}

/* ----------- stylized ----------- */
#stylized{
	border:solid 1px #e3e3e3;
	background:#fff;
}
#stylized h1 {
font-size:14px;
font-weight:bold;
margin-bottom:8px;
}
#stylized p{
	font-size:11px;
	color:#666666;
	margin-bottom:20px;
	border-bottom:solid 1px #e3e3e3;
	padding-bottom:10px;
}
#stylized label{
display:block;
font-weight:bold;
text-align:right;
width:140px;
float:left;
}
#stylized .small{
color:#666666;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}
#stylized input{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 0 20px 10px;
}

#stylized select{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:156px;
	margin:2px 0 20px 10px;
}

#stylized .input2 {
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:456px;
	margin:2px 10px 20px 10px;
}

#stylized button{
clear:both;
margin-left:150px;
width:125px;
height:31px;
background:#666666 url(img/button.png) no-repeat;
text-align:center;
line-height:31px;
color:#FFFFFF;
font-size:11px;
font-weight:bold;
}
</style>



</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard" title="Mi Tablero"></a></li>
          <li><a href="index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../ventas/index.php" class="ventas" title="Acumulado Anual"></a></li>
          <li><a href="../evaluaciones/evaluacion.php" class="evaluaciones" title="Evaluaciones Mensuales"></a></li>
          <li><a href="" class="casos" title="Archivos"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="#">Resumen</a></li>
                <li class=""><a href="checklistview.php?organizacion=<?php echo $claveorganizacion;?>">Checklist</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class="selected"><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
                <li class=""><a href="venta.php?organizacion=<?php echo $claveorganizacion;?>">Venta</a></li>
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
        
        <?php
		
		$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
		$resultorg= mysql_query ($sqlorg,$db);

		while($myroworg=mysql_fetch_array($resultorg))
		{
			$empresa=$myroworg[organizacion];
			$clave=$myroworg[clave_unica];
			$claves=explode(",",$myroworg[clave_unica]);
			//echo count($claves);
			
			$clavescliente="(";
			for($i=0; $i<count($claves); $i++)//Venta para varias claves
			{
				$clavescliente.="`K_Cliente`='$claves[$i]'";
				if($i<count($claves)-1){$clavescliente.=" OR ";}else{$clavescliente.=")";}
			}
			
			//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			
			//Semaforización de oportunidades
			if($dias>90){$resaltadocontacto="#FFBBBB";}
			elseif($dias>=31&&$dias<=90){$resaltadocontacto="#FFE784";}
			else{$resaltadocontacto="#BFE6B9";}

			//Telefonos de Organización
			$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC";
			$resulttelorg= mysql_query ($sqltelorg,$db);
			
			//Emails de Organización
			$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC";
			$resultemailorg= mysql_query ($sqlemailorg,$db);
			
			//Direcciones Web de Organización
			$sqlweborg="SELECT * FROM `direccionesweb` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_direccionweb ASC";
			$resultweborg= mysql_query ($sqlweborg,$db);
			
			//Domicilios de la Organización
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC";
			$resultdomorg= mysql_query ($sqldomorg,$db);
			
			//Contactos de la Organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_contacto ASC";
			$resultconorg= mysql_query ($sqlconorg,$db);
			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail">
				<?php
                /*$sqlarchivos="SELECT * FROM archivos WHERE tipo_registro='O' AND clave_registro='$claveorganizacion' AND tipo_archivo='Logotipo' AND (ext_archivo='JPG' OR ext_archivo='BMP' OR ext_archivo='PNG' OR ext_archivo='GIF')";
				$resultarc= mysql_query ($sqlarchivos,$db);
				while($myrowarc=mysql_fetch_array($resultarc))
				{
					$logotipo=$myrowarc[archivo];
				}
				?>
                <?php if($logotipo){?><a href="subirarchivo.php?organizacion=<?php echo $claveorganizacion; ?>" class="clsVentanaIFrame clsBoton" rel="Hola, esto es un iframe"><img class="picture-normal" src="../../logos/<?php echo $logotipo; ?>" width="70" alt="picture" /></a><?php } else {?> <a href="subirarchivo.php?organizacion=<?php echo $claveorganizacion; ?>&t=L" class="clsVentanaIFrame clsBoton" rel="Hola, esto es un iframe"><img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" /></a> <?php }*/?>
				<img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href="http://google.com/search?q=<?php echo $empresa; ?>" target="_blank"><?php echo $empresa; ?></a></h1><br />
				</div>
			  </div>
              <div id="projectdetallestxtblank">
				<div id="projectdetallestxt">
                    	<ul class="contact-details">
						<?php
                        while($myrowtelorg=mysql_fetch_array($resulttelorg))
                        {
                            ?>
							<li class="phone"><?php echo $myrowtelorg[telefono]; ?> <span class="type"><?php echo $myrowtelorg[tipo_telefono]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class="email"><a href="mailto:<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="http://<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowdomorg=mysql_fetch_array($resultdomorg))
                        {
                            ?>
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span><br /><?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?> <?php echo $myrowdomorg[estado]; ?> <?php echo $myrowdomorg[cp]; ?> <?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }
                        ?>
                    	</ul>
                        
                        <ul class="formActions compact" style="margin-top: 10px;">
            <li><img src="https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png" class="linkImage" /><a href="formedit.php?organizacion=<?php echo $claveorganizacion;?>">Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>
        
        <fieldset class="fieldsetlateral">
        <legend>Acerca de</legend>
        <?php
		$sqlchecklist="SELECT * FROM `checklist` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_checklist ASC";
		$resultchecklist= mysql_query ($sqlchecklist,$db);
		$check = mysql_num_rows($resultchecklist);
		?>
		<?php if($check!=0){?><img src="../../images/add.png" class="linkImage" /><a href="checklistedit.php?organizacion=<?php echo $claveorganizacion;?>">Modificar checklist</a><?php }else{?><img src="../../images/exclamation.png" class="linkImage" /><a href="checklist.php?organizacion=<?php echo $claveorganizacion;?>">Llenar checklist</a><?php }?>
		</fieldset>
            
		<fieldset class="fieldsetlateral">
        <legend>Contactos</legend>
        	<img src="../../images/add.png" class="linkImage" /><a href="#">Añadir un contacto</a>
            <table>
			<?php
            while($myrowconorg=mysql_fetch_array($resultconorg))
            {
                ?>
                <!--<div class="cuadradito" id="<?php echo $myrowconorg[id_contacto]; ?>"></div>-->
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>
                        
<!--<a href="ficha.php?id=<?php echo $myrowconorg[id_contacto]; ?>" class="clsVentanaIFrame clsBoton" rel="Editar Contacto"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a>-->

<?php
$ficha="<div id='projectbg'>
			  	<div id='projectthumnail'>
				<img class='picture-normal' src='../../images/org_avatar_70.png' width='70' height='70' alt='picture' />
                </div>
			  <div id='projecttxtblank'>
				<div id='projecttxt'><h1><a href='http://google.com/search?q=<?php echo $empresa; ?>' target='_blank'><?php echo $empresa; ?></a></h1><br />
				</div>
			  </div>
              <div id='projectdetallestxtblank'>
				<div id='projectdetallestxt'>
                    	<ul class='contact-details'>
						<?php
                        while($myrowtelorg=mysql_fetch_array($resulttelorg))
                        {
                            ?>
							<li class='phone'><?php echo $myrowtelorg[telefono]; ?> <span class='type'><?php echo $myrowtelorg[tipo_telefono]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class='contact-details'>
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class='email'><a href='mailto:<?php echo $myrowemailorg[correo]; ?>'><?php echo $myrowemailorg[correo]; ?></a><span class='type'><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class='formActions compact' style='margin-top: 10px;'>
            <li><img src='https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png' class='linkImage' /><a href='formedit.php?organizacion=<?php echo $claveorganizacion;?>'>Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>";
?>

<a href=""><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span>
                      <br />
                      <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span><br />
                      <img src="<?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo '../../images/cake.png';} else {echo '../../images/cakebn.png';} ?> " /> <?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?>
                    </td>
                </tr>
               
				<?php
            }
            ?>
            </table>

		</fieldset>
            </div>
            
            <div id="derecho">
            <table style="width:100%; margin-bottom:5px;">
                    <tr>
                        <td class="list-column-center" style="width:50%; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px; background-color:<?php echo $resaltadocontacto; ?>;">
                                <div class="roundedpanel-content">
                                    Último Contacto<br />
                                        <b style="font-size:16px;"><?php if($myroworg[fecha_ultimo_contacto]){echo htmlentities(strftime("%e de %B de %Y", strtotime($myroworg[fecha_ultimo_contacto])));}else{echo "Ninguno";} ?></b>
                                </div>
                            </div>
                        </td>
                        <td class="list-column-center" style="width:50%; padding-left:5px; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px;">
                                <div class="roundedpanel-content">
                                    Consumo Mensual
                                    <br />
                                        <b style="font-size:16px;"><?php if($venta['total']) {echo "$ ".number_format($venta['total']);} else { echo "Ninguno"; }?></b>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
        
        <div id="stylized" class="myform">
                <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                <h1>Iniciar nuevo proceso de promoción</h1>
                <p>Ingrese los datos del proceso</p>
                
                <label>Tipo de Crédito
                </label>
                <select name="tipo">
                	<option value="" selected="selected">Sin especificar</option>
					<option value="Revolvente">Revolvente</option>
                </select>
                
                <label>Monto <span class="small">Monto del crédito solicitado</span>
                </label>
                <input type="text" name="Monto" id="Monto" />
                <div class="spacer"></div>
                
                <label>Plazo:
                </label>
                <select name="plazo">
                	<option value="">Sin especificar</option>
					<option value="24">24 meses</option>
                    <option value="60">60 meses</option>
                </select>
                <div class="spacer"></div>
                
                <label>Destino del Crédito
                </label>
                <input type="text" class="input2" name="puesto" id="puesto" />

                <label>Etapa
                </label>
                <select name="plaza" class="input2" id="plaza">
                	<?php
					if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable ORDER BY id_etapa";}
					$resultetapa=mysql_query($sqletapa,$db);
					while($myrowetapa=mysql_fetch_array($resultetapa))
					{
					?>
                    <option value="<?php echo $myrowetapa[id_etapa]; ?>"><?php echo $myrowetapa[etapa]; ?></option>
                    <?php
					}
					?>
                </select>
                <div class="spacer"></div>
              
                <button type="submit">Grabar</button>
                <div class="spacer"></div>
                <input type="hidden" name="a" id="a"  value="Ag" /><!-- archivo: Agentes -->
                <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                </form>
                </div>
        </div>
        
		
		<?php
		}
		?>
        </div><!--fin de midtxt-->
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
		<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>
</body>
