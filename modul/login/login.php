<?PHP
//require_once('../modul/log/log.php'); // modul vytvářející logů v DB
require_once('session.php'); //modul usnadňující práci se session
require_once('user.php');
/* NASTAVENÍ PROMĚNÝCH */
define("_loginID_", "id");
define("_loginNick_", 'username');
/* LOGOUT */
$lastUser = "";
$logout1 = get('logout'); 
$restart = get('restartovat');
if($restart == 1){
  session_unset();
  session_destroy();
  session_start();
  }
if($logout1 == "logout"){
  $lastUser = $_SESSION['nick'];
  $_SESSION['ok']=false;
  $_SESSION['nick'] = "";
  
  //session_unset(); 
  //session_destroy();
  //session_start();
  $output = htmlspecialchars($_SERVER["QUERY_STRING"],ENT_QUOTES);
  $output = str_replace("&amp;logout=logout", "", $output);
  $output = str_replace("&logout=logout", "", $output);
  $output = str_replace("?logout=logout", "", $output);
  $output = str_replace("logout=logout", "", $output);
  if(strlen($output)>0){
    $output = "?".$output;
    }
  if(!empty($lastUser)){
    $_SESSION['lastnick'] = $lastUser;
    }
  header("Location: ".$_SERVER['PHP_SELF'].htmlspecialchars_decode($output));
  exit;
  }
/* FUNKCE */
function getLogout($vDivu = true, $class = null){
  $kVraceni = "";
  $vypisTridu = "";
  if(!empty($class)){
    $vypisTridu = "class=\"".$class."\"";
    }
  if($vDivu){
    $kVraceni .= "<div class=\"logout\">";
    }
  $url = $vlozka = "";
  if((!defined("_odhlaseniVynulovani_"))||(!_odhlaseniVynulovani_)){
    $url = htmlspecialchars($_SERVER["QUERY_STRING"],ENT_QUOTES);
    if(strlen($url)>0){
      $vlozka = "&amp;";
      }
    }
  $kVraceni .= "<a href=\"".$_SERVER['PHP_SELF']."?".$url.$vlozka."logout=logout\" ".$vypisTridu.">Odhlásit: </a>";
  if($vDivu){
    $kVraceni .= "</div>";
    }
  return $kVraceni;
  }

  
function vymazKlice($spojeni){
  $cas = time() - _aktivni_;
  //echo "UPDATE uzivatel SET prihlasen = NULL WHERE UNIX_TIMESTAMP(prihlasen) < ".$cas.";<br />";
  mysql_query("UPDATE uzivatel SET prihlasen = NULL WHERE UNIX_TIMESTAMP(prihlasen) < ".$cas.";",$spojeni);
  }  
  
