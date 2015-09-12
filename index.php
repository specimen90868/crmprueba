<?php
include ("seguridad.php");
include ("config/config.php");
include ("util.php");
include ("includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$nivel=1;

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];

$trimestre=trimestre($Mes);
$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE (`fecha` < '".$date."' AND `completa`='2') AND `usuario`=  '".$claveagente."'";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact);

//Meta trimestral
$sqlmeta="SELECT * FROM cuotas WHERE `clave_agente`='".$claveagente."' AND `trimestre`='".$trimestre."' AND `anio`='".$Anio."'";
$rsmeta = mysql_query($sqlmeta, $db);
$rwmeta=mysql_fetch_array($rsmeta);

//Datos del usuario
$sqlusuario= "SELECT * FROM `usuarios` WHERE `claveagente`='".$claveagente."'";
$rsusuario = mysql_query($sqlusuario, $db);
$rwusuario=mysql_fetch_array($rsusuario);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/styleform.css" />
<link rel="stylesheet" type="text/css" href="css/ventanas-modales.css">
<script language="JavaScript"  src="js/FusionCharts.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="assets/ui/js/lib.js" language="Javascript"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

<script type="text/javascript">
function avanzar(id,o,a,av,org)
{
	var a = confirm("¿Est\xe1 seguro de avanzar con las condiciones actuales del proceso?");
	if(a)
	{
		var dir = "modulos/oportunidades/update.php?id="+id+"&o="+o+"&a="+a+"&av="+av+"&organizacion="+org;
		window.open(dir,'_self');
	}
	else return false;
}
</script>

<script type="text/javascript">
$(document).ready(function () {
    $("#toggle").click(function () {
        $("div").toggleClass("hidden unhidden");
    });
});
</script>

