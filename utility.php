<?php
// v8 by IK4LZH 20210129

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
    case 10:
      $out=preg_split('/\s+/',$in);
      break;
  }
  return $out;
}

function mybreakdown($contest,$call,$datacontest,$aqso,$apoint,$amult){
  $myd=array_unique(array_keys($aqso));
  sort($myd);
  $name=$contest."_".$call."_".$datacontest."_".md5($contest.$call.$datacontest.rand());
  $fp=fopen("/home/www/ik4lzh.mazzini.org/breakdown/$name.csv","w");
  echo "<a href='https://ik4lzh.mazzini.org/breakdown/$name.csv' download>Download breakdown</a><br>";
  $z1=$z2=$z3=0;
  foreach($myd as $dd){
    $z1+=$aqso[$dd];
    if(isset($apoint[$dd]))$z2+=$apoint[$dd];
    if(isset($amult[$dd]))$z3+=$amult[$dd];
    fwrite($fp,$dd.",".$z1.",".$z2.",".$z3."\n");
    echo $dd.",".$z1.",".$z2.",".$z3."\n";
  }
  file_put_contents("/home/www/ik4lzh.mazzini.org/log.txt",date("Y-m-d H:i:s").",".$contest.",".$call.",".$datacontest.",".$z1.",".$z2.",".$z3."\n", FILE_APPEND | LOCK_EX);
  fclose(fp);
}

function findcall($a){
  global $z1,$z2,$z3;
  $call=strtoupper($a);
  if(isset($z2[$call]))return $z1[$z2[$call]];
  $to1=strpos($call,"/");
  if($to1!==false){
    $lc=strlen($call);
    if($to1<$lc-$to1-1)$call=substr($call,0,$to1);
    else $call=substr($call,$to1+1);
  }
  $lc=strlen($call);
  $s=-1;
  for($q=1;$q<=$lc;$q++){
    if(isset($z3[substr($call,0,$q)]))$s=$z3[substr($call,0,$q)];
  }
  if($s!=-1)return $z1[$s];
}

$j=0;
$hh=fopen("cty.csv","r");
while(!feof($hh)){
  $dd=fgetcsv($hh,100000);
  if($dd===FALSE)continue;
  $ee=explode(" ",substr($dd[9],0,-1));
  foreach($ee as $ff){
    $z1[$j]["base"]=$dd[0];
    $z1[$j]["name"]=$dd[1];
    $z1[$j]["dxcc"]=$dd[2];

    $to1=strpos($ff,"(");
    if($to1!==false){
      $to2=strpos($ff,")",$to1+1);
      $z1[$j]["cqzone"]=(int)substr($ff,$to1+1,$to2-$to1-1);
      $ff=substr($ff,0,$to1).substr($ff,$to2+1);
    }
    else $z1[$j]["cqzone"]=(int)$dd[4];

    $to1=strpos($ff,"[");
    if($to1!==false){
      $to2=strpos($ff,"]",$to1+1);
      $z1[$j]["ituzone"]=(int)substr($ff,$to1+1,$to2-$to1-1);
      $ff=substr($ff,0,$to1).substr($ff,$to2+1);
    }
    else $z1[$j]["ituzone"]=(int)$dd[5];

    $to1=strpos($ff,"{");
    if($to1!==false){
      $to2=strpos($ff,"}",$to1+1);
      $z1[$j]["cont"]=substr($ff,$to1+1,$to2-$to1-1);
      $ff=substr($ff,0,$to1).substr($ff,$to2+1);
    }
    else $z1[$j]["cont"]=$dd[3];
    
    $to1=strpos($ff,"<");
    if($to1!==false){
      $to2=strpos($ff,">",$to1+1);
      $ff=substr($ff,0,$to1).substr($ff,$to2+1);
    }

    $to1=strpos($ff,"~");
    if($to1!==false){
      $to2=strpos($ff,"~",$to1+1);
      $ff=substr($ff,0,$to1).substr($ff,$to2+1);
    }

    if($ff[0]=="="){
      $z2[substr($ff,1)]=$j;
    }

    $to1=strpos($ff,"/");
    if($to1!==false){
      $pre=substr($ff,0,$to1);
      $post=strtoupper(substr($ff,$to1+1));
      $z3[$pre.$post]=$j;
      for($w1=48;$w1<58;$w1++)$z3[$pre.chr($w1).$post]=$j;
      for($w1=48;$w1<58;$w1++)for($w2=48;$w2<58;$w2++)$z3[$pre.chr($w1).chr($w2).$post]=$j;
    }

    $z1[$j]["prefix"]=$ff;
    $z3[$ff]=$j;

    $j++;
  }
}
fclose($hh);

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
