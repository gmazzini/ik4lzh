<?php

function mypar($str,$start,$len){
  $aux=substr($str,$start-1,$len);
  $mypos=strpos($aux,":");
  return trim(substr($aux,0,$mypos));
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
      $zz[$j]["prelen"]=strlen($v);
      $zz[$j]["base"]=$call;
      $zz[$j]["cqzone"]=(int)$cqzone;
      $zz[$j]["ituzone"]=(int)$ituzone;
      $zz[$j]["cont"]=$cont;
      $zz[$j]["name"]=$name;
      $j++;
    }
  }
}
fclose($hh);

// think to like 3D2/c

usort($zz, function($a, $b){
  $la=strlen($a["prefix"]);
  $lb=strlen($b["prefix"]);
  if($la<>$lb)return $lb-$la;
  return strcmp($a["prefix"],$b["prefix"]);
});

$mm=$zz[0]["prelen"];
$ppp[$mm]=0;
for($i=0;$i<$j;$i++){
  if($zz[$i]["prelen"]<$mm){
    $mm=$zz[$i]["prelen"];
    $ppp[$mm]=$i;
  }
}

echo "<pre>";
$call=strtoupper($_POST['call']);
for($q=strlen($call);$q>0;$q--){
  for($i=$ppp[$q];$i<$j;$i++){
    $vv=min($q,$zz[$i]["prelen"]);
    if(substr($call,0,$vv)==substr($zz[$i]["prefix"],0,$vv)){
      echo "$i ";
      print_r($zz[$i]);
      echo "\n";
      break 2;
    }
  }
}
echo "</pre>";

?>
