<?PHP
require_once("convert.php");
require_once("printer.class.php");
/* FUNKCE NA ZÍSKÁVÁNÍ PROMĚNNÝCH Z ADRESY STRÁNKY */
function get($promenna){
  $kVraceni = NULL;
  if(isset($_GET[$promenna])){
    $g = trim($_GET[$promenna]);
    if(!empty($g)){
      return osetri($g);
      }
    } 
  return $kVraceni; 
  }
  
/* FUNKCE NA ZISKAVANI POST */
function post($promenna){
  $kVraceni = NULL;
  if(isset($_POST[$promenna])){
    if(!is_array($_POST[$promenna])){
      $p = trim($_POST[$promenna]);
      if(!empty($p)){
        $kVraceni = osetri($p);
        }
      }
    else{
      if(!empty($_POST[$promenna])){
        return $_POST[$promenna];
        }  
      }
    } 
  return $kVraceni; 
  }
function predat($promenna){
  $vrat = get($promenna);
  if(isset($_POST[$promenna])){
    $vrat = post($promenna);
    }
  return $vrat;
  }
/* FUNKCE NA ZISKAVANI SESSION */
function sess($promenna,$hodnota = null){
  if((!empty($hodnota))&&(strlen($hodnota)>0)){
    $_SESSION[$promenna] = $hodnota;
    //echo "aaa:".$promenna."::".$hodnota.";<br />";
    }
  else{
    $kVraceni = NULL;
    if(isset($_SESSION[$promenna])){
      $s = trim($_SESSION[$promenna]);
      if(!empty($s)){
        return osetri($s);
        }
      } 
    return $kVraceni; 
    }
  }
/* FUNKCE NA OŠETŘENÍ INPUTŮ */
function osetri($osetri){
  $najdi = array("&", "\"", "'", "<", ">");
  $nahrad = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;");
  return str_replace($najdi, $nahrad, $osetri);
  //return htmlentities($osetri,ENT_QUOTES);
  }
function odOsetri($osetri){
  $najdi = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;");
  $nahrad = array("&", "\"", "'", "<", ">");
  return str_replace($najdi, $nahrad, $osetri);
  //return htmlentities($osetri,ENT_QUOTES);
  }
  

/* FUNKCE VRACEJÍCÍ CAS Z DB */
function timestamptodate($timestamp){
  $year=substr($timestamp,0,4);
  $month=substr($timestamp,4,2);
  $day=substr($timestamp,6,2);
  $hour=substr($timestamp,8,2);
  $minute=substr($timestamp,10,2);
  $second=substr($timestamp,12,2);
  $newdate=mktime($hour,$minute,$second,$month,$day,$year);
  RETURN ($newdate);
  }
/* FUNKCE NA OVERENI EXISTENCE SQL DAT*/
function existujeVDB($dotaz){
  if((!empty($dotaz))&&(MySQL_Num_Rows($dotaz)>0)){
    return true;
    }
  else{
    return false;
    }
  }
/* FUNKCE NA ZÍSKÁNÍ DATA */

function datum(){
  return date('Y-m-d H:i:s',time());
  }

function datumZMySQL($time){
  return date('Y-m-d H:i:s',$time);
  }

/* FUNKCE NA KODOVANI A DEKODOVANI RETEZCE */
function encrypt($string, $key) {
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
    }
  return base64_encode($result);
  }
function decrypt($string, $key) {
  $result = '';
  $string = base64_decode($string);
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
    }
  return $result;
  }
/*****************************/
/***** PRÁCE S MYSQL *********/

function fetchnormal($zdroj){
  return MySQL_Fetch_Array($zdroj);
  } 
function assoc($zdroj){
  return mysql_fetch_assoc($zdroj);
  } 
