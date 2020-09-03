<?php

// version 1
// computer the CQWWDIGI score from cabrillo file

for($i1=0;$i1<18;$i1++){
  for($j1=0;$j1<18;$j1++){
    for($i2=0;$i2<18;$i2++){
      for($j2=0;$j2<18;$j2++){
        $gg1=chr(65+$i1).chr(65+$j1);
        $gg2=chr(65+$i2).chr(65+$j2);
        $lat1=-85+$j1*10;
        $log1=-170+$i1*20;
        $lat2=-85+$j2*10;
        $log2=-170+$i2*20;
        $mydist=2*6371000*ASIN(SQRT((SIN(($lat2*(3.14159/180)-$lat1*(3.14159/180))/2))**2+COS($lat2*(3.14159/180))*COS($lat1*(3.14159/180))*SIN((($log2*(3.14159/180)-$log1*(3.14159/180))/2))**2));
        $dist[$gg1.$gg2]=$mydist/3000000;
      }
    }
  }
}

$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $pars=preg_split('/\s+/',$line);
  $call=$pars[7];
  $freq=floor(floatval($pars[1])/1000);
  $mygrid=substr($pars[6],0,2);
  $yourgrid=substr($pars[8],0,2);
  $qso[$call.$freq]=1+floor($dist[$mygrid.$yourgrid]);
  $mul[$yourgrid.$freq]=1;
}
fclose($hh);

$valqso=array_sum($qso);
$valmul=array_sum($mul);
$tot=$valqso*$valmul;
echo "QSO:$valqso MUL:$valmul SCORE:$tot\n";

?>
