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
  $band=$bb[floor($parts[1]/1000)];
  $mybb[$nqso]=$band;
  if(!isset($mylab[$band]))$mylab[$band]=1;
  $mytt[$nqso]=strtotime($parts[3]." ".substr($parts[4],0,2).":".substr($parts[4],2,2).":00");
  $nqso++;
}
fclose($hh);
sort($mytt);
$mylb=array_keys($mylab);
sort($myld);

$ntqso=0;
$myt[$ntqso]=$mytt[0];
for(;;){
  if($myt[$ntqso]>$mytt[$nqso-1])break;
  $ntqso++;
  $myt[$ntqso]=$myt[$ntqso-1]+300;
}
$ntqso++;

for($m=0;$m<$ntqso;$m++){
  $myq[$m][0]=0;
  for($n=1;$n<$nqso;$n++){
    if($mytt[$n]>=$myt[$m]&&$mytt[$n]<$myt[$m]+3600)$myq[$m][0]++;
  }
  foreach($mylb as $bb){
    $myq[$m][$bb]=0;
    for($n=1;$n<$nqso;$n++){
      if($mytt[$n]>=$myt[$m]&&$mytt[$n]<$myt[$m]+3600&&$mybb[$n]==$bb)$myq[$m][$bb]++;
    }
  }
}

$name=uniqid("qsorate".$myband."_",true);
echo "<a href='https://ik4lzh.mazzini.org/breakdown/$name.csv' download>Download breakdown</a><br>";
$fp=fopen("/home/www/ik4lzh.mazzini.org/breakdown/$name.csv","w");

echo "<pre>";
echo "DATA:TIME,*";
foreach($mylb as $bb)echo ",".$bb;
echo "\n";

for($n=0;$n<$ntqso;$n++){
  printf("%s,%d",date("Y-m-d:Hi",$myt[$n]),$myq[$n][0]);
  fprintf($fp,"%s,%d",date("Y-m-d:Hi",$myt[$n]),$myq[$n][0]);
  foreach($mylb as $bb){
    printf(",%d",$myq[$n][$bb]);
    fprintf($fp,",%d",$myq[$n][$bb]);
  }
  printf("\n");
  fprintf($fp,"\n");
}
fclose($fp);

?>
