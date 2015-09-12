<?php
include ("../../seguridad.php");
include('../../config/config.php');
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];

$_SESSION['Search'] = $_POST['busqueda'];
$busqueda=$_SESSION[Search];
$promotor=$_POST['promotor'];
$tipo=$_POST['tiporegistro'];

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


/*if($_SESSION["Tipo"]=="Promotor"||$_SESSION["Tipo"]=="Supervisor"){$bus = " AND clave_agente ='$claveagente' ";}
else{$bus = "";}*/


if($action == "asignar")
{
	$contactos=0;
	foreach( $_POST['Asignados'] as $asignado)
	{
		$sqlasignado="UPDATE `organizaciones` SET `clave_agente`='$_POST[agente]', `asignado`='1' WHERE id_organizacion='".$asignado."'";
		mysql_query("SET NAMES UTF8");
		mysql_query ($sqlasignado,$db);
		$contactos++;
	}
	//Enviar mail
	$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$_POST[agente]."'";
	echo $sqlpromotor;
	$rspromotor= mysql_query($sqlpromotor,$db);
	$rwpromotor=mysql_fetch_array($rspromotor);
	$headers = "MIME-Version: 1.1\n";
	$headers .= "Content-type: text/plain; charset=UTF-8\n";
	$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
	$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
	$cuerpo = "Hola ".$rwpromotor[nombre]." el usuario ".$claveagente." te ha asignado ".$contactos." nuevo(s) contacto(s), no olvides darles seguimiento \n\n";
	$cuerpo .= "\nAdministrador del CRM";
	$asunto = $rwpromotor[nombre]." Tienes nuevos contactos asignados en el sistema";
	mail("denmed2210@gmail.com",$asunto,$cuerpo,$headers);
	//mail($rwpromotor[email],$asunto,$cuerpo,$headers);
	
}
elseif($action == "descartar")
{
	foreach( $_POST['Asignados'] as $asignado )
	{
		$sqlvalidar="SELECT * FROM `organizaciones` WHERE id_organizacion='".$asignado."'";
		$rsvalidar= mysql_query ($sqlvalidar,$db);
		$rwvalidar=mysql_fetch_array($rsvalidar);
		if($rwvalidar[procedencia]=="Website"&&$rwvalidar[asignado]==0)
		{
			//echo "Se borrarán los registros: ".$asignado;
			$sqlasignado="UPDATE `organizaciones` SET `estatus`='0' WHERE id_organizacion='".$asignado."'";
			mysql_query ($sqlasignado,$db);
		}
		else
		{
			$alerta[$asignado]="El registro procede del Website y ya ha sido asignado a un promotor. No puede ser descartado";
		}
	}
}


if($promotor)
{
	$_pagi_sql="SELECT * FROM organizaciones WHERE clave_agente LIKE '".$promotor."' ORDER BY fecha_ultimo_contacto DESC";
	$_pagi_propagar = array("busqueda","tipo","promotor");
	$_pagi_cuantos = 10;
	include("paginator.inc.php");
}
//echo $_pagi_sql;

