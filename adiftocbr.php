<?php
// v1 by IK4LZH 20221203

// rm -rf ik4lzh; git clone https://github.com/gmazzini/ik4lzh; cp ik4lzh/adiftocbr.php .
// cat lz22.adi | php adiftocbr.php | head -n 20

function myextract($buf,$token){
  $pos=stripos($buf,"<".$token.":");
  if($pos===false)return null;
  $pose=stripos($buf,">",$pos);
  $ltok=strlen($token)+2;
  $ll=(int)substr($buf,$pos+$ltok,$pose-$pos-$ltok);
  return substr($buf,$pose+1,$ll);
}

$mymode=array("SSB"=>"PH","CW"=>"CW");

$myrun=0;
if(isset($_FILES['cbrfile']['tmp_name']))$hh=fopen($_FILES['cbrfile']['tmp_name'],"r");
else $hh=fopen("php://stdin","r");
while(!feof($hh)){
  $line=fgets($hh);

  if($myrun==0){
    $pos=stripos($line,"<EOH>");
    if($pos===false)continue;
    $myrun=1;
  }

  $aux=myextract($line,"CALL");
  if($aux)$call=$aux;
  $aux=myextract($line,"OPERATOR");
  if($aux)$operator=$aux;
  $aux=myextract($line,"MODE");
  if($aux)$mode=$aux;
  $aux=myextract($line,"QSO_DATE");
  if($aux)$qsodate=$aux;
  $aux=myextract($line,"TIME_ON");
  if($aux)$qsotime=$aux;
  $aux=myextract($line,"FREQ");
  if($aux)$freq=$aux;
  $aux=myextract($line,"RST_SENT");
  if($aux)$rstsent=$aux;
  $aux=myextract($line,"RST_RCVD");
  if($aux)$rstrcvd=$aux;
  $aux=myextract($line,"STX");
  if($aux)$stx=$aux;
  $aux=myextract($line,"STX_STRING");
  if($aux)$stxstring=$aux;
  $aux=myextract($line,"SRX");
  if($aux)$srx=$aux;
  $aux=myextract($line,"SRX_STRING");
  if($aux)$srxstring=$aux;
  
  $pos=stripos($line,"<EOR>");
  if($pos!==false){
    $out="QSO: ";
    $out.=sprintf("%5d ",$freq*1000);
    $out.=sprintf("%2s ",$mymode[$mode]);
    $out.=sprintf("%04d-%02d-%02d ",substr($qsodate,0,4),substr($qsodate,4,2),substr($qsodate,6,2));
    $out.=sprintf("%04d ",substr($qsotime,0,4));
    $out.=sprintf("%-13s ",$operator);
    $out.=sprintf("%3s ",$rstsent);
    if($stxstring)$out.=sprintf("%-6s ",$stxstring);
    else $out.=sprintf("%-6s ",$stx);
    $out.=sprintf("%-13s ",$call);
    $out.=sprintf("%3s ",$rstrcvd);
    if($stxstring)$out.=sprintf("%-6s ",$srxstring);
    else $out.=sprintf("%-6s ",$srx);
    $out.="0";
    $oo[$qsodate.$qsotime]=$out;
    $call="";
    $operator="";
    $mode="";
    $qsodate="";
    $qsotime="";
    $freq="";
    $rstsent="";
    $rstrcvd="";
    $stx="";
    $stxstring="";
    $srx="";
    $srxstring="";
  }
}

ksort($oo);

echo "START-OF-LOG: 3.0\n";
echo "CONTEST: xxxxxx\n";
echo "CALLSIGN: xxxxxx\n";
echo "CATEGORY-OPERATOR: SINGLE-OP\n";
echo "CATEGORY-ASSISTED: ASSISTED\n";
echo "CATEGORY-BAND: ALL\n";
echo "CATEGORY-POWER: LOW\n";
echo "CATEGORY-TRANSMITTER: ONE\n";
echo "CREATED-BY: IK4LZH converter V1\n";
echo "NAME: xxxxxxx xxxxxx\n";
echo "ADDRESS: xxxxxx\n";
echo "ADDRESS-CITY: xxxxx\n";
echo "ADDRESS-POSTALCODE: xxxxxx\n";
echo "ADDRESS-COUNTRY: xxxxxx\n";
echo "OPERATORS: xxxxxx\n";

foreach($oo as $key => $val){
  echo "$val\n";
}

echo "END-OF-LOG:\n";

?>
