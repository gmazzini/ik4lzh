<?php
// v1 by IK4LZH 20230407

include("utility.php");
if(isset($_POST['myband']))$myband=$_POST['myband'];
else $myband="";
$nqso=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,10);
  $band=$bb[floor($parts[1]/1000)];
  if($myband==""||$myband==$band){
    $mytt[$nqso]=strtotime($parts[3]." ".substr($parts[4],0,2).":".substr($parts[4],2,2).":00");
    $nqso++;
  }
}
fclose($hh);
sort($mytt);

$ntqso=1;
$myt[0]=$mytt[0];
$mqt[0]=1;
for($n=1;$n<$nqso;$n++){
  if($mytt[$n]>$myt[$ntqso-1]){
    $myt[$ntqso]=$mytt[$n];
    $mqt[$ntqso]=1;
    $ntqso++;
  }
  else $mqt[$ntqso-1]++;
}

echo "<pre>";
for($n=0;$n<$ntqso;$n++){
  $q=$mqt[$n];
  for($w=$n+1;$w<$ntqso;$w++){
    if($myt[$w]>$myt[$n]+3600)break;
    else $q+=$mqt[$w];
  }
  printf("%s %d\n",date("dmy H:i",$myt[$n]),$q);
}

?>
