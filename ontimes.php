<?php
// v1 by IK4LZH 20211226


echo "<pre>";

include("utility.php");
$base=1;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  
  $mytt=strtotime($parts[3]." ".$parts[4].":00");

  echo $mytt;
  
}
fclose($hh);


?>
