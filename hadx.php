<?php
// v1 by IK4LZH 20210119

include("utility.php");
$base=1;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,0);
  if($base){
    $base=0;
    $mys=findcall($parts[5]);
    $mycont=$mys["cont"];
  }
  
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $myid=$band."-".$parts[2]."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;  
  if($mys["base"]=="HA")$pp=10;
  else if($mys["cont"]!=$mycont)$pp=5;
  else $pp=2;
  if(!isset($point[$myid]))$point[$myid]=$pp;
   
  if($mys["base"]!="HA"){
    $myid=$band."-".$mys["base"];
    if(!isset($mult[$myid]))$mult[$myid]=1;
  }
  else {
    $myid=$band."!".$parts[10];
    if(!isset($mult[$myid]))$mult[$myid]=1;
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOSSBs\tQSOCQs\tPNTSSBs\tPNTCWs\tM_CYs\tM_PRs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=$z4=$z5=$z6=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee."-PH"); $z1+=$xx; echo $xx."\t";
  $xx=mysum($qso,"-",$ee."-CW"); $z2+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee."-PH"); $z3+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee."-CW"); $z4+=$xx; echo $xx."\t";
  $xx=mysum($mult,"-",$ee); $z5+=$xx; echo $xx."\t";
  $xx=mysum($mult,"!",$ee); $z6+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\t$z4\t$z5\t$z6\n";
echo $parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n";
?>
