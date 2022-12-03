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
    echo "QSO: ";
    printf("%5d ",$freq*1000);
    printf("%2s ",$mymode[$mode]);
    printf("%04d-%02d-%02d ",substr($qsodate,0,4),substr($qsodate,4,2),substr($qsodate,6,2));
    printf("%04d ",substr($qsotime,0,4));
    printf("%-13s ",$operator);
    printf("%3s ",$rstsent);
    if($stxstring)printf("%-6s ",$stxstring);
    else printf("%-6s ",$stx);
    printf("%-13s ",$call);
    printf("%3s ",$rstrcvd);
    if($stxstring)printf("%-6s ",$srxstring);
    else printf("%-6s ",$srx);
    echo "0\n";
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

?>
