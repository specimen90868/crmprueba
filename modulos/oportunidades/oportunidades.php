<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];
$nivel=2;
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

if($_GET[estatus]||$_GET[agente])
{
	switch($_GET[estatus])
	{
		case ''://Todas las oportunidades
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE usuario ='".$claveagente."' ORDER BY `fecha_captura`,`usuario` DESC";}
			else{if($_GET[agente]){$_pagi_sql="SELECT * FROM oportunidades WHERE usuario ='".$_GET[agente]."' ORDER BY `fecha_captura`,`usuario` DESC";}else{$_pagi_sql="SELECT * FROM oportunidades ORDER BY `fecha_captura`,`usuario` DESC";}}
			break;
        
		case 'Abiertas'://Todas las abiertas
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') AND usuario ='".$claveagente."' ORDER BY `fecha_captura`,`usuario` DESC";}
			else{if($_GET[agente]){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') AND usuario ='".$_GET[agente]."' ORDER BY `fecha_captura`,`usuario` DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa!='10' AND id_etapa!='11') ORDER BY `fecha_captura`,`usuario` DESC";}}
			break;
        
		case 'Cerradas'://Todas las cerradas
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') AND usuario ='".$claveagente."' ORDER BY `fecha_captura`,`usuario` DESC";}
			else{if($_GET[agente]){$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') AND usuario ='".$_GET[agente]."' ORDER BY `fecha_captura`,`usuario` DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE (id_etapa='10' OR id_etapa='11') ORDER BY `fecha_captura`,`usuario` DESC";}}
			break;
        
		case 'MesCerradas'://Cerradas en el mes actual
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11') AND usuario = '".$claveagente."'";}
			else{if($_GET[agente]){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11') AND usuario = '".$_GET[agente]."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_cierre_real) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND (id_etapa='10' OR id_etapa='11')";}}
			break;
			
		case 'MesCaptura'://Capturadas en el mes actual
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND usuario = '".$claveagente."'";}
			else{if($_GET[agente]){$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE) AND usuario = '".$_GET[agente]."'";}else{$_pagi_sql = "SELECT * FROM oportunidades  as o WHERE MONTH(o.fecha_captura) = MONTH(CURRENT_DATE) AND YEAR(o.fecha_captura) = YEAR(CURRENT_DATE)";}}
			break;
        
		case '11':
		case '10':	
		case '9':
		case '8':
		case '7':
		case '6':
		case '5':
		case '4':
		case '3':
		case '2':
		case '1':
			if($_SESSION["Tipo"]=="Promotor"){$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$_GET[estatus]."' AND usuario LIKE '".$claveagente."' ORDER BY `fecha_captura`,`usuario` DESC";}
			else{if($_GET[agente]){$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$_GET[estatus]."' AND usuario LIKE '".$_GET[agente]."' ORDER BY `fecha_captura`,`usuario` DESC";}else{$_pagi_sql="SELECT * FROM oportunidades WHERE id_etapa='".$_GET[estatus]."' ORDER BY `fecha_captura`,`usuario` DESC";}}
			break;
	}
}
else
{
	if($_SESSION["Tipo"]=="Promotor"){
	$_pagi_sql = "SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' ORDER BY `fecha_captura` DESC";}
	else{$_pagi_sql = "SELECT * FROM `oportunidades` ORDER BY `fecha_captura`,`usuario` DESC";}
}

$_pagi_nav_num_enlaces=20;
$_pagi_cuantos = 10;
$_pagi_propagar = array("estatus","agente");
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
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">
<link rel="icon" href="images/icon.ico" />

<script type="text/javascript">
function avanzar(id,o,a,av,org)
{
	var a = confirm("¿Est\xe1 seguro de avanzar con las condicines actuales del proceso?");
	if(a)
	{
		var dir = "../oportunidades/update.php?id="+id+"&o="+o+"&a="+a+"&av="+av+"&organizacion="+org;
		window.open(dir,'_self');
	}
	else return false;
}
</script>

</head>
<body>
    <?php include('../../header.php'); ?>
    <div id="titulo">Oportunidades</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="oportunidades.php">Oportunidades</a></li>
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
        <form name="frmbusquedaopt" method="post" action="" enctype="application/x-www-form-urlencoded" onsubmit="buscaropt(); return false">
          <div>
            <label>Etapa: </label>
            <select name="estatusopt" size="1" onchange="">
                <option value="" <?php if($_GET[estatus]==""){echo "selected='selected'";} ?>>Todas</option>
                <option value="Abiertas" <?php if($_GET[estatus]=="Abiertas"){echo "selected='selected'";} ?>>Abiertas</option>
                <option value="Cerradas" <?php if($_GET[estatus]=="Cerradas"){echo "selected='selected'";} ?>>Cerradas</option>
                <option value="MesCerradas" <?php if($_GET[estatus]=="MesCerradas"){echo "selected='selected'";} ?>>Cerradas este mes</option>
                <option value="MesCaptura" <?php if($_GET[estatus]=="MesCaptura"){echo "selected='selected'";} ?>>Capturadas este mes</option>
                <option value="10" <?php if($_GET[estatus]=="10"){echo "selected='selected'";} ?>>Etapa: Cierre (Crédito otorgado)</option>
                <option value="11" <?php if($_GET[estatus]=="11"){echo "selected='selected'";} ?>>Etapa: Cierre (Crédito rechazado)</option>
                <option value="14" <?php if($_GET[estatus]=="14"){echo "selected='selected'";} ?>>Etapa: Recabación de Expediente de Formalización</option>
                <option value="9" <?php if($_GET[estatus]=="9"){echo "selected='selected'";} ?>>Etapa: Depósito de Seriedad</option>
                <option value="7" <?php if($_GET[estatus]=="7"){echo "selected='selected'";} ?>>Etapa: Entrega de Propuesta a Prospecto</option>
                <option value="13" <?php if($_GET[estatus]=="13"){echo "selected='selected'";} ?>>Etapa: Pre autorización</option>
                <option value="12" <?php if($_GET[estatus]=="12"){echo "selected='selected'";} ?>>Etapa: Estudio de Crédito</option>
                <option value="5" <?php if($_GET[estatus]=="5"){echo "selected='selected'";} ?>>Etapa: Análisis de Expediente Preliminar</option>
                <option value="4" <?php if($_GET[estatus]=="4"){echo "selected='selected'";} ?>>Etapa: Recabación de Expediente Preliminar</option>
                <option value="3" <?php if($_GET[estatus]=="3"){echo "selected='selected'";} ?>>Etapa: Confirmación Verbal</option>
                <option value="2" <?php if($_GET[estatus]=="2"){echo "selected='selected'";} ?>>Etapa: Cita</option>
                <option value="1" <?php if($_GET[estatus]=="1"){echo "selected='selected'";} ?>>Etapa: Primer Contacto</option>
            </select>
            
            <?php if($_SESSION["Tipo"]!="Promotor")
			{
                ?>
                <label>Agente: </label>
                <select name="agente" size="1" onchange="">
                    <option value="" <?php if($_SESSION['Agente']==""){echo 'selected="selected"';} ?> >Todos</option>
                    <?php
                    $sqlagt="SELECT * FROM usuarios WHERE tipo='Promotor' ORDER BY claveagente";
                    $resultagt= mysql_query ($sqlagt,$db);
                    while($myrowagt=mysql_fetch_array($resultagt))
                    {
                    ?>
                        <option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($_GET[agente]==$myrowagt[claveagente]){echo "selected='selected'";} ?> ><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                    <?php
                    }
                    ?>
                </select>
                <?php
			}
            else
            {
                ?>
                <label>Agente: </label>
                <select name="agente" size="1" onchange="">
                    <?php
                    $sqlagt="SELECT * FROM usuarios WHERE tipo='Promotor' AND claveagente='".$claveagente."'";
                    $resultagt= mysql_query ($sqlagt,$db);
                    while($myrowagt=mysql_fetch_array($resultagt))
                    {
                    ?>
                        <option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($_GET[agente]==$myrowagt[claveagente]){echo "selected='selected'";} ?> ><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                    <?php
                    }
                    ?>
                </select>
                <?php
            }
			?>
            <input type="submit" value="Buscar"> 
          </div>
          <input  type="hidden" id="organizacion" name="organizacion" value="<?php echo $claveorganizacion; ?>" onkeyup=";" onblur="" />
        </form>
</fieldset>
        
        <div id="resultado">
        <fieldset class="fieldsetgde">
        <legend>Mostrando <?php echo $_pagi_info; ?> Oportunidades</legend>

        <table class="recordList">
		<thead>
        <tr>
        <th class="list-column-left" scope="col">Oportunidad</th>
        <th class="list-column-left" scope="col">Etapa</th>
        <th class="list-column-left" scope="col">Antigüedad</th>
        <th class="list-column-left" scope="col">Real</th>
        </tr>
        </thead> 
        <tbody>
		
		<?php
		$c=0;
		while($myrowopt=mysql_fetch_array($_pagi_result))
		{
			$nombre_oportunidad = $myrowopt[productos];
			$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
			if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
			if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
			if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
			$id_etapa = $myrowopt[id_etapa];
			
			list($dias, $meses) = diferencia_dias($date,$myrowopt[fecha_captura]);
			
			//Semaforización de oportunidades
			if($dias>90){$antiguedad= $meses." meses"; $resaltado="#FF7F7F";}
			elseif($dias>=31&&$dias<=90){$antiguedad= $meses." meses"; $resaltado="#FFCC00";}
			else{if($meses==1){$antiguedad=$meses. "mes";}else{$antiguedad= $dias." días"; $resaltado="#86CE79";}}
			
			//Datos del Agente
			$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myrowopt[usuario]."'";
			$resultagente= mysql_query ($sqlagente,$db);
			while($myrowagente=mysql_fetch_array($resultagente))
			{
				$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
			}
			//Datos de la organización
			$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowopt[clave_organizacion]."'";
			$resultorg= mysql_query ($sqlorg,$db);
			while($myroworg=mysql_fetch_array($resultorg))
			{
				$organizacion=$myroworg[organizacion];
                $tipoorg=$myroworg[tipo_persona];
			}
			
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
				if($myrowetp[id_responsable]==$responsable||$responsable==3)
				{
					$celda="#FFFFFF";
					$vinculo=1;
				}
				else
				{
					$celda="#F0F0F0";
					$vinculo=0;
				}
			}//Fin de while de etapa
			
			//Verificar si se solicita algún expediente en la etapa de la oportunidad
			$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$id_etapa."' OR id_etapa='".$anterior."'";
			$resultexp= mysql_query ($sqlexp,$db);
			$myrowexp=mysql_fetch_array($resultexp);
			if($myrowexp)//Si hay expediente asociado a la etapa de la oportunidad, obtener los datos necesarios.
			{			
				//Obtener cuántos tipos de archivos tiene el expediente solicitado
				$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
				$resulttipos= mysql_query ($sqltipos,$db);
				$totarch=mysql_num_rows($resulttipos);//archivos totales del expediente
				$myrowtipos=mysql_fetch_array($resulttipos);
				
				//Numeros para expediente
				$sqlarchivo="SELECT count(`id_archivo`)as total, aprobado FROM `archivos` WHERE id_oportunidad = '".$myrowopt[id_oportunidad]."' GROUP BY (`aprobado`)";
				$resultarchivo= mysql_query ($sqlarchivo,$db);
				$aprobados=0;$nrevisado=0;$rechazados=0;$cargados=0;
				while($myrowarchivo=mysql_fetch_array($resultarchivo))
				{						
					if($myrowarchivo[aprobado]==0){$nrevisado=$myrowarchivo[total];}elseif($myrowarchivo[aprobado]==1){$aprobados=$myrowarchivo[total];}else{$rechazados=$myrowarchivo[total];}
				}
				$cargados=$aprobados+$rechazados+$nrevisado;
			}
			
			switch($id_etapa)//Definir vínculo según etapa de la opurtunidad
			{
				case 1:
				case 2:
				case 3:
					$link="";
					break;
				case 4:
					if($cargados!=$totarch)//No se han subido todos los archivos del expediente
					{
						//Vínculo de expediente
						$documentos = "<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='rojo' title='Cargados(".$cargados.") de (".$totarch.") documentos'>".$myrowexp[expediente]."</a>";
					}
					else//Ya se han subido todos los archivos del expediente
					{
						$documentos = "<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul' title='Cargados(".$cargados.") de (".$totarch.") documentos'>".$myrowexp[expediente]."</a>";
						if($vinculo!=0)
						{
							$avanzar = '<img src="../../images/next_16.png" width="16" height="16"  class="linkImage" /><a onclick="avanzar(\''.$myrowopt[id_oportunidad].'\', \'U\', \'oP\',\''.$id_etapa.'\',\''.$myrowopt[clave_organizacion].'\')" href="#" id="addButton" class="azul" title="Solicitar validación de expediente">Avanzar</a>';
						}
					}
					break;
				case 5:		
					//Verificar si se capturo análisis
					$sqlanalisis="SELECT * FROM analisis WHERE id_oportunidad='".$myrowopt[id_oportunidad]."'";
					$rsanalisis= mysql_query ($sqlanalisis,$db);
					$totanalisis=mysql_num_rows($rsanalisis);
					$rwanalisis=mysql_fetch_array($rsanalisis);
					
					if($nrevisado==0&&$aprobados!=$totarch&&$vinculo==1)
					{
						$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
						$retroceder="<img src='../../images/prev_16.png' width='16' height='16'  class='linkImage' /><a href='../oportunidades/update.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&re=".$id_etapa."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul'>Retroceder</a>";
						if($totanalisis!=0)
						{
							$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&an=".$rwanalisis[id_analisis]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul'>Análisis de crédito</a>";
						}
						else
						{
							$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=P&an=".$rwanalisis[id_analisis]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='rojo'>Análisis de crédito</a>";
						}
					}
					elseif($aprobados==$totarch&&$vinculo==1)
					{
						if($totanalisis!=0)
						{
							$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&an=".$rwanalisis[id_analisis]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul'>Análisis de crédito</a>";
							$avanzar="<img src='../../images/next_16.png' width='16' height='16'  class='linkImage' /><a href='../oportunidades/update.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&av=".$id_etapa."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul'>Avanzar</a>";
						}
						else
						{
							$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=P&an=".$rwanalisis[id_analisis]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='rojo'>Análisis de crédito</a>";
						}
						$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";	
					}
					else
					{
						if($nrevisado==0)
						{
							$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
						}
						else
						{
							$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=P&e=".$myrowexp[id_expediente]."&organizacion=".$myrowopt[clave_organizacion]."' id='addButton' class='rojo' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
						}
					}
					break;
				case '6':
					$link="";
					break;
				case '7':
					$link="";
					break;
				case '8':
					$link="";
					break;
				case '9':
					$link="";
					break;
				case '10':
					$link="";
					break;
				case '11':
					$link="";
					break;
			}
			$link = "<br />".$documentos." ".$analisis." ".$avanzar." ".$retroceder;
			?>
        	<tr class="odd-row">
<td class="list-column-left">
                        <?php if($myrowopt[marcado]==1){echo "<span class='label-overdue'>".$myrowopt[motivo]."</span>";} ?>
                        <span class="task-title"><a href="../oportunidades/forminsert.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=U&a=P&fecha=<?php echo $fecha_cierre_esperado;?>&organizacion=<?php echo $myrowopt[clave_organizacion];?>">
                            <?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?>
                        </a></span> para <?php if($tipoorg){?> <span class="highlight" title="<?php echo $tipoorg; ?>"><?php echo $tipoorg[0]; ?> </span> <?php } ?><a href="../organizaciones/detalles.php?organizacion=<?php echo $myrowopt[clave_organizacion];?>">
                            <?php echo $organizacion; ?></a> <?php if($_SESSION["Tipo"]!="Promotor"){echo "<span class='highlight' style='background-color:#9FC733;'>".$agente."</span>"; }?>
                        <br />
                        <span class="subtext">Destino del crédito: <?php if($myrowopt[destino_credito]){echo $myrowopt[destino_credito];}else{echo "Sin especificar";} ?></span><?php list($link,$autorizacion)= vinculos($myrowopt[clave_organizacion],$myrowopt[id_oportunidad],$myrowopt[id_etapa],P,2,$responsable); echo $link; ?>
                        <?php list ($barra, $campos) = barra($myrowopt[clave_organizacion],$myrowopt[clave_oportunidad],2); echo $barra;echo "<span class='subtext'>".number_format($campos,0)."%</span>"; ?>
                        </td>
<td class=" list-column-left">
                        <span class="highlight" style="background-color:#<?php if($id_etapa!=10&&$id_etapa!=11){echo $myrowcolor[color];}else{echo "C1C1C1"; }?>;">
                        <?php echo $etapa; ?></span></td>
<td class="list-column-center"><span class="highlight" style="background-color:<?php if($id_etapa!=10&&$id_etapa!=11){echo $resaltado;}else{echo "#C1C1C1";}?>;" title="<?php echo htmlentities(strftime('%A, %d de %B', strtotime($myrowopt[fecha_captura]))); ?>"><?php echo $antiguedad; ?></span></td>
<td><span class='highlight' style='background-color:#C1C1C1;'><?php echo htmlentities(strftime('%A, %d de %B', strtotime($myrowopt[fecha_cierre_real]))); ?></span></td>
</tr>
		<?php
		$link = ""; $documentos=""; $analisis=""; $avanzar=""; $retroceder="";
		}
		?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; 
?>
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

<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../js/ventanas-modales.js"></script>

</body>
