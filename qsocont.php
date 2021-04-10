
<?php
// v1 by IK4LZH 20210410

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
$ea=array_keys($myrep);
natsort($ea);
$za=array_keys($mycont);
natsort($za);
$z1=$z2=$z3=0;
echo "\t";
foreach($za as $ze){
  echo $ze."\t";
}
echo "TOTAL\n";
foreach($ea as $ee){
  echo $ee."\t";
  $saux=0;
  foreach($za as $ze){
    if(!isset($zcont[$ze]))$zcont[$ze]=0;
    $aux=$zqso[$ee."-".$ze];
    if(!isset($aux))$aux=0;
    $saux+=$aux;
    $zcont[$ze]+=$aux;
    echo $aux."\t";
  }
  echo $saux."\n";
}
$aux=0;
echo "TOTAL\t";
foreach($za as $ze){
  echo $zcont[$ze]."\t";
  $aux+=$zcont[$ze];
}
echo "$aux\n";
?>
