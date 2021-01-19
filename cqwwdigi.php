<?php
// v2 by IK4LZH 20210119

include("utility.php");
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line);
  
  $band=$bb[floor($parts[1]/1000)];
  $myid=$band."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  $mygrid=substr($parts[5],0,2);
  $yourgrid=substr($parts[8],0,2);
  $pp=1+floor($dist[$mygrid.$yourgrid]);
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  $myid=$band."-".$yourgrid;
  if(!isset($mult[$myid]))$mult[$myid]=1;
  if(!isset($myband[$band]))$myband[$band]=1;
}
fclose($hh);

echo "<pre>\n";
echo "BAND\tQSOs\tPOINTs\tMULT\n";
$ea=array_keys($myband);
sort($ea);
foreach($ea as $ee){
  echo $ee."\t";
  echo mysum($qso,"-",$ee)."\t";
  echo mysum($point,"-",$ee)."\t";
  echo mysum($mult,"-",$ee)."\n";
}
echo $mycall." SCORE: ".array_sum($point)*array_sum($mult)."\n";

?>
