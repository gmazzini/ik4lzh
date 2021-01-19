<?php
// v3 by IK4LZH
include("utility.php");

if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,0);
  
  $band=$bb[floor($parts[1]/1000)];
  $myid=$band."-".$parts[2]."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  if($parts[2]=="PH")$pp=1;
  else if($parts[2]=="CW")$pp=2;
  $aux=substr($parts[8],0,2);
  if($aux=="IQ"||$aux=="IY")$pp=10;
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  $myid=$band."-".$parts[2]."-".substr($parts[10],0,2);
  if(!isset($mult[$myid]))$mult[$myid]=1;
  $mdxc=(int)substr($parts[10],3);
  if($mdxc>0){
    $myid=$band."-".$parts[2]."!".$mdxc;
    if(!isset($mult[$myid]))$mult[$myid]=1;  
  }
  
  $myid=$band."-".$parts[2];
  if(!isset($myrep[$myid]))$myrep[$myid]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tM_PROVs\tM_MDXCs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=$z4=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee); $z1+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee); $z2+=$xx; echo $xx."\t";
  $xx=mysum($mult,"-",$ee); $z3+=$xx; echo $xx."\t";
  $xx=mysum($mult,"!",$ee); $z4+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\t$z4\n";
echo $parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n";
?>
