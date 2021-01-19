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
  
  $myid=$band."!".(int)$parts[10];
  if(!isset($mult[$myid]))$mult[$myid]=1;
  $myid=$band."-".$parts[2];
  if(!isset($myrep[$myid]))$myrep[$myid]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tM_CYs\tM_CQs\n";
$ea=array_keys($myrep);
sort($ea);
foreach($ea as $ee){
  echo $ee."\t";
  echo mysum($qso,"-",$ee)."\t";
  echo mysum($point,"-",$ee)."\t";
  echo mysum($mult,"-",$ee)."\t";
  echo mysum($mult,"!",$ee)."\n";
}
echo $mycall." SCORE: ".array_sum($point)*array_sum($mult)."\n";
?>