function fetch($zdroj){
  return MySQL_Fetch_Array($zdroj);
  } 
         
                        /*
function fetchnormal($zdroj){
  return MySQL_Fetch_Array($zdroj);
  } 
         
                      
function fetch($zdroj){
  $text = MySQL_Fetch_Array($zdroj);
  $prelozenyText = array();
  foreach($text as $klic => $hodnota){
    $jeUTF = mb_detect_encoding($hodnota, 'UTF-8', true); 
    if($jeUTF){
      $prelozenyText[$klic] = $hodnota;
      }
    else{
      $prelozenyText[$klic] = win2utf($hodnota);
      }
    }
  return $prelozenyText;
  }     */

                                   /*
function fetch($zdroj){
  $text = MySQL_Fetch_Array($zdroj);
  $prelozenyText = array();
  foreach($text as $klic => $hodnota){
    $jeUTF = mb_detect_encoding($hodnota, 'UTF-8', true); 
    $jeWIN = mb_detect_encoding($hodnota, 'windows-1250', true); 
    $jeISO1 = mb_detect_encoding($hodnota, 'iso-8859-1', true);
    $jeISO2 = mb_detect_encoding($hodnota, 'iso-8859-2', true);
    $jeLatin1 = mb_detect_encoding($hodnota, 'latin1', true); 
    $jeLatin2 = mb_detect_encoding($hodnota, 'latin2', true); 
    if($jeUTF){
      $prelozenyText[$klic] = $hodnota;
      }
    elseif($jeWIN){
      $prelozenyText[$klic] = iconv("windows-1250", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }
    elseif($jeISO2){
      $prelozenyText[$klic] = iconv("iso-8859-2", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }  
    elseif($jeLatin2){ 
      $prelozenyText[$klic] = iconv("latin2", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }  
    elseif($jeISO1){
      $prelozenyText[$klic] = iconv("iso-8859-1", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }  
    elseif($jeLatin1){ 
      $prelozenyText[$klic] = iconv("latin1", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }  
    else{
      $current_encoding = mb_detect_encoding($hodnota, 'auto'); 
      $prelozenyText[$klic] = iconv("windows-1250", "UTF-8//TRANSLIT//IGNORE", $hodnota);
      }
    }
  return $prelozenyText;
  }              */
function fetchtext($zdroj){
  $text = MySQL_Fetch_Array($zdroj);
  /*
  $prelozenyText = array();
  foreach($text as $klic => $hodnota){
    $jeUTF = mb_detect_encoding($hodnota, 'UTF-8', true); 
    $jeWIN = mb_detect_encoding($hodnota, 'windows-1250', true); 
    $jeISO = mb_detect_encoding($hodnota, 'iso-8859-2', true);
    $jeLatin2 = mb_detect_encoding($hodnota, 'latin2', true);   
    if($jeUTF){
      $prelozenyText[$klic] = $hodnota;
      }
    elseif($jeWIN){
      $prelozenyText[$klic] = iconv("windows-1250", "UTF-8", $hodnota);
      }
    elseif($jeISO){
      $prelozenyText[$klic] = iconv("iso-8859-2", "UTF-8", $hodnota);
      }  
    elseif($jeLatin2){ 
      $prelozenyText[$klic] = iconv("latin2", "UTF-8", $hodnota);
      }  
    else{
      $current_encoding = mb_detect_encoding($hodnota, 'auto'); 
      $prelozenyText[$klic] = iconv($current_encoding, "UTF-8", $hodnota);
      }
    //$current_encoding = mb_detect_encoding($hodnota, 'auto');
    //$text = iconv($current_encoding, 'UTF-8//TRANSLIT', $text);
    //$prelozenyText[$klic] = iconv("Windows-1250", "UTF-8//TRANSLIT", $hodnota);
    }
   //echo mb_detect_encoding($text); 
  return $prelozenyText;            */
  return $zdroj;            
  }
/*
function fetch($zdroj){
  return MySQL_Fetch_Array($zdroj);
  } 
*/
function query($pozadavek, $spojeni = null){/*
  echo "p: ".$pozadavek."<br />";
  echo "s: ".$spojeni."<br />";*/
  if(empty($spojeni)){
    $vysledek = MySQL_Query($pozadavek);
    }
  else{
    $vysledek = MySQL_Query($pozadavek,$spojeni);
    }
  return $vysledek;
  }
