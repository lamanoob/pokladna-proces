<?PHP 
include("stoly.php");
include("receptury.php");

function zakladni($uzivatel){
  $moznosti = array();
  $moznosti['novy']['text'] = "Nový účet";
  $moznosti['novy']['pravo'] = 3;
  $moznosti['objednavka']['text'] = "Objednávka";
  $moznosti['objednavka']['pravo'] = 4;
  $moznosti['platba']['text'] = "Platba";
  $moznosti['platba']['pravo'] = 7;
  $moznosti['zmena']['text'] = "Změna stolu";
  $moznosti['zmena']['pravo'] = 8;
  $moznosti['presunpol']['text'] = "Přesun položek mezi účty";
  $moznosti['presunpol']['pravo'] = 21;
  //$moznosti['uhrada']['text'] = "část. úhrada";
  //$moznosti['uhrada']['pravo'] = "část. úhrada";
  $moznosti['typplatby']['text'] = "Typ platby";
  $moznosti['typplatby']['pravo'] = "19";
  //$moznosti['zasuvka']['text'] = "Zásuvka";
  //$moznosti['zasuvka']['pravo'] = "Zásuvka";
  $moznosti['dotisk']['text'] = "Dotisk";
  $moznosti['dotisk']['pravo'] = "9";
  return $moznosti;
  }

function linkNazev($page){
  $link = $page;
  if($page == "novy"){
    $link = "novyucet";
    }
  elseif($page == "novyucet"){
    $link = "zalozit";
    }
  elseif($page == "objednavka"){
    $link = "upravit";
    }
  elseif($page == "upravit"){
    $link = "uprava";
    }
  elseif($page == "platba"){
    $link = "platbastul";
    }
  elseif($page == "platbastul"){
    $link = "platbadb";
    }
  elseif($page == "zmena"){
    $link = "zmenazestolu";
    }
  elseif($page == "zmenazestolu"){
    $link = "zmenanastul";
    }
  elseif($page == "dotisk"){
    //$link = "zmenatypu";
    }
  return $link;
  }
  
function receptury($str = null, $kat = null, $subkat = null){
  $sqlKR = $sqlSKR = $sqlRec = "";
  if(!empty($kat)){
    $sqlKR = " provozovna_id = ".$str." ";
    }
  if(!empty($kat)){
    $sqlSKR = " kategorie_id = ".$kat." ";
    }
  if(!empty($subkat)){
    $sqlRec = " kategorie_id = ".$subkat." ";
    }
  $vrat = array();
  //echo "SELECT id, nazev FROM kategorie_receptur WHERE smazano = 0 ".$sqlKR." ORDER BY poradi ASC, nazev ASC;<br />";
  $KRnacet = query("SELECT * FROM kategorie_receptur WHERE smazano = 0 ".$sqlKR." ORDER BY poradi ASC, nazev ASC;");
  if(existujeVDB($KRnacet)){
    $kr = array();
    while($KRvyber = assoc($KRnacet)){
      $vrat[$KRvyber['id']]['nazev'] = $KRvyber['nazev'];
      $kr[] = $KRvyber['id'];  
      }
    //echo "SELECT * FROM subkategorie_receptur WHERE smazano = 0 ".$sqlSKR." AND kategorie_id IN (".implode(",", $kr).") ORDER BY kategorie_id asc, poradi ASC, nazev ASC;<br />";//exit;
    $SKRnacet = query("SELECT * FROM subkategorie_receptur WHERE smazano = 0 ".$sqlSKR." AND kategorie_id IN (".implode(",", $kr).") ORDER BY kategorie_id asc, poradi ASC, nazev ASC;");
    if(existujeVDB($SKRnacet)){
      $skr = array();
      while($SKRvyber = assoc($SKRnacet)){
        $vrat[$SKRvyber['kategorie_id']]['skr'][$SKRvyber['id']]['nazev'] = $SKRvyber['nazev'];
        $vrat[$SKRvyber['kategorie_id']]['skr'][$SKRvyber['id']]['provozovna_id'] = $SKRvyber['provozovna_id'];
        $skr[$SKRvyber['id']] = $SKRvyber['kategorie_id'];  
        }
      unset($kr);
      //echo "SELECT * FROM receptury WHERE smazano = 0 ".$sqlSKR." AND kategorie_id IN (".implode(",", array_keys($skr)).") ORDER BY kategorie_id asc, poradi ASC, nazev ASC;<br />";exit;
      $RecNacet = query("SELECT * FROM receptury WHERE smazano = 0 ".$sqlSKR." AND kategorie_id IN (".implode(",", array_keys($skr)).") ORDER BY kategorie_id asc, poradi ASC, nazev ASC;");
      if(existujeVDB($RecNacet)){
        while($RecVyber = assoc($RecNacet)){
          $vrat[$skr[$RecVyber['kategorie_id']]]['skr'][$RecVyber['kategorie_id']]['rec'][$RecVyber['id']]['nazev'] = $RecVyber['nazev'];
          $vrat[$skr[$RecVyber['kategorie_id']]]['skr'][$RecVyber['kategorie_id']]['rec'][$RecVyber['id']]['cena'] = $RecVyber['cena'];
          $vrat[$skr[$RecVyber['kategorie_id']]]['skr'][$RecVyber['kategorie_id']]['rec'][$RecVyber['id']]['provozovna_id'] = $RecVyber['provozovna_id']; 
          $recPul = 0;
          $recCenaPul = $RecVyber['cena']; 
          if((!empty($RecVyber['pomer']))&&(intval($RecVyber['pomer']) != 0)){
            $recPul = 1;
            $recCenaPul = $RecVyber['cena_pul'];
            }
          $vrat[$skr[$RecVyber['kategorie_id']]]['skr'][$RecVyber['kategorie_id']]['rec'][$RecVyber['id']]['polovicni'] = $recPul;
          $vrat[$skr[$RecVyber['kategorie_id']]]['skr'][$RecVyber['kategorie_id']]['rec'][$RecVyber['id']]['cenapul'] = $recCenaPul;
          }
        unset($skr);
        }
      }
    }
  //printrko($vrat); exit;
  return $vrat; 
  }
function ucet($id){
  $vrat = null;
  if(!empty($id)){
    $nacet = query("SELECT * FROM ");
    }
  return $vrat;
  }
function stul($id){
  $vrat = null;
  if((!empty($id))&&(is_numeric($id))){
    $nacet = query("SELECT nazev FROM stoly WHERE id = ".$id." LIMIT 1;");
    if(existujeVDB($nacet)){
      $vyber = assoc($nacet);
      $vrat = $vyber['nazev'];
      }
    }
  return $vrat;
  }
function kasa($id){
  $vrat = array();
  if((!empty($id))&&(is_numeric($id))){
    //echo "SELECT k.id AS kasaid, k.nazev AS kasa, k.ucet_ident AS kasaucet, str.nazev AS stredisko, str.ucet_ident AS strucet FROM kasa AS k JOIN stredisko AS str ON k.stredisko = str.id WHERE k.id = ".$id." LIMIT 1;<br />";
    $nacet = query("SELECT k.id AS kasaid, k.nazev AS kasa, k.ucet_ident AS kasaucet, str.nazev AS stredisko, str.ucet_ident AS strucet FROM kasa AS k JOIN stredisko AS str ON k.stredisko = str.id WHERE k.id = ".$id." LIMIT 1;");
    if(existujeVDB($nacet)){
      $vyber = assoc($nacet);
      $vrat['kasaid'] = $vyber['kasaid'];
      $vrat['kasa'] = $vyber['kasa'];
      $vrat['kasaucet'] = $vyber['kasaucet'];
      $vrat['stredisko'] = $vyber['stredisko'];
      $vrat['strucet'] = $vyber['strucet'];
      }
    }
  return $vrat;
  }
function vysledek($typ, $vysledek){
  $vrat = "";
  if($typ == 'zalozit'){
    if($vysledek == 1){
      //$vrat = "Účet byl úspěšně založen.";
      }
    else{
      $vrat = "Účet se nepodařilo úspěšně založit.";
      }
    }
  elseif($typ == 'uprava'){
    if($vysledek == 1){
      //$vrat = "Položky byly úspěšně vloženy.";
      }
    else{
      $vrat = "Nepodařilo se vložit nové položky.";
      }
    }
  elseif($typ == 'platbadb'){
    if($vysledek == 1){
      //$vrat = "Položky byly úspěšně vloženy.";
      }
    else{
      $vrat = "Nepodařilo se vložit nové položky.";
      }
    }
  elseif($typ == 'dotisk'){
    if($vysledek == 1){
      //$vrat = "Položky byly úspěšně vloženy.";
      }
    else{
      $vrat = "Nepodařilo se vytisknout kopii uzavřeného účtu.";
      }
    }
  return $vrat;
  }
function polozkyUctu($id){
  $vrat = array();
  if((!empty($id))&&(is_numeric($id))){
    //echo "SELECT oup.*, rec.nazev AS receptura, uz.username AS vlozil, UNIX_TIMESTAMP(oup.cas_vlozeni) AS vlozeno FROM otevrene_ucty_polozky AS oup JOIN receptury AS rec ON (oup.id_receptury = rec.id) JOIN uzivatel AS uz ON (oup.id_uzivatele = uz.id) WHERE id_uctu = ".$id.";<br />";exit; 
    $nacet = query("SELECT oup.*, rec.nazev AS receptura, uz.username AS vlozil, UNIX_TIMESTAMP(oup.cas_vlozeni) AS vlozeno, rec.id AS recid FROM otevrene_ucty_polozky AS oup JOIN receptury AS rec ON (oup.id_receptury = rec.id) JOIN uzivatel AS uz ON (oup.id_uzivatele = uz.id) WHERE id_uctu = ".$id.";");
    if(existujeVDB($nacet)){
      while($vyber = assoc($nacet)){
        $vrat[$vyber['id']]['rec'] = $vyber['receptura'];
        $vrat[$vyber['id']]['mnoz'] = $vyber['mnozstvi'];
        $vrat[$vyber['id']]['cena'] = $vyber['cena_celkem'];
        $vrat[$vyber['id']]['cena_bez_dph'] = $vyber['cena_bez_dph'];
        $vrat[$vyber['id']]['vlozil'] = $vyber['vlozil'];
        $vrat[$vyber['id']]['vlozeno'] = $vyber['vlozeno'];
        $vrat[$vyber['id']]['polovicni'] = $vyber['polovicni'];
        $vrat[$vyber['id']]['pokladna'] = $vyber['pokladna'];
        $vrat[$vyber['id']]['recid'] = $vyber['recid'];
        }
      }
    }
  //printrko($vrat);exit;
  return $vrat; 
  }
