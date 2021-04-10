
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

echo "<pre>"; print_r($zqso);

echo "<pre>\n";
echo "xxxxx\n";
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
?>