/* PRÁCE S FORMULÁŘI */
function input($jmeno, $hodnota = "", $trida = "", $id = "", $type = "text"){
  $text = "";
  if(!empty($trida)){
    $text .= " class='".$trida."'";
    }
  if(!empty($id)){
    $text .= " id='".$id."'";
    }
  if(!empty($type)){
    $text .= " type='".$type."'";
    }
  $vypis = "<input name=\"".$jmeno."\" value=\"".$hodnota."\" ".$text."/>";
  return $vypis;
  }
function label($text, $id){
  return "<label for='".$id."'>".$text."</label>";
  }
function check($jmeno, $hodnota, $oznacen = false, $trida = "", $id = ""){
  $checked = "";
  if($oznacen){
    $checked = "checked=\"checked\"";
    }
  if(!empty($trida)){
    $trida = "class='".$trida."' ";
    }
  if(!empty($id)){
    $id = "id='".$id."' ";
    }
  $vypis = "<input type=\"checkbox\" name=\"".$jmeno."\" value=\"".$hodnota."\" ".$trida.$checked.$id." />";
  return $vypis;
  }
function radio($jmeno, $hodnota, $oznacen = false, $trida = "",$idcko = ""){
  $checked = "";
  if((!empty($oznacen))&&($oznacen)){
    $checked = " checked=\"checked\"";
    }
  if(!empty($trida)){
    $trida = " class=\"".$trida."\"";
    }
  if(!empty($idcko)){
    $idcko = " id=\"".$idcko."\"";
    }
  $vypis = "<input type=\"radio\" name=\"".$jmeno."\" value=\"".$hodnota."\" ".$trida.$idcko.$checked."/>";
  return $vypis;
  }
function radioAnoNe($jmeno, $oznacen = 0){
  $checkedAno = "";
  $checkedNe = "";
  if($oznacen == 1){
    $checkedAno = "checked=\"checked\"";
    }
  else{
    $checkedNe = "checked=\"checked\"";
    }
  $vypis = "<span class=\"anone\">Ano<input class=\"anone\" type=\"radio\" name=\"".$jmeno."\" value=\"1\" ".$checkedAno."/>";
  $vypis .= "Ne<input class=\"anone\" type=\"radio\" name=\"".$jmeno."\" value=\"0\" ".$checkedNe."/></span>";
  return $vypis;
  }
function radioAnoNeClass($jmeno, $class = "",$oznacen = 0){
  $checkedAno = "";
  $checkedNe = "";
  if($oznacen == 1){
    $checkedAno = "checked=\"checked\"";
    }
  else{
    $checkedNe = "checked=\"checked\"";
    }
  if(!empty($class)){
    $class = "class=\"".$class."\"";
    }
  $vypis = "Ano<input type=\"radio\" name=\"".$jmeno."\" value=\"1\" ".$checkedAno." ".$class."/>";
  $vypis .= "Ne<input type=\"radio\" name=\"".$jmeno."\" value=\"0\" ".$checkedNe." ".$class."/>";
  return $vypis;
  }
function radioAnoNeBr($jmeno, $oznacen = 0){
  $checkedAno = "";
  $checkedNe = "";
  if($oznacen == 1){
    $checkedAno = "checked=\"checked\"";
    }
  else{
    $checkedNe = "checked=\"checked\"";
    }
  $vypis = "<input type=\"radio\" name=\"".$jmeno."\" value=\"1\" ".$checkedAno."/> Ano<br />";
  $vypis .= "<input type=\"radio\" name=\"".$jmeno."\" value=\"0\" ".$checkedNe."/> Ne";
  return $vypis;
  }
/*****************************************/
function nahodny_retezec($delka="15",$bazen = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',$konec = true){
  if(empty($bazen)){
    $bazen = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
  $ok = false;
  //$bazen = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for ($i=0; $i < $delka; $i++) {
    $str .= substr($bazen, mt_rand(0, strlen($bazen) -1), 1);
    }
  if($konec){
    $str = $str."a";
    }
  return $str;
  }
/*******************************************/
function prelozDoUTF($text){
  $prelozenyText = array();
  foreach($text as $klic => $hodnota){
    $jeUTF = mb_detect_encoding($hodnota, 'UTF-8', true); 
    if($jeUTF){
      $prelozenyText[$klic] = $hodnota;
      }
    else{
      $prelozenyText[$klic] = win2utf($hodnota);
      }
    }
  return $prelozenyText;
  }