function polozkyUzavrenehoUctu($id){
  $vrat = array();
  if((!empty($id))&&(is_numeric($id))){
    //echo "SELECT uup.*, rec.nazev AS receptura, uz.username AS vlozil, UNIX_TIMESTAMP(uup.cas_vlozeni) AS vlozeno FROM uzavrene_ucty_polozky AS uup JOIN receptury AS rec ON (uup.id_receptury = rec.id) JOIN uzivatel AS uz ON (uup.id_uzivatele = uz.id) WHERE id_uctu = ".$id.";<br />";exit; 
    $nacet = query("SELECT uup.*, rec.nazev AS receptura, uz.username AS vlozil, UNIX_TIMESTAMP(uup.cas_vlozeni) AS vlozeno, rec.id AS recid FROM uzavrene_ucty_polozky AS uup JOIN receptury AS rec ON (uup.id_receptury = rec.id) JOIN uzivatel AS uz ON (uup.id_uzivatele = uz.id) WHERE id_uctu = ".$id.";");
    if(existujeVDB($nacet)){
      while($vyber = assoc($nacet)){
        $vrat[$vyber['id']]['rec'] = $vyber['receptura'];
        $vrat[$vyber['id']]['mnoz'] = $vyber['mnozstvi'];
        $vrat[$vyber['id']]['cena'] = $vyber['cena_celkem'];
        $vrat[$vyber['id']]['cena_bez_dph'] = $vyber['cena_bez_dph'];
        $vrat[$vyber['id']]['vlozil'] = $vyber['vlozil'];
        $vrat[$vyber['id']]['vlozeno'] = $vyber['vlozeno'];
        $vrat[$vyber['id']]['polovicni'] = $vyber['polovicni'];
        $vrat[$vyber['id']]['pokladna'] = $vyber['pokladna'];
        $vrat[$vyber['id']]['recid'] = $vyber['recid'];
        }
      }
    }
  //printrko($vrat);exit;
  return $vrat; 
  }
function infoUcet($spojeni, $ucet, $vlozil = null){
  $vrat = null;
  $vlozilSel = $vlozilJoin = "";
  if(!empty($vlozil)){
    $vlozilSel = ", uz.username AS vlozil";
    $vlozilJoin = "JOIN uzivatel AS uz ON uz.id = ".$vlozil."";
    }
  //echo "SELECT ou.*, ka.nazev AS kasa, st.nazev AS stul, uzzal.username AS zalozil ".$vlozilSel." FROM otevrene_ucty AS ou JOIN stoly AS st ON ou.stul_id = st.id JOIN kasa AS ka ON ou.pokladna_zalozeni = ka.id ".$vlozilJoin." JOIN uzivatel AS uzzal ON ou.zakladajici_uzivatel = uzzal.id WHERE ou.id = ".$ucet." LIMIT 1;<br />"; 
  $nacet = query("SELECT ou.*, ka.nazev AS kasa, st.nazev AS stul, uzzal.username AS zalozil ".$vlozilSel." FROM otevrene_ucty AS ou JOIN stoly AS st ON ou.stul_id = st.id JOIN kasa AS ka ON ou.pokladna_zalozeni = ka.id ".$vlozilJoin." JOIN uzivatel AS uzzal ON ou.zakladajici_uzivatel = uzzal.id WHERE ou.id = ".$ucet." LIMIT 1;",$spojeni);
  if(existujeVDB($nacet)){
    $vrat = assoc($nacet);
    }
  return $vrat;
  //exit;
  }

function zalozeniUctu($spojeni, $uzivatel, $stul, $kasa){
  $vrat = array('stav' => false, 'cislouctu' => null, 'id' => null);
  //echo "INSERT INTO `otevrene_ucty` (`cislo_uctu`, `cas_zalozeni`, `zakladajici_uzivatel`, `stul_id`, `typ_uctu`, `pokladna_zalozeni`, `puvodni_ucet`, `unused`, `celebrity`) VALUES ('', now(), '".$uzivatel->getIDUser()."', '".$stul."', '0', '".$kasa['kasaid']."', '0', '0', '0');<br />";
  $vlozeni = query("INSERT INTO `otevrene_ucty` (`cislo_uctu`, `cas_zalozeni`, `zakladajici_uzivatel`, `stul_id`, `typ_uctu`, `pokladna_zalozeni`, `puvodni_ucet`, `unused`, `celebrity`) VALUES ('', now(), '".$uzivatel->getIDUser()."', '".$stul."', '0', '".$kasa['kasaid']."', '0', '0', '0');");
  if($vlozeni){
    $ouid = mysql_insert_id();
    $cislouctu = "";
    for($i = strlen($ouid);$i < _pocetMistUcet_;$i++){
      $cislouctu .= "0";
      }
    $cislouctu = $kasa['strucet']."-".$kasa['kasaucet']."-".$cislouctu.$ouid;
    $uprava = query("UPDATE otevrene_ucty SET cislo_uctu = '".$cislouctu."' WHERE id = ".$ouid." LIMIT 1;");
    $vrat['stav'] = true;
    $vrat['cislouctu'] = $cislouctu;
    $vrat['id'] = $ouid;  
    }
  //printrko($vrat); exit;
  return $vrat;
  }


/*************************************
 ***** OBJEDNAVKA ********************
 *************************************/ 
