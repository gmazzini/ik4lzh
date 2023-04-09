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

$ntqso=0;
$myt[$ntqso]=$mytt[0];
for(;;){
  if($myt[$ntqso]>$mytt[$nqso-1])break;
  $ntqso++;
  $myt[$ntqso]=$myt[$ntqso-1]+300;
}
$ntqso++;

for($m=0;$m<$ntqso;$m++){
  $myq[$m]=0;
  for($n=1;$n<$nqso;$n++){
    if($mytt[$n]>=$myt[$m]&&$mytt[$n]<$myt[$m])$myq[$m]++;
  }
}

$name=uniqid("qsorate".$myband."_",true);
echo "<a href='https://ik4lzh.mazzini.org/breakdown/$name.csv' download>Download breakdown</a><br>";
$fp=fopen("/home/www/ik4lzh.mazzini.org/breakdown/$name.csv","w");
echo "<pre>";
for($n=0;$n<$ntqso;$n++){
  printf("%s,%d\n",date("Y-m-d:Hi",$myt[$n]),$myq[$n]);
  fprintf($fp,"%s,%d\n",date("Y-m-d:Hi",$myt[$n]),$myq[$n]);
}
fclose($fp);

?>