function prelozDoUTFSam($text){
  $jeUTF = mb_detect_encoding($text, 'UTF-8', true); 
  if($jeUTF){
    $prelozenyText = $text;
    }
  else{
    $prelozenyText = win2utf($text);
    }
  return $prelozenyText;
  }
/******************************************/
function nahradCarku($text){
  $vrat = str_replace(",",".",$text);
  return $vrat;
  }
function nahradSQLCarku($text){
  $vrat = str_replace("'","\'",$text);
  return $vrat;
  }

function cena($cena, $round = 1){
  $vrat = round($cena, $round);
  if((substr($vrat,-(1 + $round),1)==",")||(substr($vrat,-(1 + $round),1)==".")){
    if($round == 1){
      $vrat = $vrat."0";
      }
    $vratDelka = strlen($vrat);
    $delka = 6;
    }
  else{
    $vratDelka = strlen($vrat);
    $vratDelka = $vratDelka;
    $delka = 3;
    }
  if($vratDelka > $delka){
    $opakovat = floor(($vratDelka-$delka) / 3);
    for($i=0;$i<=$opakovat;$i++){
      $misto = -$delka-($i*3)-$i;
      $zacatek = substr($vrat, 0, $misto);
      $konec = substr($vrat, $misto);
      $vrat = $zacatek." ".$konec;
      } 
    }  
  $vrat = str_replace(".",",",$vrat);
  return $vrat."";
  }
function cenaKc($cena){
  $vrat = round($cena,1);
  $vrat = str_replace ( ".", ",", $vrat);
  //$vrat = "-".substr($vrat,-2,1)."-";
  if((substr($vrat,-2,1)==",")||(substr($vrat,-2,1)==".")){
    $vrat = $vrat."0";
    }
  else{
    $vrat = $vrat.",- ";
    }
  $vratDelka = strlen($vrat);
  if($vratDelka > 6){
    //echo "v:".$vratDelka."-<br />";
    $opakovat = floor(($vratDelka-6) / 3);
    //echo "op:".$opakovat."-<br />";
    for($i=0;$i<=$opakovat;$i++){
      $misto = -6-($i*3)-$i;
      //echo "m:".$misto."-<br />";
      $zacatek = substr($vrat, 0, $misto);
      $konec = substr($vrat, $misto);
      $vrat = $zacatek." ".$konec;
      } 
    }
  /*
  $rest = substr("abcdef", 0, -1);  // returns "abcde"
  $rest = substr("abcdef", 2, -1);  // returns "cde"
  $rest = substr("abcdef", 4, -4);  // returns false
  $rest = substr("abcdef", -3, -1); // returns "de"
  */      
  return $vrat." Kč";
  }
function mnoz($mnoz, $delka = 1){
  $vrat = round($mnoz,$delka);
  $vrat = str_replace ( ".", ",", $vrat);
  return $vrat;
  }
function datumUnix($cas,$format = "H:i:s d.m.Y"){
  return date($format,$cas);
  }

function date4timestamp($date)
{
	list($date, $time)=explode(' ', trim($date));
	list($day, $month, $year)=explode('.', trim($date));
	list($hour, $minute, $second)=explode(':', trim($time));

	return (int) $year . sprintf("%02s", (int) $month) . sprintf('%02s', (int) $day) . sprintf('%02s', $hour) . sprintf('%02s', $minute) . sprintf('%02s', $second);
}

function date4user($date)
{
	if ($date == '0000-00-00 00:00:00' && !$date) return false;
	
	$y=substr($date, 0, 4);
	$m=substr($date, 4, 2);
	$d=substr($date, 6, 2);
  $h=substr($date, 8, 2);
  $m=substr($date, 10, 2);
  $s=substr($date, 12, 2);
	return "$d.$m.$y $h:$m:$s";
}
/*
function date4user($date)
{
	if ($date == '0000-00-00 00:00:00' && !$date) return false;
	
	list($date, $time) = explode(' ', $date); 
	$y=substr($date, 0, 4);
	$m=substr($date, 5, 2);
	$d=substr($date, 8, 2);

	return "$d.$m.$y $time";
}
*/

