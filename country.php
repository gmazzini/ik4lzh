<?php

// version 2 by IK4LZH
// look for country in cty.dat file

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
function findcall($a){
  global $myt;
  $call=strtoupper($a);
  $lc=strlen($call);
  $s=-1;
  for($q=1;$q<=$lc;$q++){
    if(isset($myt[substr($call,0,$q)]))$s=$myt[substr($call,0,$q)];
  }
  return $s;
}

echo "<pre>";
// $call=$argv[1];
$call=$_POST['call'];
$mys=findcall($call);
print_r($zz[$mys]);
echo "</pre>";


                                                         

?>
