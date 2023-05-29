<?php
// v2 by IK4LZH 20210122
include("utility.php");

if(isset($_GET['fromlog']))$hh=fopen($_GET['fromlog'],"r");
elseif(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  
  $mytt=$parts[3].":".$parts[4];
  $band=$bb[floor($parts[1]/1000)];
  $mode=$parts[2];
  if($mode=="DG")$mode="RY";
  
  $myid=$band."-".$mode."-".$parts[8];
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($aqso[$mytt]))$aqso[$mytt]=1;
    else $aqso[$mytt]++;
  }
  if($mode=="PH")$pp=1;
  else if($mode=="RY")$pp=2;
  else if($mode=="CW")$pp=3;
  else $pp=0;
  if(!isset($point[$myid])){
    $point[$myid]=$pp;
    if(!isset($apoint[$mytt]))$apoint[$mytt]=$pp;
    else $apoint[$mytt]+=$pp;
  }
 
  $myid=$band."-".$mode."-".substr($parts[10],0,2);
  if(!isset($mult[$myid])){
    $mult[$myid]=1;
    if(!isset($amult[$mytt]))$amult[$mytt]=1;
    else $amult[$mytt]++;
  }
  
  $myid=$band."-".$mode;
  if(!isset($myrep[$myid]))$myrep[$myid]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tM_PROVs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee); $z1+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee); $z2+=$xx; echo $xx."\t";
  $xx=mysum($mult,"-",$ee); $z3+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\n";
echo "\n".$parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n\n";
mybreakdown("4080",$parts[5],$parts[3],$aqso,$apoint,$amult);
?>
