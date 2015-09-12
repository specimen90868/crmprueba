<?php
require'EasyGoogleMap.class.php';

//SUSTITUIR POR LA KEY DE GOOGLE
$key ="AIzaSyBNf8QeTTIpLHXc9965s_DmkQImdY1tM8s";

$gm = & new EasyGoogleMap($key);
$gm->SetMapZoom(15);
$gm->SetAddress("San Pedro Plus, León Gto.");
$gm->SetInfoWindowText("Este es el texto para el punto 1");


$gm->SetAddress("Periodista Azzati 7, Valencia");
$gm->SetInfoWindowText("Este es el texto para el punto 2");


$gm->mScale = false;
$gm->mInset = true;
$gm->SetMapWidth(600); 
$gm->SetMapHeight(400);
?>
<html>
<head>
<title>EasyGoogleMap</title>
<?php echo $gm->GmapsKey(); ?>
</head>
<body>
<?php echo $gm->MapHolder(); ?>
<?php echo $gm->InitJs(); ?>
<?php echo $gm->UnloadMap(); ?>
</body>
</html>