switch($tipo)
{
	case '':
		$_pagi_sql="SELECT * FROM organizaciones WHERE estatus= '1' ORDER BY Organizacion ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'Persona':
		$_pagi_sql="SELECT * FROM contactos WHERE (apellidos LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%')".$bus." AND estatus= '1' ORDER BY apellidos ASC";
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'Organizacion':
		$_pagi_sql="SELECT * FROM organizaciones WHERE organizacion LIKE '%$busqueda%'".$bus." AND estatus= '1' ORDER BY fecha_ultimo_contacto ASC";
		$_pagi_propagar = array("busqueda","tipo");
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'Email':
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM correos WHERE (correo LIKE '%$busqueda%') AND capturo ='".$claveagente."' ORDER BY tipo_registro ASC";}
		else{$_pagi_sql="SELECT * FROM correos WHERE (correo LIKE '%$busqueda%') ORDER BY id_correo ASC";}
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'Telefono':
		//Buscar teléfono en la tabla de teléfonos (dónde están los de organizaciones)
		$sql_tel="SELECT * FROM telefonos WHERE (telefono LIKE '%$busqueda%')".$bus."ORDER BY id_telefono ASC";
		$resulttel= mysql_query ($sql_tel,$db);
		$numtel=mysql_num_rows($resulttel);
		//Si el teléfono no existe en la tabla de teléfonos, entonces buscarlo en la tabla de contactos
		if($numtel==0){$_pagi_sql="SELECT * FROM contactos WHERE (telefono_casa LIKE '%".$busqueda."%' OR telefono_oficina LIKE '%".$busqueda."%' OR telefono_celular LIKE '%".$busqueda."%' OR telefono_otro1 LIKE '%".$busqueda."%' OR telefono_otro2 LIKE '%".$busqueda."%')".$bus."ORDER BY apellidos ASC"; $tabla="contactos";}
		else{$_pagi_sql="SELECT * FROM telefonos WHERE (telefono LIKE '%$busqueda%')".$bus."ORDER BY id_telefono ASC"; $tabla="telefonos";}
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'Direccion':
		$_pagi_sql="SELECT * FROM domicilios WHERE (domicilio LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'Clave':
		$_pagi_sql="SELECT * FROM organizaciones WHERE (clave_unica LIKE '%$busqueda%') ORDER BY organizacion ASC";
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'Social':
		$_pagi_sql="SELECT DISTINCT(Anunciante),K_Cliente FROM ventas WHERE (`Anunciante` LIKE '%$busqueda%') ORDER BY Anunciante ASC";
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
	case 'RFC':
		$_pagi_sql="SELECT DISTINCT(Rfc),K_Cliente,Anunciante FROM ventas WHERE (`Rfc` LIKE '%$busqueda%') ORDER BY Rfc ASC";
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("busqueda","tipo");
		include("paginator.inc.php");
		break;
}
//echo $_pagi_sql;
?>
<link href="../../style.css" rel="stylesheet" type="text/css" />

<fieldset class="fieldsetgde">
<legend>Mostrando <?php echo $_pagi_info; ?> resultados</legend>

<form action="<?php echo $PHP_SELF; ?>" method="post">
<?php if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
{
?>
<i>Para los elementos que están seleccionados,</i>
<label>asignar a: </label>
	<img src="../../images/user_16.png" class="linkImage" />
	<select name="agente" size="1">
	<option value="" selected="selected">Seleccionar Promotor</option>
	<?php
	$sqlagt="SELECT * FROM usuarios WHERE tipo='Promotor' OR tipo='Supervisor' ORDER BY claveagente";
	$resultagt= mysql_query ($sqlagt,$db);
	while($myrowagt=mysql_fetch_array($resultagt))
	{
	?>
		<option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
	<?php
	}
	?>
	</select>
	<img src="../../images/assigned_16.png" class="linkImage" />    
<input type='submit' name='action' value="asignar" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
<img src="../../images/trash_16.png" class="linkImage" />
<input type='submit' name='action' value="descartar" style="background-color: #FF0000; border: 1px solid #FF0000; color: #fff; border-radius: 5px;"/>
<?php
}
?>