/** STRÁNKOVAČ **/
function strankovac($levaprava,$misto,$part,$pocetStran,$url){           
  //$url = $_SERVER['QUERY_STRING'];
  //echo "a: ".$_SERVER['QUERY_STRING']."-<br />";
  //echo "a: ".$url."-<br />";
  //echo "url1: ".$url."-<br />ss:".substr($url,1)."--<br />";
  //$url = preg_replace('/(.*)&part=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
  $vypis = "";
  if($pocetStran > 1){
    if((substr($url,0,1))=="?"){
      $url = substr($url,1);
      }
    $url = str_replace("&amp;","&",$url);
    $pokus = preg_match ( '/(.*)(\?|&)part=[^&]+?(&)(.*)/i' , $url );
    if($pokus){
      $urlZacatek = "?".preg_replace('/(.*)(\?|&)part=[^&]+?(&)(.*)/i', '$1$2part=1&$4', $url . '&');
      $urlPred = "?".preg_replace('/(.*)(\?|&)part=[^&]+?(&)(.*)/i', '$1$2part='.($part-1).'&$4', $url . '&');
      $urlKonec = "?".preg_replace('/(.*)(\?|&)part=[^&]+?(&)(.*)/i', '$1$2part='.$pocetStran.'&$4', $url . '&');
      $urlDalsi = "?".preg_replace('/(.*)(\?|&)part=[^&]+?(&)(.*)/i', '$1$2part='.($part+1).'&$4', $url . '&');
      $urlZacatek = substr($urlZacatek, 0, -1);
      $urlKonec = substr($urlKonec, 0, -1);
      $urlPred = substr($urlPred, 0, -1);
      $urlDalsi = substr($urlDalsi, 0, -1);
      }
    else{
      $urlZacatek = $_SERVER['PHP_SELF']."?".$url."&amp;part=1";
      $urlPred = $_SERVER['PHP_SELF']."?".$url."&amp;part=".($part-1);
      $urlKonec = $_SERVER['PHP_SELF']."?".$url."&amp;part=".$pocetStran;
      $urlDalsi = $_SERVER['PHP_SELF']."?".$url."&amp;part=".($part+1);   
      }
    /************/
    $vypisLeva = "<span class=\"leva\">&nbsp;</span>";
    $vypisPrava = "<span class=\"prava\">&nbsp;</span>";
    if($part >= 1){
      if($part >= $pocetStran){
        }
      elseif(($part+1)<$pocetStran){
        $vypisPrava = "<div class=\"prava\"><a href=\"".$urlKonec."\" class=\"casti\"> konec (".$pocetStran."/".$pocetStran.")</a>";
        $vypisPrava .= "<a href=\"".$urlDalsi."\" class=\"casti\"> další (".($part+1)."/".$pocetStran.")</a></div>";
        }
      else{
        $vypisPrava = "<div class=\"prava\"><a href=\"".$urlDalsi."\" class=\"casti\"> další (".$pocetStran."/".$pocetStran.")</a></div>";
        }
      }
    if($part <= $pocetStran){
      if(($part-1)>1){
        $vypisLeva = "<div class=\"leva\"><a href=\"".$urlPred."\" class=\"casti\"> předchozí (".($part-1)."/".$pocetStran.")</a>";
        $vypisLeva .= "<a href=\"".$urlZacatek."\" class=\"casti\"> začátek (1/".$pocetStran.")</a></div>";
        }
      elseif(($part-1)==1){
        $vypisLeva = "<div class=\"leva\"><a href=\"".$urlPred."\" class=\"casti\"> předchozí (".($part-1)."/".$pocetStran.")</a></div>";
        } 
      }
    //$vypisStred = "<div class=\"stred\">&nbsp;</div>";
    $vypisStred = "<div class=\"stred\">Přejít na <select name=\"part".$misto."\">";
    for($zac = 1;$zac <= $pocetStran;$zac++){
      $selected = "";
      if($zac == $part){
        $selected = "selected=\"selected\"";
        }
      $vypisStred .= "  <option value=\"".$zac."\" ".$selected.">".$zac."</option>";
      }
    $vypisStred .= "</select><input type=\"submit\" name=\"akce".$misto."\" value=\"prechod\"/></div>";
    $vypis .= "  <tr>";
    $vypis .= "    <td colspan=\"".$levaprava."\"><div class=\"strankovac\">".$vypisLeva.$vypisStred.$vypisPrava."</div></td>";
    //$vypis .= "    <td colspan=\"".."\" align=\"right\"></td>";
    $vypis .= "  </tr>";    
    $vypis = str_replace("&","&amp;",$vypis);
    $vypis = str_replace("&amp;nbsp;","&nbsp;",$vypis);
    }
  return $vypis;
  }
