<?php
// v1 by IK4LZH 20230337

include("utility.php");
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");

$nqso=0;
while(!feof($hh)){
  $line=trim(fgets($hh));
  if(substr($line,0,4)!="QSO:")continue;
  $lqso[$nqso]=$line;
  $parts=mysep($line,10);
  $tqso[$nqso]=$parts[3].":".$parts[4].":".$nqso;
  $nqso++;
}
fclose($hh);
sort($tqso);

$name=rand().rand().rand().rand().".cbr";
$fp=fopen("/home/www/ik4lzh.mazzini.org/breakdown/$name","w");
fprintf($fp,"START-OF-LOG: 3.0\n");
fprintf($fp,"CONTEST: xxxxxx\n");
fprintf($fp,"CALLSIGN: xxxxxx\n");
fprintf($fp,"CATEGORY-OPERATOR: SINGLE-OP\n");
fprintf($fp,"CATEGORY-ASSISTED: ASSISTED\n");
fprintf($fp,"CATEGORY-BAND: ALL\n");
fprintf($fp,"CATEGORY-POWER: LOW\n");
fprintf($fp,"CATEGORY-TRANSMITTER: ONE\n");
fprintf($fp,"CREATED-BY: IK4LZH converter V1\n");
fprintf($fp,"NAME: xxxxxxx xxxxxx\n");
fprintf($fp,"ADDRESS: xxxxxx\n");
fprintf($fp,"ADDRESS-CITY: xxxxx\n");
fprintf($fp,"ADDRESS-POSTALCODE: xxxxxx\n");
fprintf($fp,"ADDRESS-COUNTRY: xxxxxx\n");
fprintf($fp,"OPERATORS: xxxxxx\n");

for($x=0;$x<$nqso;$x++){
  $y=substr($tqso[$x],16);
  fprintf($fp,"%s\n",$lqso[$y]);
}

fprintf($fp,"END-OF-LOG:\n");
fclose($fp);

echo "<pre><a href='https://ik4lzh.mazzini.org/breakdown/$name' download>Download Cabrillo</a><br>";

?>
