<?php

// version 1 by IK4LZH
// compute the CQWW scopre from cabrillo file

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

$oldmyd=0;
$base=1;
$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
while(!feof($hh)){
  $line=fgets($hh);
  if(substr($line,0,4)!="QSO:")continue;
  $parts=preg_split('/\s+/',$line);
  if($base){
    $base=0;
    $mys=findcall($parts[5]);
    $mycountryB=$zz[$mys]["base"];
    $mycB=$zz[$mys]["cont"];
    $myfirstday=(int)substr($parts[3],8,2);
  }
  $freq=substr($parts[1],0,strlen($parts[1])-3);
  $myd=((int)substr($parts[4],2,2))+((int)substr($parts[4],0,2))*60+(((int)substr($parts[3],8,2))-$myfirstday)*1440;
  $call=$parts[8];
  $zone=$parts[10];
  $mul[$zone.$freq]=1;
  $mys=findcall($call);
  if($mys!=-1&&$myd>=$oldmyd){
    $oldmyd=$myd;
    $mycountry=$zz[$mys]["base"];
    $myc=$zz[$mys]["cont"];
    $mul[$mycountry.$freq]=1;
    if($myc!=$mycB)$qso[$call.$freq]=3;
    else if($myc=="NA"&&$mycB=="NA"&&$mycountry!=$mycountryB)$qso[$call.$freq]=2;
    else if($myc==$mycB&&$mycountry!=$mycountryB)$qso[$call.$freq]=1;
    else $qso[$call.$freq]=0;
    $valqso=array_sum($qso);
    $valmul=array_sum($mul);
    $valqsoT[$myd]=$valqso;
    $valmulT[$myd]=$valmul;
  }
}
fclose($hh);

echo "<pre>";
$valqso=0;
$valmul=0;
for($i=0;$i<2880;$i++){
  if(isset($valqsoT[$i])){
    $valqso=$valqsoT[$i];
    $valmul=$valmulT[$i];
    $valcc=$valqso*$valmul;
  }
  echo "$i,$valqso,$valmul,$valcc\n";
}
echo "</pre>";

?>