<table class="recordList" > 
<tbody>
<?php
$i=1;
while ($row = mysql_fetch_array($_pagi_result))
{
    if($row[clave_agente]!=$claveagente&&$_SESSION["Tipo"]=="Promotor"){$celda="#FFFFCC";}else{$celda="#FFFFFF";}
	//Telefonos de Organización
    $sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$row[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
    $resulttelorg= mysql_query ($sqltelorg,$db);
    $telorg=""; $tipotelorg="";
    while($myrowtelorg=mysql_fetch_array($resulttelorg))
    {	
        $telorg=$myrowtelorg[telefono];
        $tipotelorg=$myrowtelorg[tipo_telefono];
    }
    //Emails de Organización
    $sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$row[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
    $resultemailorg= mysql_query ($sqlemailorg,$db);
    $mailorg="";
    while($myrowmailorg=mysql_fetch_array($resultemailorg))
    {	
        $mailorg=$myrowmailorg[correo];
    }
    //Domicilios de la Organización
    $sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$row[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
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
    
    //Telefonos del Contacto
    $sqltelcon="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$row[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_telefono ASC LIMIT 1";
    $resulttelcon= mysql_query ($sqltelcon,$db);
    $telcon=""; $tipotelcon="";
    while($myrowtelcon=mysql_fetch_array($resulttelcon))
    {	
        $telcon=$myrowtelcon[telefono];
        $tipotelcon=$myrowtelcon[tipo_telefono];
    }
    
    //Emails del Contacto
    $sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$row[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_correo ASC LIMIT 1";
    $resultemailcon= mysql_query ($sqlemailcon,$db);
    $mailcon="";
    while($myrowmailcon=mysql_fetch_array($resultemailcon))
    {	
        $mailcon=$myrowmailcon[correo];
    }
    
    //Contactos de la organización
    $sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$row[clave_organizacion]."' ORDER BY id_contacto ASC";
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
    
    if($row[fecha_ultimo_contacto])
    {
        list($dias, $meses) = diferencia_dias($row[fecha_ultimo_contacto],$date);
        //Semaforización de oportunidades
        if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
        elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
        else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
    }
    else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}
    
    //Datos del Agente
    $sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$row[clave_agente]."'";
    $resultagente= mysql_query ($sqlagente,$db);
    while($myrowagente=mysql_fetch_array($resultagente))
    {
        $agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
    }
	
	//Oportunidades para la organización
	$sqlopt="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$row[clave_organizacion]."' AND (`id_etapa`!=10 AND `id_etapa`!=11) ORDER BY `fecha_captura` ASC";
	$resultopt= mysql_query($sqlopt,$db);
	//Ciclo que recorre los datos de las oportunidades halladas en la consulta
	$myrowopt=mysql_fetch_array($resultopt);
	$id_etapa = $myrowopt[id_etapa];
	//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	$myrowetp=mysql_fetch_array($resultetp);
	$etapa = $myrowetp[etapa];
	$probabilidad = $myrowetp[probabilidad];
	$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
	$resultcolor=mysql_query($sqlcolor,$db);
	$myrowcolor=mysql_fetch_array($resultcolor);
	
	//Relaciones de los contactos
	$sqlrelacion="SELECT * FROM `relaciones` WHERE `clave_organizacion` LIKE '".$row[clave_organizacion]."' ORDER BY id_rol ASC" ;
	$rsrelacion= mysql_query ($sqlrelacion,$db);
	$rel="";
	while($rwrelacion=mysql_fetch_array($rsrelacion))
	{
		$sqlrelcol="SELECT * FROM roles WHERE id_rol = '".$rwrelacion[id_rol]."'";
		$rscolor= mysql_query ($sqlrelcol,$db);
		$rwcolor=mysql_fetch_array($rscolor);
		$rel.="<span class='highlight' style='background:".$rwcolor[color]."' title='".$rwrelacion[rol]."'>".$rwrelacion[rol][0]."</span> ";
	}
    
    //PLANTILLAS DE PRESENTACIÓN DE LOS RESULTADOS DE LA BÚSQUEDA
    switch($tipo)
    {
		case '':
			break;
		case 'Persona':
			$res="<tr class='even-row'>
            <td class='list-column-picture'><img class='picture-thumbnail' src='../../images/person_avatar_32.png' width='32' height='32' alt='picture' /></td><td class=' list-column-left'><a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."'>".resaltar($busqueda,$row[apellidos])." ".resaltar($busqueda,$row[nombre])."</a> ".$row[puesto]." en <a href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:'".$resaltadocontacto."'>".$ultimocontacto."</span></td><td class=' list-column-left'><a target='' href='mailto:".$mailcon."'>".$mailcon."</a><br />";if($telcon){$res.= $telcon." (".$tipotelcon.")</td></tr>";} else{$res.= "</td></tr>";}
			break;
		case 'Organizacion':
			$res="<tr class='even-row' style='background-color:".$celda."'>";
			if(($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"))
			{
				$res.="<td class='list-column-checkbox'><input type='checkbox' name='Asignados[]' id='Asignados[]' value='".$row[id_organizacion]."' /></td>";
			}
			$res.="<td class='list-column-picture'><img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' /></td><td class=' list-column-left'>"; 
			if($_SESSION["Tipo"]=="Promotor")
			{
				if($row[clave_agente]!=$claveagente)
				{
					$res.="<b>".strtoupper(resaltar($busqueda,$row[organizacion]))."</b>";
				}
				else
				{
					$res.="<a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."' style='text-transform:uppercase;'>".resaltar($busqueda,$row[organizacion])."</a>";
				}
			}
			else 
			{
				$res.="<a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."' style='text-transform:uppercase;'>".resaltar($busqueda,$row[organizacion])."</a>";
			}
			$res.="<span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$row[fecha_ultimo_contacto]."</span> ".$rel;
			
			if($myrowopt){$res.="<span class='highlight' style='background-color:#".$myrowcolor[color]."' title='Etapa del proceso abierto'>".$etapa."</span>";}
			
			/*if($row[fecha_fundacion]!='0000-00-00'){$res.="<img src='../../images/awardstar.png' /> ";}else{$res.="<img src='../../images/awardstarbn.png' /> ";}
			if($totcont!=0)
			{
				if($totcump==$totcont)
				{
					$res.="<img src='../../images/cake.png' /> "; 
				}
				else
				{
					$res.="<img src='../../images/cakebn.png' /> "; 
				}
			}
			else
			{
				$res.="<img src='../../images/userbn.png' /> ";
			}
			if($numrfc!=0){$res.="<img src='../../images/invoice.png' /> ";}else {$res.="<img src='../../images/invoicebn.png' /> "; }*/
			$res.="<br />".$domicilio."<br /><span class='subtext'>Etiquetado como:<span class='nobreaktext'>Prospecto</span></span></td><td class=' list-column-left'><a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} 
			if($_SESSION["Tipo"]=="Promotor"&&$row[asignado]==1)
			{ 
				if($row[clave_agente]!=$claveagente)
				{
					$res.="<br /><span class='highlight' style='background-color:#9FC733;'>".$agente."</span>";
				}
			}
			else
			{
				if($row[asignado]!=1)
				{
					$res.="<br /><span class='highlight'>No asignado</span>";
				}
				else{$res.="<br /><span class='highlight' style='background-color:#9FC733;'>".$agente."</span>";}
			} 
			$res.="</td></tr>";
			break;
		case 'Direccion':
			//Datos de Organización
			$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$row[clave_registro]."' ORDER BY organizacion ASC";
			$resultorg= mysql_query ($sqlorg,$db);
			$myroworg= mysql_fetch_array($resultorg);
			if($myroworg[clave_agente]!=$claveagente&&$_SESSION["Tipo"]=="Promotor"){$celda="#FFFFCC";}else{$celda="#FFFFFF";}
			//Telefonos de Organización
			$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$row[clave_registro]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
			$resulttelorg= mysql_query ($sqltelorg,$db);
			$myrowtelorg=mysql_fetch_array($resulttelorg);
			//Emails de Organización
			$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$row[clave_registro]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
			$resultemailorg= mysql_query ($sqlemailorg,$db);
			$myrowmailorg=mysql_fetch_array($resultemailorg);
			//Contactos de la organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$row[clave_registro]."' ORDER BY id_contacto ASC";
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
			//Domicilios de la Organización
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$row[clave_registro]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
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
			$sqlrfc="SELECT * FROM `razonessociales` WHERE `clave_registro` LIKE '".$row[clave_registro]."' AND `tipo_registro` = 'O' ORDER BY id_razonsocial ASC LIMIT 1";
			$resultrfc= mysql_query ($sqlrfc,$db);
			$numrfc=mysql_num_rows($resultrfc);
			$myrowrfc=mysql_fetch_array($resultrfc);
			//Checklist
			$sqlchecklist="SELECT * FROM `checklist` WHERE `clave_organizacion` LIKE '".$row[clave_registro]."' ORDER BY id_checklist ASC";
			$resultchecklist= mysql_query ($sqlchecklist,$db);
			$check = mysql_num_rows($resultchecklist);
			
			if($row[fecha_ultimo_contacto])
			{
				list($dias, $meses) = diferencia_dias($row[fecha_ultimo_contacto],$date);
				if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
				elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
				else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
			}
			else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}
			
			$res="<tr class='even-row' style='background-color:".$celda."'><td class='list-column-picture'><img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' /></td><td class=' list-column-left'>"; 
			if($_SESSION["Tipo"]=="Promotor")
			{
				if($myroworg[clave_agente]!=$claveagente)
				{
					$res.="<b>".strtoupper(resaltar($busqueda,$myroworg[organizacion]))."</b>";
				}
				else
				{
					$res.="<a class='keytext' href='detalles.php?organizacion=".$myroworg[clave_organizacion]."' style='text-transform:uppercase;'>".resaltar($busqueda,$myroworg[organizacion])."</a>";
				}
			}
			else 
			{
				$res.="<a class='keytext' href='detalles.php?organizacion=".$myroworg[clave_organizacion]."' style='text-transform:uppercase;'>".resaltar($busqueda,$myroworg[organizacion])."</a>";
			}
			
			$res.="<span class='highlight'>".$myroworg[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span>";
			if($myroworg[fecha_fundacion]!='0000-00-00'){$res.="<img src='../../images/awardstar.png' /> ";}else{$res.="<img src='../../images/awardstarbn.png' /> ";}
			if($totcont!=0)
			{
				if($totcump==$totcont)
				{
					$res.="<img src='../../images/cake.png' /> "; 
				}
				else
				{
					$res.="<img src='../../images/cakebn.png' /> "; 
				}
			}
			else
			{
				$res.="<img src='../../images/userbn.png' /> ";
			}
			if($numrfc!=0){$res.="<img src='../../images/invoice.png' /> ";}else {$res.="<img src='../../images/invoicebn.png' /> ";}
			$res.="<br />".resaltar($busqueda,$domicilio)."<br /><span class='subtext'>Etiquetado como: <span class='nobreaktext'>".$myroworg[tipo_organizacion]."</span></span></td><td class=' list-column-left'><a target='' href='mailto:".$myrowmailorg[correo]."'>".$myrowmailorg[correo]."</a><br />"; if($myrowtelorg[telefono]){$res.= $myrowtelorg[telefono]." (".$myrowtelorg[tipo_telefono].")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
			break;
		case 'Email':
		case 'Telefono':
			if($row[tipo_registro]=='O')//Email de Organización
			{
				$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion = '".$row[clave_registro]."'";
				$resultorg=mysql_query ($sqlorg,$db);
				while($myroworg=mysql_fetch_array($resultorg))
				{	
					if($myroworg[clave_agente]!=$claveagente&&$_SESSION["Tipo"]=="Promotor"){$celda="#FFFFCC";}else{$celda="#FFFFFF";}
					//Telefonos de Organización
					$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
					$resulttelorg= mysql_query ($sqltelorg,$db);
					$telorg=""; $tipotelorg="";
					while($myrowtelorg=mysql_fetch_array($resulttelorg))
					{	
						$telorg=$myrowtelorg[telefono];
						$tipotelorg=$myrowtelorg[tipo_telefono];
					}//Fin while teléfonos
					
					//Emails de Organización
					$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
					$resultemailorg= mysql_query ($sqlemailorg,$db);
					$mailorg="";
					while($myrowmailorg=mysql_fetch_array($resultemailorg))
					{	
						$mailorg=$myrowmailorg[correo];
						if($myroworg[clave_agente]!=$claveagente&&$_SESSION["Tipo"]=="Promotor"){$celda="#FFFFCC";}else{$celda="#FFFFFF";}
					}//Fin while email
					
					//Domicilios de la Organización
					$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
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
					}//Fin while domicilios
					
					//Telefonos del Contacto
					$sqltelcon="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$myroworg[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_telefono ASC LIMIT 1";
					$resulttelcon= mysql_query ($sqltelcon,$db);
					$telcon=""; $tipotelcon="";
					while($myrowtelcon=mysql_fetch_array($resulttelcon))
					{	
						$telcon=$myrowtelcon[telefono];
						$tipotelcon=$myrowtelcon[tipo_telefono];
					}//Fin while teléfonos contacto
					
					//Emails del Contacto
					$sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myroworg[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_correo ASC LIMIT 1";
					$resultemailcon= mysql_query ($sqlemailcon,$db);
					$mailcon="";
					while($myrowmailcon=mysql_fetch_array($resultemailcon))
					{	
						$mailcon=$myrowmailcon[correo];
					}//Fin while emails contacto
					
					//Contactos de la organización
					$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$myroworg[clave_organizacion]."' ORDER BY id_contacto ASC";
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
					
					if($myroworg[fecha_ultimo_contacto])
					{
						list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
						if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
						elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
						else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
					}//Fin ultimo contacto
					else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}
					
					//Datos del Agente
					$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
					$resultagente= mysql_query ($sqlagente,$db);
					while($myrowagente=mysql_fetch_array($resultagente))
					{
						$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
					}//Fin while agente
					
					$res="<tr class='even-row'>
					<td class='list-column-picture'>
						<img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' />
					</td>
					
					<td class=' list-column-left'>
						<a class='keytext' href='detalles.php?organizacion=".$myroworg[clave_organizacion]."'>".$myroworg[organizacion]."</a> <span class='highlight'>".$row[K_Cliente]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span>";
						if($myroworg[fecha_fundacion]!="0000-00-00"){$res.="<img src='../../images/awardstar.png' />";}else{$res.="<img src='../../images/awardstarbn.png' />";}
						if($totcont!=0)
						{
							if($totcump==$totcont)
							{
								$res.="<img src='../../images/cake.png' />"; 
							}
							else
							{
								$res.="<img src='../../images/cakebn.png' />"; 
							}
						}
						else
						{
							echo $res.="<img src='../../images/userbn.png' />";
						}
						$res.="<br />".$domicilio."
						<br />
						<span class='subtext'>Etiquetado como:
							<span class='nobreaktext'>Prospecto</span>
						</span>
					</td>
					
					<td class=' list-column-left'>
						<a target='' href='mailto:".$mailorg."'>".resaltar($busqueda,$mailorg)."</a><br />"; if($telorg){$res.= resaltar($busqueda,$telorg)." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
				}//
			}
			else//Email de Contacto
			{
				if($tabla=="contactos"){$sqlcon="SELECT * FROM contactos WHERE clave_contacto = '".$row[clave_contacto]."'";}
				else{$sqlcon="SELECT * FROM contactos WHERE clave_contacto = '".$row[clave_registro]."'";}
				$resultcon=mysql_query ($sqlcon,$db);
				while($myrowcon=mysql_fetch_array($resultcon))
				{
					
					$res="<tr class='even-row'>
					<td class='list-column-picture'><img class='picture-thumbnail' src='../../images/person_avatar_32.png' width='32' height='32' alt='picture' /></td>
					<td class=' list-column-left'>
							<a class='keytext' href='detalles.php?organizacion=".$myrowcon[clave_organizacion]."'>".$myrowcon[apellidos]." ".$myrowcon[nombre]."</a> ".$myrowcon[puesto]."
								en <a href='detalles.php?organizacion=".$myrowcon[clave_organizacion]."'>".$myrowcon[organizacion]."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:'".$resaltadocontacto."'>".$ultimocontacto."</span></td>
					<td class=' list-column-left'>
					<a target='' href='mailto:".$mailcon."'>".$mailcon."</a>
						<br />";
						if($myrowcon[telefono_casa]){$res.= resaltar($busqueda,$myrowcon[telefono_casa])." (Casa)<br />";}
						if($myrowcon[telefono_oficina]){$res.= resaltar($busqueda,$myrowcon[telefono_oficina])." (Oficina)<br />";}
						if($myrowcon[telefono_celular]){$res.= resaltar($busqueda,$myrowcon[telefono_celular])." (Celular)<br />";}
					$res.= "</td></tr>";
				}
			}
			break;
		case 'Clave':
			//Checklist
			$sqlchecklist="SELECT * FROM `checklist` WHERE `clave_organizacion` LIKE '".$row[clave_organizacion]."' ORDER BY id_checklist ASC";
			$resultchecklist= mysql_query ($sqlchecklist,$db);
			$check = mysql_num_rows($resultchecklist);
			$res="<tr class='even-row'><td class='list-column-picture'><img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' /></td><td class=' list-column-left'><a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."'>".resaltar($busqueda,$row[organizacion])."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span> ";
			if($row[fecha_fundacion]!='0000-00-00'){$res.="<img src='../../images/awardstar.png' /> ";}else{$res.="<img src='../../images/awardstarbn.png' /> ";}
			if($totcont!=0)
			{
				if($totcump==$totcont)
				{
					$res.="<img src='../../images/cake.png' /> "; 
				}
				else
				{
					$res.="<img src='../../images/cakebn.png' /> "; 
				}
			}
			else
			{
				$res.="<img src='../../images/userbn.png' /> ";
			}
			if($numrfc!=0){$res.="<img src='../../images/invoice.png' /> ";}else {$res.="<img src='../../images/invoicebn.png' /> "; }
			if($check!=0){$res.="<img src='../../images/checklist.png' /> "; }else {$res.="<img src='../../images/checklistbn.png' /> "; }

			$res.="<br />".$domicilio."<br /><span class='subtext'>Etiquetado como:<span class='nobreaktext'>Prospecto</span></span></td><td class=' list-column-left'><a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
			break;
		case 'Social':
		case 'RFC':
			$sqlsoc="SELECT * FROM razonessociales WHERE (razon_social like '%".$row[Anunciante]."%')";
			$resultsoc=mysql_query ($sqlsoc,$db);
			$numsoc=mysql_num_rows($resultsoc);
			$myrowsoc=mysql_fetch_array($resultsoc);
			//Ultima Facturación
			$sqlfac="SELECT * FROM ventas WHERE (Anunciante like '%".$row[Anunciante]."%') ORDER BY FechaCaptura DESC Limit 1";
			$resultfac=mysql_query ($sqlfac,$db);
			$numfac=mysql_num_rows($resultfac);
			$myrowfac=mysql_fetch_array($resultfac);
			if($numfac!=0)
			{
				list($dias, $meses) = diferencia_dias($myrowfac[FechaCaptura],$date);
				//Semaforización de venta
				if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
				elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
				else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
			}//
			else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}//
			
			$res="<tr class='even-row'>
			<td class='list-column-picture'>
				<img class='picture-thumbnail' src='../../images/sales.png' width='32' height='32' alt='picture' />
			</td>
			
			<td class=' list-column-left'>";
			if($numsoc!=0){$res.="
				<a class='keytext' href='detalles.php?organizacion=".$myrowsoc[clave_registro]."'>".$row[Anunciante]."</a>";}
			else{$res.=$row[Anunciante];}$res.=" <span class='highlight'>".$row[K_Cliente]."</span> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span> ";
				if($numsoc!=0){$res.="<img src='../../images/user.png' />";}else{$res.="<img src='../../images/userbn.png' />";}
				$res.="<br />".$domicilio."
				<br />
				<span class='subtext'>Etiquetado como:
					<span class='nobreaktext'>Prospecto</span>
				</span>
			</td>
			
			<td class=' list-column-left'>
				<a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
			break;
    }
	echo $res;
    $i++;
}
//}
?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>
</fieldset>
</form>