<?PHP
 // pripojeni k lokální databazi 
$spojeni = mysql_connect($databaze['server'],$databaze['uzivatel'],$databaze['heslo'],true);
mysql_query("SET CHARACTER SET utf8;");
mysql_query("SET NAMES utf8;");
$spojenidb = mysql_select_db($databaze['databaze'],$spojeni);  
if (!$spojeni){
  echo"<div id=\"chyba1\">Nepodařilo se navázat spojení se základním serverem.</div>";
  $spojeni = null;
  }
if (!$spojenidb){
  $spojeni = null;
  echo"<div id=\"chyba2\">Nepodařilo se navázat spojení se základní databází.</div>";
  }
/**************************
 **************************
 **************************/ 
?>
