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
	text-align:left;
	width:659px;
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
	padding:2px 2px;
	border:solid 1px #E3E3E3;
	width:654px;
	margin:5px 10px 20px 0;
}

#stylized .input3 {
	float:none;
	font-size:12px;
	padding:0 0 0 0;
	border:solid 1px #cccccc;
	margin:0 0 0 0;
	width: 20px;
	text-align: center;
}

#stylized button{
	clear:both;
	width:125px;
	height:31px;
	background:#666666 url(img/button.png) no-repeat;
	text-align:center;
	line-height:31px;
	color:#FFFFFF;
	font-size:11px;
	font-weight:bold;
	margin: 10px 0 0px 267px;
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

			<?php
            switch($_GET[o])
            {
				case 'I':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					$myrowopt=mysql_fetch_array($resultopt);
					$nombre_oportunidad = $myrowopt[productos];
					$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
					if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
					if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
					if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
	
					$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$myrowopt[id_etapa]."'";
					$resultexp= mysql_query ($sqlexp,$db);
					$myrowexp=mysql_fetch_array($resultexp);
				?>    
					<table style="width:100%; margin-bottom:5px;">
                        <tr>
                           <?php
                            //Por qué etapas ha pasado la oportunidad
							$sqlproc="SELECT * FROM  `etapasoportunidades` WHERE clave_oportunidad = '".$myrowopt[clave_oportunidad]."' ORDER BY id_etapa ASC";
							$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
                            $resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                                $resultproc=mysql_query($sqlproc,$db);
								while($myrowproc=mysql_fetch_array($resultproc))
								{
									if($myrowetapa[numero_etapa]==$myrowproc[id_etapa]){$color[$myrowproc[id_etapa]]=$myrowcolor[color];}else{if($myrowetapa[numero_etapa]>$myrowopt[id_etapa]){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[id_etapa]]="D6D6D6";}}
								}
								?>
                                <td class="list-column-center" style="width:10%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                    <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:14px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:9px;"><?php echo $myrowcolor[responsable]; ?></span></div>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                    
                    <div id="stylized" class="myform">
                        <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                        <h1>Subir <?php echo $myrowexp[expediente]; ?></h1>
                        <p><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?></p>
						
                        <?php
                        $sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
						$resulttipos= mysql_query ($sqltipos,$db);
						while($myrowtipos=mysql_fetch_array($resulttipos))
						{
							$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowexp[id_expediente]";
							$resultcolor=mysql_query($sqlcolor,$db);
                            $myrowcolor=mysql_fetch_array($resultcolor);
							//Verificar si hay archivo cargado
							$sqlarchivo="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."'";
							$resultarchivo= mysql_query ($sqlarchivo,$db);
							$myrowarchivo=mysql_fetch_array($resultarchivo);
							?>
                            <label><?php echo $myrowtipos[tipo_archivo];
                            if($myrowarchivo){
							?>
                             <img src="../../images/acrobat_16.png"  class="linkImage" /> <a href="../../expediente/<?php echo $myrowarchivo[nombre]; ?>" target="_blank"><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>; font-weight:normal;"> <?php echo $myrowarchivo[nombre_original]; ?> </span></a> <?php }
                            ?>
							</label>
                            <input name="archivo<?php echo $myrowtipos[id_tipoarchivo];?>" type="file" class="input2" id="archivo<?php echo $myrowtipos[id_tipo];?>" />
                            <div class="spacer"></div>
                        <?php
						}
						?>
                            
                      
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
                        <input type="hidden" name="id" id="id"  value="<?php echo $_GET[id]; ?>" />
                        <input type="hidden" name="a" id="a"  value="oP" /><!-- archivo: Organizaciones Oportunidades -->
                        <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                        </form>
                        </div>
                    <?php
					break;
				case 'U':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					$myrowopt=mysql_fetch_array($resultopt);
					$nombre_oportunidad = $myrowopt[productos];
					$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
					if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
					if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
					if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
	
					if($myrowopt[id_etapa]==5){$etapaxp=4;}else{$etapaxp=2;}
					$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$etapaxp."'";
					$resultexp= mysql_query ($sqlexp,$db);
					$myrowexp=mysql_fetch_array($resultexp);
				?>    
					<table style="width:100%; margin-bottom:5px;">
                        <tr>
                           <?php
                            //Por qué etapas ha pasado la oportunidad
							$sqlproc="SELECT * FROM  `etapasoportunidades` WHERE clave_oportunidad = '".$myrowopt[clave_oportunidad]."' ORDER BY id_etapa ASC";
							$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
                            $resultetapa=mysql_query($sqletapa,$db);
                            while($myrowetapa=mysql_fetch_array($resultetapa))
                            {
                                $sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
                                $resultcolor=mysql_query($sqlcolor,$db);
                                $myrowcolor=mysql_fetch_array($resultcolor);
                                $resultproc=mysql_query($sqlproc,$db);
								while($myrowproc=mysql_fetch_array($resultproc))
								{
									if($myrowetapa[numero_etapa]==$myrowproc[id_etapa]){$color[$myrowproc[id_etapa]]=$myrowcolor[color];}else{if($myrowetapa[numero_etapa]>$myrowopt[id_etapa]){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[id_etapa]]="D6D6D6";}}
								}
								?>
                                <td class="list-column-center" style="width:10%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                    <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:14px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:9px;"><?php echo $myrowcolor[responsable]; ?></span></div>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                    
                    <div id="stylized" class="myform">
                        <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                        <h1>Validar <?php echo $myrowexp[expediente]; ?></h1>
                        <p><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?></p>
                        
                        <table class="recordList" style="margin-top: 12px;">
                        <thead>
                            <tr>
                            <th class="list-column-checkbox" scope="col">Aprobar</th>
                            <th class="list-column-left" scope="col">Tipo Documento</th>
                            <th class="list-column-left" scope="col">Documento</th>
                            </tr>
                        </thead>
                        <tbody>
						
                        <?php
                        $sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
						$resulttipos= mysql_query ($sqltipos,$db);
						while($myrowtipos=mysql_fetch_array($resulttipos))
						{
							$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowexp[id_expediente]";
							$resultcolor=mysql_query($sqlcolor,$db);
                            $myrowcolor=mysql_fetch_array($resultcolor);
							//Verificar si hay archivo cargado
							$sqlarchivo="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."' AND id_oportunidad='".$_GET[id]."'";
							$resultarchivo= mysql_query ($sqlarchivo,$db);
							$myrowarchivo=mysql_fetch_array($resultarchivo);
							?>
                            <tr class="odd-row" style="background-color:<?php echo $celda; ?>;">
							<td class="list-column-center"><input name="aprobados[]" type="checkbox" class="input3" value="<?php echo $myrowtipos[id_tipoarchivo];?>" /></td>
							<td class=" list-column-left"><?php echo $myrowtipos[tipo_archivo];?></td>
                            <td class=" list-column-left"><img src="../../images/acrobat_16.png"  class="linkImage" /> <a href="../../expediente/<?php echo $myrowarchivo[nombre]; ?>" target="_blank"><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>; font-weight:normal;"> <?php echo $myrowarchivo[nombre_original]; ?> </span></a></td>
							</tr>
                        <?php
						}
						?>
                      	</tbody>
						</table>
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
                        <input type="hidden" name="id" id="id"  value="<?php echo $_GET[id]; ?>" />
                        <input type="hidden" name="a" id="a"  value="oP" /><!-- archivo: Organizaciones Oportunidades -->
                        <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                        </form>
                        </div>
                    <?php
				break;
			}
			?>
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
</body>
