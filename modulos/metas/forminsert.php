<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
require_once("../../xajax/xajax.inc.php"); //incluimos la librelia xajax

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];

if($_GET[agente]){$sqlidusr="SELECT * FROM usuarios WHERE idagente LIKE '".$_GET[usuario]."'"; $resultidusr = mysql_query($sqlidusr, $db); $myrowidusr=mysql_fetch_array($resultidusr); $claveusuario=$myrowidusr[idagente];}
else{$claveusuario=$_GET[usuario];}

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
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />

<link rel="icon" href="images/icon.ico" />
<script type="text/javascript" src="js/cmxform.js"></script>

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
        minDate: '+0d',
		showOn: "button",
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy"
	});
});
</script>

<script type="text/javascript">

function mostrarReferencia()
{
	if(document.frm.id_etapa.value==2)
	{
		document.getElementById('desdeotro').style.display='block';
	}
	else
	{
		document.getElementById('desdeotro').style.display='none';
	}
}
</script>

<script src="ajax.js"></script>

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

#stylized .select2{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:120px;
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
          <li><a href="../organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
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
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="../agentes/detalles.php?usuario=<?php echo $claveusuario;?>">Resumen</a></li>
                <li class="selected"><a href="cuotas.php?idagente=<?php echo $claveusuario;?>">Metas</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="#">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="#">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
                <li class=""><a href="#">Venta</a></li>
            </ul>
            <ul class="pageActions">
            	<li class="item"><img src="../../images/add.png" class="linkImage" /><a href="../agentes/cuotas.php?usuario=<?php echo $claveusuario;?>">Lista de metas</a></li>  
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
		
		$sqlusr="SELECT * FROM `usuarios` WHERE `idagente` LIKE '".$claveusuario."'";
		$resultusr= mysql_query ($sqlusr,$db);
		while($myrowusr=mysql_fetch_array($resultusr))
		{
			$agente=$myrowusr[apellidopaterno]." ".$myrowusr[apellidomaterno]." ".$myrowusr[nombre];
			$clave=$myrowusr[clave_unica];
			$claves=explode(",",$myroworg[clave_unica]);
			$ingreso=$myrowusr[ingreso];
			$trimestre=trimestre(substr($ingreso,5,2));
			$anioingreso=substr($ingreso,0,4);
			
			//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			
			//Semaforización de oportunidades
			if($dias>90){$resaltadocontacto="#FFBBBB";}
			elseif($dias>=31&&$dias<=90){$resaltadocontacto="#FFE784";}
			else{$resaltadocontacto="#BFE6B9";}
				
			
			//Domicilios del Usuario
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC";
			$resultdomorg= mysql_query ($sqldomorg,$db);
			
			//Grupos de producto y facturación
			$sqlgpopto="SELECT * FROM `gruposproducto` WHERE `id_grupoproducto` = '".$myrowusr[id_grupoproducto]."'"; 
			$resultgpopto= mysql_query ($sqlgpopto,$db);
			$myrowgpopto=mysql_fetch_array($resultgpopto);
			
			$sqlgpofac="SELECT * FROM `gruposfacturacion` WHERE `id_grupofacturacion` = '".$myrowusr[id_grupofacturacion]."'"; 
			$resultgpofac= mysql_query ($sqlgpofac,$db);
			$myrowgpofac=mysql_fetch_array($resultgpofac);
			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail">
				<?php if($myrowusr[foto]){?><img class="picture-normal" src="../../fotos/<?php echo $myrowusr[foto]; ?>" width="70" alt="" /><?php } else {?> <img class="picture-normal" src="../../images/person_avatar_70.png" width="70" height="70" alt="" /> <?php }?>
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href=""><?php echo $agente; ?></a></h1><br />
				</div>
			  </div>
              <div id="projectdetallestxtblank">
				<div id="projectdetallestxt">
                
                    <ul class="contact-details">
					<?php
                    if($myrowusr[telcasa]){?><li class="phone"><?php echo format_Telefono($myrowusr[telcasa]); ?> <span class="type">Casa</span></li><? }
                    if($myrowusr[teloficina]){?><li class="phone"><?php echo format_Telefono($myrowusr[teloficina]); ?> Ext. <?php echo $myrowusr[extoficina]; ?><span class="type">Oficina</span></li><? }
					if($myrowusr[nextel]){?><li class="phone"><?php echo format_Telefono($myrowusr[nextel]); ?> <span class="type">Nextel</span></li><? }
					if($myrowusr[idnextel]){?><li class="phone"><?php echo $myrowusr[idnextel]; ?> <span class="type">ID Nextel</span></li><? }
					if($myrowusr[teldirecto]){?><li class="phone"><?php echo format_Telefono($myrowusr[teldirecto]); ?> <span class="type">Directo</span></li><? }?>
                    </ul>
                    
                    <ul class="contact-details">
                    <?php
                    if($myrowusr[email])
                    {?><li class="email"><a href="mailto:<?php echo $myrowusr[email]; ?>"><?php echo $myrowusr[email]; ?></a></li><?php }
					if($myrowusr[emailotro])
                    {?><li class="email"><a href="mailto:<?php echo $myrowusr[emailotro]; ?>"><?php echo $myrowusr[emailotro]; ?></a></li><?php }?>
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
            <li><img src="../../images/edit_16.png" class="linkImage" /><a href="forminsert.php?idusuario=<?php echo $claveusuario;?>&o=U">Editar Usuario
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>

			<fieldset class="fieldsetlateral">
            <legend>Acerca de</legend>
                <ul class="contact-details">
                    <li class="ingreso">Fecha de Ingreso: <span class="type"><?php echo $myrowusr[ingreso]; ?></span></li>
                    <li class="goal">Meta: <?php echo $myrowgpofac[porcentaje]; ?>%</li>
                </ul>
            </fieldset>
        
            </div>
            
            <div id="derecho">

			<?php
            switch($_GET[o])
            {
				case 'I':

					?>    
					<div id="stylized" class="myform">

                <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                <h1>Mínimos trimestrales</h1>
                <p>Ingrese los montos mínimos a cada trimestre</p>
                <?php
                for($i=1;$i<=4;$i++)
				{
					switch($trimestre)
					{
						case '1':
							$meses="ene-feb-mar";
							break;
						case '2':
							$meses="abr-may-jun";
							break;
						case '3':
							$meses="jul-ago-sep";
							break;
						case '4':
							$meses="oct-nov-dic";
							break;
					}
					?>
					<label>Trimestre <?php echo $trimestre; ?>:<span class="small"><?php echo $meses; ?></span></label>
					<input type="text" name="min_trim_<?php echo $trimestre; ?>" id="min_trim_<?php echo $trimestre; ?>" />
					<input type="text" name="min_anio_<?php echo $anioingreso; ?>" id="min_anio_<?php echo $anioingreso; ?>" value="<?php echo $anioingreso; ?>" /><div class="spacer"></div>
					<?php
					$trimestre++;
					if($trimestre>4)
					{
						$anioingreso++;
						$trimestre=1;
					}
				}
                
                ?>
                <h1>Metas trimestrales</h1>
                <p>Ingrese los montos correspondientes a cada trimestre</p>
                <?php
                $anioingreso=substr($ingreso,0,4);
				for($i=1;$i<=4;$i++)
				{
					switch($trimestre)
					{
						case '1':
							$meses="ene-feb-mar";
							break;
						case '2':
							$meses="abr-may-jun";
							break;
						case '3':
							$meses="jul-ago-sep";
							break;
						case '4':
							$meses="oct-nov-dic";
							break;
					}
					?>
					<label>Trimestre <?php echo $trimestre; ?>:<span class="small"><?php echo $meses; ?></span></label>
					<input type="text" name="meta_trim_<?php echo $trimestre; ?>" id="meta_trim_<?php echo $trimestre; ?>" />
					<input type="text" name="meta_anio_<?php echo $anioingreso; ?>" id="meta_anio_<?php echo $anioingreso; ?>" value="<?php echo $anioingreso; ?>" /><div class="spacer"></div>
					<?php
					$trimestre++;
					if($trimestre>4)
					{
						$anioingreso++;
						$trimestre=1;
					}
				}
                
                ?>
                <button type="submit">Grabar</button>
                <div class="spacer"></div>
                <input type="hidden" name="agente" id="agente"  value="<?php echo $_GET[usuario]; ?>" />
                <input type="hidden" name="a" id="a"  value="M" /><!-- archivo: Metas -->
                <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                </form>
                </div>
                    <?php
					break;
				case 'U':
					$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
					$resultopt= mysql_query ($sqlopt,$db);
					while($myrowopt=mysql_fetch_array($resultopt))
					{
						//Responsable de la etapa en la que está la oportunidad
						$sqletapa="SELECT * FROM  `etapas` WHERE id_etapa = $myrowopt[id_etapa] ORDER BY id_etapa";
						$resultetapa=mysql_query($sqletapa,$db);
						$myrowetapa=mysql_fetch_array($resultetapa);
						//Color del responsable por etapa
						$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
						$resultcolor=mysql_query($sqlcolor,$db);
						$myrowcolor=mysql_fetch_array($resultcolor);
						
						/*if($myrowetapa[id_responsable]!=$responsable){?><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;"><?php echo $myrowetapa[etapa]; ?> (<?php echo $myrowetapa[probabilidad]; ?>%)</span><?php }else{}*/
						
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
                                <td class="list-column-center" style="width:9%; padding-right:5px;">
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
        				<form name="frm" method="POST" action="update.php">
                        <h1>Actualizar proceso de promoción</h1>
                        <p></p>
                        
                        <?php
                        if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
						{
							?>
                            <label>Promotor
                            </label>
                            <select id="promotor" name="promotor" class="input2">
                            <option value="0">Sin Asignar</option>
                            <?php
                            $sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";
                            $resultagt= mysql_query ($sqlagt,$db);
                            while($myrowagt=mysql_fetch_array($resultagt))
                            {
                                ?>
                                <option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myrowagt[claveagente]==$myrowopt[usuario]){echo "selected";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                            <div class="spacer"></div>
                        	<?php
						}
						?>
                        
                        <label>Etapa</label>
                        <select name="id_etapa" class="input2" id="id_etapa" onchange="mostrarReferencia();">
						<?php
						if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable ORDER BY id_etapa";}
						$resultetapa=mysql_query($sqletapa,$db);
						while($myrowetapa=mysql_fetch_array($resultetapa))
						{
						?>
							<option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowopt[id_etapa]==$myrowetapa[id_etapa]){echo "selected";}?> <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[numero_etapa]." - ".$myrowetapa[etapa]; ?></option>
						<?php
						}
						?>
                        </select>
                        <div class="spacer"></div>
                        
                        <!--div oculto!-->
                        <div id="desdeotro" style="display:none; background-color:#eeeeee; padding-top:20px;">
                        <label>Programar cita para: </label><input type="text" name="actividad" id="actividad" class="input2"/>
                        <div class="spacer"></div>
                        <label>Descripción: </label><textarea name="descripcion" id="descripcion" cols="45" rows="5" class="input2"></textarea>
            			<div class="spacer"></div>
                        <label>Fecha: </label>
                          <input type="text" id="alternate" size="30" value="<?php if($_GET['fecha']){echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha'])));}else{echo htmlentities(strftime("%A, %e %B, %Y", strtotime($date)));} ?>"/>
              <input type="text" name="date" id="date" value="<?php if($_GET['fecha']){echo $_GET['fecha'];}else{echo $date;} ?>" onchange="MostrarConsulta('consulta.php',this.value,'<?php echo $claveorganizacion; ?>'); return false"/>
              <input type="hidden" name="o" id="o" value="<?php if($_GET[o]){echo $_GET[o];}else{echo $_POST[o];}?>"  />
                          <div class="spacer"></div>
                          <label>Hora: </label>
                            <select id="hora_actividad" name="hora_actividad">
                              <option value="">--</option>
                              <option value="00">00</option>
                              <option value="01">01</option>
                              <option value="02">02</option>
                              <option value="03">03</option>
                              <option value="04">04</option>
                              <option value="05">05</option>
                              <option value="06">06</option>
                              <option value="07">07</option>
                              <option value="08">08</option>
                              <option value="09">09</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                            </select>
                            <select id="min_actividad" name="min_actividad">
                              <option>--</option>
                              <option value="00">00</option>
                              <option value="05">05</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                              <option value="25">25</option>
                              <option value="30">30</option>
                              <option value="35">35</option>
                              <option value="40">40</option>
                              <option value="45">45</option>
                              <option value="50">50</option>
                              <option value="55">55</option>
                            </select>
                        <div class="spacer"></div> 
            			<div id="resultado" style="margin:0 0 20px 145px; padding: 0 0 20px 0;"></div>
                        </div>
                        
                        <label>Tipo de Crédito
                        </label>
                        <select name="tipo_credito" id="tipo_credito">
                            <option value="" <?php if($myrowopt[tipo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="Revolvente" <?php if($myrowopt[tipo_credito]=="Revolvente"){echo "selected";}?>>Revolvente</option>
                        </select>
                        
                        <label>Monto <span class="small">Monto del crédito solicitado</span>
                        </label>
                        <input type="text" name="monto_credito" id="monto_credito" value="<?php echo $myrowopt[monto]; ?>"/>
                        <div class="spacer"></div>
                        
                        <label>Plazo:
                        </label>
                        <select name="plazo_credito" id="plazo_credito">
                            <option value="" <?php if($myrowopt[plazo_credito]==""){echo "selected";}?>>Sin especificar</option>
                            <option value="24" <?php if($myrowopt[plazo_credito]=="24"){echo "selected";}?>>24 meses</option>
                            <option value="60" <?php if($myrowopt[plazo_credito]=="60"){echo "selected";}?>>60 meses</option>
                        </select>
                        <div class="spacer"></div>
                        
                        <label>Destino del Crédito
                        </label>
                        <input type="text" class="input2" name="destino_credito" id="destino_credito" value="<?php echo $myrowopt[destino_credito]; ?>"/>
                      
                        <button type="submit">Grabar</button>
                        <div class="spacer"></div>
                        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" /><!--organizacion-->
                <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                <input type="hidden" name="etapa" id="etapa"  value="<?php echo $myrowopt[id_etapa]; ?>" /><!--oportunidad-->
                <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
                <input type="hidden" name="o" id="o"  value="U" /><!-- operación: Update -->
                        </form>
                        </div>
                    	<?php
                    }
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
