<?php
// v1 by IK4LZH 20221203

function extr($buf,$token){
  $pos=stripos($buf,"<CALL:");
  $pose=stripos($buf,">",$pos);
  echo "$buf - $pos - $pose\n";
}

// include("utility.php");
$ff=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  echo ">>> $line\n";
  
  if($ff==0){
    $pos=stripos($line,"<EOH>");
    if($pos===false)continue;
  }
  else $ff=1;

  extr($line,"ppp");

  
}
 
?>