function objednavka($uzivatel, $stul, $link, $ucet, $stulUcty, $oup){
  $cena = 0;
  $nazev = $kr = $skr = $rec = $obj = $skrnazev = $krnazev = "";
  //$ucetid = $ucet;
  if(!empty($ucet)){
    //printrko($stulUcty);
    $ucetid = $ucet;
    $ucet = $stulUcty[$ucetid]['cislo'];
    }
  else{
    $ucetid = -1;
    $ucet = "nový účet";
    }
  if((isset($oup))&&(!empty($oup))){
    foreach($oup AS $key => $data){
      $kliknuti = "";
      $objNazev = $data['rec'];
      if($data['polovicni']){
        $objNazev = "&frac12; ".$objNazev;
        } 
      if(($uzivatel->getPravo(6))&&($data['mnoz'] > 0)){
        $kliknuti = " onclick=\"vytvorStorno(".$data['cena'].", ".$data['mnoz'].", '".$data['polovicni']."', '".$data['rec']."', ".$data['recid'].");\"";  
        }
      $obj .= "<table class='stare'".$kliknuti." cellspacing='0'>\n";
      $obj .= "  <td class='mnoz'>".$data['mnoz']."</td>"; 
      $obj .= "  <td class='naz'><div>".$objNazev."</div></td>"; 
      $obj .= "  <td class='pra'>".$data['cena']."<input type='hidden' class='cena' name='cena2[]' value='".$data['cena']."'/></td>"; 
      $obj .= "</table>"; 
      $cena += $data['cena']; 
      }
    }
  $stulnazev = stul($stul);
  $aktUcet = array();
  $kridcko = $skridcko = $recidcko = null;
  $nacet = receptury();
  //printrko($nacet);exit;
  if(!empty($nacet)){
    $i = $k = 0;
    foreach($nacet AS $krid => $krdata){
      $kr .= "  <a href=\"javascript: ukaz('krclass', 'krdiv".$i."', 'katnazev', '".$krdata['nazev']."');\" onclick=\"\" class='vyber'>".$krdata['nazev']."</a>\n";
      $krstyl = "";
      if($i==0){
        $krstyl = " style='display:block';' ";
        $krnazev = $krdata['nazev'];
        }
      if(!empty($krdata['skr'])){   
        $skr .= "<div id='krdiv".$i."' class='krclass' ".$krstyl.">";
        $j = 0;
        foreach($krdata['skr'] AS $skrid => $skrdata){
          $skrstyl = "";
          if(($k==0)){
            $skrstyl = " style='display:block';' ";
            $skrnazev = $skrdata['nazev'];
            $k++;
            }
          //echo $skrid."::".$skrdata['nazev'].";;<br />";
          $skr .= "  <a href=\"javascript: ukaz('skrclass', 'skrdiv".$i."-".$j."', 'subkatnazev', '".$skrdata['nazev']."');\" onclick=\"\" class='vyber'>".$skrdata['nazev']."</a>\n";
          if(!empty($skrdata['rec'])){
            $rec .= "<div id='skrdiv".$i."-".$j."' class='skrclass' ".$skrstyl.">";
            foreach($skrdata['rec'] AS $recid => $recdata){ 
              //$rec .= "  <a href=\"javascript: ukaz('skrclass', 'skrdiv".$j."', 'subkatnazev', '".$skrdata['nazev']."');\" onclick=\"\" class='vyber'>".$skrdata['nazev']." // ".$krdata['nazev']."</a>\n";
              $cena = $polovicni = $cenaPul = 0;
              if(!empty($recdata['cena'])){
                $cena = $recdata['cena']; 
                }
              if(!empty($recdata['polovicni'])){
                $polovicni = $recdata['polovicni']; 
                }
              if(!empty($recdata['cenapul'])){
                $cenaPul = $recdata['cenapul']; 
                }
              /***/
              $rec .= "  <a href=\"javascript: vyberRec(".$recid.", '".$recdata['nazev']."', ".$cena.", ".$polovicni.", ".$cenaPul.");\" onclick=\"\" class='vyber'>".$recdata['nazev']."</a>\n";
              }
            $rec .= "</div>";
            }
          $j++;
          }
        $skr .= "</div>";
        }    
      $i++;
      }
    unset($nacet);
    }        
  $objednavka = array();   
  $objednavka['ucetid'] = $ucetid; 
  $objednavka['ucet'] = $ucet; 
  $objednavka['stulid'] = $stul; 
  $objednavka['stul'] = $stulnazev; 
  /*******************************
   *******************************/      
  echo "<div id='objednavka'>\n";
  echo "  <form action='?page=".$link."' method='post' id='objednavkaform'>\n";
  echo "    <div id='objednanoinfo'>\n";
  if($ucetid != -1){
    echo "      <input type='hidden' name='ucet' value='".$ucetid."'/>\n";
    }
  echo "      <input type='hidden' name='stul' value='".$stul."'/>\n";
  echo "      <input type='hidden' name='pocetDivu' id='pocetDivu' value='0'/>\n";
  echo "      <div>Účet: ".$ucet."</div>\n";
  echo "      <div>Stůl: ".$stulnazev."</div>\n";
  echo "      <div>Cena celkem: <span id='objcena'>".$cena."</span>Kč</div>\n";
  echo "    </div>\n";
  echo "    <div id='objednavkadiv'>\n";
  echo "      <div id='stareveci'>".$obj."</div>\n";
  echo "      <div id='objednano'></div>\n";
  echo "    </div>\n";
  echo "    <div class='dole'>";
  echo "      <a href=\"javascript: posun('objednavkadiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "      <a href=\"javascript: posun('objednavkadiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "    </div>\n";
  echo "  </form>\n";
  echo "</div>";
  echo "<div id='kat'>\n";
  echo "  <div id='katdiv'>\n";
  echo "    <div>".$kr."</div>\n";
  echo "  </div>\n";
  echo "  <div class='dole'>";
  echo "    <a href=\"javascript: posun('katdiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "    <a href=\"javascript: posun('katdiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  </div>\n";
  echo "</div>";
  echo "<div id='subkat'>\n";
  echo "  <div id='katnazev'>".$krnazev."</div>\n";
  echo "  <div id='subkatdiv'>\n";
  echo "    <div>".$skr."</div>\n";
  echo "  </div>\n";
  echo "  <div class='dole'>";
  echo "    <a href=\"javascript: posun('subkatdiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "    <a href=\"javascript: posun('subkatdiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  </div>\n";
  echo "</div>";
  echo "<div id='receptury'>\n";
  echo "  <div id='subkatnazev'>".$skrnazev."</div>\n";
  echo "  <div id='recepturydiv'>\n";
  echo "    <div>".$rec."</div>\n";
  echo "  </div>\n";
  echo "  <div class='dole' style='width: 100%; text-align:center;'>";
  echo "    <a href=\"javascript: posun('recepturydiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "    <a href=\"javascript: posun('recepturydiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  </div>\n";
  echo "</div>\n";
  echo "<div id='klavesnicepozadi'></div>\n";
  echo "<div id='klavesnice'>\n";
  echo "  <input type='hidden' name='klavrecid' id='klavrecid' value='' />";
  echo "  <input type='hidden' name='klavcena' id='klavcena' value='' />";
  echo "  <table>";
  /*
  echo "    <tr>";
  echo "      <td colspan='2'>Účet:<input type='hidden' name='klavucetid' value='".$ucetid."' /></td>";
  echo "      <td colspan='4' id='klavucet'>".$ucet."</td>";
  echo "    </tr>";
  echo "    <tr>";
  echo "      <td colspan='2'>Stůl:<input type='hidden' name='klavstulid' value='".$stul."' /></td>";
  echo "      <td colspan='4' id='klavstul'>".$stulnazev."</td>";
  echo "    </tr>";
  echo "    <tr>";
  echo "      <td colspan='2'>Receptura:";
  echo "      </td>";
  echo "      <td colspan='4' id='klavrec'></td>";
  echo "    </tr>";
  */
  echo "    <tr>";
  $sloupce = 2;
  if($uzivatel->getPravo(6)){
    $sloupce++;
    }
  echo "      <td colspan='".$sloupce."'>Množství:</td>";
  echo "      <td colspan='2' id='klavmnoz'></td>";
  echo "      <td colspan='1'>Poloviční:</td>";
  echo "      <td colspan='2' id='klavpol'>ne</td>";
  echo "    </tr>";
  echo "    <tr>";
  if($uzivatel->getPravo(6)){  
    echo "      <td class='klav2' rowspan='2'><a href=\"javascript:klikni('-');\"><br /><br /><br /><br />-</a></td>";
    }
  //echo "      <td class='klav2' rowspan='2'><a href=\"javascript:klikni('OK');\"><br /><br /><br /><br />OK</a></td>";
  echo "      <td class='klav3'><a href=\"javascript:klikni('BS');\"><br /><br />|<--</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(1);\"><br /><br />1</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(2);\"><br /><br />2</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(3);\"><br /><br />3</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(4);\"><br /><br />4</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(5);\"><br /><br />5</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni('--');\"><br /><br />---</a></td>";
  echo "    </tr>";
  echo "    <tr>";
  //echo "      <td class='klav'><a href=\"javascript:klikni('ZR');\"><br /><br />ZRUŠ</a></td>";
  echo "      <td class='klav3'><a href=\"javascript:klikni('RS');\"><br /><br />RESET</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(6);\"><br /><br />6</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(7);\"><br /><br />7</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(8);\"><br /><br />8</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(9);\"><br /><br />9</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(0);\"><br /><br />0</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni('/');\"><br /><br />1/2</a></td>";
  echo "    </tr>";
  echo "  </table>";
  echo "</div>\n";
  if($uzivatel->getPravo(5)){  
    echo "<div id='smazani'>\n";
    echo "  <div class='dotaz'>\n";
    echo "    <input type='hidden' name='smazaniid' id='smazaniid' value='' />";
    echo "    Opravdu smazat objednanou položku \"<span id='smazrec' class='tucne'></span>\" v množství \"<span id='smazmnoz' class='tucne'></span>\" v celkové ceně \"<span id='smazcena' class='tucne'></span>\"???\n";
    echo "  </div>\n";
    echo "  <div>\n";
    echo "    <a href='javascript:smazaniPotvrd();'>Smazat</a>\n";
    echo "    <a href='javascript:smazaniZrusit();'>Zrušit</a>\n";
    echo "  </div>\n";
    echo "</div>\n";
    }
  if($uzivatel->getPravo(6)){  
  
    }
  echo "    <script type='text/javascript'>
    $('#klavesnicepozadi').click(function(){
      $('#klavesnicepozadi').hide();
      //$('#klavesnice').hide();
      var mnoz = $('#klavmnoz');
      if(mnoz){
        mnoz.html('');
        }
      $('#smazani').hide();
      });
    function zkontroluj(){
      var mnoz = $('#objednano input.mnozstvi').length;
      var idcka = $('#objednano input.recid').length;
      var ceny = $('#objednano input.cena').length;
      if((mnoz == idcka)&&(mnoz == ceny)&&(mnoz > 0)){
        $('#objednavkaform').submit();
        }
      }
    function smazaniVyber(polozka){
      $('#smazaniid').val(polozka);
      $('#smazmnoz').html($('#'+polozka+' .mnoz').html());
      $('#smazrec').html($('#'+polozka+' .naz').html());
      $('#smazcena').html($('#'+polozka+' .pra').html());
      $('#smazani').show();
      $('#klavesnicepozadi').show();
      }
    function smazaniPotvrd(){
      smazPolozku($('#smazaniid').val());
      $('#smazani').hide();
      $('#klavesnicepozadi').hide();
      }
    function smazaniZrusit(){
      $('#smazani').hide();
      $('#klavesnicepozadi').hide();
      }
    function zrusobj(){
      var obj = $('#objednano .objednavky');
      if(obj.length > 0){
        var last = $('#objednano .objednavky').last();
        last.remove();
        }
      spoctiCelkem();
      }
    /**************/
    function vyberRec(recid, recnazev, cena, pomer, cenapul, mnozstvi){
      var mnoz = $('#klavmnoz');
      cena = parseInt(cena);
      var pocet = 1;
      var polovicni = 0;
      if(typeof(mnozstvi) != 'undefined' && mnozstvi != 0){
        var pocet = mnozstvi;
        if(pomer == 1){
          cena = cenapul;
          }
        }
      else{
        if(mnoz){
          if(mnoz.html().length > 0){
            if(mnoz.html() == '-'){
              pocet = -1;
              }
            else{
              pocet = parseInt(mnoz.html());
              }
            }
          }
        if(pomer == 1){
          var pol = $('#klavpol');
          if(pol.html() == 'ano'){
            polovicni = 1;
            cena = cenapul;
            recnazev = '&#189; '+recnazev;";
            if(defined('_polovicniNulovani_')&&(_polovicniNulovani_ == 1)){
              echo "          pol.html('ne');";
              }
            echo "
            }  
          }
        }
      var pocetDivu = $('#pocetDivu');
      var pocetvdivu = parseInt(pocetDivu.val())+1;
      var newdiv = document.createElement('table');
      newdiv.setAttribute('id','trpolozky'+ pocetvdivu);
      newdiv.setAttribute('class','objednavky');  
      newdiv.setAttribute('cellspacing','0');  
      ";
      if($uzivatel->getPravo(5)){  
        echo "newdiv.setAttribute('onclick','smazaniVyber(\"trpolozky'+ pocetvdivu+'\");');\n";
        }      
      echo "       
      newdiv.innerHTML=''
        + '<tr>'
        + '  <td class=\'mnoz\'>'+pocet+'x</td>'
        + '  <td class=\'naz\'>'
        + '    <span style=\'display:none\'>'
        + '      <input type=\"hidden\" class=\"recid\" name=\"recid[]\" value=\"'+recid+'\"/>'
        + '      <input type=\"hidden\" class=\"mnozstvi\" name=\"mnozstvi[]\" value=\"'+pocet+'\"/>'
        + '      <input type=\"hidden\" class=\"cena\" name=\"cena[]\" value=\"'+(pocet * cena)+'\"/>'
        + '      <input type=\"hidden\" class=\"polovicni\" name=\"polovicni[]\" value=\"'+polovicni+'\"/>'
        + '    </span>'
        + '    <div>'+recnazev+'</div>'
        + '  </td>'
        + '  <td class=\'pra\'>'+(pocet * cena)+'</td>';
        + '</tr>'
         

      pocetDivu.val(pocetvdivu);
      $('#objednano').append(newdiv);
      var vyskaStare = $('#objednavkadiv #stareveci').outerHeight();
      var vyskaNove = $('#objednavkadiv #objednano').outerHeight();
      $('#objednavkadiv').scrollTop((vyskaStare + vyskaNove));
      spoctiCelkem();
      mnoz.html('');
      }

    function spoctiCelkem(){
      var cena = 0;
      $('#objednavkadiv input.cena').each(function(i, hodn){
        cena += parseFloat(hodn.value);
        });
      $('#objcena').html(cena);
      }
    function smazPolozku(jmenoDivu) {
        $('#'+jmenoDivu).remove();
        spoctiCelkem();
        }    
    function smazVse(){     
      $('#pocetDivu').val(0);
      $('#objednano').html('');
      }
    function vytvorStorno(danaCena, mnoz, pol, recnazev, recid){
      var cena = 0;
      var cenapul = 0;
      danaCena = parseFloat(danaCena);
      mnoz = parseInt(mnoz);
      if(mnoz > 0){
        if(pol == 1){
          cenapul = danaCena / mnoz;
          }
        else{
          cena = danaCena / mnoz;
          }
        //alert(cena+' '+cenapul+' '+mnoz+' '+pol);
        vyberRec(recid, recnazev, cena, pol, cenapul, -mnoz);
        }
      }
    function upravacasti() {
      var snizenikat = 0;
      var snizenisubkat = 0;
      /****/
      var vyska = $('#stred').outerHeight();          
      
      var klavesniceSirka = $('#klavesnice').width();
      var klavesniceVyska = $('#klavesnice').height();
      
      var subkatOff = $(\"#subkat\").offset();
      var katOff = $(\"#kat\").offset();
      if(subkatOff.left < klavesniceSirka){
        snizenisubkat = klavesniceVyska + 10;
        }
      if(katOff.left < klavesniceSirka){
        snizenikat = klavesniceVyska + 10;
        }
      
      var snizeniobj = $('#objednavka .dole').outerHeight();
      var snizeniobjinfo = $('#objednanoinfo').outerHeight();
      $('#objednavkadiv').height((vyska - snizeniobj - snizeniobjinfo - 10));
      
      var snizenikr = $('#kat .dole').outerHeight();
      $('#kat').height((vyska - snizenikat));
      $('#katdiv').height((vyska - snizenikr - snizenikat));
      
      var vyskakrnazev = $('#katnazev').outerHeight();
      var snizeniskr = $('#subkat .dole').outerHeight();
      
      $('#subkat').height((vyska - snizenisubkat));
      $('#subkatdiv').height((vyska - vyskakrnazev - snizeniskr - snizenisubkat - 10));
      
      var vyskaskrnazev = $('#subkatnazev').outerHeight();
      var snizenirec = $('#receptury .dole').outerHeight();
      var vyskaklav = $('#klavesnice').outerHeight();
      //alert('A:'+(vyska - vyskaskrnazev - snizenirec)+'; k:'+vyskaklav);   
      $('#receptury').height((vyska-vyskaklav));
      $('#recepturydiv').height((vyska - vyskaskrnazev - snizenirec - vyskaklav - 10));
      /**********/
      var sirka = $('#stred').outerWidth(); 
      var mezeraobj = $('#objednavka').css('right').replace('px', ''); 
      var mezerarec = $('#receptury').css('right').replace('px', '');
      var novasirka = (sirka - mezerarec - mezeraobj); 
      //$('#klavesnice').width(novasirka);   
      $('#receptury').width(novasirka);
      /*
      var snizeniobj = $('#objednavka').outerWidth();
      var snizenikr = $('#kat').outerWidth();
      var snizeniskr = $('#subkat').outerWidth();
      var mezerakr = $('#kat').css('right').replace('px', ''); 
      var mezeraskr = $('#subkat').css('right').replace('px', ''); 
      alert(mezerarec+' - '+mezeraskr+' - '+mezerakr+' - '+mezeraobj);
      $('#receptury').width((sirka - snizeniobj - snizenikr - snizeniskr - (mezerarec - mezeraskr - mezerakr - mezeraobj)));
      */
      /************************/
      var klavpoz = $('#klavesnicepozadi');
      if(klavpoz){
        klavpoz.height(vyska).width(sirka);
        }
      };
  </script>";
  }
function objednavkaNaUcet($spojeni, $uzivatel, $ouid){
  $vrat['stav'] = false;
  $recpost = post('recid');
  $mnoz = post('mnozstvi');
  $pol = post('polovicni');
  $recpovolene = $obj = $rec = $kVytisteni = $prikazPole = array();
  $dphNazvy = dphNazvy($spojeni);                              
  $pokus = $uspech = $i = 0;
  /****/
  foreach($recpost AS $key => $data){
    $obj[$i]['rec'] = $data;
    $obj[$i]['mnoz'] = $mnoz[$key];
    $obj[$i]['pol'] = $pol[$key];
    if((is_numeric($data))&&($data > 0)){
      $recpovolene[] = $data; 
      }
    $i++;
    }
  //printrko($obj);exit;
  //echo "SELECT rec.*, round(rec.cena_pul / (1 + dph.koeficient),2) AS cena_pul_bezdph FROM receptury AS rec JOIN dph AS dph ON rec.dph_id = dph.id WHERE rec.id IN (".implode(",",$recpovolene).");<br />";exit; 
  $recNacet = query("SELECT rec.*, round(rec.cena_pul / (1 + dph.koeficient),2) AS cena_pul_bezdph FROM receptury AS rec JOIN dph AS dph ON rec.dph_id = dph.id WHERE rec.id IN (".implode(",",$recpovolene).");");
  if(existujeVDB($recNacet)){
    while($recVyber = assoc($recNacet)){  
      $rec[$recVyber['id']]['cena'] = $recVyber['cena'];
      $rec[$recVyber['id']]['nazev'] = $recVyber['nazev'];
      $rec[$recVyber['id']]['dph_id'] = $recVyber['dph_id'];
      $rec[$recVyber['id']]['cena_pul'] = $recVyber['cena_pul'];
      $rec[$recVyber['id']]['cena_pul_bezdph'] = $recVyber['cena_pul_bezdph'];
      $rec[$recVyber['id']]['cena_bezdph'] = $recVyber['cena_bezdph'];
      $rec[$recVyber['id']]['dph'] = ($recVyber['cena'] - $recVyber['cena_bezdph']);
      $rec[$recVyber['id']]['tisk'] = $recVyber['objednavka_tiskarna_id'];
      }
    }
  /*******/
  $prikaz = "INSERT INTO `otevrene_ucty_polozky` (`id_uctu`, `id_receptury`, `id_uzivatele`, `cas_vlozeni`, `dph_id`, `mnozstvi`, `polovicni`, `pokladna`, `cena_bez_dph`, `dph`, `cena_celkem`, `odepsano_mnozstvi`, `odepsano_cena`, `odecteno`, `id_mrec`, `stav`) VALUES ";
  /*******************/
  $kTiskuInfo = infoUcet($spojeni, $ouid, $uzivatel->getIDUser());
  $kTiskuInfo['cas_zalozeni'] = datetimeToDate($kTiskuInfo['cas_zalozeni']);
  $kTiskuInfo['cas_vlozeni'] = ted(); 
  foreach($obj AS $data){
    $key = $data['rec'];
    if((is_numeric($key))&&($key > 0)){
      $polovicni = 0;
      $nazev = $rec[$key]['nazev'];
      $cena = $rec[$key]['cena'];
      $tiskarnaRec = $rec[$key]['tisk'];
      $cenaBez = $rec[$key]['cena_bezdph'];
      $cenaDPH = $rec[$key]['dph']; 
      $dphID = $rec[$key]['dph_id'];
      if($data['pol'] == 1){
        $polovicni = 1;
        $cena = $rec[$key]['cena_pul'];
        $cenaBez = $rec[$key]['cena_pul_bezdph'];
        $cenaDPH = round($dphNazvy[$dphID]['koef'] * $rec[$key]['cena_pul_bezdph'],2); //bbb
        //$nazev = "&frac12; ".$nazev;
        $nazev = "1/2 ".$nazev;
        }
      $mnozstvi = intval($data['mnoz']); 
      $cenaCelkem = "";//$cena * $mnozstvi;
      $prikazPole[] = "('".$ouid."', '".$key."', '".$uzivatel->getIDUser()."', now(), '".$dphID."', '".$mnozstvi."', '".$polovicni."', '"._kasa_."', '".($cenaBez * $mnozstvi)."', '".($cenaDPH * $mnozstvi)."', '".($cena * $mnozstvi)."', '0', '0.00', '0', NULL, NULL)";
      /*****/
      $kVytisteni[$tiskarnaRec][] = array($mnozstvi."x", $nazev);                   
      $secti = strlen($nazev) + strlen(strval($cenaCelkem));
      }
    else{
      if((!empty($kVytisteni))){
        foreach($kVytisteni AS $tiskarna => $text){
          $text1 = $text2 = " ";
          for($i = 0; $i < (_tiskMnoz_ - 1);$i++){
            $text1 .= "=";
            } 
          for($i = 0; $i < (_tiskText_ - 1);$i++){
            $text2 .= "=";
            } 
          $kVytisteni[$tiskarna][] = array($text1, $text2);
          }
        }
      }
    //$kTisku .= $kTiskuAkt;
    }
  //printrko($kVytisteni);//exit;
  /**********/
  //echo $kTisku;exit;
  if(!empty($prikazPole)){
    $prikaz = $prikaz.implode(",", $prikazPole).";";
    //echo $prikaz."<br />";
    //$vloz = false;
    $vloz = query($prikaz);
    if($vloz){
      $vrat['stav'] = true;
      }
      
    }
  
  if((_tiskPovolen_) && (!empty($kVytisteni))){
    $vrat['nevytisteno'] = sizeof($kVytisteni);
    $tiskarny = tiskarny();
    $tiskove = tiskove(); 
    $tiskNadpis = array();
    foreach($tiskove AS $tiskKey => $tiskData){
      $tiskNadpis[] = array(ucfirst($tiskData['text']).":", $kTiskuInfo[$tiskKey]);
      }
    //echo "T:".$tiskarny[2].";<br />";    printrko($kVytisteni);exit;
    foreach($kVytisteni AS $tiskarna => $textPole){
      //printrko($textPole);exit;
      if($tiskarna == 0){
        $tiskarna = _tiskarna_;
        }
      if(isset($tiskarny[$tiskarna])){
        $tisk = new Printer();
        $tisk->select($tiskarny[$tiskarna]);
        $tisk->format('utf2win');
        $tisk->width(_tiskRadek_);
        $tisk->left(2);
        $tisk->right(2);
        $tisk->align('center');
        $tisk->wrap();        
        $tisk->fill("=");
        $tisk->wrap();       
        $tisk->insert("OBJEDNÁVKA");
        $tisk->wrap();       
        $tisk->fill("=");
        $tisk->wrap();        
        $velikosti = array(15);
        $zarovnani = array("right", "left", 'right');
        $mezery = array(2); 
        $tisk->insertTable($tiskNadpis, $velikosti, $zarovnani, $mezery);
        $tisk->wrap();     
        $tisk->fill("-");
        $tisk->wrap();      
        $velikosti = array(_tiskMnoz_, 0);
        $zarovnani = array("right", "left", 'right');
        $mezery = array(2); 
        $tisk->insertTable($textPole, $velikosti, $zarovnani, $mezery); 
        //echo $tisk->show_buffer();$vytisteno = true;
        $vytisteno = $tisk->print_buffer();
        if($vytisteno){
          echo "vytisten pokus pro tiskarnu:".$tiskarna."...<br />";
          $vrat['nevytisteno']--;
          }
        else{
          echo "nic nevytisteno pro tiskarnu:".$tiskarna."...<br />";
          }
        }
      else{
        echo "není zvolená tiskárna:".$tiskarna."...<br />";
        }
      sleep(1);
      }
    }
  else{
    //$vytisteno = true;
    }
  //exit;
  return $vrat;
  }
/**************************************
 ***** PLATBY *************************
 **************************************/ 
function platbaNaStole($uzivatel, $stul, $link, $ucet, $stulUcty, $oup){
  $cena = 0;
  $nazev = $kr = $skr = $rec = $obj = $skrnazev = $krnazev = "";
  if(!empty($ucet)){
    //printrko($stulUcty);
    $ucetid = $ucet;
    $ucet = $stulUcty[$ucetid]['cislo'];
    }
  else{
    $ucet = "chyba účtu";
    }
  $vybratVse = true;
  $vybratVseTrida = "vyber"; 
  //$vybratVseTrida = "vyber";
  if((isset($oup))&&(!empty($oup))){
    $i = 0;
    foreach($oup AS $key => $data){
      $hodnota = "";
      if($vybratVse){
        $hodnota = $key;
        }
      $dataNazev = $data['rec'];
      if($data['polovicni']){
        $dataNazev = "&frac12; ".$dataNazev;
        } 
      $online = " ";
      /****/
      $obj .= "<div class='stare".$vybratVseTrida."' onclick=\"vyberPolozku(".$key.", 'stare', 'starevyber');\">\n";
      $obj .= "  <input type='hidden' class='kplaceni' name='kplaceni[]' id='kplaceni_".$key."' value='".$hodnota."'/>\n";
      $obj .= "  <input type='hidden' class='vyberplatby' name='vyberplatby[]' id='vyberplatby_".$key."' value='".$key."'/>\n";
      $obj .= "  <input type='hidden' class='vybercena' value='".$data['cena']."'/>\n";
      $obj .= "  <span class='mnoz'>".$data['mnoz']."x</span>"; 
      $obj .= "  <span class='naz'>".$dataNazev."</span>"; 
      $obj .= "  <span class='pra'>".$data['cena']."</span>"; 
      $obj .= "</div>"; 
      $cena += $data['cena']; 
      $i++;
      }
    }
  $stulnazev = stul($stul);
  /*************************************
   *************************************/      
  echo "<form action='?page=".$link."' method='post' id='objednavkaform'>\n";
  echo "  <div id='kplatbe'>\n";
  echo "    <input type='hidden' name='vybranytyp' id='vybranytyp' value='0'/>\n";
  echo "    <input type='hidden' name='vybranaslevaproc' id='vybranaslevaproc' value='1'/>\n";
  echo "    <input type='hidden' name='vybranaslevahodn' id='vybranaslevahodn' value='0'/>\n";
  echo "    <input type='hidden' name='vybranazakkarta' id='vybranazakkarta' value='0'/>\n";
  echo "    <input type='hidden' name='vybranyservis' id='vybranyservis' value='0'/>\n";
  echo "    <div id='vybertypu'>\n";
  echo "      <div>Typ platby:</div>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 0);\" class='typplatbyakt' id='typplatby0'>Hotově</a>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 1);\" class='typplatby' id='typplatby1'>Kartou</a>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 2);\" class='typplatby' id='typplatby2'>Fakturou</a>\n";
  echo "    </div>\n";
  if($uzivatel->getPravo(12)){
    echo "    <div id='vyberslevy'>";
    echo "      <div>Sleva: <span id='slevaid'></span></div>\n";
    echo "      <a href=\"javascript: vlozAZmen('vybranaslevaproc', 'typslevy', 1);\" class='typslevy' id='typslevy1'>Sleva v %</a>\n";
    echo "      <a href=\"javascript: vlozAZmen('vybranaslevaproc', 'typslevy', 0);\" class='typslevy' id='typslevy0'>Sleva v korunách</a>\n";
    echo "      <a href=\"javascript: zapisSlevu(1);\" class='zakaznickakarta'>Vymaz slevu</a>\n";
    echo "    </div>\n";
    }
  echo "    <div id='vyberservisu'>";
  echo "      <div>Servis:</div>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranyservis', 'typservisu', 0);\" class='typservisuakt' id='typservisu0'>Bez servisu</a>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranyservis', 'typservisu', 1);\" class='typservisu' id='typservisu1'>Se servisem</a>\n";
  echo "      <a href=\"javascript: ;\" class='zakaznickakarta'>Zákaznická karta</a>\n";
  echo "    </div>\n";
  echo "  </div>\n";
  echo "  <div id='platba'>\n";
  echo "    <div id='platbainfo'>\n";
  if($ucetid != -1){
    echo "      <input type='hidden' name='ucet' value='".$ucetid."'/>\n";
    }
  echo "      <input type='hidden' name='stul' value='".$stul."'/>\n";
  echo "      <input type='hidden' name='pocetDivu' id='pocetDivu' value='0'/>\n";
  echo "      <div>Účet: ".$ucet."</div>\n";
  echo "      <div>Stůl: ".$stulnazev."</div>\n";
  echo "      <div>Cena všeho: ".$cena." Kč</div>\n";
  echo "      <div class='objcena'>Cena vybraného: <span id='objcena'>".$cena."</span> Kč</div>\n";
  echo "    </div>\n";
  echo "    <div id='platbadiv'>\n";
  echo "      <div id='stareveci'>".$obj."</div>\n";
  echo "      <div id='platbaveci'></div>\n";
  echo "    </div>\n";
  echo "    <div class='dole'>";
  echo "      <a href=\"javascript: platbyVyber('kplaceni', 'vyberplatby', 'stare', 'starevyber');\" class='vyber1'>Vybrat vše</a>\n";
  echo "      <a href=\"javascript: platbyZruseni('kplaceni', 'starevyber', 'stare');\" class='vyber2'>Zrušit vše</a>\n";
  echo "      <a href=\"javascript: posun('platbadiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "      <a href=\"javascript: posun('platbadiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "    </div>\n";
  echo "  </div>";
  echo "</form>\n";
  echo "<div id='klavesnice'>\n";
  echo "  <input type='hidden' name='klavrecid' id='klavrecid' value='' />";
  echo "  <input type='hidden' name='klavcena' id='klavcena' value='' />";
  echo "  <table>";
  echo "    <tr>";
  echo "      <td colspan='2'>Sleva:</td>";
  echo "      <td colspan='4' id='klavmnoz'></td>";
  echo "    </tr>";
  echo "    <tr>";
  echo "      <td class='klav3'><a href=\"javascript:klikni('BS');\"><br /><br />|&lt;--</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(1);\"><br /><br />1</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(2);\"><br /><br />2</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(3);\"><br /><br />3</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(4);\"><br /><br />4</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(5);\"><br /><br />5</a></td>";
  echo "    </tr>";
  echo "    <tr>";
  echo "      <td class='klav3'><a href=\"javascript:klikni('RS');\"><br /><br />RESET</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(6);\"><br /><br />6</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(7);\"><br /><br />7</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(8);\"><br /><br />8</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(9);\"><br /><br />9</a></td>";
  echo "      <td class='klav'><a href=\"javascript:klikni(0);\"><br /><br />0</a></td>";
  echo "    </tr>";
  echo "  </table>";
  echo "</div>\n";
  echo "    <script type='text/javascript'>
    function zapisSlevu(vymaz){
      if(vymaz == 1){
        $('#slevaid').html('');
        $('#vybranaslevahodn').val('');
        $('a.typslevyakt').removeClass('typslevyakt').addClass('typslevy');
        }
      else{
        var ulozeno = $('#vybranaslevahodn').val();
        var jeProcenta = $('#vybranaslevaproc').val();
        var sleva = $('#klavmnoz').html();
        var pokracovat = false;
        var pridani = ' Kč';
        var text = 0;
        if(sleva > 0){
          text = sleva;
          if(jeProcenta == 1){
            pridani = '%';
            if(sleva > 100){
              sleva = 100;
              text = 100;
              }
            }
          pokracovat = true;
          }
        else if(ulozeno > 0){
          var sleva = ulozeno;
          text = ulozeno;
          if(jeProcenta == 1){
            pridani = '%';
            if(ulozeno > 100){
              sleva = 100;
              text = 100;
              }
            }
          pokracovat = true;
          }
        if(pokracovat){
          text = text + pridani; 
          $('#slevaid').html(text);
          $('#vybranaslevahodn').val(parseInt(sleva));
          $('#klavmnoz').html('');
          }
        else{
          $('a.typslevyakt').removeClass('typslevyakt').addClass('typslevy');
          }
        }
      }
    function upravacasti() {
      var vyska = $('#stred').outerHeight();
      $('#platba').height(novavyska);
      var novavyska = (vyska - 15); 
      novavyska -= $('#platbainfo').outerHeight();
      novavyska -= $('#platba .dole').outerHeight();
      $('#platbadiv').height(novavyska); 
      
      /**********/
      var sirka = $('#stred').outerWidth(); 
      var sirkaklav = $('#klavesnice').outerWidth(); 
      //var mezeraobj = $('#objednavka').css('right').replace('px', ''); 
      //var mezerarec = $('#receptury').css('right').replace('px', '');
      var novasirka = (sirka - sirkaklav) - 20; 
      //$('#klavesnice').width(novasirka);   
      $('#platba').width(novasirka);
      /************************/
      var klavpoz = $('#klavesnicepozadi');
      if(klavpoz){
        klavpoz.height(vyska).width(sirka);
        }
      /*************************/
      var klavesnicesirka = $('#klavesnice').width();
      var klavesnicevyska = $('#klavesnice').height();
      $('#kplatbe').width(klavesnicesirka);
      $('#kplatbe').height((vyska - klavesnicevyska - 25));
      };             
    
    function vyberPolozku(vyberNazev, stara, nova){
      //$('li.item-a').parent().css('background-color', 'red');
      var kplaceni = $('#kplaceni_'+vyberNazev);
      var vyberplatby = $('#vyberplatby_'+vyberNazev);
      if(kplaceni && vyberplatby){
        var hodn = vyberplatby.val();   
        var aktualni = kplaceni.val();           
        if(hodn != aktualni){
          kplaceni.val(hodn);
          kplaceni.parent().removeClass(stara).addClass(nova);
          }
        else{
          kplaceni.val('');
          kplaceni.parent().removeClass(nova).addClass(stara);
          }
        spoctiPlatbu();
        }
      }
    function platbyVyber(vyber, idcka, stara, nova){
      $('.'+stara).removeClass(stara).addClass(nova);
      $('.'+nova).each(function(i){
        var hodn = $(this).children('.'+idcka).val(); 
        $(this).children('.'+vyber).val(hodn);
        });
      spoctiPlatbu();
      }
    function platbyZruseni(vyber, stara, nova){
      $('.'+stara).removeClass(stara).addClass(nova);
      $('.'+vyber).val('');
      spoctiPlatbu();
      }
    function platbaVyber(vyber, idcka, stara, nova){
      $('.'+stara).removeClass(stara).addClass(nova);
      $('.'+nova).each(function(i){
        var hodn = $(this).children('.'+idcka).val(); 
        $(this).children('.'+vyber).val(hodn);
        });
      spoctiPlatbu();
      }
    function platbaZruseni(vyber, stara, nova){
      $('.'+stara).removeClass(stara).addClass(nova);
      $('.'+vyber).val('');
      spoctiPlatbu();
      }
    function spoctiPlatbu(){       
      var cena = 0;
      $('.starevyber .vybercena').each(function(i, hodn){
        cena += parseFloat(hodn.value); 
        });
      $('#objcena').html(cena);
      }
    function zkontroluj(){
      var typ = $('#vybranytyp');
      var slevaproc = $('#vybranaslevaproc');
      var slevahodn = $('#vybranaslevahodn');
      var zakkarta = $('#vybranazakkarta');
      var servis = $('#vybranyservis');
      var vybrane = $('#platbadiv .starevyber').length;
      var kplaceni = $('#platbadiv .kplaceni').length;
      var vyberplatby = $('#platbadiv .vyberplatby').length;
      if((typ && slevaproc && slevahodn && zakkarta && servis)&&(vybrane > 0)&&(kplaceni == vyberplatby)){
        $('#objednavkaform').submit();
        }
      else if(vybrane <= 0){
        alert('Musíte zvolit nejméně jednu položku k platbě!');
        }
      }
  </script>";
  }
function platbaUctu($spojeni, $uzivatel){
  $vrat = false;
  $ucetID = post('ucet');
  $tisk = $hlavicka = $paticka = "";
  $kVytisteni = array();
  $puvUcet = 0;
  if(!empty($ucetID)){
    //printrko($_POST);
    $vybranytyp = post('vybranytyp');                
    $vybranaslevaproc = post('vybranaslevaproc');
    $vybranaslevahodn = post('vybranaslevahodn');
    $vybranazakkarta = post('vybranazakkarta');
    $vybranyservis = post('vybranyservis');
    $stul = post('stul');
    $kPlaceni = post('kplaceni');
    $kPlaceni = array_unique($kPlaceni);
    $prazdne = array_search('',$kPlaceni);
    if(($prazdne > 0 )|| ($prazdne === 0)){
      unset($kPlaceni[$prazdne]);
      }
    $pocet = $sleva = $servis = $slevaCastka = $cenaKplatbe = $servisCastka = $slevaZapis = $servisZapis = $pocetPolozek = 0;
    $dph = $ucet = $oup = $preved = $ceny = array();
    if(empty($vybranaslevaproc)){
      $vybranaslevaproc = 0;
      }
    if(empty($vybranytyp)){
      $vybranytyp = 0;
      }
    /*****************
     *****************/
    $dphNacet = query("SELECT id, koeficient FROM dph;", $spojeni); 
    while($dphVyber = assoc($dphNacet)){
      $dph[$dphVyber['id']] = $dphVyber['koeficient']; 
      }
    //echo "SELECT str.* FROM kasa AS kas LEFT JOIN stredisko AS str ON (kas.stredisko = str.id) WHERE kas.id = "._kasa_." LIMIT 1;<br />";exit; 
    $strNacet = query("SELECT str.* FROM kasa AS kas LEFT JOIN stredisko AS str ON (kas.stredisko = str.id) WHERE kas.id = "._kasa_." LIMIT 1;",$spojeni);
    if(existujeVDB($strNacet)){
      $stredisko = assoc($strNacet);
      $hlavicka = $stredisko['hlavicka'];
      $paticka = $stredisko['paticka'];
      }
    /*****************
     *****************/  
    //echo "SELECT count(id) AS pocet FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID.";<br />";     
    $oupPocetNacet = query("SELECT count(id) AS pocet FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID.";", $spojeni);
    if(existujeVDB($oupPocetNacet)){
      $pocetPolozek = assoc($oupPocetNacet);
      $pocetPolozek = $pocetPolozek['pocet'];
      }          
    //echo "SELECT ou.*, count(oup.id) AS pocet, sum(cena_bez_dph) AS cena_bez_dph, SUM(cena_celkem) AS cena_celkem, '".date("H:i:s d.m.Y")."' AS cas_uzavreni, st.nazev AS stul, uziv.username AS uzavrel, kurz.hodnota AS kurz FROM otevrene_ucty AS ou JOIN otevrene_ucty_polozky AS oup ON ou.id = oup.id_uctu LEFT JOIN stoly AS st ON (ou.stul_id = st.id) LEFT JOIN uzivatel AS uziv ON (uziv.id = ".$uzivatel->getIDUser().") LEFT JOIN kurzovni_listek AS kurz ON (kurz.mena = 'EUR') WHERE ou.id = ".$ucetID." AND oup.id IN (".implode(",", $kPlaceni).") GROUP BY ou.id;<br />";exit;
    $ucetNacet = query("SELECT ou.*, count(oup.id) AS pocet, sum(cena_bez_dph) AS cena_bez_dph, SUM(cena_celkem) AS cena_celkem, '".date("H:i:s d.m.Y")."' AS cas_uzavreni, st.nazev AS stul, uziv.username AS uzavrel, kurz.hodnota AS kurz FROM otevrene_ucty AS ou JOIN otevrene_ucty_polozky AS oup ON ou.id = oup.id_uctu LEFT JOIN stoly AS st ON (ou.stul_id = st.id) LEFT JOIN uzivatel AS uziv ON (uziv.id = ".$uzivatel->getIDUser().") LEFT JOIN kurzovni_listek AS kurz ON (kurz.mena = 'EUR') WHERE ou.id = ".$ucetID." AND oup.id IN (".implode(",", $kPlaceni).") GROUP BY ou.id;", $spojeni);
    if(existujeVDB($ucetNacet)){
      $ucet = assoc($ucetNacet);
      $pocet = $ucet['pocet'];
      $cenaCelkem = $ucet['cena_celkem'];
      $ouID = $ucet['id']; 
      $tisk = "";
      /*****/
      if((!empty($vybranaslevahodn))&&($vybranaslevahodn != 0)){
        if($vybranaslevaproc == 1){
          $sleva = $vybranaslevahodn;
          $slevaZapis = $sleva; 
          }
        else{
          $sleva = 100*($vybranaslevahodn / $ucet['cena_bez_dph']);
          $slevaZapis = $vybranaslevahodn; 
          }
        if($sleva > 100){
          $sleva = 100;
          }
        }
      //echo "SELECT oup.*, rec.sleva_max AS sleva_max FROM otevrene_ucty_polozky AS oup JOIN receptury AS rec ON (oup.id_receptury = rec.id) WHERE oup.id IN (".implode(",", $kPlaceni).");<br />";exit;
      $oupNacet = query("SELECT oup.*, rec.sleva_max AS sleva_max, rec.id_sklad_odpis AS id_sklad_odpis, rec.nazev AS receptura FROM otevrene_ucty_polozky AS oup JOIN receptury AS rec ON (oup.id_receptury = rec.id) WHERE oup.id IN (".implode(",", $kPlaceni).");", $spojeni);
      if(existujeVDB($oupNacet)){
        while($oupVyber = assoc($oupNacet)){
          $nazev = $oupVyber['receptura']; 
          if($oupVyber['polovicni'] == 1){
            $nazev = "1/2 ".$nazev;
            }
          $tiskRec = $nazev;
          $tiskMnoz = $oupVyber['mnozstvi']."x";
          /**********/
          $oupID = $oupVyber['id'];
          $dphID = $oupVyber['dph_id']; 
          $sleva_max = $oupVyber['sleva_max'];
          /***/
          $preved[$oupID] = $oupVyber;
          unset($preved[$oupID]['id']);
          /***/  
          $cenaS = $oupVyber['cena_celkem']; 
          $cenaBez = $oupVyber['cena_bez_dph']; 
          $cenaDPH = $oupVyber['dph'];
          if(!empty($sleva)){
            if($sleva_max != -1){
              if(($sleva <= $sleva_max)||($sleva_max == 0)){
                $procenta = ((100-$sleva) / 100);
                }
              else{
                $procenta = ((100-$sleva_max) / 100);
                }
              $cenaS = round($oupVyber['cena_celkem'] * $procenta, 2); 
              $cenaBez = round($oupVyber['cena_bez_dph'] * $procenta, 2); 
              $cenaDPH = round($oupVyber['dph'] * $procenta, 2);
              } 
            }              
          if(!isset($ceny[$dphID])){
            $ceny[$dphID]['s'] = 0;
            $ceny[$dphID]['bez'] = 0;
            $ceny[$dphID]['dph'] = 0;
            }
          //echo "D:".$dphID."; S:".$cenaS." : ".$cenaBez." : ".$cenaDPH."<br />";
          $ceny[$dphID]['s'] += $cenaS;
          $ceny[$dphID]['bez'] += $cenaBez;
          $ceny[$dphID]['dph'] += $cenaDPH;
          $cenaKplatbe += $cenaS; 
          $kVytisteni[] = array($tiskMnoz, $tiskRec, $cenaS); 
          }
        //printrko($kVytisteni);
        /****************
         ****************/          
        $dphNazvy = dphNazvy($spojeni);
        $dph_id_0 = $dph_id_1 = $dph_id_2 = $zaklad_dph_0 = $zaklad_dph_1 = $zaklad_dph_2 = $dph_0 = $dph_1 = $dph_2 = 0; 
        /*****/
        if((!empty($vybranyservis))&&($vybranyservis==1)){
          $servisDPH = $dph[_servisDPH_]; 
          $servis = round($cenaCelkem * $servisDPH, 2);
          $servisCastka = round($servis / (1+$servisDPH),2);  
          //echo "CC:".$cenaCelkem.";<br />DD:".$dph[_servisDPH_].";<br />";
          //echo "S-:".$servis.";<br />SC:".$servisCastka.";<br />";
          //$cenaCelkem += $servis; 
          $servisZapis = ($dph[_servisDPH_]*100);
          if(!isset($ceny[_servisDPH_])){
            $ceny[_servisDPH_]['s'] = 0;
            $ceny[_servisDPH_]['bez'] = 0;
            $ceny[_servisDPH_]['dph'] = 0;
            }
          $servisCastkaDPH = ($servis * $servisDPH);
          $ceny[_servisDPH_]['s'] += $servisCastka;
          $ceny[_servisDPH_]['bez'] += ($servisCastka - $servisCastkaDPH);
          $ceny[_servisDPH_]['dph'] += $servisCastkaDPH;
          }
        /*****/
        if(!empty($sleva)){
          $slevaCastka = $cenaCelkem - $cenaKplatbe; 
          }      
        if(!empty($servisCastka)){
          $cenaKplatbe += $servisCastka; 
          //$cenaCelkem += $servis;
          }      
        /*****/
        $i = 0;
        $tiskDPH = array();
        foreach($ceny AS $cenyDPH => $cenyData){
          $nazevDPHcka = $dphNazvy[$cenyDPH]['nazev'];
          $j1 = "dph_id_".$i;
          $j2 = "zaklad_dph_".$i;
          $j3 = "dph_".$i;
         
          $$j1 = $cenyDPH;
          $$j2 = $cenyData['bez'];
          $$j3 = $cenyData['dph'];
          
          $tiskDPH[] = array(_tiskDPHZaklad_.":", $nazevDPHcka, cena($cenyData['bez']));
          $tiskDPH[] = array(_tiskDPH_.":", $nazevDPHcka, cena($cenyData['dph']));
          $i++;
          }
        /******************/
        /******************/  
        //echo "SELECT count(id) AS pocet FROM uzavrene_ucty WHERE ou_id = ".$ucetID.";<br />";
        //echo "SELECT count(id) AS pocet FROM uzavrene_ucty_polozky WHERE id_uctu = ".$ucetID.";<br />";//exit;                
        $uuNacet = query("SELECT count(id) AS pocet FROM uzavrene_ucty WHERE ou_id = ".$ucetID.";", $spojeni);
        $uupNacet = query("SELECT count(id) AS pocet FROM uzavrene_ucty_polozky WHERE id_uctu = ".$ucetID.";", $spojeni);
        if((existujeVDB($uuNacet))&&(existujeVDB($uupNacet))){
          $uuVyber = assoc($uuNacet);
          $uupVyber = assoc($uupNacet);
          if(($uuVyber['pocet'] != 0)||($uupVyber['pocet'] != 0)){
            //printrko();exit; 
            $kasa = kasa($ucet['pokladna_zalozeni']);
            $zalozeni = zalozeniUctu($spojeni, $uzivatel, $ucet['stul_id'], $kasa);
            if($zalozeni['stav']){
              $puvUcet = $ucetID;
              $ouID = $zalozeni['id'];
              echo "NCU:".$zalozeni['cislouctu'].";<br />";
              $ucet['cislo_uctu'] = $zalozeni['cislouctu'];
              }
            }
          //exit;
          /*
          echo "INSERT INTO `uzavrene_ucty` (`cislo_uctu`, `cas_zalozeni`, `cas_uzavreni`, `zakladajici_uzivatel`, `uzavirajici_uzivatel`, 
          `stul_id`,`typ_uctu`, `dph_id_0`, `dph_id_1`, `dph_id_2`, 
          `typ_platby`, `typ_slevy`, `ou_id`, `celkova_cena`, `dph_0`, 
          `dph_1`, `dph_2`, `servis`, `sleva`, `cena_kplatbe`, 
          `zaklad_dph_0`, `zaklad_dph_1`, `zaklad_dph_2`, `zakaznicka_karta`, `pokladna_zalozeni`, 
          `pokladna_uzavreni`, `id_skupiny_uctu`, `servis_castka`, `sleva_castka`, `puvodni_ucet`, 
          `uzavreni_skladu_id`, `id_karty`, `custom_text`, `zpracovat`)
  VALUES ('".$ucet['cislo_uctu']."', '".$ucet['cas_zalozeni']."', '".datum()."', '".$ucet['zakladajici_uzivatel']."', '".$uzivatel->getIDUser()."', 
  '".$stul."', '0', '".$dph_id_0."', '".$dph_id_1."', '".$dph_id_2."',  
  '".$vybranytyp."', '".$vybranaslevaproc."', '".$ouID."', '".round($cenaCelkem, 2)."', '".round($dph_0, 2)."', 
  '".round($dph_1, 2)."', '".round($dph_2, 2)."', '".$servisZapis."', '".$slevaZapis."', '".round($cenaKplatbe, 2)."', 
  '".round($zaklad_dph_0, 2)."', '".round($zaklad_dph_1, 2)."', '".round($zaklad_dph_2, 2)."', NULL, '".$ucet['pokladna_zalozeni']."', 
  '"._kasa_."', '0', '".round($servisCastka, 2)."', '".round($slevaCastka, 2)."', '".$puvUcet."',      
  '0', '0', NULL, NULL);<br />";
          echo "INSERT INTO `uzavrene_ucty_polozky` 
          (`id_uctu`, `id_receptury`, `id_uzivatele`, `cas_vlozeni`, `cena_bez_dph`, 
          `dph_id`, `dph`, `cena_celkem`, `mnozstvi`, `polovicni`, 
          `pokladna`, `pokladna_zalozeni`, `odepsano_mnozstvi`, `odepsano_cena`, `id_sklad_odpis`) 
          (SELECT '".$ouID."', `id_receptury`, `id_uzivatele`, `cas_vlozeni`, `cena_bez_dph`, 
          `dph_id`, `dph`, `cena_celkem`, `mnozstvi`, `polovicni`,
          `pokladna`, '".$ucet['pokladna_zalozeni']."', '0', '0.00', '0' FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID." AND id IN (".implode(",", $kPlaceni)."));<br />";       */
          //exit;
          /************************/
          /***** TISK UCTU ********/
          //exit;
          $tiskInfo = vypisInfo(tiskovePlatba(), $ucet);
          if((!_tiskPovolen_) && (!empty($kVytisteni))){
            $tiskCeny = array();
            $tiskCeny[] = array(_tiskSuma_, strval($ucet['cena_celkem'])." CZK ");
            $tiskCeny[] = array(_tiskPlatba_, strval(cena($cenaKplatbe))." CZK ");
            $tiskCeny[] = array(" ", strval(cena($cenaKplatbe / $ucet['kurz'],2))." EUR ");
            if($slevaCastka > 0){
              $tiskCeny[] = array(_tiskSleva_, strval(cena($slevaCastka))." CZK ");
              }
            if($servisCastka > 0){
              $tiskCeny[] = array(_tiskServis_, strval(cena($servisCastka))." CZK ");
              }
            /*********/
            $tiskarny = tiskarny();
            $tisk = new Printer();
            $tisk->format('utf2win');
            $tisk->select(_tiskarna_);
            $tisk->width(_tiskRadek_);
            $tisk->setMargin( 2, 2);
            $tisk->align('center');
            $tisk->wrap();        
            $tisk->fill("_");
            $tisk->wrap();       
            $tisk->insert($hlavicka);
            $tisk->wrap();       
            $tisk->fill("=");
            $tisk->wrap();        
            $tisk->right(4);
            $tisk->insert("VÁŠ ÚČET");
            $tisk->wrap();       
            $mezery = array(2); 
            $velikosti = array(13);
            $zarovnani = array("right", "left", 'right');
            $tisk->insertTable($tiskInfo, $velikosti, $zarovnani, $mezery); 
            $tisk->right(2);
            $tisk->fill("-");
            $tisk->wrap();        
            $tisk->right(4);
            $velikosti = array(_tiskMnoz_, 0, _tiskCena_);
            $tisk->insertTable($kVytisteni, $velikosti, $zarovnani, $mezery); 
            $tisk->right(2);
            $tisk->fill("-");
            $tisk->wrap();       
            $tisk->right(4);
            $velikosti = array(23);
            $zarovnani = array("right", "right");
            $tisk->insertTable($tiskCeny, $velikosti, $zarovnani, $mezery);
            $tisk->wrap();     
            $tisk->right(2);
            $tisk->insert(_tiskZpusob_);
            $tisk->wrap();     
            $tisk->insert(platbyUcet($vybranytyp));
            $tisk->wrap();     
            $tisk->wrap();     
            $tisk->right(5);
            $mezery = array(1); 
            $velikosti = array(16, 0, 7);
            $zarovnani = array("right", "left", "right");
            $tisk->insertTable($tiskDPH, $velikosti, $zarovnani, $mezery);
            $tisk->wrap();     
            $tisk->right(2);
            $tisk->fill("=");
            $tisk->wrap();       
            $tisk->insert($paticka);
            $tisk->wrap();       
            $tisk->fill("-");
            $tisk->wrap();      
            echo $tisk->show_buffer();$vytisteno = true;//exit;
            //$vytisteno = $tisk->print_buffer();
            if($vytisteno){
              echo "vytisten pokus pro tiskarnu:".$tiskarny[_tiskarnaID_]."...<br />";
              }
            else{
              echo "nic nevytisteno pro tiskarnu:".$tiskarny[_tiskarnaID_]."...<br />";
              }
            }
          exit;
          /***********************/
          /***********************/
          $ou = query("INSERT INTO `uzavrene_ucty` (`cislo_uctu`, `cas_zalozeni`, `cas_uzavreni`, `zakladajici_uzivatel`, `uzavirajici_uzivatel`, 
          `stul_id`,`typ_uctu`, `dph_id_0`, `dph_id_1`, `dph_id_2`, `typ_platby`, `typ_slevy`, `ou_id`, `celkova_cena`, `dph_0`, `dph_1`, `dph_2`, `servis`, `sleva`, `cena_kplatbe`, `zaklad_dph_0`, `zaklad_dph_1`, `zaklad_dph_2`, `zakaznicka_karta`, `pokladna_zalozeni`, `pokladna_uzavreni`, `id_skupiny_uctu`, `servis_castka`, `sleva_castka`, `puvodni_ucet`, `uzavreni_skladu_id`, `id_karty`, `custom_text`, `zpracovat`) VALUES ('".$ucet['cislo_uctu']."', '".$ucet['cas_zalozeni']."', '".datum()."', '".$ucet['zakladajici_uzivatel']."', '".$uzivatel->getIDUser()."', '".$stul."', '0', '".$dph_id_0."', '".$dph_id_1."', '".$dph_id_2."', '".$vybranytyp."', '".$vybranaslevaproc."', '".$ouID."', '".round($cenaCelkem, 2)."', '".round($dph_0, 2)."', '".round($dph_1, 2)."', '".round($dph_2, 2)."', '".$servisZapis."', '".$slevaZapis."', '".round($cenaKplatbe, 2)."', '".round($zaklad_dph_0, 2)."', '".round($zaklad_dph_1, 2)."', '".round($zaklad_dph_2, 2)."', NULL, '".$ucet['pokladna_zalozeni']."', '"._kasa_."', '0', '".round($servisCastka, 2)."', '".round($slevaCastka, 2)."', '".$puvUcet."', '0', '0', NULL, NULL);", $spojeni);
          $oup = query("INSERT INTO `uzavrene_ucty_polozky` 
          (`id_uctu`, `id_receptury`, `id_uzivatele`, `cas_vlozeni`, `cena_bez_dph`, 
          `dph_id`, `dph`, `cena_celkem`, `mnozstvi`, `polovicni`, 
          `pokladna`, `pokladna_zalozeni`, `odepsano_mnozstvi`, `odepsano_cena`, `id_sklad_odpis`) 
          (SELECT '".$ouID."', `id_receptury`, `id_uzivatele`, `cas_vlozeni`, `cena_bez_dph`, 
          `dph_id`, `dph`, `cena_celkem`, `mnozstvi`, `polovicni`,
          `pokladna`, '".$ucet['pokladna_zalozeni']."', '0', '0.00', '0' FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID." AND id IN (".implode(",", $kPlaceni)."));", $spojeni);
          if($pocetPolozek == sizeof($preved)){
            echo "DELETE FROM otevrene_ucty WHERE id = ".$ucetID." LIMIT 1;<br />"; 
            $smazanoNOU = query("DELETE FROM otevrene_ucty WHERE id = ".$ucetID." LIMIT 1;", $spojeni);
            }
          //echo "DELETE FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID." AND id IN (".implode(",", $kPlaceni).");<br />";
          $smazanoOUP = query("DELETE FROM otevrene_ucty_polozky WHERE id_uctu = ".$ucetID." AND id IN (".implode(",", $kPlaceni).");", $spojeni);
          if($smazanoOUP){
            $vrat = true;
            }
          /*
          elseif($pocetPolozek > sizeof($kPlaceni)){
            
            
            }
            */  
          }
        } 
      } 
    }
  else{
    //echo "bbbb;<br />";
    }
  //echo "VRAT:".$vrat.";<br />";
  //exit;                                                  
  return $vrat;
  }
function konceUctu($textik, $radek){
  //$radek = 17;
  $vrat = "";
  $deleni = explode("\n", $textik);
  if(!empty($deleni)){
    foreach($deleni AS $key => $text){
      $zbytek = $text;
      do{
        $aktualni = substr($zbytek, 0, $radek);
        $delka = strlen($aktualni);
        $chybi =  floor(($radek - $delka)/2);
        for($i = 0; $i <= $chybi; $i++){
          $vrat .= " ";
          } 
        $vrat .= $aktualni."\n"; 
        $zbytek = substr($zbytek, $radek); 
        //echo $key.":: R:".$radek."; D:".$delka."; Z:".strlen($zbytek).";";
        }
      while((!empty($zbytek))&&(strlen($zbytek)>0));
      }
    }
  return $vrat;
  }
function vypisInfo($pole, $vyber){
  $vrat = array();
  if(!empty($pole)){
    foreach($pole AS $vybrane => $text){
      if(array_key_exists($vybrane, $vyber)){
        $vrat[] = array($text['text'].":", $vyber[$vybrane]);
        }
      }
    }
  return $vrat;
  }
function vypisInfo2($pole, $vyber, $max, $pred = 0){
  $vrat = "";
  $znaku = 0;
  if(!empty($pole)){
    $zprac = array();
    foreach($pole AS $key => $tisk){
      $nazev = $tisk['text'].":"; 
      $zprac[ucfirst($nazev)] = $vyber[$key];
      if($znaku < strlen($nazev)){
        $znaku = strlen($nazev);
        }
      }
    if(!empty($zprac)){
      foreach($zprac AS $key => $data){
        $delkaKey = delkaUTF($key);
        $delkaData = delkaUTF($data);
        $chybi = $pred + $znaku;
        $radek = $max - $chybi;
        //echo "M:".$max.";Z:".$znaku.";P:".$pred.";R:".$radek.";<br />";
        $aktualni = substr($data, 0, $radek);
        $zbytek = substr($data, $radek);
        /***/
        for($k = 0; $k < $pred; $k++){
          $vrat .= " ";
          }
        $vrat .= $key;
        for($j = $delkaKey; $j <= $znaku;$j++){
          $vrat .= " ";
          }
        $vrat .= $aktualni."\n";
        while((!empty($zbytek))&&(strlen($zbytek)>0)){
          $aktualni = substr($zbytek, 0, $radek);
          for($i = 0; $i <= $chybi; $i++){
            $vrat .= " ";
            } 
          $vrat .= $aktualni."\n"; 
          $zbytek = substr($zbytek, $radek); 
          }
        //$vrat .= " ".$data."\n";
        }
      }
    }
  //exit;
  return $vrat;
  }
function vyplnText(&$text, $radek, $zacatek = 0, $vlozeni = " "){
  for($i = $zacatek; $i < ($radek-(2*$zacatek)) ; $i++){
    $text .= $vlozeni;
    }              
  }  
function dphNazvy($spojeni){
  $vrat = array();
  $nacet = query("SELECT * FROM dph;",$spojeni);
  if(existujeVDB($nacet)){
    while($vyber = assoc($nacet)){
      $vrat[$vyber['id']]['nazev'] = $vyber['nazev'];
      $vrat[$vyber['id']]['koef'] = $vyber['koeficient'];
      }
    }
  return $vrat;
  }  
  
function zmenaStoluUctu($spojeni, $uzivatel, $ucet, $novyStul){
  $vrat = 2;
  if(($uzivatel->getPravo(8))&&(!empty($ucet))&&(!empty($novyStul))){
    echo "SELECT * FROM otevrene_ucty WHERE id = ".$ucet." LIMIT 1;<br />"; 
    $nacet = query("SELECT * FROM otevrene_ucty WHERE id = ".$ucet." LIMIT 1;", $spojeni);
    if(existujeVDB($nacet)){
      $vyber = assoc($nacet);
      echo "UPDATE otevrene_ucty SET stul_id = ".$novyStul." WHERE id = ".$ucet." LIMIT 1;<br />";
      $presun = query("UPDATE otevrene_ucty SET stul_id = ".$novyStul." WHERE id = ".$ucet." LIMIT 1;", $spojeni);
      if($presun){
        $vrat = 1;
        }
      }
    }
  return $vrat;
  }  
  
function zmenaPlatby($stulUcty, $ucetID){
  if(!isset($stulUcty[$ucetID])){
    echo "chyba!!!<br />";
    exit;
    }
  $ucet = $stulUcty[$ucetID];
  $typ1 = $typ2 = $typ3 = "";
  $platby = platby();
  $typPlatby = $ucet['platba'];
  if(!array_key_exists($typPlatby, $platby)){
    $typPlatby = 0;
    } 
  $typ0 = "typ".($typPlatby+1);
  $$typ0 = 'akt';
  /********/        
  echo "<form action='?page=zmenatypu' method='post' id='objednavkaform'>\n";
  echo "  <div id='zmenaplatby'>\n";
  echo "    <input type='hidden' name='vybranytyp' id='vybranytyp' value='".$typPlatby."'/>\n";
  echo "    <input type='hidden' name='ucet' value='".$ucetID."'/>\n";
  echo "    <div id='vybertypu'>\n";
  echo "      <div>Změnit typ platby '".$platby[$typPlatby]."' na:</div>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 0);\" class='typplatby".$typ1."' id='typplatby0'>Hotově</a>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 1);\" class='typplatby".$typ2."' id='typplatby1'>Kartou</a>\n";
  echo "      <a href=\"javascript: vlozAZmen('vybranytyp', 'typplatby', 2);\" class='typplatby".$typ3."' id='typplatby2'>Fakturou</a>\n";
  echo "    </div>\n";
  echo "    <div id='ucetinfo'>\n";
  echo "      <div>Číslo účtu: <span class='tucne'>".$ucet['cislo']."</span></div>\n";
  echo "      <div>Cena k platbě: <span class='tucne'>".$ucet['kplatbe']."</span></div>\n";
  echo "      <div>Cena celkem: <span class='tucne'>".$ucet['celkem']."</span></div>\n";
  echo "      <div>Cena položek: <span class='tucne'>".$ucet['cena']."</span></div>\n";
  echo "      <div>Založil: <span class='tucne'>".$ucet['zalozil']."</span></div>\n";
  echo "      <div>Uzavřen: <span class='tucne'>".$ucet['uzavrel']."</span></div>\n";
  echo "      <div>Založeno: <span class='tucne'>".datumUnix($ucet['zalozeno'])."</span></div>\n";
  echo "      <div>Uzavřeno: <span class='tucne'>".datumUnix($ucet['uzavreno'])."</span></div>\n";
  echo "      <div>Pokladna založení: <span class='tucne'>".$ucet['kasa']['kasa']."</span></div>\n";
  echo "      <div>Pokladna uzavření: <span class='tucne'>".$ucet['kasa2']['kasa']."</span></div>\n";
  echo "    </div>\n";
  echo "  </div>\n";
  echo "</form>\n";                                                                                        
  echo "<script type='text/javascript'>
    function zkontroluj(){
      var mnoz = $('#vybranytyp').length;
      if((mnoz > 0)){
        $('#objednavkaform').submit();
        }
      }
    </script>";
  }  
  
function zmenaTypuPlatby($spojeni, $uzivatel, $ucetID){
  $vrat = 2;
  $typPlatby = post('vybranytyp');
  if(empty($typPlatby)){
    $typPlatby = 0;
    }
  //echo "SELECT uu.* FROM uzavrene_ucty AS uu  WHERE uu.id = ".$ucetID.";<br />"; 
  $nacet = query("SELECT uu.* FROM uzavrene_ucty AS uu WHERE uu.id = ".$ucetID.";", $spojeni);
  if(existujeVDB($nacet)){
    $vyber = assoc($nacet);
    //echo "UPDATE uzavrene_ucty SET typ_platby = ".$typPlatby." WHERE id = ".$ucetID.";<br />"; 
    $uprava = query("UPDATE uzavrene_ucty SET cas_zalozeni = cas_zalozeni, cas_uzavreni = cas_uzavreni, typ_platby = ".$typPlatby." WHERE id = ".$ucetID.";", $spojeni);
    if($uprava){
      $vrat = true;
      }
    }
  return $vrat;
  }  
function infoUzavrenyUcet($spojeni, $ucet){
  $vrat = null;
  //echo "SELECT uu.*, ka1.nazev AS kasazalozeni, ka2.nazev AS kasauzavreni, st.nazev AS stul, uzzal.username AS zalozil, uzuza.username AS uzavrel FROM uzavrene_ucty AS uu JOIN stoly AS st ON uu.stul_id = st.id JOIN kasa AS ka1 ON uu.pokladna_zalozeni = ka1.id JOIN kasa AS ka2 ON uu.pokladna_uzavreni = ka2.id JOIN uzivatel AS uzzal ON uu.zakladajici_uzivatel = uzzal.id JOIN uzivatel AS uzuza ON uu.uzavirajici_uzivatel = uzuza.id WHERE uu.id = ".$ucet." LIMIT 1;<br />"; 
  $nacet = query("SELECT uu.*, ka1.nazev AS kasazalozeni, ka2.nazev AS kasauzavreni, st.nazev AS stul, uzzal.username AS zalozil, uzuza.username AS uzavrel FROM uzavrene_ucty AS uu JOIN stoly AS st ON uu.stul_id = st.id JOIN kasa AS ka1 ON uu.pokladna_zalozeni = ka1.id JOIN kasa AS ka2 ON uu.pokladna_uzavreni = ka2.id JOIN uzivatel AS uzzal ON uu.zakladajici_uzivatel = uzzal.id JOIN uzivatel AS uzuza ON uu.uzavirajici_uzivatel = uzuza.id WHERE uu.id = ".$ucet." LIMIT 1;",$spojeni);
  if(existujeVDB($nacet)){
    $vrat = assoc($nacet);
    }
  return $vrat;
  //exit;
  }

  
  
function dotisk($spojeni, $uzivatel, $stulUcty, $oup, $ucet){
  $vytisteno = 2;
  if(!_tiskPovolen_){
    $textPole = array();
    $hlavicka = $paticka = "";
    $ucetInfo = infoUzavrenyUcet($spojeni, $ucet);
    $ucetInfo['cas_zalozeni'] = datetimeToDate($ucetInfo['cas_zalozeni']);
    $ucetInfo['cas_uzavreni'] = datetimeToDate($ucetInfo['cas_uzavreni']);
    $tiskarny = tiskarny();
    $tiskove = tiskoveUzavrene();
    $tiskNadpis = array();
    foreach($tiskove AS $tiskKey => $tiskData){
      $tiskNadpis[] = array(ucfirst($tiskData['text']).":", $ucetInfo[$tiskKey]);
      }
    $polPole = polozkyUzavrenehoUctu($stulUcty[$ucet]['ouid']);
    foreach($polPole AS $polKey => $polData){
      $textPole[] = array($polData['mnoz']."x", $polData['rec'], $polData['cena']);
      }
    $tiskInfo = vypisInfo(tiskovePlatba(), $ucetInfo);
    $tiskCeny = array();
    $tiskCeny[] = array(_tiskSuma_, strval($ucetInfo['celkova_cena'])." CZK ");
    $tiskCeny[] = array(_tiskPlatba_, strval(cena($ucetInfo['cena_kplatbe']))." CZK ");
    $tiskCeny[] = array(" ", strval(cena($ucetInfo['cena_kplatbe'] / $ucet['kurz'],2))." EUR ");
    if($ucetInfo['sleva_castka'] > 0){
      $tiskCeny[] = array(_tiskSleva_, strval(cena($ucetInfo['sleva_castka']))." CZK ");
      }
    if($ucetInfo['servis_castka'] > 0){
      $tiskCeny[] = array(_tiskServis_, strval(cena($ucetInfo['servis_castka']))." CZK ");
      }
    $dphNazvy = dphNazvy($spojeni);
    $tiskDPH = array();
    if((!empty($ucetInfo['dph_id_0']))&&(!empty($ucetInfo['dph_0']))){
      $nazevDPHcka = $dphNazvy[$ucetInfo['dph_id_0']]['nazev']; 
      $tiskDPH[] = array(_tiskDPHZaklad_.":", $nazevDPHcka, cena($ucetInfo['dph_0']));
      $tiskDPH[] = array(_tiskDPH_.":", $nazevDPHcka, cena($ucetInfo['dph_0']));
      }
    if((!empty($ucetInfo['dph_id_1']))&&(!empty($ucetInfo['dph_1']))){
      $nazevDPHcka = $dphNazvy[$ucetInfo['dph_id_1']]['nazev']; 
      $tiskDPH[] = array(_tiskDPHZaklad_.":", $nazevDPHcka, cena($ucetInfo['dph_1']));
      $tiskDPH[] = array(_tiskDPH_.":", $nazevDPHcka, cena($ucetInfo['dph_1']));
      }
    if((!empty($ucetInfo['dph_id_2']))&&(!empty($ucetInfo['dph_2']))){
      $nazevDPHcka = $dphNazvy[$ucetInfo['dph_id_2']]['nazev']; 
      $tiskDPH[] = array(_tiskDPHZaklad_.":", $nazevDPHcka, cena($ucetInfo['dph_2']));
      $tiskDPH[] = array(_tiskDPH_.":", $nazevDPHcka, cena($ucetInfo['dph_2']));
      }
    //echo "SELECT str.* FROM kasa AS kas LEFT JOIN stredisko AS str ON (kas.stredisko = str.id) WHERE kas.id = "._kasa_." LIMIT 1;<br />";exit; 
    $strNacet = query("SELECT str.* FROM kasa AS kas LEFT JOIN stredisko AS str ON (kas.stredisko = str.id) WHERE kas.id = "._kasa_." LIMIT 1;",$spojeni);
    if(existujeVDB($strNacet)){
      $stredisko = assoc($strNacet);
      $hlavicka = $stredisko['hlavicka'];
      $paticka = $stredisko['paticka'];
      }
    $tiskarna = $tiskarny[_tiskarnaID_];
    $tisk = new Printer();
    $tisk->select($tiskarna);
    $tisk->format('utf2win');
    $tisk->width(_tiskRadek_);
    $tisk->setMargin( 1, 1);
    $tisk->align('center');
    $tisk->wrap();        
    $tisk->fill("_");
    $tisk->wrap();       
    $tisk->insert($hlavicka);
    $tisk->wrap();       
    $tisk->fill("=");
    $tisk->wrap();        
    $tisk->right(6);
    $tisk->insert(_tiskVasUcet_);
    $tisk->wrap();       
    $mezery = array(2); 
    $velikosti = array(13);
    $zarovnani = array("right", "left", 'right');
    $tisk->insertTable($tiskNadpis, $velikosti, $zarovnani, $mezery); 
    $tisk->right(1);
    $tisk->fill("-");
    $tisk->wrap();        
    $tisk->right(4);
    $velikosti = array(_tiskMnoz_, 0, _tiskCena_);
    $tisk->insertTable($textPole, $velikosti, $zarovnani, $mezery); 
    $tisk->right(1);
    $tisk->fill("-");
    $tisk->wrap();       
    $tisk->right(4);
    $velikosti = array(23);
    $zarovnani = array("right", "right");
    $tisk->insertTable($tiskCeny, $velikosti, $zarovnani, $mezery);
    $tisk->wrap();     
    $tisk->insert("======== "._tiskKopie_." ========");
    $tisk->wrap();       
    $tisk->right(1);
    $tisk->insert(_tiskZpusob_);
    $tisk->wrap();     
    $tisk->insert(platbyUcet($ucetInfo['typ_platby']));
    $tisk->wrap();     
    $tisk->wrap();     
    $tisk->right(5);
    $mezery = array(1); 
    $velikosti = array(16, 0, 7);
    $zarovnani = array("right", "left", "right");
    $tisk->insertTable($tiskDPH, $velikosti, $zarovnani, $mezery);
    $tisk->wrap();     
    $tisk->right(1);
    $tisk->fill("=");
    $tisk->wrap();       
    $tisk->insert($paticka);
    $tisk->wrap();       
    $tisk->fill("-");
    $tisk->wrap();      
    //echo $tisk->show_buffer();//exit;
    $vytisteno = $tisk->print_buffer();
    if($vytisteno){
      $vytisteno = 1;
      }
    }
  //exit;
  return $vytisteno;
  }  
  
  
  
  
  
  
  
?>