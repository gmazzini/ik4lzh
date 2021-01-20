<?php
// v2 by IK4LZH 20210120

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
echo "<pre>";
echo implode($qq);
echo "</pre>";
?>
