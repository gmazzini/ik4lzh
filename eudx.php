<?php
// v1 by IK4LZH 20210129

include("utility.php");
$eu=array("5B","9A","9H","CT","CT3","CU","DL","EA","EA6","EA8","EI","ES","F",
           "FG","FM","FR","FY","HA","I","IS","LX","LY","LZ","OE","OH","OH0","OJ0",
           "OK","OM","OZ","PA","S5","SM","SP","SV","SV5","SV9","TK","YL","YO");
$base=1;
if(isset($_GET['fromlog']))$hh=fopen($_GET['fromlog'],"r");
elseif(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
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
    if(in_array($mybase,$eu))$myeu=1;
    else $myeu=0;
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
  if($myeu){
    if($mybase==$mys["base"])$pp=1;
    else if(in_array($mys["base"],$eu))$pp=10;
    else if($mycont==$mys["cont"])$pp=3;
    else $pp=5;
  }
  else {
    if(in_array($mys["base"],$eu))$pp=10;
    else if($mybase==$mys["base"])$pp=1;
    else if($mycont==$mys["cont"])$pp=3;
    else $pp=5;
  }
  if(!isset($point[$myid])){
    $point[$myid]=$pp;
    if(!isset($apoint[$mytt]))$apoint[$mytt]=$pp;
    else $apoint[$mytt]+=$pp;
  }
   
  $myid=$band."-".$mys["base"];
  if(!isset($mult[$myid])){
    $mult[$myid]=1;
    $myid=$band."-".$parts[2]."-".$mys["base"];
    if(!isset($vmult[$myid]))$vmult[$myid]=1;
    if(!isset($amult[$mytt]))$amult[$mytt]=1;
    else $amult[$mytt]++;
  }
  if(!is_numeric($parts[10])){
    $myid=$band."!".$parts[10];
    if(!isset($mult[$myid])){
      $mult[$myid]=1;
      $myid=$band."-".$parts[2]."!".$parts[10];
      if(!isset($vmult[$myid]))$vmult[$myid]=1;
      if(!isset($amult[$mytt]))$amult[$mytt]=1;
      else $amult[$mytt]++;
    }
  }
  $myid=$band."-".$parts[2];
  if(!isset($myrep[$myid]))$myrep[$myid]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tM_CYs\tM_PRs\n";
$ea=array_keys($myrep);
natsort($ea);
$z1=$z2=$z3=$z4=0;
foreach($ea as $ee){
  echo $ee."\t";
  $xx=mysum($qso,"-",$ee); $z1+=$xx; echo $xx."\t";
  $xx=mysum($point,"-",$ee); $z2+=$xx; echo $xx."\t";
  $xx=mysum($vmult,"-",$ee); $z3+=$xx; echo $xx."\t";
  $xx=mysum($vmult,"!",$ee); $z4+=$xx; echo $xx."\n";
}
echo "TOTAL\t$z1\t$z2\t$z3\t$z4\n";
echo "\n".$parts[5]." SCORE: ".array_sum($point)*array_sum($mult)."\n\n";
mybreakdown("eudx",$parts[5],$parts[3],$aqso,$apoint,$amult);
?>