function login(&$uzivatel, &$spojeni ){
  $kVraceni['stav'] = $pokracuj = false;
  $kVraceni['chyba'] = null;
  //printrko($_POST);
  /* vymazání zastaralých klíčů */
  vymazKlice($spojeni);
  /* ZJIŠTĚNÍ EXISTENCE PŘIHLAŠOVACÍCH ÚDAJŮ */
  $nick = post('nick');
  if(_loginKod_){
    $nick = "ok";
    }
  $heslo = post('heslo');
  //echo "N:".$nick.";H:".$heslo.";<br />";
  if((!empty($nick))&&(!empty($heslo))){
    if(!_loginKod_){
      //echo "SELECT * FROM uzivatel WHERE "._loginNick_." LIKE '".$nick."' LIMIT 1;<br />";
      $nickNacet = query("SELECT * FROM uzivatel WHERE "._loginNick_." LIKE '".$nick."' LIMIT 1;",$spojeni);
      /******/
      if(_obohacovatHesla_){
        $delka = strlen($heslo);
        $delka1 = ceil($delka/2);
        $heslo1a = substr($heslo, 0, $delka1); // returns "d"
        $heslo1b = substr($heslo, $delka1, ($delka - $delka1)); // returns "d" 
        $heslo = "-U".$heslo1a."Gu".$heslo1b."8.";
        }
      if(_sifrovaniHesla_){
        $heslo = hash("sha512",$heslo); 
        }
      $ulozeneHeslo = $nickVyber['heslo']; 
      if(_obohacovatSifru_ && _sifrovaniHesla_){
        $ulozeneHesloA = substr($ulozeneHeslo, 0, 11);
        $ulozeneHesloB = substr($ulozeneHeslo, 43, (39-11));   
        $ulozeneHesloC = substr($ulozeneHeslo, 103, (96-39));  
        $ulozeneHesloD = substr($ulozeneHeslo, 192, (105-96));     
        $ulozeneHesloE = substr($ulozeneHeslo, 233, (128-105));       
        $ulozeneHeslo = $ulozeneHesloA.$ulozeneHesloB.$ulozeneHesloC.$ulozeneHesloD.$ulozeneHesloE;
        }
      if((($heslo == $ulozeneHeslo)&&(!$nickVyber['blokovan']))){
        $pokracuj = true;
        }
      /******/
      }
    else{
      //echo "SELECT * FROM uzivatel WHERE kod_karty LIKE '".$heslo."' LIMIT 1;<br />";//exit; 
      $nickNacet = query("SELECT * FROM uzivatel WHERE kod_karty LIKE '".$heslo."' LIMIT 1;",$spojeni);
      $pokracuj = true;
      }
    if(existujeVDB($nickNacet)){
      $nickVyber = fetch($nickNacet);
      $nick = $nickVyber[_loginNick_];
      if($pokracuj){
        /****************/
        $porad = post('porad');
        $id = $nickVyber[_loginID_]; 
        $klic = nahodny_retezec(128,"",false);
        $kod = nahodny_retezec(128,"",false); 
        /****************/
        //echo "UPDATE uzivatel SET klic = '".$klic."', prihlasen = '".date("Y-m-d H:i:s",time())."' WHERE "._loginID_." = '".$id."' LIMIT 1;<br />";//exit;
        $pridejKlic = query("UPDATE uzivatel SET klic = '".$klic."', prihlasen = '".date("Y-m-d H:i:s",time())."' WHERE "._loginID_." = '".$id."' LIMIT 1;",$spojeni);
        if($pridejKlic){
          if(_loginKod_){           
            $nick = $nickVyber[_loginNick_];
            }
          $uzivatel->loginUser($id, $nick, $nickVyber['opravneni'], $nickVyber['admin']);
          sess("nick",$nick);
          sess("kod",$kod);
          sess("klic",$klic);
          sess("ad",$uzivatel->getAD());
          sess("ok",true);
          sess("porad",$porad);   
          sess("posledni", time());          
          }
        /****************/
        }
      }
    else{
      $lastNick = sess('lastnick');
      //if(empty($lastNick)){
        $kVraceni['chyba'] = "Neexistující uživatel nebo chybné heslo!";
        //}
      }
    }
  else{
    //$kod = sess('kod');                   
    $klic = sess('klic');
    $ok = sess('ok');                   
    $ad = sess('ad');
    $porad = sess('porad');
    if(!empty($klic)){
      //echo "SELECT * FROM uzivatel WHERE klic LIKE '".$klic."' LIMIT 1;<br />"; 
      $nickSessNacet = query("SELECT * FROM uzivatel WHERE klic LIKE '".$klic."' LIMIT 1;",$spojeni);
      if((existujeVDB($nickSessNacet))&&($ok)){
        $nickSessVyber = fetch($nickSessNacet);
        if(($klic == $nickSessVyber['klic'])&&($nickSessVyber['blokovan']==0)){
          $cas = strtotime($nickSessVyber['prihlasen']);
          if(($cas > (time()-_aktivni_))||($porad == "on")){
            //echo "UPDATE uzivatel SET prihlasen = '".date("Y-m-d H:i:s",time())."' WHERE "._loginID_." = '".$nickSessVyber[_loginID_]."' LIMIT 1;<br />";exit;
            $pridejKlic = query("UPDATE uzivatel SET prihlasen = '".date("Y-m-d H:i:s",time())."' WHERE "._loginID_." = '".$nickSessVyber[_loginID_]."' LIMIT 1;",$spojeni);
            $uzivatel->loginUser($nickSessVyber[_loginID_], $nickSessVyber[_loginNick_], $nickSessVyber['opravneni'], $nickSessVyber['admin']);
            sess("posledni", time());
            }
          else{
            $kVraceni['chyba'] = "Z důvodu neaktivity jste byli automaticky odhlášení.Prosím přihlaste se znovu."; 
            }
          }
        else{
          $kVraceni['chyba'] = "Nebylo povoleno Vám přihlásit se.Pokuste se znovu přihlásit a případně kontaktujte administrátora.";
          }
        }
      else{
        $lastNick = sess('lastnick');
        if(empty($lastNick)){
          $kVraceni['chyba'] = "Neexistující uživatel nebo chybné heslo!";
          }
        }
      }
    else{
      
      }
    }
  return $kVraceni;
  } 
?>