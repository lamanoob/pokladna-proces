<?PHP         
function zkontrolujLog($spojeniMaster){
  $kVraceni = false;
  $prikaz = MySQL_Query("SELECT * FROM log;",$spojeniMaster);
  if($prikaz){
    $kVraceni = true;
    }
  return $kVraceni;
  }

function pridejLog($spojeni, $databaze, $uzivatel, $akce, $tabulka, $polozka = NULL, $co = NULL, $zpet = NULL, $text = NULL){
  $dbnazev = "log";
  //echo "zpet:".$zpet."-<br />";exit;
  //echo "nick::".$uzivatel->getUser()."-<br />";exit;
  if((!empty($polozka))&&(!is_numeric($polozka))){
    //$co = "--".$polozka."--".$co;
    $polozka = 0;
    }
  if(is_array($databaze)){
    $db['id'] = $databaze->getID();
    $db['nazev'] = $databaze->getNazev();
    }
  elseif(is_object($databaze)){
    $db['id'] = $databaze->getID();
    $db['nazev'] = $databaze->getNazev();
    }
  else{
    //echo "1: INSERT INTO `".$dbnazev."` (id_db, nazev, id_user, nick, kdy, akce, tabulka, polozka, co, zpet) VALUES ('".$databaze->getID()."', '".$databaze->getNazev()."', '".$uzivatel->getIDUser()."', '".$uzivatel->getUser()."', NOW(), '".nahradCarkuVLogu($akce)."', '".nahradCarkuVLogu($tabulka)."', '".$polozka."', '".nahradCarkuVLogu($co)."', '".nahradCarkuVLogu($zpet)."');<br />";exit;
    //query("INSERT INTO `".$dbnazev."` (id_db, nazev, id_user, nick, kdy, akce, tabulka, polozka, co, zpet) VALUES ('0', '---', '".$uzivatel->getIDUser()."', '".$uzivatel->getUser()."', NOW(), '".nahradCarkuVLogu($akce)."', '".nahradCarkuVLogu($tabulka)."', '".$polozka."', '".nahradCarkuVLogu($co)."', '".nahradCarkuVLogu($zpet)."');",$spojeni);
    echo $databaze."<br />";
    echo"chyba logu databaze...";
    exit;
    }
  if(!empty($databaze)){
    
    }
  $nazevAdr = "";
  if(!empty($_SERVER['REMOTE_HOST'])){
    $nazevAdr = "(".$_SERVER['REMOTE_HOST'].")";
    }
  //echo "INSERT INTO `".$dbnazev."` (id_db, nazev, id_user, nick, kdy, akce, tabulka, polozka, co, zpet) VALUES ('".$databaze->getID()."', '".$databaze->getNazev()."', '".$uzivatel->getIDUser()."', '".$uzivatel->getUser()."', NOW(), '".nahradCarkuVLogu($akce)."', '".nahradCarkuVLogu($tabulka)."', '".$polozka."', '".nahradCarkuVLogu($co)."', '".nahradCarkuVLogu($zpet)."');";exit;
  $prikaz = "INSERT INTO `".$dbnazev."` (id_db, nazev, id_user, nick, kdy, akce, tabulka, polozka, co, zpet, ip, text) VALUES ('".$db['id']."', '".$db['nazev']."', '".$uzivatel->getIDUser()."', '".$uzivatel->getUser()."', NOW(), '".nahradCarkuVLogu($akce)."', '".nahradCarkuVLogu($tabulka)."', '".$polozka."', '".nahradCarkuVLogu($co)."', '".nahradCarkuVLogu($zpet)."','".$nazevAdr.$_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT']."', '".nahradCarkuVLogu($text)."');"; 
  if(!empty($spojeni)){
    //echo "-1;";exit;
    query($prikaz,$spojeni);
    }
  else{
    //echo "-2;";exit;
    query($prikaz);
    }
  }

function nahradCarkuVLogu($text){
  // Provides: You should eat pizza, beer, and ice cream every day
  $pred = array("'");
  $po   = array("\'");
  $novyText  = str_replace($pred, $po, $text);
  return $novyText;
  }
?>