<?php
// v1 by IK4LZH 20230407

include("utility.php");
$ntqso=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $aux=strtotime($parts[3]." ".substr($parts[4],0,2).":".substr($parts[4],2,2).":00");
  if($aux<>$mytt[$ntqso]){
    $mytt[$ntqso]=$aux;
    $ntqso++;
  }
}
fclose($hh);
sort($mytt);

echo "<pre>\n\n$ntqso\n";
for($n=1;$n<$ntqso;$n++){
  $q=0;
  for($w=$n+1;$w<$ntqso;$w++){
    if($mytt[$w]>$mytt[$n]+3600)break;
    else $q++;
  }
  printf("%d %d\n",$mytt[$n],$q);
}

?>
