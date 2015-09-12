<?php

include("config/config.php");

function generateKey()
{//Generate a unique key
    do{
    $key = uniqid();
    }
    while(validateKey($key));
 
    return $key;
}
 
function validateKey($key)
{//check the DB
	$query = "SELECT organizaciones.clave_organizacion, contactos.clave_contacto FROM organizaciones, contactos WHERE organizaciones.clave_organizacion ='".$key."' AND contactos.clave_contacto ='".$key."' LIMIT 1";
	
	$result=mysql_query($query);
	
	if (mysql_num_rows($result)) return true;
	else return false;
}

function nombre_mes($mes)
{
	switch ($mes)
	{
    case 1:
        $nombre= "Enero";
        break;
    case 2:
        $nombre= "Febrero";
        break;
    case 3:
        $nombre= "Marzo";
		break;
	case 4:
        $nombre= "Abril";
		break;
	case 5:
        $nombre= "Mayo";
		break;
	case 6:
        $nombre= "Junio";
		break;
	case 7:
        $nombre= "Julio";
		break;
	case 8:
        $nombre= "Agosto";
		break;
	case 9:
        $nombre= "Septiembre";
		break;
	case 10:
        $nombre= "Octubre";
		break;
	case 11:
        $nombre= "Noviembre";
		break;
	case 12:
        $nombre= "Diciembre";
		break;
	}
	return($nombre);
}

function ultimo_dia($mes,$anio)
{
	$ultimodia=date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));
	return($ultimodia);
}

function trimestre($mes=null)
{
	$mes = is_null($mes) ? date('m') : $mes;
	$trim=floor(($mes-1) / 3)+1;
	return $trim;
}

//////////// CODIFICACION GEOGRAFICA /////////////////////////

/*define("MAPS_HOST", "maps.google.com");
define("KEY", "AIzaSyBNf8QeTTIpLHXc9965s_DmkQImdY1tM8s");

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . AIzaSyBNf8QeTTIpLHXc9965s_DmkQImdY1tM8s;

// Iterate through the rows, geocoding each address

  $geocode_pending = true;

  while ($geocode_pending)
  {
    $address = $direccion;
    //$id = $row["id"];
    $request_url = $base_url . "&q=" . urlencode($address);
    echo $request_url;
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0)
    {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
      
      echo $lat;
      echo $lng;

     }
    else if (strcmp($status, "620") == 0)
    {
      // sent geocodes too fast
      $delay += 100000;
    }
    else
    {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocoded. ";
      echo "Received status " . $status . "
\n";
    }
    usleep($delay);
  }
*/

function diferencia_dias($fecha1,$fecha2)
{
	//defino fecha 1 
	$minuendo=explode("-",$fecha1);
	$ano1 = $minuendo[0]; 
	$mes1 = $minuendo[1]; 
	$dia1 = $minuendo[2]; 
	
	//defino fecha 2 
	$sustraendo=explode("-",$fecha2);
	$ano2 = $sustraendo[0]; 
	$mes2 = $sustraendo[1]; 
	$dia2 = $sustraendo[2];
	
	//calculo timestamp de las dos fechas 
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
	$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
	
	//resto a una fecha la otra 
	$segundos_diferencia = $timestamp1 - $timestamp2; 
	//echo $segundos_diferencia; 
	
	//convierto segundos en días y meses
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	$meses_diferencia = $segundos_diferencia / (60 * 60 * 24 * 30); 
	
	//obtengo el valor absoulto de los días (quito el posible signo negativo) 
	$dias_diferencia = abs($dias_diferencia); 
	$meses_diferencia = abs($meses_diferencia);
	
	//quito los decimales a los días de diferencia 
	$dias_diferencia = floor($dias_diferencia); 
	$meses_diferencia = floor($meses_diferencia);
	
	return array($dias_diferencia,$meses_diferencia);
}

