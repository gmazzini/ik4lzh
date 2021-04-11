<?php
// v0 by IK4LZH 20210411

include("utility.php");
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $myid=$band."-".$parts[8];
  $mytt=$parts[3].":".$parts[4];
  $myyy=$mytt."-".$band;
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($lcount[$band]))$lcount[$band]=1;
    else $lcount[$band]++;
    $yqso[$myyy]=$lcount[$band];
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
  if(!isset($myact[$mytt]))$myact[$mytt]=1;
}
fclose($hh);

echo "<pre>\n";
$myd=array_keys($myact);
sort($myd);
$ea=array_keys($myrep);
natsort($ea);
echo "ciao\n";

$name=uniqueid("qsoband_",true);
$fp=fopen("/home/www/ik4lzh.mazzini.org/breakdown/$name.csv","w");
echo "<a href='https://ik4lzh.mazzini.org/breakdown/$name.csv' download>Download breakdown</a><br>";
foreach($myd as $dd){
  echo $dd;
  fwrite($fp,$dd);
  foreach($ea as $ee){
    $myyy=$dd."-".$ee;
    echo ",".$yqso[$myyy];
    fwrite($fp,",".$yqso[$myyy]);
  }
  echo "\n";
  fwrite($fp,"\n");
}
fclose($fp);

?>
