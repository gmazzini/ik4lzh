Last login: Wed Nov 23 16:14:45 on ttys000
gmazzini@Gianlucas-MacBook-Pro ~ % cd Downloads 
gmazzini@Gianlucas-MacBook-Pro Downloads % nano adiftocbr.php
gmazzini@Gianlucas-MacBook-Pro Downloads % nano -c adiftocbr.php
gmazzini@Gianlucas-MacBook-Pro Downloads % nano -c adiftocbr.php
gmazzini@Gianlucas-MacBook-Pro Downloads % nano -c adiftocbr.php



































































  GNU nano 7.0                                       adiftocbr.php                                                 
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
