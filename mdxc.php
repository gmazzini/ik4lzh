<?php
// IK4LZH v1 generate MDXC list 

for($i=0;$i<1000;$i+=30){
  $aux=file_get_contents("http://maxlaconca.com/mdxc_directory/lista_ita.php?startrow=$i");
  $pp=explode("<tr>",$aux);
  foreach ($pp as &$vv){
    $qq=explode("</td>",$vv);
    if(isset($qq[3])){
      $qq1=strrpos($qq[0],">");
      $myid=(int)substr($qq[0],$qq1+1);
      $qq2=strrpos($qq[3],">");
      $mycall=substr($qq[3],$qq2+1);
      if($myid>0&&strlen($mycall)>2)$mylist["$mycall"]=$myid;
    }
  }
}
file_put_contents("/home/www/ik4lzh.mazzini.org/mdxc.list",var_export($mylist,true));

?>
