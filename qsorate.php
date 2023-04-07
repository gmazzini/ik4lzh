<?php
// v1 by IK4LZH 20230407

include("utility.php");
$nqso=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $mytt[$nqso]=strtotime($parts[3]." ".substr($parts[4],0,2).":".substr($parts[4],2,2).":00");
  $nqso++;
}
fclose($hh);
sort($mytt);

$ntqso=1;
$myt[0]=$mytt[0];
for($n=1;$n<$nqso;$n++){
  if($mytt[$n]>$myt[$ntqso-1]){
    $myt[$ntqso]=$mytt[$n];
    $ntqso++;
  }
}

echo "<pre>\n$nqso $ntqso\n";
for($n=0;$n<$ntqso;$n++){
  $q=0;
  for($w=$n+1;$w<$ntqso;$w++){
    if($mytt[$w]>$mytt[$n]+3600)break;
    else $q++;
  }
  printf("%d %d\n",$mytt[$n],$q);
}

?>
