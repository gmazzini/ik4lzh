<?php
// v3 by IK4LZH 20210120

include("utility.php");
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  
  $mytt=$parts[3].":".$parts[4];
  $band=$bb[floor($parts[1]/1000)];
  $myid=$band."-".$parts[7];
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($aqso[$mytt]))$aqso[$mytt]=1;
    else $aqso[$mytt]++;
  }
  $mygrid=substr($parts[6],0,2);
  $yourgrid=substr($parts[8],0,2);
  $pp=1+floor($dist[$mygrid.$yourgrid]);
  if(!isset($point[$myid])){
    $point[$myid]=$pp;
    if(!isset($apoint[$mytt]))$apoint[$mytt]=$pp;
    else $apoint[$mytt]+=$pp;
  }
 
  $myid=$band."-".$yourgrid;
  if(!isset($mult[$myid])){
    $mult[$myid]=1;
    if(!isset($amult[$mytt]))$amult[$mytt]=1;
    else $amult[$mytt]++;
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tMULTs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=$z4=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee); $z1+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee); $z2+=$xx; echo $xx."\t";
  $xx=mysum($mult,"-",$ee); $z3+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\n";
echo "\n".$parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n\n";

$myd=array_unique(array_keys($aqso));
sort($myd);
$z1=$z2=$z3=0;
foreach($myd as $dd){
  $z1+=$aqso[$dd];
  if(isset($apoint[$dd]))$z2+=$apoint[$dd];
  if(isset($amult[$dd]))$z3+=$amult[$dd];
  echo $dd.",".$z1.",".$z2.",".$z3."\n";
}
?>
