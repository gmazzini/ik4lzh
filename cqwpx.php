<?php
// v2 by IK4LZH 20210122

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
    $mybase=$mys["base"];
    $mycont=$mys["cont"];
  }
  
  $mytt=$parts[3].":".$parts[4];
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $myid=$band."-".$parts[8];
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($aqso[$mytt]))$aqso[$mytt]=1;
    else $aqso[$mytt]++;
  }  
  if($mys["cont"]!=$mycont){
    if($band<=20)$pp=3;
    else $pp=6;
  }
  else if($mys["cont"]=="NA" && $mycont=="NA"){
    if($band<=20)$pp=2;
    else $pp=4;
  }
  else if($mys["base"]!=$mybase){
    if($band<=20)$pp=1;
    else $pp=2;
  }
  else $pp=1;
  if(!isset($point[$myid])){
    $point[$myid]=$pp;
    if(!isset($apoint[$mytt]))$apoint[$mytt]=$pp;
    else $apoint[$mytt]+=$pp;
  }
  
  for($i=strlen($parts[8])-1;$i>0;$i--)if(is_numeric($parts[8][$i]))break;
  $myid=substr($parts[8],0,$i+1);        
  if(!isset($mult[$myid])){
    $mult[$myid]=1;
    $myid=$band."-".$myid;
    if(!isset($vmult[$myid]))$vmult[$myid]=1;
    if(!isset($amult[$mytt]))$amult[$mytt]=1;
    else $amult[$mytt]++;
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tM_WPXs\n";
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

$myd=array_unique(array_keys($aqso));
sort($myd);
$z1=$z2=$z3=0;
foreach($myd as $dd){
  $z1+=$aqso[$dd];
  if(isset($apoint[$dd]))$z2+=$apoint[$dd];
  if(isset($amult[$dd]))$z3+=$amult[$dd];
  echo $dd.",".$z1.",".$z2.",".$z3."\n";
}
file_put_contents("/home/www/ik4lzh.mazzini.org/log.txt",date("Y-m-d H:i:s").",cqwpx,".$parts[5].",".$parts[3].",".$z1.",".$z2.",".$z3."\n", FILE_APPEND | LOCK_EX);

?>