function format_Telefono($Telefono)
{
	$digitos=strlen($Telefono);
	$c=substr($Telefono, 0, 1);
	switch($digitos)
	{
		//Celular 
		case 13:
			$prefijo = substr($Telefono, -13, 3);
			$lada = substr($Telefono, -10, 3);
			$parte1 = substr($Telefono, -7, 3);
			$parte2 = substr($Telefono, -4, 4);
			$Tel = $prefijo."(".$lada.")".$parte1."-".$parte2;
			break;
		case 12:
			$prefijo = substr($Telefono, -12, 2);
			$lada = substr($Telefono, -10, 3);
			$parte1 = substr($Telefono, -7, 3);
			$parte2 = substr($Telefono, -4, 4);
			$Tel = $prefijo."(".$lada.")".$parte1."-".$parte2;
			break;
		case 10:
			switch ($c)
			{
				case '5':
				case '8':
				case '3':
					$lada = substr($Telefono, -10, 2);
					$parte1 = substr($Telefono, -7, 4);
					$parte2 = substr($Telefono, -4, 4);
				break;
				default;
        			$lada = substr($Telefono, -10, 3);
					$parte1 = substr($Telefono, -7, 3);
					$parte2 = substr($Telefono, -4, 4);
    			break;
			}
			$Tel = "(".$lada.")".$parte1."-".$parte2;
			break;
		case 7:
			$parte1 = substr($Telefono, -7, 3);
			$parte2 = substr($Telefono, -4, 4);
			$Tel = $parte1."-".$parte2;
			break;
	}
	return($Tel);
}

function resaltar($search,$string)
{
        
    $search = preg_quote($search,"/");
    preg_match_all('/'. $search .'/i',$string,$matches);
    $replace = array();
    $new_search = $matches[0];
    foreach($new_search as $r) { $replace[] = '<span style="color:#00CCCC">'. $r .'</span>';  }
    return str_replace($new_search,$replace,$string);
}

function barra($organizacion,$oportunidad,$nivel)
{
	/*$host="minia001.mysql.guardedhost.com";
	$user="minia001_premo";
	$pass="3kX96tQd";
	$basedatos="minia001_premo";
	$db=mysql_connect($host, $user, $pass);
	mysql_select_db ($basedatos,$db);*/
	global $db;
	
	//Definir rutas de imágenes y vínculos
	if($nivel==1){$rutaimg=""; $rutavin="modulos/";}else{$rutaimg="../../"; $rutavin="../";}
	
	//Verificar si falta información importante por capturar
	$c=0;
	$sqloportunidad="SELECT * FROM oportunidades WHERE clave_oportunidad='".$oportunidad."'";
	$resultopt= mysql_query($sqloportunidad,$db);
    $myrowopt=mysql_fetch_array($resultopt);
	
	$sqlempresa="SELECT * FROM organizaciones WHERE	clave_organizacion='".$organizacion."'";
	$rsempresa= mysql_query ($sqlempresa,$db);
	$rwempresa=mysql_fetch_array($rsempresa);
	
	$sqlgarante="SELECT * FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$oportunidad."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC";
	$rsgarante= mysql_query ($sqlgarante,$db);
	$rwgarante=mysql_fetch_array($rsgarante);
	
	$t=6;
	$falta="Falta ";
	if($rwempresa[tipo_persona])
	{
		$c++;
		if($rwempresa[tipo_persona]=="Moral")
		{
			$t+=2;
			$sqlacrrl="SELECT * FROM relaciones WHERE clave_organizacion='".$rwempresa[clave_organizacion]."' AND rol='Representante Legal'";
			$rsacrrl= mysql_query ($sqlacrrl,$db);
			$rwacrrl=mysql_fetch_array($rsacrrl);
			if($rwacrrl){$c++;}
			else{$falta.="Representante Legal del Acreditado, ";}
			
			$sqlacrap="SELECT * FROM relaciones WHERE clave_organizacion='".$rwempresa[clave_organizacion]."' AND rol='Accionista Principal'";
			$rsacrap= mysql_query ($sqlacrap,$db);
			$rwacrap=mysql_fetch_array($rsacrap);
			if($rwacrap){$c++;}
			else{$falta.="Accionista Principal del Acreditado, ";}
		}
	}
	else{$falta.="Tipo de Persona del Acreditado, ";}
	
	if($myrowopt[tipo_credito]){$c++;}else{$falta.="Tipo de Crédito,";}
	if($myrowopt[monto]){$c++;}else{$falta.="Monto de Crédito, ";}
	if($myrowopt[plazo_credito]){$c++;}else{$falta.="Plazo de Crédito, ";}
	if($myrowopt[destino_credito]){$c++;}else{$falta.="Destino de Crédito, ";}
	
	if($rwgarante)
	{
		 $c++;
		 if($rwgarante[tipo_persona]=="Moral")
		 { 
			$t+=2;
			$sqlacrrl="SELECT * FROM relaciones WHERE clave_organizacion='".$rwgarante[clave_relacion]."' AND rol!='Representante Legal'";
			$rsacrrl= mysql_query ($sqlacrrl,$db);
			$rwacrrl=mysql_fetch_array($rsacrrl);
			if($rwacrrl){$c++;}
			else{$falta.="Representante Legal del Garante, ";}
			
			$sqlacrap="SELECT * FROM relaciones WHERE clave_organizacion='".$rwgarante[clave_relacion]."' AND rol!='Accionista Principal'";
			$rsacrap= mysql_query ($sqlacrap,$db);
			$rwacrap=mysql_fetch_array($rsacrap);
			if($rwacrap){$c++;}
			else{$falta.="Accionista Principal del Garante, ";}
		}
	}
	else{$falta.="Garante, ";}
	$campos=$c*100/$t;
	$barra="";
	for($j=0;$j<$c;$j++){$barra.="<img src='".$rutaimg."images/verde.png' title='".$falta."'/>";}
	for($k=0;$k<($t-$c);$k++){$barra.="<img src='".$rutaimg."images/blanco.png' title='".$falta."'/>";}
	return array ($barra, $campos);
}

