<?php
include ("../config/configpremo.php");
include ("../config/config.php");
include ("../util.php");

$sqlsubmissionspremo="SELECT * FROM `webform_submissions` ORDER BY `submitted` DESC";
$rssubmissionspremo= mysql_query ($sqlsubmissionspremo,$dbpremo);
echo $sqlsubmissionspremo;
$err   = mysql_error();
      if( $err != "" ) echo "error=$err  ";

echo mysql_num_rows($rssubmissionspremo);



?>