</head>
<body>
    
    <?php include('header.php'); ?>
    <div id="titulo">Mi Tablero</div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt">
          <div id="derecho">
            <?php
			if($_SESSION["Tipo"]=="Promotor"){
			?>
            <table style="width:100%; margin-bottom:5px;">
              <tr>
                <td class="list-column-center" style="width:50%; padding-right:5px;"><div class="roundedpanel" style="height:65px;">
                  <div class="roundedpanel-content"> Avance de Meta<br />
                    <b style="font-size:16px;"><?php echo "$ ".number_format($vtatotal,2)." - ".number_format($avance); ?>%</b> </div>
                </div></td>
                <td class="list-column-center" style="width:50%; padding-left:5px; padding-right:5px;"><div class="roundedpanel" style="height:65px;">
                  <div class="roundedpanel-content"> Meta Trimestre <?php echo $trimestre; ?><br />
                    <b style="font-size:16px;"><?php echo "$ ".number_format($rwmeta['meta']); ?></b> 
    
                  </div>
                </div></td>
              </tr>
            </table>
            <?php
			}
			?>
            <fieldset class="fieldsethistorial">
            <legend>Contactos</legend>
            <table id="" class="recordList">
            <tbody>         
			<?php
            $sqlneworg = "SELECT * FROM `organizaciones` WHERE `clave_agente` = '".$claveagente."' AND `capturo`!='".$claveagente."' AND `fecha_captura` >='".$date."'  ORDER BY`fecha_captura` ASC";
            $rsneworg = mysql_query($sqlneworg,$db);
            $celdaorg="";
            while($rwneworg=mysql_fetch_array($rsneworg))
            {
				//Telefonos de Organización
				$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$rwneworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
				$resulttelorg= mysql_query ($sqltelorg,$db);
				$telorg=""; $tipotelorg="";
				while($myrowtelorg=mysql_fetch_array($resulttelorg))
				{	
					$telorg=$myrowtelorg[telefono];
					$tipotelorg=$myrowtelorg[tipo_telefono];
				}
				
				//Emails de Organización
				$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$rwneworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
				$resultemailorg= mysql_query ($sqlemailorg,$db);
				$mailorg="";
				while($myrowmailorg=mysql_fetch_array($resultemailorg))
				{	
					$mailorg=$myrowmailorg[correo];
				}
				
				//Domicilios de la Organización
				$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$rwneworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
				$resultdomorg= mysql_query ($sqldomorg,$db);
				$domicilio="";
				while($myrowdomorg=mysql_fetch_array($resultdomorg))
				{	
					$domicilio = $myrowdomorg[domicilio];
					if($myrowdomorg[ciudad])
					{
						$domicilio.=", ".$myrowdomorg[ciudad];
						if($myrowdomorg[estado])
						{
							$domicilio.=", ".$myrowdomorg[estado];
							if($myrowdomorg[cp])
							{
								$domicilio.=", ".$myrowdomorg[cp];
								if($myrowdomorg[pais])
								{
									$domicilio.=", ".$myrowdomorg[pais];
								}
							}
						}
					} 
				}
				
				//Razones sociales y RFC de Organización
				$sqlrfc="SELECT * FROM `razonessociales` WHERE `clave_registro` LIKE '".$rwneworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_razonsocial ASC LIMIT 1";
				//echo $sqlrfc;
				$resultrfc= mysql_query ($sqlrfc,$db);
				$numrfc=mysql_num_rows($resultrfc);
				$razonsocial="";
				$rfc="";
				while($myrowrfc=mysql_fetch_array($resultrfc))
				{	
					$razonsocial=$myrowrfc[razon_social];
					$rfc=$myrowrfc[rfc];
				}
				if($rwneworg[fecha_ultimo_contacto])
				{
					list($dias, $meses) = diferencia_dias($rwneworg[fecha_ultimo_contacto],$date);
					//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
					//Semaforización de oportunidades
					if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
					elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
					else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				}
				else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";} 
				
				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$rwneworg[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
				{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}
				//Contactos de la organización
				$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$rwneworg[clave_organizacion]."' ORDER BY id_contacto ASC";
				$resultconorg= mysql_query ($sqlconorg,$db);
				$totcump=0;
				$totcont=mysql_num_rows($resultconorg);
				while($myrowconorg=mysql_fetch_array($resultconorg))
				{
					if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0')
					{
						$totcump++;
					}	
				}
				?>
				<tr class="even-row">
				<td class="list-column-picture">
				<img class="picture-thumbnail" style="height:37px;" src="images/org_new_32.png" width="32" height="37" alt="" />
					
				</td>
				<td class=" list-column-left">
					<a class="keytext" href="modulos/organizaciones/detalles.php?organizacion=<?php echo $rwneworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $rwneworg[organizacion]; ?></a> <span class="highlight"><?php echo $rwneworg[clave_unica]; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span>
					<br />
					<?php echo $domicilio; ?>
					<br />
					<span class="subtext">Etiquetado como:
						<span class="nobreaktext"><?php echo $rwneworg[tipo_organizacion]; ?></span>
					</span>
					<a target="" href="mailto:<?php echo $mailorg; ?>"><?php echo $mailorg; ?></a><br /><?php if($telorg){echo format_Telefono($telorg)." (".$tipotelorg.")"; }?>                     
					<?php
					if($_SESSION["Tipo"]!="Promotor")
					{
						if($rwneworg[asignado]==1)
						{
							?><span class="highlight" style="background-color:#9FC733"><?php echo $agente;?></span>
						<?php 
						}
						else
						{
							?><span class="highlight">No asignado</span>
						<?php 
						}
					}
					?>
				</td>
				</tr>
			<?php
		}
		?>
        </tbody>
</table>
</fieldset>

            <fieldset class="fieldsethistorial">
            	<legend>Actividades</legend>          
				<table class="recordList" style="margin-top: 12px;">
                <thead>
                </thead>
                <tbody>
                <?php
				$_pagi_sql = "SELECT * FROM `actividades` WHERE `usuario` = '".$claveagente."' AND `completa`='2' ORDER BY `fecha` ASC";
                $rsact = mysql_query($_pagi_sql,$db);
                $celdaact="";
				while($myrowact=mysql_fetch_array($rsact))
				{
					$fecha=explode("-",$myrowact["fecha"]);
					$time=explode(":",$myrowact["hora"]);
					$hora=$time[0].":".$time[1];
					if($myrowact[usuario_capturo]!=$myrowact[usuario]){$celdaact="#FFFFCC";}else{$celdaact="#FFFFFF";}
					//Datos del Agente
					$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myrowact[usuario]."'";
					$resultagente= mysql_query ($sqlagente,$db);
					while($myrowagente=mysql_fetch_array($resultagente))
					{
						$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
					}
					?>
					<tr class="odd-row" style="background-color:<?php echo $celdaact; ?>;">
					<td class="list-column-left">
					<?php
					if(strtotime($myrowact[fecha]) < strtotime($date)&&$myrowact[completa]==2){echo "<span class='label-overdue'>Atrasado</span>";}
					if($_SESSION["Tipo"]!="Promotor"){echo "<span class='highlight' style='background-color:#C1C1C1;'>".$agente." </span> ";}
					?>
					<span id="">
					<span class="task-title"><span class="highlight" style="background-color:<?php echo $myrowact[color]; ?>;"><?php echo $myrowact[tipo]; ?> </span> <a href="modulos/actividades/forminsert.php?id=<?php echo $myrowact[id_actividad]; ?>&o=U&a=D&fecha=<?php echo $myrowact[fecha]; ?>&organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[subtipo]; ?></a></span></span> para <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion]; ?>"><?php echo $myrowact[organizacion]; ?></a><span class="more-detail"><?php echo $myrowact[descripcion]; ?>
		</span><?php if($myrowact[usuario_capturo]!=$myrowact[usuario]){?> programada por <span class="highlight" ><?php echo $myrowact[usuario_capturo]; ?> </span><br /><?php } ?><img src="images/clock_16.png" class="linkImage" height="12px;" /><span class="nowraptext subtext"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?></span> a las <span class="nowraptext subtext"><?php echo $hora; ?></span><span class="subtext"><span class="nowraptext"><?php echo $myrowact[oportunidad]; ?></span></span></td>
					</tr>
				<?php
				}
				?>
                </tbody>
                </table>
            </fieldset>
            
            <fieldset class="fieldsethistorial">
            	<legend>Procesos</legend>          
				<table class="recordList" style="margin-top: 12px;">
                <thead>
                </thead>
                <tbody>
                <?php
    			$sqletapas="SELECT * FROM `etapas` WHERE `id_responsable`= '".$responsable."' ORDER BY `numero_etapa` ASC";       
				$rsetapas= mysql_query($sqletapas,$db);
                $totetapas=mysql_num_rows($rsetapas);
				$cadetapas="(";
				$i=0;
				while($rwetapas=mysql_fetch_array($rsetapas))
				{
					$cadetapas.="`id_etapa`='$rwetapas[id_etapa]'";
					if($i<$totetapas-1){$cadetapas.=" OR ";}else{$cadetapas.=")";}
					$i++;
				}
				
				if($_SESSION["Tipo"]=="Promotor")
                {
                     $_pagi_sql="SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' AND ".$cadetapas." AND (id_etapa!=10 AND id_etapa!=11) ORDER BY `id_etapa`,`fecha_captura` ASC";
                }
                else
                {
                    $_pagi_sql="SELECT * FROM `oportunidades` WHERE ".$cadetapas." AND (id_etapa!=10 AND id_etapa!=11) ORDER BY `id_etapa`,`fecha_captura` ASC";
                }
                
                $resultopt= mysql_query($_pagi_sql,$db);
                while($myrowopt=mysql_fetch_array($resultopt))
                {
                    $nombre_oportunidad = $myrowopt[productos];
                    $descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
                    if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
                    if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
                    if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
                    $id_etapa = $myrowopt[id_etapa];
					
					//Datos de organización
					$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$myrowopt[clave_organizacion]."'";
                    $rsorg= mysql_query($sqlorg,$db);
                	$rworg=mysql_fetch_array($rsorg);
					//echo $sqlorg;
                    
                    //Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
					$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
					$resultetp= mysql_query($sqletp,$db);
					while($myrowetp=mysql_fetch_array($resultetp))
					{
						$etapa = $myrowetp[etapa];
						$anterior= $myrowetp[etapa_anterior];
						$siguiente= $myrowetp[etapa_siguiente];
						$probabilidad = $myrowetp[probabilidad];
						$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
						$resultcolor=mysql_query($sqlcolor,$db);
						$myrowcolor=mysql_fetch_array($resultcolor);
						
						//Color de celda y vínculos
						if($myrowetp[id_responsable]!=$responsable)
						{
							$celda="#F0F0F0";
							$vinculo=0;
						}
						else
						{
							$celda="#FFFFFF";
							$vinculo=1;
						}
					}//Fin de while de etapa

                    ?>
                    <tr class="odd-row" style="background-color:<?php echo $celda; ?>;">
                		<td class="list-column-left">
                        <?php if($myrowopt[marcado]==1){echo "<span class='label-overdue'>".$myrowopt[motivo]."</span>";} ?>
                        <span class="task-title"><?php if($vinculo==1){?><a href="modulos/oportunidades/forminsert.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=U&a=oP&an=<?php echo $rwanalisis[id_analisis]; ?>&e=<?php echo $myrowexp[id_expediente]; ?>&organizacion=<?php echo $rworg[clave_organizacion];?>"><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?> </a> <?php } else { echo $nombre_oportunidad." ".$tipo.$monto.$plazo; } ?></span> para <?php if($rworg[tipo_persona]){?> <span class="highlight"><?php echo $rworg[tipo_persona][0]; ?> </span> <?php } ?><a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $rworg[clave_organizacion]; ?>"><?php echo $rworg[organizacion]; ?></a>
                        <br /><span class="subtext">Destino del crédito: <?php if($myrowopt[destino_credito]){echo $myrowopt[destino_credito];}else{echo "Sin especificar";} ?></span><?php list($link,$autorizacion) = vinculos($rworg[clave_organizacion],$myrowopt[id_oportunidad],$myrowopt[id_etapa],D,1,$responsable); echo $link; ?>
                       
						<?php list ($barra, $campos) = barra($rworg[clave_organizacion],$myrowopt[clave_oportunidad],1); echo $barra;echo "<span class='subtext'>".number_format($campos,0)."%</span>"; ?>
                       
                        </td>
                        <td class=" list-column-left"><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;">
<?php echo $etapa; ?></span></td>
                	</tr>
                    <?
                $link = ""; $documentos = ""; $analisis = ""; $avanzar = ""; $retroceder = ""; 
				}
                ?>
                </tbody>
                </table>
            </fieldset>
            
			<?php
			if($_SESSION["Tipo"]!="Promotor"&&$_SESSION["Tipo"]!="Supervisor")//Gráfica de Procesos para usuarios administradores
			{
			?>
            	<fieldset class="fieldsethistorial">
				<legend>Gráfico de Procesos</legend>
					<?php
					$strXML="";
					$strXML="<chart yAxisName='Total de procesos' caption='Procesos por etapa' numberPrefix='' useRoundEdges='1' bgColor='FFFFFF,FFFFFF' showBorder='0'>";
					
					$sqlchart= "SELECT COUNT(`id_oportunidad`) as total, `id_etapa` FROM  `oportunidades` GROUP BY `id_etapa` ORDER BY `id_etapa` ASC"; 
					$rschart= mysql_query ($sqlchart,$db);
					while($rwchart=mysql_fetch_array($rschart))
					{
						$sqletapa="SELECT * FROM `etapas` WHERE `id_etapa`='".$rwchart[id_etapa]."'";
						$rsetapa= mysql_query ($sqletapa,$db);
						while($rwetapa= mysql_fetch_array($rsetapa))
						{
							$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $rwetapa[id_responsable]";
							$rscolor=mysql_query($sqlcolor,$db);
							$rwcolor=mysql_fetch_array($rscolor);
							if($rwetapa[id_etapa]!=11){}
							$strXML.="<set label='".$rwetapa[etapa]."' value='".$rwchart['total']."' tooltext='".$rwetapa[etapa]."' color='"; if($rwetapa[id_etapa]!=11){$strXML.=$rwcolor[color];}else{$strXML.="FF7F7F";} $strXML.="'/>";
						}
						
					}
					$strXML.= "</chart>";	
					
					$animateChart = $_GET['animate'];
					//Set default value of 1
					if ($animateChart=="")
						$animateChart = "1";

					$ruta = "Data/";
					$name_file = $myrowagt[claveagente]."admin.xml";
					$file = fopen($ruta.$name_file,"w+");
					fwrite ($file,$strXML);
					fclose($file);
					//echo renderChart("Charts/Column2D.swf", $ruta.$name_file, "", $myrowagt[claveagente], 600, 300, false, false);
					?>
                    <div id="chartContainer">FusionCharts XT will load here!</div> 
    				<script type="text/javascript">
					var myChart = new FusionCharts( "Bar2D", "myChartId", "600", "300" );
					myChart.setXMLUrl("Data/admin.xml");
			  	    myChart.render("chartContainer"); 
	                </script>
                    
				</fieldset>
			<?php
			}
			?>
          </div>
          <div id="lateral">
          
          <!-- CELEBRACIONES DE HOY -->
          <fieldset class="fieldsetlateral">
              <legend>Celebraciones</legend>
              <form action="modulos/actividades/update.php" method="post">
              <?php
			  //Separar fecha de nacimiento
			  $nacimiento=explode("-",$date);
			  $anio=$nacimiento[0];$mes=$nacimiento[1];$dia=$nacimiento[2];
			  
              //Cumpleaños de Contactos
			  if($_SESSION["Tipo"]=="Usuario"){$sqlcump="SELECT * FROM `contactos` WHERE (`dia_cumpleanios` = '".$dia."' AND `mes_cumpleanios`='".$mes."') AND `clave_agente`=  '".$claveagente."'";}else{$sqlcump="SELECT * FROM `contactos` WHERE (`dia_cumpleanios` = '".$dia."' AND `mes_cumpleanios`='".$mes."')";}
        	  $resultcump= mysql_query ($sqlcump,$db);
			  $numcump=mysql_num_rows($resultcump);
			  //Aniversarios de Fundación
			  if($_SESSION["Tipo"]=="Usuario"){$sqlfund="SELECT * FROM `organizaciones` WHERE (`fecha_fundacion` LIKE '%-".$mes."-".$dia."') AND `clave_agente`=  '".$claveagente."'";}else{$sqlfund="SELECT * FROM `organizaciones` WHERE (`fecha_fundacion` LIKE '%-".$mes."-".$dia."')";}
        	  $resultfund= mysql_query ($sqlfund,$db);
			  $numfund=mysql_num_rows($resultfund);
			  //Cumpleaños de Agentes
			  $sqlcumpag="SELECT * FROM `usuarios` WHERE (`fechanacimiento` LIKE '%-".$mes."-".$dia."')";
			  $resultcumpag= mysql_query ($sqlcumpag,$db);
			  $numcumpag=mysql_num_rows($resultcumpag);
			  ?>
              
              <div class="grouped-list">Contactos <span class="count important" style="background-color:#F60;"><?php echo $numcump; ?></span>
              							Usuarios <span class="count important" style="background-color:#00CCCC;"><?php echo $numcumpag; ?></span>
                                        Empresas <span class="count important" style="background-color:#9900cc;"><?php echo $numfund; ?></span>
                    <ul class="list">
                    <?php
		//Cumpleaños de Agentes
		while($myrowcump=mysql_fetch_array($resultcump))
		{
			$fecha=explode("-",$myrowcump[fecha]);
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			?>             
            <li class="item">
            <div class="checkbox">
            <img src="images/cake.png" />
            </div>
                <div class="detail">
                    <span class="text">
                        <span id="campo">
                            <span class="task-title"><span class="highlight" style="background-color:#F60">Contacto</span> <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowcump[clave_organizacion];?>"> <?php echo $myrowcump[nombre_completo];?></a></span><?php if($myrowcump[puesto]){echo " ". $myrowcump[puesto]." en ";}?>
                            
                            <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowcump[clave_organizacion];?>"><?php echo $myrowcump[organizacion];?></a>
                            

                        </span>
                    </span>
                    <div class="text">
                    </div>
                </div>
            </li>
            <?php
			}
			//Fundaciones de empresa
			while($myrowfund=mysql_fetch_array($resultfund))
			{
			$fechafund=explode("-",$myrowfund[fecha_fundacion]);
			$anios=$Anio-$fechafund[0];
			?>             
            <li class="item">
            <div class="checkbox">
            <img src="images/awardstar.png" />
            </div>
                <div class="detail">
                    <span class="text">
                        <span id="campo">
                            <span class="task-title"><span class="highlight" style="background-color:#9900cc">Empresa</span> <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowfund[clave_organizacion];?>"> <?php echo $myrowfund[organizacion];?></a></span><?php if($anios<2){echo $anios." año";}else{echo $anios." años";}?>
                        </span>
                    </span>
                    <div class="text">
                    </div>
                </div>
            </li>
            <?php
			}
			
			//Cumpleaños de Usuarios
			while($myrowcumpag=mysql_fetch_array($resultcumpag))
			{
				$fecha=explode("-",$myrowcump[fecha]);
				$time=explode(":",$myrowact["hora"]);
				$hora=$time[0].":".$time[1];
				?>             
				<li class="item">
				<div class="checkbox">
				<img src="images/cake.png" />
				</div>
					<div class="detail">
						<span class="text">
							<span id="campo">
								<span class="task-title"><span class="highlight" style="background-color:#00CCCC">Usuario</span> <a href=""> <?php echo $myrowcumpag[nombre]." ".$myrowcumpag[apellidopaterno]." ".$myrowcumpag[apellidomaterno] ; ?></a>
								</span>
								<a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowcumpag[clave_organizacion];?>"><?php echo $myrowcumpag[organizacion];?></a>
	
							</span>
						</span>
						<div class="text">
						</div>
					</div>
				</li>
				<?php
				}
			?> 
            </ul>
        </div>
        	<!--<p align="center"><input type="submit" name="submit" class="" value="Completar" /></p>-->
            <input type="hidden" name="o" id="o" value="C" /><!-- operación: Completar -->
            <input type="hidden" name="a" id="a" value="D" /><!-- archivo: Dashboard -->
            </form>  
            </fieldset>
            
            <!--CONTACTOS ASIGNADOS SIN PROCESO ABIERTO-->
            <table class='recordList' style="width:98%;">
            <thead>
            <tr>
            <th class='list-column-center' scope='col'>Registros asignados sin proceso abierto</th>
            </tr>
            </thead>
            <tbody>
            <?php
			
			//Obtener la lista de contactos asignados
			$sqlasignados="SELECT * FROM `organizaciones` WHERE fecha_captura <=CURDATE() AND fecha_captura >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
			//echo $sqlasignados;
			$rsasignados= mysql_query($sqlasignados,$db);
			$total=mysql_num_rows($rsasignados);
			while($rwasignados=mysql_fetch_array($rsasignados))
			{
				$registros[$i]=$rwasignados[clave_organizacion];
				$i++;
			}
			for($j=0;$j<count($registros);$j++){$claves.="oportunidades.clave_organizacion='".$registros[$j]."'"; if($j<count($registros)-1){$claves.=" OR ";}}
			
			$sqlsin="SELECT organizaciones.organizacion,organizaciones.clave_agente, organizaciones.clave_organizacion, organizaciones.fecha_captura, COUNT(oportunidades.id_oportunidad) AS cuenta FROM `organizaciones` LEFT JOIN (oportunidades) ON (oportunidades.clave_organizacion=organizaciones.clave_organizacion) WHERE (".$claves.") GROUP BY organizaciones.clave_organizacion ORDER BY cuenta,organizaciones.clave_agente,organizaciones.organizacion ASC";
            echo $sqlsin;
			$rssin = mysql_query($sqlsin,$db);
            $totalsin=0;
            while($rwsin=mysql_fetch_array($rssin))
            {
                if($rwsin[cuenta]==0)
                {
                    $totalsin++;
                    ?>
                     <tr class='even-row'><td class='list-column-left'><a href="../../modulos/organizaciones/detalles.php?organizacion=<?php echo $rwsin[clave_organizacion]; ?>&a=P"><?php echo $rwsin[organizacion]; ?></a> <span class="highlight" style="background-color:#C1C1C1;"><?php echo $rwsin[clave_agente]; ?></span></td></tr>
                    <?php
                }
            }
            ?>
            <tr class='even-row'>
                <td class='list-column-right'><?php echo $totalsin; ?></td>
            </tr>
            </tbody>
            </table>
        
        </div>
            
            
          
          <!-- ATRASADAS -->
          <!--   <fieldset class="fieldsetlateral">
              <legend>Mis Actividades atrasadas</legend>
              <div class="prop"><img src="images/add.png" alt="" class="linkImage" /><a href="modulos/actividades/forminsert.php?o=I&a=D&organizacion=<?php echo $claveorganizacion; ?>">agregar actividad</a></div>
              <div class="grouped-list">
                 <span class="itemListTitle">Atrasadas <span class="count important"><?php echo $overdueact; ?></span></span>
                    <ul class="list">
                            <?php
        $sqlact="SELECT * FROM `actividades` WHERE (`fecha` < '".$date."' AND `completa`='2') AND `usuario`=  '".$claveagente."'";
        $resultact= mysql_query ($sqlact,$db);
		
		while($myrowact=mysql_fetch_array($resultact))
		{
			$fecha=explode("-",$myrowact[fecha]);
			//$fechaact=$fecha[2]."/".$fecha[1]."/".$fecha[0];
			//$fechaact="2012/10/22 00:00:00";
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			?>             
            <li class="item">
            <div class="checkbox">
            <img src="images/incompleta16.png" />
            </div>
                <div class="detail">
                    <span class="text">
                        <span id="campo">
                            <span class="task-title"><span class="highlight"><?php echo $myrowact[tipo]; ?></span> <a href="modulos/actividades/forminsert.php?id=<?php echo $myrowact[id_actividad]; ?>&organizacion=<?php echo $myrowact[clave_organizacion]; ?>&o=U&a=D&fecha=<?php echo $myrowact[fecha];?>" class="" rel="Editar Registro" > <?php echo $myrowact[subtipo]; ?> <?php if ($myrowact[oportunidad]){echo "(".$myrowact[oportunidad].")";} ?></a>
                            </span>
                            <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion];?>"><?php echo $myrowact[organizacion];?></a>
                            <span class="more-detail"><?php echo $myrowact[descripcion]; ?></span> <span class="highlight"><?php echo $myrowact[usuario]; ?></span>
                            <span class="task-time"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?>, <?php echo $hora;?>
                             </span>
                        </span>
                    </span>
                    <div class="text">
                    </div>
                </div>
            </li>
            <?php
			}
			?> 
            </ul>
        </div>
            </fieldset> -->
            
            
          </div>
          <div class="wrapper">
    <div class="toggle"><img src="images/agenda.png" /></div>
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
				if($myrowagente[foto]){?><img class="picture-thumbnail" src="fotos/<?php echo $myrowagente[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                </td>
                <td class=" list-column-left">
                    <a href=""><?php echo $agente; ?></a><br /> <?php echo format_Telefono($myrowagente[teloficina]); ?> <span class="highlight" style="background-color: #9FC733;"><?php echo $myrowagente[extoficina]; ?></span><br /><a target="" href="mailto:<?php echo $myrowagente[email]; ?>"><?php echo $myrowagente[email]; ?></a><br />
                </td>
                </tr>
				<?php
            }
			?>
</tbody>
</table>
    
    </div><!-- FIN DE DIV DIRECTORIO -->
</div>
        </div><!--Fin de midtxt -->
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


<script type="text/javascript" src="js/ext/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/ventanas-modales.js"></script>


</body>
</html>