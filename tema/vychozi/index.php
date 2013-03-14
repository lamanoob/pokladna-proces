<?PHP  
/* OVĚŘENÍ ZDA JE UŽIVATEL PŘIHLÁŠEN */
require_once('nastaveni.php');
require_once('hlavicky.php');
if($uzivatel->getPrihlasen()){
  //printrko($uzivatel);exit;
  $nacteno = false;
  $stul = get('stul');
  $ucet = get('ucet');
    
  if(file_exists('modul/pokladna/index.php')){
    require_once('modul/pokladna/index.php');
    $nacteno = true;
    }
  else{
    }
  if($page == "novyucet"){
    //založení nového účtu na stole po vyberu objednavky...
    $stul = get('stul');
    // vytvoreni noveho uctu
  
    }
  elseif(($page == "zalozit")||($page == "uprava")){
    /***** ZALOZENI NOVEHO UCTU NA STOLE *****/
    $vysledek = 2;
    $velRec = sizeof(post('recid'));
    $velMnoz = sizeof(post('mnozstvi'));
    $velPol = sizeof(post('polovicni'));
    $stul = post('stul');
    $kasa = kasa(_kasa_);
    //(!empty($obj))&&(!empty($rec))
    if((!empty($kasa))&&(($velRec == $velMnoz)&&($velRec == $velPol))){
      if(($uzivatel->getPravo(3))&&($page == "zalozit")){
        $zalozeni = zalozeniUctu($spojeni, $uzivatel, $stul, $kasa);
        $ouid = $zalozeni['id'];
        $cislouctu = $zalozeni['cislouctu'];
        $uprava = $zalozeni['stav'];
        }
      else{
        $uprava = true;
        $ouid = post('ucet');
        }
      //echo "UP:".$uprava."; ouid:".$ouid.";<br />";
      if(($uzivatel->getPravo(4))&&(($uprava) && (!empty($ouid)))){ //vlozeni polozek
        $vlozeno = objednavkaNaUcet($spojeni, $uzivatel, $ouid);
        if($vlozeno['stav']){
          $vysledek = 1;
          }
        if((isset($vlozeno['nevytisteno']))&&($vlozeno['nevytisteno'] > 0)){
          $vysledek = $vysledek."&amp;nevytisteno=";
          }
        }
      }
    //echo "V:".$vysledek.";<br />";exit;
    echo "<meta http-equiv=\"refresh\" content=\"0;url=?akce=".$page."&vysledek=".$vysledek."\">";
    exit;
    }
  elseif($page == "platbadb"){
    $vysledek = 2;
    if($uzivatel->getPravo(7)){
      $vysledek = platbaUctu($spojeni, $uzivatel);
      }
    echo "<meta http-equiv=\"refresh\" content=\"0;url=?akce=".$page."&vysledek=".$vysledek."\">";
    exit;
    }
  elseif(($page == "upravit")||($page == "platbastul")||($page == "zmenazestolu")){
    /***** platba ************************/
    if(!empty($stul)){
      $vyberstolu = true;
      $ucet = get('ucet');
      $stulUctyNacet = stulUcty($stul, $ucet); 
      $stulUcty = $stulUctyNacet['stulUcty'];
      $oup = $stulUctyNacet['oup'];
      $ucet = $stulUctyNacet['ucet']; 
      //exit;
      }
    }
  elseif($page == "zmenanastul"){
    if(!empty($ucet)){
      $vysledek = 2;
      if($uzivatel->getPravo(8)){
        $vysledek = zmenaStoluUctu($spojeni, $uzivatel, $ucet, $stul);
        }
      //echo "V:".$vysledek.";<br />";
      echo "<meta http-equiv=\"refresh\" content=\"0;url=?akce=".$page."&vysledek=".$vysledek."\">";
      exit;
      }
    $page = "";
    }    
  elseif((!empty($ucet))&&($page == "dotisk")){
    $ucet = get('ucet');
    $stulUctyNacet = stulUzavreneUcty($stul, $ucet);
    $stulUcty = $stulUctyNacet['stulUcty'];
    $oup = $stulUctyNacet['oup'];
    $ucet = $stulUctyNacet['ucet'];
    $vysledek = dotisk($spojeni, $uzivatel, $stulUcty, $oup, $ucet);
    echo "<meta http-equiv=\"refresh\" content=\"0;url=?akce=".$page."&vysledek=".$vysledek."\">";
    exit;
    }
  elseif(($page == "typplatby")||($page == "dotisk")){
    /***** platba ************************/
    if(!empty($stul)){
      $vyberstolu = true;
      $ucet = get('ucet');
      $stulUctyNacet = stulUzavreneUcty($stul, $ucet);
      $stulUcty = $stulUctyNacet['stulUcty'];
      $oup = $stulUctyNacet['oup'];
      $ucet = $stulUctyNacet['ucet'];
      unset($stulUctyNacet);
      //printrko($stulUcty); 
      //exit; //aaaa vyber uzavreneho uctu
      }
    }
  elseif(($page == "zmenatypu")){
    $ucet = post('ucet');
    /***** platba ************************/
    if(!empty($ucet)){
      $vysledek = 2;
      if($uzivatel->getPravo(19)){
        $vysledek = zmenaTypuPlatby($spojeni, $uzivatel, $ucet);
        }
      //echo "V:".$vysledek.";<br />";
      echo "<meta http-equiv=\"refresh\" content=\"0;url=?akce=".$page."&vysledek=".$vysledek."\">";
      exit;
      }
    $page = "";
    }
  //akce
  /******************************************************
   ******************************************************
   ******************************************************/
  hlavicka();
  echo " <body class='fullscreen-supported'>\n";
  echo "  <div id='obsah' class='fullscreen-supported'>";
  echo "  <div id='logo'>";
  //echo "    <button onclick='$(document).fullScreen(true);' style='display:none;' id='fullscreensubmit'>Fullscreen whole page</button>";
  echo "    <div id='prihlasen'>Přihlášen: <span class='prihlasen'>".$uzivatel->nick()."</span></div>";
  //echo "    Naposledy přihlášen v ".date("H:i:s d.m.Y")."<div id='hodiny'></div>";
  echo "    <script type='text/javascript'> hodiny('hodiny'); </script>";
  echo "    <script type='text/javascript'>
  function upravasirek(){
    var sirka = $(window).outerWidth();
    var levasirka = 0;
    var pravasirka = 0;
    if($('#leva').is(':visible')){
      levasirka = $('#leva').outerWidth();
      }
    if($('#prava').is(':visible')){
      pravasirka = $('#prava').outerWidth();
      }
    var sirkafinalni = (sirka - levasirka - pravasirka);
    $('#stred').width(sirkafinalni);
    $('#logo').width(sirkafinalni);
    } 
  function upravavysek(){
    var vyska = $(window).outerHeight();
    var vyskahorni = 0;
    var vyskadolni = 0;
    if($('#logo').is(':visible')){
      vyskahorni = $('#logo').outerHeight();
      }
    if($('#dole').is(':visible')){
      vyskadolni = $('#dole').outerHeight();
      }
    var vyskafinalni = (vyska - vyskahorni - vyskadolni);
    $('#stred').height(vyskafinalni);
    //$('#leva').height(vyskafinalni);
    //$('#prava').height(vyskafinalni);
    } 
  
  $(document).ready(function(){
    upravasirek();
    upravavysek();
    if(typeof window.upravacasti == 'function') {
      upravacasti();
      }
    else{
      //alert('calculateSum does not exist');
      }
    });
  $(window).resize(function() {
    upravasirek();
    upravavysek();
    if(typeof window.upravacasti == 'function') {
      upravacasti();
      }
    
  });
      
    </script>";
  echo "</div>\n";
  echo "  <div id='leva'></div>\n";
  echo "  <div id='stred'>\n";
  if(!empty($vysledek)){
    echo "    <div id='vysledek'>\n";
    echo vysledek($akce, $vysledek);
    echo "    </div>\n";
    }
  if((empty($page))||(!$nacteno)){
    $zakladni = zakladni($uzivatel);
    if(!empty($zakladni)){
      foreach($zakladni AS $key => $data){
        if($uzivatel->getPravo($data['pravo'])){
          echo "    <a href='?page=".$key."' class='zakladni'>".$data['text']."</a>\n";
          }
        }
      }
    }
  else{ 
    /***** vyber dalsi cesty *****/  
    $link = linkNazev($page);
    if(!isset($ucet)){ $ucet = null; $oup = null; }
    if(!isset($stulUcty)){ $stulUcty = null; }
                             
    /***/ //aaa pokračovat s výběrem účtu na stole pro objednávku a připravit pro platby a změnu stolu
    //if(((empty($ucet))&&(!empty($stul))&&($page=="upravit"))||((empty($ucet))&&(!empty($stul))&&($page=="platbastul"))){
    if((!empty($stul)) && (empty($ucet))&&(($page=="upravit")||($page=="platbastul"))||(($page == "typplatby")&&($uzivatel->getPravo(19))&&(!empty($stul))&&(empty($ucet)))||(($page == "dotisk")&&(!empty($stul))&&(empty($ucet)))){   
      $uzavrene = null;
      if(($page == "typplatby")||($page == "dotisk")){
        $uzavrene = true;
        }
      ucetNaStole($page, $stul, $stulUcty, $ucet, $uzavrene);
      }
    elseif(($page == "novy")||($page == "platba")||(($page == "objednavka")&&(empty($ucet))&&(empty($stul)))||(($page == "typplatby")&&($uzivatel->getPravo(19))&&(empty($stul))&&(empty($ucet)))||(($page == "dotisk")&&($uzivatel->getPravo(19))&&(empty($stul))&&(empty($ucet)))){
      /**********************************/
      /***** PRECHOD NA VYBER STOLU *****/   
      if(!empty($link)){
        vyberStolu($link);
        }
      else{
        
        }
      }
    elseif((($page == "novyucet")&&($uzivatel->getPravo(3)))||(($page == "upravit")&&(!empty($ucet))&&($uzivatel->getPravo(4)))){
      //echo "UCET:".$ucet.";<br />";exit;
      //printrko($oup);
      objednavka($uzivatel, $stul, $link, $ucet, $stulUcty, $oup);                                                              
      }
    elseif((!empty($ucet))&&(!empty($stul))&&($page == "platbastul")){
      /**********************************/
      /***** PLATBA NA VYBRANEM STOLE ***/
      if(($uzivatel->getPravo(7))||($uzivatel->getPravo(7))){
        //printrko($oup);
        platbaNaStole($uzivatel, $stul, $link, $ucet, $stulUcty, $oup);
        }
      }
    elseif($page == "mezisoucet"){
      
      }
    elseif(($page == "zmena")&&($uzivatel->getPravo(8))){
      echo "<div class='nadpis'>Výběr stolu pro převod mezi stoly</div>";
      vyberStolu($link);
      }                       
    elseif(($page == "zmenazestolu")&&($uzivatel->getPravo(8))){
      if((empty($ucet))){
        echo "<div class='nadpis'>Výběr účtu pro převod mezi stoly</div>";
        ucetNaStole($page, $stul, $stulUcty);
        }
      else{
        $link = linkNazev($page);
        echo "<div class='nadpis'>Výběr stolu, kam převést účet</div>";
        vyberStolu($link, $ucet);
        }
      }
    elseif(($page == "typplatby")&&($uzivatel->getPravo(19))&&(!empty($stul))&&(!empty($ucet))){  
      //echo "aaa<br />"; 
      zmenaPlatby($stulUcty, $ucet);
      
      }
    elseif($page == "dotisk"){
      
      }
    }
  echo "  </div>\n";
  echo "  <div id='prava'>\n";
  echo getLogout(true, 'akce')."\n";
  if(($page=='novyucet')||(($page=='upravit')&&(!empty($ucet)))){
    echo "  <a href=\"javascript:zkontroluj();\" class='akce'>OK</a>\n";
    if($uzivatel->getPravo(5)){
      echo "  <a href=\"javascript:zrusobj();\" class='akce'>Zruš<br />poslední</a>\n";
      }
    else{
      //echo "  <span class='nelzeakce'></span>"; 
      }
    }
  elseif((($page=='platbastul')&&(!empty($ucet)))||(($page=='typplatby')&&(!empty($ucet)))){
    echo "  <a href=\"javascript:zkontroluj();\" class='akce'>OK</a>\n";
    }
  if(!empty($page)){
    echo "  <a href=\"?page=\" class='akce'>Zpět</a>\n";
    }
  echo "</div>\n\n";
  echo "  <div id='dole'></div>\n";
  echo "</div>\n\n";
  paticka();
  }
else{
  echo "nepřihlášen...";
  }
?>