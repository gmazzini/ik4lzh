<?php

// v3 by IK4LZH 20210119

$bb=array(1=>160,3=>80,5=>60,7=>40,10=>30,14=>20,18=>17,21=>15,24=>12,28=>10,29=>10);

function mysum($arr,$sep,$mykey){
  $sum=0;
  foreach($arr as $kk => $vv){
    $a1=strrpos($kk,$sep);
    if(substr($kk,0,$a1)==$mykey)$sum+=$vv;
  }
  return $sum;
}

function mysep($in,$vers){
  switch($vers){
    case 0:
      $out[1]=trim(substr($in,5,5));
      $out[2]=trim(substr($in,11,2));
      $out[3]=trim(substr($in,14,10));
      $out[4]=trim(substr($in,25,4));
      $out[5]=trim(substr($in,30,13));
      $out[6]=trim(substr($in,44,3));
      $out[7]=trim(substr($in,48,6));
      $out[8]=trim(substr($in,55,13));
      $out[9]=trim(substr($in,69,3));
      $out[10]=trim(substr($in,73,6));
      break;
    case 1:
      $out[1]=trim(substr($in,5,5));
      $out[2]=trim(substr($in,11,2));
      $out[3]=trim(substr($in,14,10));
      $out[4]=trim(substr($in,25,4));
      $out[5]=trim(substr($in,30,13));
      $out[6]="";
      $out[7]=trim(substr($in,44,8));
      $out[8]=trim(substr($in,53,13));
      $out[9]="";
      $out[10]=trim(substr($in,67,8));
      break;
  }
  return $out;
}

function mypar($str,$start,$len){
  $aux=substr($str,$start-1,$len);
  $mypos=strpos($aux,":");
  return trim(substr($aux,0,$mypos));
}

function findcall($a){
  global $myt,$zz;
  $call=strtoupper($a);
  $lc=strlen($call);
  $s=-1;
  for($q=1;$q<=$lc;$q++){
    if(isset($myt[substr($call,0,$q)]))$s=$myt[substr($call,0,$q)];
  }
  return $zz[$s];
}

$j=0;
$hh=fopen("cty.dat","r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)=="    "){
    $mll=$mll.trim(substr($line,4));
  }
  else {
    $mll="";
    $cqzone=mypar($line,27,5);
    $ituzone=mypar($line,32,5);
    $cont=mypar($line,37,5);
    $call=mypar($line,70,6);
    $name=mypar($line,1,26);
  }
  $tq2=strpos($mll,";");
  if($tq2!==false){
    $tq1=0;
    $tq3=strlen($mll);
    $mll[$tq2]=",";
    for(;;){
      if($tq1>=$tq3)break;
      $tq2=strpos($mll,",",$tq1);
      $v=substr($mll,$tq1,$tq2-$tq1);
      $tq1=$tq2+1;
      $to1=strpos($v,"=");
      if($to1!==false && $to1==0){
        $v=substr($v,1);
      }
      $to1=strpos($v,"(");
      if($to1!==false){
        $to2=strpos($v,")");
        $cqzone=(int)substr($v,$to1+1,$to2-$to1-1);
        $v=substr($v,0,$to1).substr($v,$to2+1);
      }
      $to1=strpos($v,"[");
      if($to1!==false){
        $to2=strpos($v,"]");
        $ituzone=(int)substr($v,$to1+1,$to2-$to1-1);
        $v=substr($v,0,$to1).substr($v,$to2+1);
      }
      $to1=strpos($v,"<");
      if($to1!==false){
        $to2=strpos($v,">");
        $v=substr($v,0,$to1).substr($v,$to2+1);
      }
      $to1=strpos($v,"{");
      if($to1!==false){
        $to2=strpos($v,"}");
        $cont=substr($v,$to1+1,$to2-$to1-1);
        $v=substr($v,0,$to1).substr($v,$to2+1);
      }
      $to1=strpos($v,"~");
      if($to1!==false){
        $to2=strpos($v,"~",$to1+1);
        $v=substr($v,0,$to1).substr($v,$to2+1);
      }
      $zz[$j]["prefix"]=$v;
      $zz[$j]["base"]=$call;
      $zz[$j]["cqzone"]=(int)$cqzone;
      $zz[$j]["ituzone"]=(int)$ituzone;
      $zz[$j]["cont"]=$cont;
      $zz[$j]["name"]=$name;
      $j++;
      $lp=strpos($call,"/");
      if($lp!==false){
        $aux=$call;
        $aux[$lp]="|";
        $zz[$j]["prefix"]=$aux;
        $zz[$j]["base"]=$call;
        $zz[$j]["cqzone"]=(int)$cqzone;
        $zz[$j]["ituzone"]=(int)$ituzone;
        $zz[$j]["cont"]=$cont;
        $zz[$j]["name"]=$name;
        $j++;
      }
    }
  }
} 
fclose($hh);

for($i=0;$i<$j;$i++){
  $qq=$zz[$i]["prefix"];
  $ll=strpos($qq,"|");
  if($ll!==false){
    $pre=substr($qq,0,$ll);
    $post=strtoupper(substr($qq,$ll+1));
    $myt[$pre.$post]=$i;
    for($w1=48;$w1<58;$w1++)$myt[$pre.chr($w1).$post]=$i;
    for($w1=48;$w1<58;$w1++)for($w2=48;$w2<58;$w2++)$myt[$pre.chr($w1).chr($w2).$post]=$i;
  }
  else $myt[$qq]=$i;
}

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

?>