function vinculos($organizacion,$oportunidad,$etapa,$origen,$nivel,$responsable)
{			
	/*$host="minia001.mysql.guardedhost.com";
	$user="minia001_premo";
	$pass="3kX96tQd";
	$basedatos="minia001_premo";
	$db=mysql_connect($host, $user, $pass);
	mysql_select_db ($basedatos,$db);*/
	
	global $db;
	$date=date("Y-m-d");
	
	//Definir rutas de imágenes y vínculos
	if($nivel==1){$rutaimg=""; $rutavin="modulos/";}else{$rutaimg="../../"; $rutavin="../";}
	
	//Datos de la oportunidad
	$sqloportunidad="SELECT * FROM oportunidades WHERE id_oportunidad='".$oportunidad."'";
	$resultopt= mysql_query($sqloportunidad,$db);
    $myrowopt=mysql_fetch_array($resultopt);
	
	list ($barra, $campos) = barra($organizacion,$myrowopt[clave_oportunidad],$nivel);
	
	//Datos del proceso
	$sqlproceso="SELECT * FROM `etapasoportunidades` WHERE `clave_oportunidad` = '".$myrowopt[clave_oportunidad]."' AND id_etapa='7'";
	$rsproceso= mysql_query($sqlproceso,$db);
	$rwproceso=mysql_fetch_array($rsproceso);
	
	//Datos de organización
	$sqlorg="SELECT * FROM organizaciones WHERE clave_organizacion='".$organizacion."'";
	$rsorg= mysql_query($sqlorg,$db);
	$rworg=mysql_fetch_array($rsorg);
	
	//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	$myrowetp=mysql_fetch_array($resultetp);

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
	$autorizacion=0;
	//Verificar si se solicita algún expediente en la etapa de la oportunidad
	$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$etapa."' OR id_etapa='".$myrowetp[etapa_anterior]."'";
	$resultexp= mysql_query ($sqlexp,$db);
	$myrowexp=mysql_fetch_array($resultexp);
	if($myrowexp)//Si hay expediente asociado a la etapa de la oportunidad, obtener los datos necesarios.
	{			
		//Verificar si el garante fue capturado y si éste es el mismo que el acreditado
		$sqlgarante="SELECT relaciones.clave_organizacion,relaciones.clave_contacto,relaciones.clave_relacion,relaciones.rol, relaciones.clave_oportunidad FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$myrowopt[clave_oportunidad]."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC";
		$rsgarante= mysql_query ($sqlgarante,$db);
		$rwgarante=mysql_fetch_array($rsgarante);
		if($rwgarante[clave_organizacion]!=$rwgarante[clave_relacion])
		{
			$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."' AND requerido='1' AND tipo_persona='".$rworg[tipo_persona]."'";
		}
		else
		{
			$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."' AND requerido='1' AND tipo_persona='".$rworg[tipo_persona]."' AND rol_persona!='Garante'";

		}
		
		//Obtener cuántos tipos de archivos REQUERIDOS tiene el expediente solicitado
		$resulttipos= mysql_query ($sqltipos,$db);
		$totarch=mysql_num_rows($resulttipos);//archivos totales del expediente
		$myrowtipos=mysql_fetch_array($resulttipos);
		
		//Numeros para expediente, obtener cuántos de los documentos requeridos fueron subidos al sistema
		$sqlrequeridos="SELECT count(archivos.id_archivo)as total FROM archivos JOIN tiposarchivos ON (archivos.id_tipoarchivo = tiposarchivos.id_tipoarchivo) WHERE archivos.id_oportunidad = '".$oportunidad."' AND archivos.id_expediente = '".$myrowexp[id_expediente]."' AND tiposarchivos.requerido='1' AND tiposarchivos.tipo_persona='".$rworg[tipo_persona]."'";
        $resultrequeridos= mysql_query ($sqlrequeridos,$db);
		$myrowrequeridos=mysql_fetch_array($resultrequeridos);
		$requeridos=$myrowrequeridos[total];
        
        //Obtener cuántos documentos son no revisados, aprobados o rechazados y cargados en total
        $sqlarchivo="SELECT count(`id_archivo`)as total, aprobado FROM `archivos` WHERE id_oportunidad = '".$oportunidad."' AND `id_expediente` = '".$myrowexp[id_expediente]."' GROUP BY (`aprobado`)";
        //echo $sqlarchivo;
		$resultarchivo= mysql_query ($sqlarchivo,$db);
		$aprobados=0;$nrevisado=0;$rechazados=0;$cargados=0;
		while($myrowarchivo=mysql_fetch_array($resultarchivo))
		{						
			if($myrowarchivo[aprobado]==0){$nrevisado=$myrowarchivo[total];}elseif($myrowarchivo[aprobado]==1){$aprobados=$myrowarchivo[total];}else{$rechazados=$myrowarchivo[total];}
		}
        
		$cargados=$aprobados+$rechazados+$nrevisado;
        //echo $aprobados."-".$nrevisado."-".$rechazados."-".$cargados;
        
        //Lista de reprobados
        $recrequ=0;
        $sqlrechazado="SELECT  archivos.aprobado,archivos.id_archivo,tiposarchivos.requerido FROM `archivos` JOIN tiposarchivos ON (archivos.id_tipoarchivo = tiposarchivos.id_tipoarchivo) WHERE archivos.id_oportunidad = '".$oportunidad."' AND archivos.id_expediente = '".$myrowexp[id_expediente]."' AND archivos.aprobado=2 AND tiposarchivos.tipo_persona='".$rworg[tipo_persona]."' ORDER BY archivos.id_archivo";
        //echo $sqlrechazado;
        $resultrechazado= mysql_query ($sqlrechazado,$db);
        while($myrowrechazado=mysql_fetch_array($resultrechazado))
		{
            if($myrowrechazado[aprobado]==2&&$myrowrechazado[requerido]==1)
            {
                $recrequ++;
            }
        }
        //echo $recrequ;
	}
	
	//Verificar si se capturo análisis
	$sqlanalisis="SELECT * FROM analisis WHERE id_oportunidad='".$oportunidad."'";
    $rsanalisis= mysql_query ($sqlanalisis,$db);
	$totanalisis=mysql_num_rows($rsanalisis);
    $rwanalisis=mysql_fetch_array($rsanalisis);
	
	//Color de celda y vínculos
	if($responsable==3)
	{
		$mensajes="<img src='".$rutaimg."images/comment_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/editarregistro.php?id=".$oportunidad."&o=I&a=".$origen."&organizacion=".$organizacion."' id='addButton' title='Agregar una nota a la oportunidad' class='clsVentanaIFrame clsBoton' rel='Agregar Nota' style='color:#4693D7;text-decoration:none; font-size:11px;'>Agregar nota</a>";
	}

	switch($etapa)//Definir vínculo según etapa de la opurtunidad
	{
		case 1://ETAPA 1: PRIMER CONTACTO
			$archivo="";
			$link="";
			break;
		case 2://ETAPA 2: CITA
			$archivo="";
			$link="";
			break;
		case 3://ETAPA 3: CONFIRMACIÓN VERBAL
			$archivo="";
			$link="";
			break;
		case 4://ETAPA 4: RECABACIÓN DE EXPEDIENTE PRELIMINAR
			$archivo="";
			if($requeridos!=$totarch)//No se han subido todos los archivos del expediente
			{
				//Vínculo de expediente
				$documentos = "<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='rojo' title='Cargados(".$requeridos.") de (".$totarch.") documentos requeridos'>".$myrowexp[expediente]."</a>";
			}
			else//Ya se han subido todos los archivos del expediente
			{
				$documentos = "<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Cargados(".$requeridos.") de (".$totarch.") documentos requeridos'>".$myrowexp[expediente]."</a>";
                if($vinculo==1&&$campos==100)
				{
					$avanzar = '<img src="'.$rutaimg.'images/next_16.png" width="16" height="16"  class="linkImage" /><a onclick="avanzar(\''.$oportunidad.'\', \'U\', \''.$origen.'\',\''.$etapa.'\',\''.$organizacion.'\')" href="#" id="addButton" class="azul" title="Solicitar validación de expediente">Avanzar</a>';
					$autorizacion=1;
				}
			}
			break;
		case 5://ETAPA 5: ANÁLISIS DE EXPEDIENTE PRELIMINAR		
			$archivo="";
            //echo $requeridos."-".rechazados."-".$nrevisado."-".$vinculo."-".$campos."-".$totanalisis."-".$recrequ;
			if($nrevisado==0&&$recrequ!=0&&$vinculo==1)
			{
				$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				$retroceder="<img src='".$rutaimg."images/prev_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/update.php?id=".$oportunidad."&o=U&a=".$origen."&re=".$id_etapa."&organizacion=".$organizacion."' id='addButton' class='azul'>Retroceder</a>";
				if($totanalisis)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
				}
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
			}
			elseif($aprobados>=$requeridos&&$vinculo==1&&$campos==100)
			{
                if($totanalisis)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
					$avanzar="<img src='".$rutaimg."images/next_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/update.php?id=".$oportunidad."&o=U&a=".$origen."&av=".$etapa."&organizacion=".$organizacion."' id='addButton' class='azul'>Avanzar</a>";
					$autorizacion=1;
				}	
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
				$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
			}
			else
			{
				if($totanalisis)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
				}
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
				if($nrevisado==0)
				{
					$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				}
				else
				{
					$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='rojo' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				}
			}
			break;
		case 12://ETAPA 6: ESTUDIO DE CRÉDITO
			$archivo="";
			if($totanalisis)
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
			}
			else
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
			}
			break;
		case 13://ETAPA 7: PRE AUTORIZACIÓN
			$archivo="";
			if($nrevisado==0&&$aprobados!=$totarch&&$vinculo==1)
			{
				$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				$retroceder="<img src='".$rutaimg."images/prev_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/update.php?id=".$oportunidad."&o=U&a=".$origen."&re=".$id_etapa."&organizacion=".$organizacion."' id='addButton' class='azul'>Retroceder</a>";
				if($totanalisis)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
				}
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
			}
			elseif($aprobados==$totarch&&$vinculo==1)
			{
				if($totanalisis!=0)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
					$avanzar="<img src='".$rutaimg."images/next_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/update.php?id=".$oportunidad."&o=U&a=".$origen."&av=".$etapa."&organizacion=".$organizacion."' id='addButton' class='azul'>Avanzar</a>";
					$autorizacion=1;
				}	
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
				$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
			}
			else
			{
				if($totanalisis)
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
				}
				else
				{
					$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				}
				if($nrevisado==0)
				{
					$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				}
				else
				{
					$documentos="<img src='".$rutaimg."images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."expediente/formupdate.php?id=".$oportunidad."&o=U&a=".$origen."&e=".$myrowexp[id_expediente]."&organizacion=".$organizacion."' id='addButton' class='rojo' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos requeridos'>".$myrowexp[expediente]."</a>";
				}
			}
			break;
		case '7': //ETAPA 8: ENTREGA DE PROPUESTA A PROSPECTO
			$sqlarchivos="SELECT * FROM archivos JOIN tiposarchivos ON (tiposarchivos.id_tipoarchivo = archivos.id_tipoarchivo) WHERE tiposarchivos.id_expediente='3' AND archivos.id_oportunidad='".$oportunidad."'";
			$resultarchivos= mysql_query ($sqlarchivos,$db);
			$rwarchivos=mysql_fetch_array($resultarchivos);
			//Vigencia de la propuesta
			list($dias, $meses) = diferencia_dias($rwproceso[fecha],$date);
			$vigencia=30-$dias;
			
			if($dias<30)
			{
				if($vigencia<=5){$resaltadocontacto="#FF7F7F";}
				elseif($vigencia>=6&&$vigencia<=11){$resaltadocontacto="#FFCC00";}
				else{$resaltadocontacto="#86CE79";}
			}
			else
			{
				$vigencia="La propuesta ha expirado";$resaltadocontacto="#FF7F7F";
			}
			$archivo="<img src='".$rutaimg."images/acrobat_16.png' class='linkImage' /> <a href='".$rutaimg."expediente/".$rwarchivos[nombre]."' target='_blank'><span class='highlight' style='background-color:#".$myrowcolor[color]."; font-weight:normal;'> ".$rwarchivos[nombre_original]."</span></a><span class='count important' style='background-color:".$resaltadocontacto.";' title='Vigencia de la propuesta'>".$vigencia."</span>";
			$avanzar="<img src='".$rutaimg."images/next_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."oportunidades/update.php?id=".$oportunidad."&o=U&a=".$origen."&av=".$etapa."&organizacion=".$organizacion."' id='addButton' class='azul'>Avanzar</a>";
			
			if($totanalisis)
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
			}
			else
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
			}
			$link="";
			break;
		case '9': //ETAPA 9: DEPÓSITO DE SERIEDAD
			$sqlarchivos="SELECT * FROM archivos JOIN tiposarchivos ON (tiposarchivos.id_tipoarchivo = archivos.id_tipoarchivo) WHERE tiposarchivos.id_expediente='3' AND archivos.id_oportunidad='".$oportunidad."'";
			$resultarchivos= mysql_query ($sqlarchivos,$db);
			$rwarchivos=mysql_fetch_array($resultarchivos);
			//Vigencia de la propuesta
			list($dias, $meses) = diferencia_dias($rwproceso[fecha],$date);
			$vigencia=30-$dias;
			
			if($dias<30)
			{
				if($vigencia<=5){$resaltadocontacto="#FF7F7F";}
				elseif($vigencia>=6&&$vigencia<=11){$resaltadocontacto="#FFCC00";}
				else{$resaltadocontacto="#86CE79";}
			}
			else
			{
				$vigencia="La propuesta ha expirado";$resaltadocontacto="#FF7F7F";
			}
			$archivo="<img src='".$rutaimg."images/acrobat_16.png' class='linkImage' /> <a href='".$rutaimg."expediente/".$rwarchivos[nombre]."' target='_blank'><span class='highlight' style='background-color:#".$myrowcolor[color]."; font-weight:normal;'> ".$rwarchivos[nombre_original]."</span></a><span class='count important' style='background-color:".$resaltadocontacto.";' title='Vigencia de la propuesta'>".$vigencia."</span>";
			if($totanalisis)
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=U&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
			}
			else
			{
				$analisis="<img src='".$rutaimg."images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='".$rutavin."analisis/forminsert.php?id=".$oportunidad."&o=I&a=".$origen."&an=".$rwanalisis[id_analisis]."&organizacion=".$organizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
			}
			$link="";
			break;
		case '14': //ETAPA 10: RECABACIÓN DE EXPEDIENTE DE FORMALIZACIÓN
			$archivo="";
			$link="";
			break;
		case '10'://ETAPA 11: CIERRE(CRÉDITO OTORGADO)
			$archivo="";
			$link="";
			break;
		case '11'://ETAPA 12: CIERRE(CRÉDITO RECHAZADO)
			$archivo="";
			$link="";
			break;
	}
	$link = "<br />".$documentos." ".$archivo." ".$analisis." ".$enviar." ".$avanzar." ".$retroceder." ".$mensajes;
	//return $autorizacion;
	return array($link,$autorizacion);
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s, $d = 'wavatar', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

<?php
function mail_utf8($to, $from_user, $from_email, 
                                             $subject = '(No subject)', $message = '')
   { 
      $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
      $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

      $headers = "From: $from_user <$from_email>\r\n". 
               "MIME-Version: 1.0" . "\r\n" . 
               "Content-type: text/html; charset=UTF-8" . "\r\n"; 

     return mail($to, $subject, $message, $headers); 
   }
?>

?>