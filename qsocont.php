
<?php
// v0 by IK4LZH 20210410

include("utility.php");
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $band=$bb[floor($parts[1]/1000)];
  $mys=findcall($parts[8]);
  $cont=$mys["cont"];
  if(!isset($cont))continue;
  $myid=$band."-".$parts[8];
  if(!isset($qso[$myid])){
    $myzz=$band."-".$cont;
    $qso[$myid]=1;
    if(!isset($zqso[$myzz]))$zqso[$myzz]=1;
    else $zqso[$myzz]++;
  }
  if(!isset($myrep[$band]))$myrep[$band]=1;
  if(!isset($mycont[$cont]))$mycont[$cont]=1;
}
fclose($hh);

echo "<pre>\n";
echo "xxxxx\n";
$ea=array_keys($myrep);
natsort($ea);
$za=array_keys($mycont);
natsort($za);
$z1=$z2=$z3=0;
echo "\t";
foreach($za as $ze){
  echo $ze."\t";
}
foreach($ea as $ee){
  echo $ee."\t";
  foreach($za as $ze){
    $aux=$zqso[$ee."-".$ze];
    if(!isset($aux))$aux=0;
    echo $aux."\t";
  }
}
echo "TOTAL\t$z1\t$z2\t$z3\n";
?>
