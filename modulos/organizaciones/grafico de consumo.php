<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<fieldset class="fieldsethistorial">
        <legend>Gráfico de consumo</legend>

		<?php
		$animateChart = $_GET['animate'];
		//Set default value of 1
		if ($animateChart=="")
			$animateChart = "1";
		
		$strXML = "<chart caption='Consumo Mensual' subcaption='' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='10' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' bgAlpha='10,10'>
	<categories>
	<category label='Ene'/>
	<category label='Feb'/>
	<category label='Mar'/>
	<category label='Abr'/>
	<category label='May'/>
	<category label='Jun'/>
	<category label='Jul'/>
	<category label='Ago'/>
	<category label='Sep'/>
	<category label='Oct'/>
	<category label='Nov'/>
	<category label='Dic'/>
	</categories>";
	
	 $strTabla = "<table class='recordList' style='margin-top: 12px;'>
		  <thead>
		  <tr>
		    <th class='list-column-left' scope='col'></th>
			<th class='list-column-left' scope='col'>Ene</th>
		    <th class='list-column-left' scope='col'>Feb</th>
		    <th class='list-column-left' scope='col'>Mar</th>
		    <th class='list-column-left' scope='col'>Abr</th>
		    <th class='list-column-left' scope='col'>May</th>
		    <th class='list-column-left' scope='col'>Jun</th>
		    <th class='list-column-left' scope='col'>Jul</th>
		    <th class='list-column-left' scope='col'>Ago</th>
		    <th class='list-column-left' scope='col'>Sep</th>
		    <th class='list-column-left' scope='col'>Oct</th>
		    <th class='list-column-left' scope='col'>Nov</th>
		    <th class='list-column-left' scope='col'>Dic</th>
		    </tr>
			</thead>
			<tbody>";
		 
		//Venta por mes, año anterior
		$strXML .= "<dataset seriesName='".$Anio_anterior."' color='1D8BD1' anchorBorderColor='1D8BD1' anchorBgColor='1D8BD1'>";
		$strTabla .= "<tr class='odd-row'><td class='list-column-left' style='color:#1D8BD1;'><b>".$Anio_anterior."</b></td>";
		
		for($ma=1;$ma<=12;$ma++)
		{
			$qultimodia_ant= $Anio_anterior."-".$ma."-".ultimo_dia($m,$Anio_anterior)." 00:00:00";
			$qprimerdia_ant= $Anio_anterior."-".$ma."-01 00:00:00";
			
			$sqlventa_ant = "SELECT SUM(ValorNeto) as total_ant FROM ventas WHERE ".$clavescliente." AND K_Agente = '".$numeroagente."' AND (Fecha >= '".$qprimerdia_ant."' AND Fecha <= '".$qultimodia_ant."')";
			
			//echo $sqlventa_ant;
			$resultventa_ant = mysql_query($sqlventa_ant, $db) or die(mysql_error());
			$venta_ant = mysql_fetch_array($resultventa_ant, MYSQL_ASSOC);
			
			$strXML .= "<set value='".$venta_ant['total_ant']."'/>";
			$strTabla.= "<td class='list-column-left'>".number_format($venta_ant['total_ant'])."</td>";
		}//fin de for
		$strXML .= "</dataset>";
		$strTabla .= "</tr>";
		
		//Venta por mes, año actual
		$strXML .= "<dataset seriesName='".$Anio."' color='F1683C' anchorBorderColor='F1683C' anchorBgColor='F1683C'>";
		$strTabla .= "<tr class='odd-row'><td class='list-column-left' style='color:#F1683C;'><b>".$Anio."</b></td>";
		
		for($m=1;$m<=$Mes;$m++)
		{
			$qultimodia= $Anio."-".$m."-".ultimo_dia($m,$Anio)." 00:00:00";
			$qprimerdia= $Anio."-".$m."-01 00:00:00";
			
			$sqlventa_act = "SELECT SUM(ValorNeto) as total FROM ventas WHERE ".$clavescliente." AND K_Agente = '".$numeroagente."' AND (Fecha >= '".$qprimerdia."' AND Fecha <= '".$qultimodia."')";
			//echo $sqlventa_act;
			
			$resultventa = mysql_query($sqlventa_act, $db) or die(mysql_error());
			$venta = mysql_fetch_array($resultventa, MYSQL_ASSOC);

			$strXML .= "<set value='".$venta['total']."'/>";
			$strTabla.= "<td class='list-column-left'>".number_format($venta['total'])."</td>";
		}//fin de for
		$strXML .= "</dataset></chart>";
		$strTabla .= "</tr></table>";
		
		$sqlventa = "SELECT SUM(ValorNeto) as total FROM ventas WHERE K_Agente = '".$numeroagente."' AND K_Cliente = '".$clave."' AND (Fecha >= '".$primerdia."' AND Fecha <= '".$ultimodia."')";
		$resultventa = mysql_query($sqlventa, $db) or die(mysql_error());
		$venta = mysql_fetch_array($resultventa, MYSQL_ASSOC);
			
		$ruta = "Data/";
		$name_file = $claveorganizacion.".xml";
		$file = fopen($ruta.$name_file,"w+");
		fwrite ($file,$strXML);
		fclose($file);
		echo renderChart("../../Charts/MSLine.swf", $ruta.$name_file, "", "FactorySum", 600, 300, false, false);
		?>
        </fieldset>
        
        
        <?php echo $strTabla; ?>

</body>
</html>