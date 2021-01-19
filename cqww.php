<?php
// v1.2 by IK4LZH 20210119

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
    $mybase=$mys["base"];
    $mycont=$mys["cont"];
  }
  
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $myid=$band."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  if($mys["cont"]!=$mycont)$pp=3;
  else if($mys["cont"]=="NA" && $mycont=="NA" && $mys["base"]!=$mybase)$pp=2;
  else if($mys["cont"]==$mycont && $mys["base"]!=$mybase)$pp=1;
  else $pp=0;
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  $myid=$band."-".$mys["base"];
  if(!isset($mult[$myid]))$mult[$myid]=1;
  $myid=$band."!".(int)$parts[10];
  if(!isset($mult[$myid]))$mult[$myid]=1;
  if(!isset($myband[$band]))$myband[$band]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tMULTs\n";
$ea=array_keys($myband);
sort($ea);
foreach($ea as $ee){
  echo $ee."\t";
  echo mysum($qso,"-",$ee)."\t";
  echo mysum($point,"-",$ee)."\t";
  echo mysum($mult,"-",$ee)."\n";
}
echo "SCORE: ".array_sum($point)*array_sum($mult)."\n";
?>
