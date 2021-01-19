<?php
// v1 by IK4LZH, usage: cd /$HOME/Documents/MLDX_Logs/macloggerdx; php hadx.php
$mycontest="hadx 2020";
$mycall="IK4LZH";

include("country.php");
$mys=findcall($mycall);
$mycont=$mys["cont"];
$db=new SQLite3("../MacLoggerDX.sql");
$mm=array("USB"=>"PH","LSB"=>"PH","CW"=>"CW","FT8"=>"DG","MFSK"=>"DG");

$res = $db->query("SELECT call,band_tx,mode,srx FROM qso_table_v007 where contest_id='$mycontest'");
while ($row = $res->fetchArray()) {
  
  $mys=findcall($row["call"]);
   
  $myid=$row["band_tx"]."-".$mm[$row["mode"]]."-".$row["call"];
  if(!isset($qso[$myid]))$qso[$myid]=1;
  if($mys["base"]=="HA")$pp=10;
  else if($mys["cont"]!=$mycont)$pp=5;
  else $pp=2;
  if(!isset($point[$myid]))$point[$myid]=$pp;
 
  $myid=$row["band_tx"]."-".$mys["base"];
  if($mys["base"]!="HA"){
    if(!isset($mult[$myid]))$mult[$myid]=1;
  }
  if($mys["base"]=="HA" && isset($row["srx"])){
    $myid=$row["band_tx"]."--".$row["srx"];
    if(!isset($mult[$myid]))$mult[$myid]=1;
  }
}

echo array_sum($qso)." qso\n";
echo array_sum($point)." point\n";
echo array_sum($mult)." mult\n";
echo array_sum($point)*array_sum($mult)." score\n";

?>
