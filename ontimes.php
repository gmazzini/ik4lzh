<?php
// v1 by IK4LZH 20211226

include("utility.php");
$nqso=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $mytt[$nqso]=strtotime($parts[3]." ".substr($parts[4],0,2).":".substr($parts[4],2,2).":00");
  $nqso++;
}
fclose($hh);
sort($mytt);
$pause=0;
for($n=1;$n<$nqso;$n++){
  $mdel=$mytt[$n]-$mytt[$n-1];
  if($mdel>=3600)$pause+=$mdel;
}
$totp=$mytt[$nqso-1]-$mytt[0];
$ontimes=$totp-$pause;
echo "<pre>";
echo "Total presence [s h]: $totp"." ".sprintf("%5.2f",$totp/3600)."\n";
echo "Total pause    [s h]: $pause"." ".sprintf("%5.2f",$pause/3600)."\n";
echo "Total on times [s h]: $ontimes"." ".sprintf("%5.2f",$ontimes/3600)."\n";

?>