function upravLink($url,$parametrStary,$parametrNovy){
  $url = str_replace("&amp;","&",$url)."&&&";
  $pokus = preg_match( '/(.*)(\?|&)'.$parametrStary.'=[^&]+?(&)(.*)/i' , $url );
  if($pokus){
    $urlNove = "".preg_replace('/(.*)(\?|&)'.$parametrStary.'=[^&]+?(&)(.*)/i', '$1$2'.$parametrStary.'='.$parametrNovy.'&$4', $url . '');
    }
  else{
    $urlNove = $url;
    }
  $urlNove = str_replace("&&&","",$urlNove);
  $url = str_replace("&","&amp;",$urlNove);
  return $urlNove;
  }
function dvojiteRazeni($levaprava,$order,$by,$order2,$by2,$razeni,$pocetZobrazeni,$kolik){
  if(empty($pocetZobrazeni)){
    $pocetZobrazeni = pocetZobrazeni();
    }
  $vypis = "    <tr><td colspan=\"".$levaprava."\">";
  $vypis .= "      <span>Nejdříve řadit podle <select name=\"order\">";
  foreach($razeni as $key => $value ){
    if(!empty($value['nazev'])){
      $selected = "";
      if($order == $key){
        $selected = "selected=\"selected\"";
        }
      $vypis .= "<option value=\"".$key."\" ".$selected.">".$value['nazev']."</option>\n";
      }
    }
  $vypis .= "    </select></span>";
  $vypis .= "      <span><select name=\"by\">";
  $selectedAsc = "selected=\"selected\"";
  $selectedDesc = "";
  if(strtolower($by) == "desc"){
    $selectedDesc = "selected=\"selected\"";
    $selectedAsc = "";
    }
  $vypis .= "<option value=\"asc\" ".$selectedAsc.">od A do Z</option>\n";
  $vypis .= "<option value=\"desc\" ".$selectedDesc.">od Z do A</option>\n";
 
  $vypis .= "    </select></span>";
  $vypis .= "      <span>poté řadit podle <select name=\"order2\">";
  $vypis .= "        <option value=\"-1\">&lt;nerozhoduje&gt;</option>";
  foreach($razeni as $key => $value ){
    if(!empty($value['nazev'])){
      $selected = "";
      if($order2 == $key){
        $selected = "selected=\"selected\"";
        }
      $vypis .= "<option value=\"".$key."\" ".$selected.">".$value['nazev']."</option>\n";
      }
    }
  $vypis .= "    </select></span>";
  $vypis .= "      <span>Seřadit podle <select name=\"by2\">";
  $selectedAsc = "selected=\"selected\"";
  $selectedDesc = "";
  if($by2 == "desc"){
    $selectedDesc = "selected=\"selected\"";
    $selectedAsc = "";
    }
  $vypis .= "<option value=\"asc\" ".$selectedAsc.">od A do Z</option>\n";
  $vypis .= "<option value=\"desc\" ".$selectedDesc.">od Z do A</option>\n";
  $vypis .= "    </select></span>";
  $vypis .= "      <span>zobrazit počet položek <select name=\"kolik\">";
  //$vypis .= "        <option value=\"0\">&lt;Výchozí&gt;</option>";
  if(sizeof($pocetZobrazeni)>0){
    foreach($pocetZobrazeni as $key => $value ){
      $selected = "";
      if($kolik == $key){
        $selected = "selected=\"selected\"";
        }
      $vypis .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
      }
    }
  $vypis .= "    </select></span>";
  $vypis .= "    <span><input type=\"submit\" name=\"akce\" value=\"nastavit\" /></span></td></tr>";
  return $vypis;
  }
