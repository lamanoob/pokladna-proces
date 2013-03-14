<?PHP         
// lokální mysql databáze 
$databaze  = array(
  "server" 	=>	 "localhost",
  "uzivatel"	=>	 "root",
  "heslo" 	=>	   "noob",
  "databaze" 	=>	 "pokladna"
  );

// nastaveni
/***** VŠEOBECNÉ ******/
define("_kasa_",1); //ID KASY V DB
define("_pocetMistUcet_",8); //ID KASY V DB
define("_polovicniNulovani_",1); //po kazdem vlozeni vynulovat polovicni porci?
define("_servisDPH_", 9); //ID servisu
//define("_servis_",21); //% za servis z celkove ceny
//define("_servisDPH_", 0.21); //% za servis z celkove ceny

/***** TISK ************/
define("_tiskarna_", "hp psc 1310 series"); //ID KASY V DB
define("_tiskarnaID_", 1); //ID KASY V DB

define("_tiskPovolen_", 0); //ID KASY V DB
define("_tiskMnoz_", 6); //velikost k vytištění množství
define("_tiskText_", 25); //velikost k vytištění textu
define("_tiskCena_", 8); //velikost k vytištění množství
define("_tiskRadek_", 42); //počet sloupců v tiskárně
define("_tiskZnaku_", 15); //
define("_tiskHlavicka_", "hlavicka"); //náhladní text hlavičky účtu
define("_tiskPaticka_", "paticka"); //náhladní text patičky účtu
define("_tiskSuma_", "Součet: / Sum:"); //text 
define("_tiskPlatba_", "K PLATBĚ: / TO PAY:"); //text
define("_tiskZpusob_", "zpusob platby / pay style:"); //text
define("_tiskSleva_", "Sleva:"); //text
define("_tiskServis_", "Servis:"); //text
define("_tiskDPHZaklad_", "Zaklad DPH/VAT"); //text
define("_tiskDPH_", "DPH/VAT"); //text
define("_tiskVasUcet_", "VÁŠ ÚČET"); //text
define("_tiskKopie_", "KOPIE"); //text

/***** PŘIHLÁŠENÍ *****/
define("_aktivni_", (30*60)); // doba uložení před vymazáním v sekundách (sekundy * minuty * hodiny * dny) příklad na 20 dn.(60 * 60)
define("_vychoziTema_","vychozi");
define("_loginStranka_",1);
define("_loginKod_",1);
define("_loginTrvale_",0);
define("_loginPrava_", "opravneni");
/***** SERVER *****/
define("_server_", $_SERVER['SERVER_NAME']."/"); // název serveru


/***** ZABEZPEČENÍ *****/
define("_klic_","8hJeoU4u-H");
define("_obohacovatHesla_",1); //obohacovat heslo
define("_sifrovaniHesla_",1); //sifrovat heslo
define("_obohacovatSifru_",1); //obohacovat zakodované heslo

/***** ulozeni obednavky *****/ 
/***************************/
/***************************/
/****** START PRÁV *********/
/***************************/         
function pocetZobrazeni(){
  $pole = array();
  $pole[5] = "5"; 
  $pole[10] = "10"; 
  $pole[25] = "25"; 
  $pole[100] = "100"; 
  $pole[250] = "250"; 
  $pole[500] = "500"; 
  $pole[-1] = "Vše"; 
  return $pole;
  }
function platby(){
  $vrat = array();
  $vrat[0] = "Hotově";
  $vrat[1] = "Kartou";
  $vrat[2] = "Fakturou";
  return $vrat;
  }
function platbyUcet($vyber = null){
  $vrat = null;
  $vrat[0] = "hotově / cash";
  $vrat[1] = "kartou / card";
  $vrat[2] = "faktura / invoice";
  if(empty($vyber)){
    $vyber = 0; 
    }              
  return $vrat[$vyber];;
  }
function tiskarny(){
  $vrat = array();
  $vrat[1] = "hp psc 1310 series"; 
  $vrat[2] = "hp psc 1310 series"; 
  $vrat[3] = "hp psc 1310 series"; 
  return $vrat;
  }
$pravaUziv = array(
  1 => "Povolit přihlášení",
  2 => "Vyžadovat kartu",
  3 => "Otevření účtu",
  4 => "Přidávání položek",
  5 => "Mazání položek",
  6 => "Storno položek",
  7 => "Uzavření účtu",
  8 => "Změny účtu",
  9 => "Dotisk",
  10 => "Otevření zásuvky",
  11 => "Mezisoučet",
  12 => "Ručně zadávat slevu",
  13 => "Přepsat max slevu",
  14 => "Markovat odpisové položky",
  15 => "Manipulace se spec. účty",
  16 => "Po namarkování zůstat v objednávkách",
  17 => "Rozdělení účtu",
  18 => "Sloučení účtu",
  19 => "Změna stolu po uzavření",  
  20 => "Minimalizace kasy",         
  21 => "Přesun položek mezi účty"  
  //p8 => "Viditelný na kase",
  );
/*******************
 *******************
 *******************/
$pocetZobrazeni = array();
$pocetZobrazeni['0'] = "&lt;Výchozí&gt;";
$pocetZobrazeni['5'] = "5";
$pocetZobrazeni['25'] = "25";
$pocetZobrazeni['50'] = "50";
$pocetZobrazeni['100'] = "100";
$pocetZobrazeni['250'] = "250";
$pocetZobrazeni['500'] = "500";
$pocetZobrazeni['-1'] = "Vše";
/**************************
 **************************
 **************************/
function tiskove(){
  $tiskove = array();
  $tiskove['cislo_uctu']['text'] = "Účet";
  $tiskove['cislo_uctu']['delka'] = 4;
  $tiskove['cas_zalozeni']['text'] = "Čas založení";
  $tiskove['cas_zalozeni']['delka'] = 12;
  $tiskove['zalozil']['text'] = "Založil";
  $tiskove['zalozil']['delka'] = 7;
  $tiskove['kasa']['text'] = "Pokladna";
  $tiskove['kasa']['delka'] = 8;
  $tiskove['stul']['text'] = "Stůl";
  $tiskove['stul']['delka'] = 4;
  $tiskove['vlozil']['text'] = "Vložil";
  $tiskove['vlozil']['delka'] = 6;
  $tiskove['cas_vlozeni']['text'] = "Čas vložení";
  $tiskove['cas_vlozeni']['delka'] = 11;
  return $tiskove;
  }
function tiskovePlatba(){
  $tiskove = array();
  $tiskove['cas_uzavreni']['text'] = "Datum";
  $tiskove['stul']['text'] = "Stůl";
  $tiskove['uzavrel']['text'] = "Obsluha";
  $tiskove['cislo_uctu']['text'] = "Účet";
  return $tiskove;
  }
function tiskoveUzavrene(){
  $tiskove = array();
  $tiskove['cas_uzavreni']['text'] = "Čas uzavření";
  $tiskove['cas_zalozeni']['text'] = "Čas založení";
  $tiskove['cislo_uctu']['text'] = "Účet";
  $tiskove['zalozil']['text'] = "Založil";
  $tiskove['kasazalozeni']['text'] = "Pokladna založení";
  $tiskove['uzavrel']['text'] = "Uzavřel";
  $tiskove['kasauzavreni']['text'] = "Pokladna uzavření";
  $tiskove['stul']['text'] = "Stůl";
  return $tiskove;
  }
?>