<?php
include ("includes/FusionCharts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="JavaScript"  src="js/FusionCharts.js"></script>
<title>Documento sin título</title>
</head>

<body>

<div id="chartContainer">FusionCharts XT will load here!</div> 
<script type="text/javascript">
var myChart = new FusionCharts( "MSCombiDY2D", "myChartId", "800", "500" );
myChart.setXMLUrl("telcel.xml");
myChart.render("chartContainer"); 
</script>

</body>
</html>
