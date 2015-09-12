<?php
include ("../../seguridad.php");
include('../../config/config.php');
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

$busqueda=$_POST['campo'];
$tipo=$_POST['tipocampo'];

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

if($_SESSION["Tipo"]=="Usuario"){$bus = " AND clave_agente ='$claveagente' ";}
else{$bus = "";}

switch($tipo)
{
	case ''://Todas las organizaciones
		if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM organizaciones WHERE usuario ='".$claveagente."' ORDER BY Organizacion ASC";}
		else{$_pagi_sql="SELECT * FROM organizaciones ORDER BY Organizacion ASC";}
		$_pagi_cuantos = 10;
		$_pagi_propagar = array("tipo, busqueda");
		include("paginator.inc.php");
		break;
	case 'Puesto'://Puesto
		$tabla="contactos";
		$sqllist="SELECT * FROM contactos WHERE (puesto LIKE '%$busqueda%')".$bus."ORDER BY apellidos ASC";
		$_pagi_sql="SELECT * FROM contactos WHERE (puesto LIKE '%$busqueda%')".$bus."ORDER BY apellidos ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'Giro'://Puesto
		$tabla="organizaciones";
		$sqllist="SELECT * FROM organizaciones WHERE (giro LIKE '%$busqueda%')".$bus."ORDER BY organizacion ASC";
		$_pagi_sql="SELECT * FROM organizaciones WHERE (giro LIKE '%$busqueda%')".$bus."ORDER BY organizacion ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'Domicilio'://Domicilio
		$sqllist="SELECT * FROM domicilios WHERE (domicilio LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_sql="SELECT * FROM domicilios WHERE (domicilio LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'Delegacion'://Delegacion
		$sqllist="SELECT * FROM domicilios WHERE (ciudad LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_sql="SELECT * FROM domicilios WHERE (ciudad LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
	case 'CP'://CP
		$sqllist="SELECT * FROM domicilios WHERE (cp LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_sql="SELECT * FROM domicilios WHERE (cp LIKE '%$busqueda%') ORDER BY id_domicilio ASC";
		$_pagi_cuantos = 10;
		include("paginator.inc.php");
		break;
}
//echo $_pagi_sql;
?>
<link href="../../style.css" rel="stylesheet" type="text/css" />
    <?php
	$i=1;
	$c=0; $l=0;
	$lista="mailto:?bcc=";
	$resultlist=mysql_query($sqllist);
	$numlist=mysql_num_rows($resultlist);
	while ($myrowlist = mysql_fetch_array($resultlist))
	{
		$l++;
		if($tabla=="contactos"){//Emails de Contactos
		$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myrowlist[clave_contacto]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";}else{
		//Emails de Organización
		$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myrowlist[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";}
		
		$resultemailorg= mysql_query ($sqlemailorg,$db);
		$mailorg="";
		while($myrowmailorg=mysql_fetch_array($resultemailorg))
		{	
			$mailorg=$myrowmailorg[correo];
			$c++;
		}
		if($mailorg!=""){$lista.=$mailorg; if($l!=$numlist){$lista.=";";}}
	}
	?>
    
    <img src="../../images/mailmessage.png" class="linkImage" /><a href="<?php echo $lista; ?>">Generar lista de correo</a>
	<fieldset class="fieldsetgde">
    <legend>Mostrando <?php echo $_pagi_info; ?> registros</legend>
    <table class="recordList" > 
	<tbody>
<?php
	
	while ($row = mysql_fetch_array($_pagi_result))
	{
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
		}//

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
		}//
		
		//Telefonos del Contacto
		$sqltelcon="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$row[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_telefono ASC LIMIT 1";
		$resulttelcon= mysql_query ($sqltelcon,$db);
		$telcon=""; $tipotelcon="";
		while($myrowtelcon=mysql_fetch_array($resulttelcon))
		{	
			$telcon=$myrowtelcon[telefono];
			$tipotelcon=$myrowtelcon[tipo_telefono];
		}//
		
		//Emails del Contacto
		$sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$row[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_correo ASC LIMIT 1";
		$resultemailcon= mysql_query ($sqlemailcon,$db);
		$mailcon="";
		while($myrowmailcon=mysql_fetch_array($resultemailcon))
		{	
			$mailcon=$myrowmailcon[correo];
		}//
		
		if($row[fecha_ultimo_contacto])
		{
			list($dias, $meses) = diferencia_dias($row[fecha_ultimo_contacto],$date);
			//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			//Semaforización de oportunidades
			if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
			elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
			else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
		}//
		else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}//
		
		//Datos del Agente
		$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$row[clave_agente]."'";
		$resultagente= mysql_query ($sqlagente,$db);
		while($myrowagente=mysql_fetch_array($resultagente))
		{
			$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
		}//
		
		switch($tipo)
		{
			case 'Giro':
				$res="<tr class='even-row'>
                <td class='list-column-picture'>
                    <img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' />
                </td>
                
                <td class=' list-column-left'>
                    <a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span><br />".$domicilio."
                    <br />
                    <span class='subtext'>Etiquetado como:
                        <span class='nobreaktext'>".$row[tipo_organizacion]."</span>
                    </span>
                </td>
                
                <td class=' list-column-left'>
                    <a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
				break;
			case 'Puesto':
				$res="<tr class='even-row'>
                <td class='list-column-picture'><img class='picture-thumbnail' src='../../images/person_avatar_32.png' width='32' height='32' alt='picture' /></td>
                <td class=' list-column-left'>
                        <a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[apellidos]." ".$row[nombre]."</a> ".$row[puesto]."
                            en <a href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:'".$resaltadocontacto."'>".$ultimocontacto."</span></td>
                <td class=' list-column-left'>
                <a target='' href='mailto:".$mailcon."'>".$mailcon."</a>
                    <br />";if($telcon){$res.= $telcon." (".$tipotelcon.")</td>
                </tr>";} else{$res.= "</td></tr>";}
				break;
			case 'Domicilio':
			case 'Delegacion':
			case 'CP':
				$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$row[clave_registro]."'";
				$rsorg= mysql_query ($sqlorg,$db);
				$rworg=mysql_fetch_array($rsorg);
				
				//Telefonos de Organización
				$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$rworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
				$resulttelorg= mysql_query ($sqltelorg,$db);
				$telorg=""; $tipotelorg="";
				while($myrowtelorg=mysql_fetch_array($resulttelorg))
				{	
					$telorg=$myrowtelorg[telefono];
					$tipotelorg=$myrowtelorg[tipo_telefono];
				}
				
				//Emails de Organización
				$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$rworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
				$resultemailorg= mysql_query ($sqlemailorg,$db);
				$mailorg="";
				while($myrowmailorg=mysql_fetch_array($resultemailorg))
				{	
					$mailorg=$myrowmailorg[correo];
				}//
		
				//Domicilios de la Organización
				$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$rworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
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
				}//
				
				//Telefonos del Contacto
				$sqltelcon="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$rworg[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_telefono ASC LIMIT 1";
				$resulttelcon= mysql_query ($sqltelcon,$db);
				$telcon=""; $tipotelcon="";
				while($myrowtelcon=mysql_fetch_array($resulttelcon))
				{	
					$telcon=$myrowtelcon[telefono];
					$tipotelcon=$myrowtelcon[tipo_telefono];
				}//
				
				//Emails del Contacto
				$sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$rworg[clave_contacto]."' AND `tipo_registro` = 'C' ORDER BY id_correo ASC LIMIT 1";
				$resultemailcon= mysql_query ($sqlemailcon,$db);
				$mailcon="";
				while($myrowmailcon=mysql_fetch_array($resultemailcon))
				{	
					$mailcon=$myrowmailcon[correo];
				}//
				
				if($row[fecha_ultimo_contacto])
				{
					list($dias, $meses) = diferencia_dias($row[fecha_ultimo_contacto],$date);
					//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
					//Semaforización de oportunidades
					if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
					elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
					else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				}//
				else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}//
				
				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$rworg[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
				{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}//
				
				$res="<tr class='even-row'>
                <td class='list-column-picture'>
                    <img class='picture-thumbnail' src='../../images/org_avatar_32.png' width='32' height='32' alt='picture' />
                </td>
                
                <td class=' list-column-left'>
                    <a class='keytext' href='detalles.php?organizacion=".$rworg[clave_organizacion]."'>".$rworg[organizacion]."</a> <span class='highlight'>".$rworg[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span><br />".resaltar($busqueda,$domicilio)."
                    <br />
                    <span class='subtext'>Etiquetado como:
                        <span class='nobreaktext'>".$rworg[tipo_organizacion]."</span>
                    </span>
                </td>
                
                <td class=' list-column-left'>
                    <a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
				break;
			case 'Puesto':
				$res="<tr class='even-row'>
                <td class='list-column-picture'><img class='picture-thumbnail' src='../../images/person_avatar_32.png' width='32' height='32' alt='picture' /></td>
                <td class=' list-column-left'>
                        <a class='keytext' href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[apellidos]." ".$row[nombre]."</a> ".resaltar($busqueda,$row[puesto])."
                            en <a href='detalles.php?organizacion=".$row[clave_organizacion]."'>".$row[organizacion]."</a> <span class='highlight'>".$row[clave_unica]."</span> <span class='highlight' style='background-color:'".$resaltadocontacto."'>".$ultimocontacto."</span></td>
                <td class=' list-column-left'>
                <a target='' href='mailto:".$mailcon."'>".$mailcon."</a>
                    <br />";if($telcon){$res.= $telcon." (".$tipotelcon.")</td>
                </tr>";} else{$res.= "</td></tr>";}
				break;
			case 'Producto':
				//Ultima Facturación
				if($_SESSION["Tipo"]=="Usuario"){$sqlfac="SELECT * FROM ventas WHERE (K_Cliente = '".$row[K_Cliente]."') AND (D_SECCION LIKE '%$busqueda%' OR D_RUBRO LIKE '%$busqueda%') AND K_Agente = '".$claveagente."' ORDER BY FechaCaptura DESC Limit 1";}else{$sqlfac="SELECT * FROM ventas WHERE (K_Cliente = '".$row[K_Cliente]."') AND (D_SECCION LIKE '%$busqueda%' OR D_RUBRO LIKE '%$busqueda%') ORDER BY FechaCaptura DESC Limit 1";}
				//echo $sqlfac;
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
				else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";}
				
				$sqlsoc="SELECT * FROM razonessociales WHERE (razon_social like '%".$myrowfac[Anunciante]."%' )";
				$resultsoc=mysql_query ($sqlsoc,$db);
				$myrowsoc=mysql_fetch_array($resultsoc);
				$numsoc=mysql_num_rows($resultsoc);
				
				$res="<tr class='even-row'>
				<td class='list-column-picture'>
					<img class='picture-thumbnail' src='../../images/sales.png' width='32' height='32' alt='picture' />
				</td>
				
				<td class=' list-column-left'>";
					if($numsoc!=0){$res.="
					<a class='keytext' href='detalles.php?organizacion=".$myrowsoc[clave_registro]."'>".$myrowfac[Anunciante]."</a>";} else{$res.=$myrowfac[Anunciante];}$res.=" <span class='highlight'>".$myrowfac[K_Cliente]."</span> <span class='highlight'>".$myrowfac[clave_unica]."</span> <span class='highlight' style='background-color:".$resaltadocontacto.";'>".$ultimocontacto."</span> ";
					if($numsoc!=0){$res.="<img src='../../images/user.png' />";}else{$res.="<img src='../../images/userbn.png' />";}
					$res.="<br />".resaltar($busqueda,$myrowfac[D_Rubro])."-".resaltar($busqueda,$myrowfac[D_Seccion])."
					<br />
					<span class='subtext'>Etiquetado como:
						<span class='nobreaktext'>Prospecto</span>
					</span>
				</td>
				
				<td class=' list-column-left'>
					<a target='' href='mailto:".$mailorg."'>".$mailorg."</a><br />"; if($telorg){$res.= $telorg." (".$tipotelorg.")";} if($_SESSION["Tipo"]!="Usuario"){ $res.="<br /><b>".$agente."</b>";} $res.="</td></tr>";
				break;
		}//Fin de switch
		echo $res;	
		$i++;
	}//Fin de while resultados
?>
	</tbody>
	</table>
    <?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>
    </fieldset>
    