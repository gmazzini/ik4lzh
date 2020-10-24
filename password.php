<?php

// version 1 by IK4LZH

echo "<pre>";
// $passlen=$argv[1];
$passlen=$_POST['passlen'];
list($usec,$sec)=explode(' ',microtime());
srand($sec+$usec*1000000);
for($i=0;$i<$passlen;$i++){
  for(;;){
    $nn=rand(48,122);
    if($nn>=58&&$nn<=64)continue;
    if($nn>=91&&$nn<=96)continue;
    $qq[$i]=chr($nn);
    break;
  }
}
$ns=rand(1,$passlen/3);
for($i=0;$i<$ns;$i++){
  $nn=rand(1,$passlen-2);
  $qq[$nn]=".";
}
echo implode($qq);
echo "</pre>";


                                                         

?>
