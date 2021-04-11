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
  $myyy=$parts[3].":".$parts[4]."-".$band;
  if(!isset($qso[$myid])){
    $qso[$myid]=1;
    if(!isset($yqso[$myyy]))$yqso[$myyy]=1;
    else $yqso[$myyy]++;
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
}
fclose($hh);

echo "<pre>\n";
print_r($yqso);
?>
