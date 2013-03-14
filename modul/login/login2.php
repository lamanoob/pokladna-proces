<?PHP
//require_once('../modul/log/log.php'); // modul vytvářející logů v DB
require_once('session.php'); //modul usnadňující práci se session
require_once('user.php');
/* NASTAVENÍ PROMĚNÝCH */
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
  $vlozka = "";
  $url = htmlspecialchars($_SERVER["QUERY_STRING"],ENT_QUOTES);
  if(strlen($url)>0){
    $vlozka = "&amp;";
    }
  $kVraceni .= "<a href=\"".$_SERVER['PHP_SELF']."?".$url.$vlozka."logout=logout\" ".$vypisTridu.">Odhlásit: </a>";
  if($vDivu){
    $kVraceni .= "</div>";
    }
  return $kVraceni;
  }

  
function vymazKlice($spojeni){
  $cas = time() - _aktivni_;
  //echo "<br />UPDATE prihlaseni SET klic = NULL WHERE UNIX_TIMESTAMP(cas) < ".$cas.";<br />";
  mysql_query("UPDATE prihlaseni SET klic = NULL WHERE UNIX_TIMESTAMP(cas) < ".$cas.";",$spojeni);
  }  
  
function login(&$uzivatel, &$spojeni ){
  $kVraceni = false;
  /* vymazání zastaralých klíčů */
  vymazKlice($spojeni);
  /* ZJIŠTĚNÍ EXISTENCE PŘIHLAŠOVACÍCH ÚDAJŮ */
  $nick = post('nick');
  $heslo = post('heslo');
  if((!empty($nick))&&(!empty($heslo))){
    //echo "SELECT * FROM uzivatel WHERE nick LIKE '".$nick."' LIMIT 1;<br />";//exit; 
    $nickNacet = query("SELECT * FROM uzivatel WHERE nick LIKE '".$nick."' LIMIT 1;",$spojeni);
    if(existujeVDB($nickNacet)){
      $nickVyber = fetch($nickNacet);
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
      /******/
      //echo "POR:<br />".$heslo."<br />".$ulozeneHeslo.";<br />";
      if(($heslo == $ulozeneHeslo)&&(!$nickVyber['blokovan'])){
        /****************/
        $porad = post('porad');
        $id = $nickVyber['ID_uziv']; 
        $klic = nahodny_retezec(128,"",false);
        $kod = nahodny_retezec(128,"",false); 
        /****************/
        //echo "UPDATE uzivatel SET klic = '".$klic."', kod = '".$kod."', cas = '".date("Y-m-d H:i:s",time())."' WHERE ID_uziv = '".$id."' LIMIT 1;<br />";exit;
        $pridejKlic = query("UPDATE uzivatel SET klic = '".$klic."', kod = '".$kod."', cas = '".date("Y-m-d H:i:s",time())."' WHERE ID_uziv = '".$id."' LIMIT 1;",$spojeni);
        if($pridejKlic){
          $uzivatel->loginUser($id, $nick, $nickVyber['prava'], $nickVyber['admin']); 
          sess("kod",$kod);
          sess("klic",$klic);
          sess("ad",$uzivatel->getAD());
          sess("ok",true);
          sess("porad",$porad);
          }
        /****************/
        }
      }
    else{
      //echo "bbb";
      }
    }
  else{
    $kod = sess('kod');                   
    $klic = sess('klic');
    $ok = sess('ok');                   
    $ad = sess('ad');
    $porad = sess('porad');
    //echo "SELECT * FROM uzivatel WHERE kod LIKE '".$kod."' LIMIT 1;<br />"; 
    $nickSessNacet = query("SELECT * FROM uzivatel WHERE kod LIKE '".$kod."' LIMIT 1;",$spojeni);
    if((existujeVDB($nickSessNacet))&&($ok)){
      $nickSessVyber = fetch($nickSessNacet);
      if(($klic == $nickSessVyber['klic'])&&($nickSessVyber['blokovan']==0)){
        $cas = strtotime($nickSessVyber['cas']);
        if(($cas > (time()-_aktivni_))||($porad == "on")){
          //echo "UPDATE uzivatel SET cas = '".date("Y-m-d H:i:s",time())."' WHERE ID_uziv = '".$nickSessVyber['ID_uziv']."' LIMIT 1;<br />";exit;
          $pridejKlic = query("UPDATE uzivatel SET cas = '".date("Y-m-d H:i:s",time())."' WHERE ID_uziv = '".$nickSessVyber['ID_uziv']."' LIMIT 1;",$spojeni);
          $uzivatel->loginUser($nickSessVyber['ID_uziv'], $nickSessVyber['nick'], $nickSessVyber['prava'], $nickSessVyber['admin']);
          }
        else{
          }
        }
      }
    else{
      //echo "bbb";
      }
    //echo "ccc";
    }
  return $kVraceni;
  } 
?>