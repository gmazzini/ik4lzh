<?php
// v1.1 by IK4LZH 20210119

include("utility.php");

$base=1;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line);
  if($base){
    $base=0;
    $mycall=$parts[5];
    $mys=findcall($mycall);
    $mycountryB=$mys["base"];
    $mycB=$mys["cont"];
  }
  
  $band=$bb[(int)$part[1]/1000];
  $mys=findcall($parts[8]);
  
  $myid=$band."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  if($myc!=$mycB)$pp=3;
  else if($mys["cont"]=="NA" && $mycB=="NA" && $mys["base"]!=$mycountryB)$pp=2;
  else if($mys["cont"]==$mycB && $mys["base"]!=$mycountryB)$pp=1;
  else $pp=0;
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  $myid=$band."-".$mys["base"];
  if(!isset($mult[$myid]))$mult[$myid]=1;
}
fclose($hh);

echo array_sum($qso)." qso\n";
echo array_sum($point)." point\n";
echo array_sum($mult)." mult\n";
echo array_sum($point)*array_sum($mult)." score\n";

?>