/******************************************************
 ******************************************************
 ******************************************************/
function exportMysqlToCsv($filename = 'export.csv',$dotaz,$spojeni,$prvniRadek = null,$poleUprava = array()){
  $typPlatby = array();
  $typPlatby[0] = "Hotově";
  $typPlatby[1] = "Kartou";
  $typPlatby[2] = "Fakturou";
  /**********/
  $csv_terminated = "\n";
  //$csv_separator = ",";
  $csv_separator = ";";
  $csv_enclosed = '"';
  $csv_escaped = "\\";                          
  //$sql_query = "select kod from kody ".$omezeni.";";
  $sql_query = $dotaz;
  // Gets the data from the database       
  $result = mysql_query($sql_query,$spojeni);
  //$result = $dotaz;
  $fields_cnt = mysql_num_fields($result);
  

  $schema_insert = '';
  if(!empty($prvniRadek)){
    
    $schema_insert = $prvniRadek.$csv_terminated;
    $schema_insert = str_replace("\n",$csv_terminated,$schema_insert); 
    }

  for ($i = 0; $i < $fields_cnt; $i++){
    $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;
    $schema_insert .= $l;
    $schema_insert .= $csv_separator;
    } // end for
  $out = trim(substr($schema_insert, 0, -1));
  $out .= $csv_terminated;
  // Format the data
  while ($row = mysql_fetch_array($result)){
    $schema_insert = '';
    $klice = array_keys($row);
    for ($j = 0; $j < $fields_cnt; $j++){
      $klic = $klice[(($j*2)+1)];
      if ($row[$j] == '0' || $row[$j] != ''){
        $vysledek = $row[$j];
        if(array_key_exists($klic,$poleUprava)){
          if($poleUprava[$klic]=="cena"){
            //$vysledek = cena($vysledek);
            $vysledek = str_replace(".",",",$vysledek);
            }
          elseif($poleUprava[$klic] == "cas"){
            $vysledek = date("H:i d.m.Y",$vysledek);
            }  
          elseif($poleUprava[$klic] == "typplatby"){
            $vysledek = $typPlatby[$vysledek];
            } 
          elseif($poleUprava[$klic] == "procenta"){
            $vysledek = cena($vysledek)."%";
            }  
          }  
        if ($csv_enclosed == ''){
          $schema_insert .= $vysledek; 
          } 
        else{
          $schema_insert .= $csv_enclosed .str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $vysledek) . $csv_enclosed;
          }
        }
      else{
        $schema_insert .= '';
        }

      if ($j < $fields_cnt - 1){
        $schema_insert .= $csv_separator;
        }
      } // end for
    $out .= $schema_insert;
    $out .= $csv_terminated;
    } // end while
    
    
  //$out = str_replace(".",",",$out); // změna . => ,
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Length: " . strlen($out));
  // Output to browser with appropriate mime type, you choose ;)
  ///header("Content-type: text/x-csv");
  //header("Content-type: text/csv");
  //header("Content-type: application/csv");
  //header("Content-type: application/vnd.ms-excel");
  header("Content-type: text/x-comma-separated-values");
  header("Content-disposition: csv" . date("Y-m-d") . ".csv");
  header("Content-Disposition: attachment; filename=$filename");
  echo $out;
  exit;
  }
/**********************************/
/**********************************/
function rozdelKalendrar($text){
  if(!empty($text)){
    $cast1 = explode(".", $text);   //0-d;1-m;2-zbytek(Y H:i)
    $cast2 = explode(" ", $cast1[2]); //0-Y;1-zbytek(H:i)
    $cast3 = explode(":", $cast2[1]); //0-H;1-i,2-s
    if(strlen($cast1[0]) == 1){
      $cast1[0] = "0".$cast1[0]; 
      }
    if(strlen($cast1[1]) == 1){
      $cast1[1] = "0".$cast1[1]; 
      }
    if(strlen($cast3[0]) == 1){
      $cast3[0] = "0".$cast3[0]; 
      }
    if(strlen($cast3[1]) == 1){
      $cast3[1] = "0".$cast3[1]; 
      }
    if((empty($cast3[2]))||(!isSet($cast3[2]))){
      $cast3[2] = "00";
      }
    elseif(strlen($cast3[2]) == 1){
      $cast3[2] = "0".$cast3[2]; 
      }
    return $cast2[0].$cast1[1].$cast1[0].$cast3[0].$cast3[1].$cast3[2];
    }
  else{
    return $text;
    }
  }
