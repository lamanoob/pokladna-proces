<?PHP
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
require_once("cfg/nastaveni.php");
session_start();
require_once("cfg/cfg.php");
require_once("cfg/pripojeni.php");
require_once("fce/fce.php");    
require_once("modul/login/login.php");

define("_csrf_ok_",csrf_protection());


/*********/
$page = predat('page');
$akce = predat('akce');
$part = predat('part');
$kolik = predat('kolik');
$order = predat('order');
$by = predat('by');
$order2 = predat('order2');
$by2 = predat('by2');
$vyhledat = predat('vyhledat');
$vysledek = predat('vysledek');
$tema = predat('tema');
if((empty($tema))||(!file_exists("tema/".$tema))){
  $tema = _vychoziTema_;
  }
define("_tema_","tema/".$tema."/");
/***/
emptyVar($by,"ASC");
emptyVar($by2,"ASC");
//emptyVar($kolik,_kolikKZobrazeni_);
/*********/

$lokalniOK = sess('ok');
$uzivatel = new User();
$vysledekLogin = login($uzivatel, $spojeni);
//define("_loginChyba_",$vysledekLogin['chyba']);
if(($uzivatel->getPrihlasen())&&($uzivatel->getPravo(1))){
  include_once(_tema_."index.php");
  }
else{
  
  include_once(_tema_.'login/loginform.php');
  }
?>