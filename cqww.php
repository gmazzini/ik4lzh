<?php
// v1.1 by IK4LZH 20210119

include("utility.php");

$oldmyd=0;
$base=1;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line);
//  $parts=preg_split('/\s+/',$line);
  if($base){
    $base=0;
    $mycall=$parts[5];
    $mys=findcall($mycall);
    $mycountryB=$mys["base"];
    $mycB=$mys["cont"];
    $myfirstday=(int)substr($parts[3],8,2);
  }
  $freq=substr($parts[1],0,strlen($parts[1])-3);
  $myd=((int)substr($parts[4],2,2))+((int)substr($parts[4],0,2))*60+(((int)substr($parts[3],8,2))-$myfirstday)*1440;
  $call=$parts[8];
  $zone=$parts[10];
  $mul[$zone.$freq]=1;
  $mys=findcall($call);
  if(isset($mys)&&$myd>=$oldmyd){
    $oldmyd=$myd;
    $mycountry=$mys["base"];
    $myc=$mys["cont"];
    $mul[$mycountry.$freq]=1;
    if($myc!=$mycB)$qso[$call.$freq]=3;
    else if($myc=="NA"&&$mycB=="NA"&&$mycountry!=$mycountryB)$qso[$call.$freq]=2;
    else if($myc==$mycB&&$mycountry!=$mycountryB)$qso[$call.$freq]=1;
    else $qso[$call.$freq]=0;
    $valqso=array_sum($qso);
    $valmul=array_sum($mul);
    $valqsoT[$myd]=$valqso;
    $valmulT[$myd]=$valmul;
  }
}
fclose($hh);

$valqso=array_sum($qso);
$valmul=array_sum($mul);
$tot=$valqso*$valmul;
echo "QSOPOINTS:$valqso MULTIPLIER:$valmul SCORE:$tot\n";

list($usec,$sec)=explode(' ',microtime());
srand($sec+$usec*1000000);
for($i=0;$i<16;$i++){
  for(;;){
    $nn=rand(48,122);
    if($nn>=58&&$nn<=64)continue;
    if($nn>=91&&$nn<=96)continue;
    $qqq[$i]=chr($nn);
    break;
  }
}
$myname=implode($qqq);

$handle=fopen("/home/www/ik4lzh.mazzini.org/tmp/$myname","w");
$valqso=0;
$valmul=0;
for($i=0;$i<2880;$i++){
  if(isset($valqsoT[$i])){
    $valqso=$valqsoT[$i];
    $valmul=$valmulT[$i];
    $valcc=$valqso*$valmul;
  }
  fwrite($handle,"$i,$valqso,$valmul,$valcc\n");
}
fclose($handle);

echo "<a href='tmp/$myname' download='$mycall'>Download Breakdown</a>";

?>