function rozdelKalendar($text){
  return rozdelKalendrar($text);
  }
  /*
function timestamptodatum($timestamp){
  $year=substr($timestamp,0,4);
  $month=substr($timestamp,4,2);
  $day=substr($timestamp,6,2);
  $hour=substr($timestamp,8,2);
  $minute=substr($timestamp,10,2);
  $second=substr($timestamp,12,2);
  return $hour.":".$minute.":".$second." ".$day.".".$month.".".$year;
  }                  */
/*********************************/
/*
function datetimetodate($cas){
  $year=substr($cas,0,4);
  $month=substr($cas,5,2);
  $day=substr($cas,8,2);
  $hour=substr($cas,11,2);
  $minute=substr($cas,14,2);
  $second=substr($cas,17,2);
  $novycas=$hour.":".$minute.":".$second." ".$day.".".$month.".".$year;
  return $novycas;
  }*/ 
function printRko($pole){
  echo "<pre>";
  print_r($pole);
  echo "</pre>";
  }
function timestamptodatum($timestamp,$format = "H:i:s d.m.Y"){
  if ($timestamp == '00000000000000' || !$timestamp) return false;
  $cas = strtotime($timestamp);
	return date($format,$cas);
  }
function datetimeToDate($date,$format = "H:i:s d.m.Y"){
	if ($date == '0000-00-00 00:00:00' || !$date) return false;
	$cas = strtotime($date);
	return date($format,$cas);
  }
function ted($format = "H:i:s d.m.Y"){
  return date($format);
  }
function dny($cislo){
  $dny = array();
  $dny[1] = "Pondělí";
  $dny[2] = "Úterý";
  $dny[3] = "Středa";
  $dny[4] = "Čtvrtek";
  $dny[5] = "Pátek";
  $dny[6] = "Sobota";
  $dny[0] = "Neděle";
  return $dny[$cislo];
  }
function overPort($server, $port, $name) { 
  $vrat['vysledek'] = false;  
  preg_match("/^(http:\/\/)?([^\/]+)/i", $server, $match); 
  $host = $match[2]; 
  preg_match_all("/\.([^\.\/]+)/",$host, $match); 
  
  $matches[0][0] = $matches[1][0]; 
  $host = trim($host); 
  
  $socket = ""; 
  @$socket = fsockopen($host, $port, $errno, $errstr, 2); 
  if(!$socket) { 
    $vrat['text'] = "\n<br /><font color=\"red\">No responce from ".$name." ".$host."!</font>"; 
    } 
  else { 
    fclose($socket); 
    $vrat['text'] = "\n<br /><font color=\"green\"> ".$name." ".$host." OK!</font>";
    $vrat['vysledek'] = true; 
    } 
  return $vrat;
  } 
/**********
 **********/
function ifnull($text){
  $vrat = $text;
  if(empty($text)){
    $vrat = 0;
    }
  return $vrat;
  }
function emptyVar(&$promenna,$nova){
  if(empty($promenna)){
    $promenna = $nova;
    }
  }

function csrf_token($s) {
  $hidden = "<div style='display: none;'><input type='hidden' name='csrf_token' value='$_SESSION[csrf_token]' /></div>";
  return preg_replace('~<form\\s+(?![^>]*\\baction=[\'"]?(?:https?:|//))[^>]*\\bmethod=[\'"]?post[^>]*>~i', "\\0\n$hidden", $s);
  }

function csrf_protection() {
  if (session_id()) {
    if (!isset($_SESSION["csrf_token"])) {
      $_SESSION["csrf_token"] = rand(1, 1e9);
      }
    ob_start('csrf_token');
    if ($_POST && post("csrf_token") != $_SESSION["csrf_token"]) {
      return false;
      }
    }
  return true;
  }
function delkaUTF($text){
  return strlen(utf8_decode($text));
  }
?>
