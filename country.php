<?php
// v2 by IK4LZH 20210119

include("utility.php");

echo "<pre>";
// $call=$argv[1];
$call=$_POST['call'];
$mys=findcall($call);
print_r($mys);
echo "</pre>";
                                             
?>
