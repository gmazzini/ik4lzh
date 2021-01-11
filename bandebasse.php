<?php

// v2 by IK4LZH
// score for bandebasse

echo "<pre>";
$bb=array(1=>160,3=>80,7=>40);
eval("\$mymdxc=".file_get_contents("/home/www/ik4lzh.mazzini.org/mdxc.list").";");
echo $mymdxc["IK4LZH"]."\n";

$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");

while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=preg_split('/\s+/',$line);
  $freq=substr($parts[1],0,strlen($parts[1])-3);
  
  $call=$parts[8];
  $freq=substr($parts[1],0,1);
  $mode=$parts[2];
  $data=$parts[3];
  $time=$parts[4];
  $prov=$parts[10];
  if(isset($parts[11]))$mdxc=(int)$parts[11];
  else $mdxc=0;
  
  $ff=strpos($call,"/");
  if($ff===false)$scall=$call;
  else $scall=substr($call,0,$ff);
  if(isset($mymdxc[$scall]))$cmdxc=$mymdxc[$scall];
  else $cmdxc=0;
  if($mdxc==0 && $cmdxc>0)echo "$call-$data-$time MDXC not specified\n";
  else if($mdxc>0 && $cmdxc==0)echo "$call-$data-$time MDXC not more existing\n";
  else if($mdxc!=$cmdxc)echo "$call-$data-$time MDXC wrong\n";
  
  $myid=$bb[$freq]."-".$mode;
  $c_call=$call."-".$myid;
  if(!isset($w_call[$c_call])){
    if(!isset($qso[$myid]))$qso[$myid]=1;
    else $qso[$myid]++;
    $w_call[$c_call]=1;
    $myinc=0;
    if($mode=="PH")$myinc=1;
    if($mode=="CW")$myinc=2;
    $aux=substr($call,0,2);
    if($aux=="IQ"||$aux=="IY")$myinc=10;
    if(!isset($point[$myid]))$point[$myid]=$myinc;
    else $point[$myid]+=$myinc;
  }

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
