<?php
// v1 by IK4LZH 2021012

include("utility.php");
$base=1;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  if($base){
    $base=0;
    $mys=findcall($parts[5]);
    $mycont=$mys["cont"];
    $mybase=$mys["base"];
  }
  
  $mytt=$parts[3].":".$parts[4];
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $myid=$band."-".$parts[2]."-".$parts[8];
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($aqso[$mytt]))$aqso[$mytt]=1;
    else $aqso[$mytt]++;
  }
  if($mybase!="SP"){
    if($mys["base"]=="SP")$pp=3;
    else $pp=0;
  }
  else {
    if($mys["base"]=="SP")$pp=0;
    else if($mys["cont"]=="EU")$pp=1;
    else $pp=3;
  }
  if(!isset($point[$myid])){
    $point[$myid]=$pp;
    if(!isset($apoint[$mytt]))$apoint[$mytt]=$pp;
    else $apoint[$mytt]+=$pp;
  }
   
  if($mybase!="SP"){
    if($mys["base"]=="SP"){
      $myid=$band."-".$parts[10];
      if(!isset($mult[$myid])){
        $mult[$myid]=1;
        $myid=$band."-".$parts[2]."-".$parts[10];
        if(!isset($vmult[$myid]))$vmult[$myid]=1;
        if(!isset($amult[$mytt]))$amult[$mytt]=1;
        else $amult[$mytt]++;
      }
    }
  }
  else {
    if($mys["base"]!="SP"){
      $myid=$band."-".$mys["base"];
      if(!isset($mult[$myid])){
        $mult[$myid]=1;
        $myid=$band."-".$parts[2]."-".$mys["base"];
        if(!isset($vmult[$myid]))$vmult[$myid]=1;
        if(!isset($amult[$mytt]))$amult[$mytt]=1;
        else $amult[$mytt]++;
      }
    }
  }
  $myid=$band."-".$parts[2];
  if(!isset($myrep[$myid]))$myrep[$myid]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tMULTs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee); $z1+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee); $z2+=$xx; echo $xx."\t";
  $xx=mysum($vmult,"-",$ee); $z3+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\n";
echo "\n".$parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n\n";
mybreakdown("spdx",$parts[5],$parts[3],$aqso,$apoint,$amult);
?>
