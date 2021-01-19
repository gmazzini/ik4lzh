<?php
// v3 by IK4LZH
include("utility.php");

if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=mysep($line,0);
  
  $band=$bb[floor($parts[1]/1000)];
  $myid=$band."-".$parts[2]."-".$parts[8];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  if($parts[2]=="PH")$pp=1;
  else if($parts[2]=="CW")$pp=2;
  $aux=substr($parts[8],0,2);
  if($aux=="IQ"||$aux=="IY")$pp=10;
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  
  $myid=$band."-".$parts[2]."-".substr($parts[10],0,2);
  if(!isset($mult[$myid]))$mult[$myid]=1;
  
  $mdxc=(int)substr($parts[10],3);
  if($mdxc>0)
  
  $myid=$band."!".(int)$parts[10];
  if(!isset($mult[$myid]))$mult[$myid]=1;
  if(!isset($myband[$band]))$myband[$band]=1;
  
  
  
    
  $prov=$parts[10+$member];
  if(isset($parts[11+$member]))$mdxc=(int)$parts[11+$member];
  else $mdxc=0;
  
  $ff=strpos($call,"/");
  if($ff===false)$scall=$call;
  else $scall=substr($call,0,$ff);
  if(isset($mymdxc[$scall]))$cmdxc=$mymdxc[$scall];
  else $cmdxc=0;
    
  
    

  $c_molt=$prov."-".$myid;
  if(!isset($w_molt[$c_molt])){
    $w_molt[$c_molt]=1;
    if(!isset($mult_p[$myid]))$mult_p[$myid]=1;
    else $mult_p[$myid]++;
  }

  if($mdxc!=0){
    $c_molt=$mdxc."-".$myid;
    if(!isset($w_molt[$c_molt])){
      $w_molt[$c_molt]=1;
      if(!isset($mult_m[$myid]))$mult_m[$myid]=1;
      else $mult_m[$myid]++;
    }
  }
}
fclose($hh);

$keys=array_keys($qso);
sort($keys);

echo "\tQSO\tPOINT\tM_PROV\tM_MDXC\n";
$vqso=0; $vpoint=0; $vmult_p=0; $vmult_m=0;
foreach ($keys as &$k) {
  echo "$k\t$qso[$k]\t$point[$k]\t$mult_p[$k]\t$mult_m[$k]\n";
  $vqso+=$qso[$k];
  $vpoint+=$point[$k];
  $vmult_p+=$mult_p[$k];
  $vmult_m+=$mult_m[$k];
}
echo "TOTAL\t$vqso\t$vpoint\t$vmult_p\t$vmult_m\n";
$tot=$vpoint*($vmult_p+$vmult_m);
echo "SCORE: $tot\n";
echo "</pre>";
?>
