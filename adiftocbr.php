<?php
// v1 by IK4LZH 20221203

function myextract($buf,$token){
  $pos=stripos($buf,"<CALL:");
  $pose=stripos($buf,">",$pos);
  echo "$buf - $pos - $pose\n";
  return;
}

// include("utility.php");
$myrun=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);
  echo ">>> $mrun $line\n";
  
  if($myrun==0){
    $pos=stripos($line,"<EOH>");
    if($pos===false)continue;
    $myrun=1;
  }

  myextract($line,"ppp");

  
}
 
?>
