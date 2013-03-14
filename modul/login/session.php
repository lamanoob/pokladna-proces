<?PHP
function pridejSess($nick, $klic){
  $_SESSION['nick'] = $nick;
  $_SESSION['klic'] = $klic;
  $_SESSION['cas'] = time();
  }
function upravCasSess(){
  $_SESSION['cas'] = time();
  }
function smazSess(){
  //session_unset();
  //session_destroy();
  }

function sessPrava(){
  $_SESSION['prava'] = time();
  }
function pridejDalsiSess($nazev,$hodnota = null){
  $_SESSION[$nazev] = $hodnota;
  }
?>