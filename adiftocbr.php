<?php
// v1 by IK4LZH 20221203

// rm -rf ik4lzh; git clone https://github.com/gmazzini/ik4lzh; cp ik4lzh/adiftocbr.php .
// cat lz22.adi | php adiftocbr.php | head -n 20

function myextract($buf,$token){
  $pos=stripos($buf,"<" & $token & ":");
  if($pos){
    $pose=stripos($buf,">",$pos);
    $ltok=$strlen($token)+2;
    echo ">>" & substr($buf,$pos+$ltok,$pose-$pos-$ltok);
    echo "$ltok - $pos - $pose\n";
  }
  return;
}

// include("utility.php");
$myrun=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  echo ">>> $myrun $line\n";
  
  if($myrun==0){
    $pos=stripos($line,"<EOH>");
    if($pos===false)continue;
    $myrun=1;
  }

  myextract($line,"CALL");

  
}
 
?>
