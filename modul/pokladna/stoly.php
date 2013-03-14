<?PHP
function stoly($skst = null, $otevrene = true){
  $sql = "";
  if(!empty($skst)){
    $sql = " skst.id = ".$skst." AND ";
    }
  $vrat = array();
  if($otevrene == true){
    $tabulka = "otevrene_ucty";
    $zkratka = "ou";
    }
  else{
    $tabulka = "uzavrene_ucty";
    $zkratka = "uu";
    }
  //echo "SELECT skst.id AS skst, skst.specialni AS specialni, skst.vip AS skstvip, skst.nazev AS skupina, st.id AS stid, st.nazev AS stul, st.poradi AS stporadi,st.vip as stvip, count(".$zkratka.".id) AS ucty FROM skupina_stolu AS skst LEFT JOIN stoly AS st ON (st.skupina_id = skst.id) LEFT JOIN ".$tabulka." AS ".$zkratka." ON (".$zkratka.".stul_id = st.id) WHERE ".$sql." skst.smazano = 0 AND st.smazano = 0 GROUP BY skst.id, st.id ORDER BY skst.poradi ASC, skst.nazev ASC, st.poradi ASC, st.nazev ASC;<br />";
  $nacet = query("SELECT skst.id AS skst, skst.specialni AS specialni, skst.vip AS skstvip, skst.nazev AS skupina, st.id AS stid, st.nazev AS stul, st.poradi AS stporadi,st.vip as stvip, count(".$zkratka.".id) AS ucty FROM skupina_stolu AS skst LEFT JOIN stoly AS st ON (st.skupina_id = skst.id) LEFT JOIN ".$tabulka." AS ".$zkratka." ON (".$zkratka.".stul_id = st.id) WHERE ".$sql." skst.smazano = 0 AND st.smazano = 0 GROUP BY skst.id, st.id ORDER BY skst.poradi ASC, skst.nazev ASC, st.poradi ASC, st.nazev ASC;");
  if(existujeVDB($nacet)){
    $posledniSKST = null;
    while($vyber = assoc($nacet)){
      if($posledniSKST != $vyber['skst']){
        $vrat[$vyber['skst']]['nazev'] = $vyber['skupina'];
        $vrat[$vyber['skst']]['poradi'] = $vyber['stporadi'];
        $vrat[$vyber['skst']]['stoly'] = array();
        $posledniSKST = $vyber['skst'];
        }
      $vrat[$vyber['skst']]['stoly'][$vyber['stid']]['nazev'] = $vyber['stul'];  
      $vrat[$vyber['skst']]['stoly'][$vyber['stid']]['poradi'] = $vyber['stporadi'];
      $vrat[$vyber['skst']]['stoly'][$vyber['stid']]['ucty'] = $vyber['ucty'];  
      }
    }
  return $vrat; 
  }
  
function stulUcty($stul, $ucet){
  $vrat = $stulUcty = array();
  $oup = null;
  if(empty($ucet)){
    //echo "SELECT ou.*, SUM(oup.cena_celkem) AS cena, IFNULL(uz.username,'--chyba--') AS zalozil, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno FROM otevrene_ucty AS ou LEFT JOIN otevrene_ucty_polozky AS oup ON (oup.id_uctu = ou.id) LEFT JOIN uzivatel AS uz ON (uz.id = ou.zakladajici_uzivatel) WHERE ou.stul_id = ".$stul." GROUP BY ou.id;<br />";exit; 
    $stulUctyNacet = query("SELECT ou.*, SUM(oup.cena_celkem) AS cena, IFNULL(uz.username,'--chyba--') AS zalozil, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno FROM otevrene_ucty AS ou LEFT JOIN otevrene_ucty_polozky AS oup ON (oup.id_uctu = ou.id) LEFT JOIN uzivatel AS uz ON (uz.id = ou.zakladajici_uzivatel) WHERE ou.stul_id = ".$stul." GROUP BY ou.id;");
    if(existujeVDB($stulUctyNacet)){
      while($stulUctyVyber = assoc($stulUctyNacet)){
        $stulUcty[$stulUctyVyber['id']]['cislo'] = $stulUctyVyber['cislo_uctu'];    
        $stulUcty[$stulUctyVyber['id']]['cena'] = $stulUctyVyber['cena'];    
        $stulUcty[$stulUctyVyber['id']]['zalozil'] = $stulUctyVyber['zalozil'];    
        $stulUcty[$stulUctyVyber['id']]['zalozeno'] = $stulUctyVyber['zalozeno'];
        $kasa = kasa($stulUctyVyber['pokladna_zalozeni']);    
        $stulUcty[$stulUctyVyber['id']]['kasa'] = $kasa;    
        }
      mysql_free_result($stulUctyNacet);
      if(sizeof($stulUcty)==1){
        $ucet = key($stulUcty);
        $oup = polozkyUctu($ucet);
        //exit;
        }
      }
    }
  else{
    //echo "SELECT ou.*, SUM(oup.cena_celkem) AS cena, IFNULL(uz.username,'--chyba--') AS zalozil, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno FROM otevrene_ucty AS ou LEFT JOIN otevrene_ucty_polozky AS oup ON (oup.id_uctu = ou.id) LEFT JOIN uzivatel AS uz ON (uz.id = ou.zakladajici_uzivatel) WHERE ou.stul_id = ".$stul." GROUP BY ou.id;<br />";exit; 
    $stulUctyNacet = query("SELECT ou.*, SUM(oup.cena_celkem) AS cena, IFNULL(uz.username,'--chyba--') AS zalozil, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno FROM otevrene_ucty AS ou LEFT JOIN otevrene_ucty_polozky AS oup ON (oup.id_uctu = ou.id) LEFT JOIN uzivatel AS uz ON (uz.id = ou.zakladajici_uzivatel) WHERE ou.stul_id = ".$stul." AND ou.id = ".$ucet." GROUP BY ou.id;");
    if(existujeVDB($stulUctyNacet)){
      $stulUctyVyber = assoc($stulUctyNacet);
      $stulUcty[$stulUctyVyber['id']]['cislo'] = $stulUctyVyber['cislo_uctu'];    
      $stulUcty[$stulUctyVyber['id']]['cena'] = $stulUctyVyber['cena'];    
      $stulUcty[$stulUctyVyber['id']]['zalozil'] = $stulUctyVyber['zalozil'];    
      $stulUcty[$stulUctyVyber['id']]['zalozeno'] = $stulUctyVyber['zalozeno'];
      $kasa = kasa($stulUctyVyber['pokladna_zalozeni']);    
      $stulUcty[$stulUctyVyber['id']]['kasa'] = $kasa;    
      $oup = polozkyUctu($stulUctyVyber['id']);
      mysql_free_result($stulUctyNacet);
      }
    else{
      $ucet = null;
      }
    }
  $vrat['ucet'] = $ucet;
  $vrat['oup'] = $oup; 
  $vrat['stulUcty'] = $stulUcty;
  return $vrat;
  }

function stulUzavreneUcty($stul, $ucet){
  $vrat = $stulUcty = array();
  $oup = null;
  if(empty($ucet)){                                     
    //echo "SELECT uu.*, SUM(uup.cena_celkem) AS cena, IFNULL(uz1.username,'--chyba--') AS zalozil, IFNULL(uz2.username,'--chyba--') AS uzavrel, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno, UNIX_TIMESTAMP(cas_uzavreni) AS uzavreno FROM uzavrene_ucty AS uu LEFT JOIN uzavrene_ucty_polozky AS uup ON (uup.id_uctu = uu.ou_id) LEFT JOIN uzivatel AS uz1 ON (uz1.id = uu.zakladajici_uzivatel) LEFT JOIN uzivatel AS uz2 ON (uz2.id = uu.uzavirajici_uzivatel) WHERE uu.stul_id = ".$stul." GROUP BY uu.id;<br />";exit; 
    $stulUctyNacet = query("SELECT uu.*, SUM(uup.cena_celkem) AS cena, IFNULL(uz1.username,'--chyba--') AS zalozil, IFNULL(uz2.username,'--chyba--') AS uzavrel, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno, UNIX_TIMESTAMP(cas_uzavreni) AS uzavreno FROM uzavrene_ucty AS uu LEFT JOIN uzavrene_ucty_polozky AS uup ON (uup.id_uctu = uu.ou_id) LEFT JOIN uzivatel AS uz1 ON (uz1.id = uu.zakladajici_uzivatel) LEFT JOIN uzivatel AS uz2 ON (uz2.id = uu.uzavirajici_uzivatel) WHERE uu.stul_id = ".$stul." GROUP BY uu.id;");
    if(existujeVDB($stulUctyNacet)){
      while($stulUctyVyber = assoc($stulUctyNacet)){
        $stulUcty[$stulUctyVyber['id']]['cislo'] = $stulUctyVyber['cislo_uctu'];    
        $stulUcty[$stulUctyVyber['id']]['cena'] = $stulUctyVyber['cena'];    
        $stulUcty[$stulUctyVyber['id']]['zalozil'] = $stulUctyVyber['zalozil'];    
        $stulUcty[$stulUctyVyber['id']]['zalozeno'] = $stulUctyVyber['zalozeno'];
        $kasa = kasa($stulUctyVyber['pokladna_zalozeni']);    
        $stulUcty[$stulUctyVyber['id']]['uzavrel'] = $stulUctyVyber['uzavrel'];    
        $stulUcty[$stulUctyVyber['id']]['uzavreno'] = $stulUctyVyber['uzavreno'];
        $stulUcty[$stulUctyVyber['id']]['ouid'] = $stulUctyVyber['ou_id'];
        $kasa2 = kasa($stulUctyVyber['pokladna_uzavreni']);    
        $stulUcty[$stulUctyVyber['id']]['kasa'] = $kasa;    
        $stulUcty[$stulUctyVyber['id']]['kasa2'] = $kasa2;    
        }
      mysql_free_result($stulUctyNacet);
      if(sizeof($stulUcty)==1){
        $ucet = key($stulUcty);
        $oup = polozkyUctu($ucet);
        //exit;
        }
      }
    }
  else{
    //echo "SELECT uu.*, SUM(uup.cena_celkem) AS cena, IFNULL(uz1.username,'--chyba--') AS zalozil, IFNULL(uz2.username,'--chyba--') AS uzavrel, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno, UNIX_TIMESTAMP(cas_uzavreni) AS uzavreno FROM uzavrene_ucty AS uu LEFT JOIN uzavrene_ucty_polozky AS uup ON (uup.id_uctu = uu.ou_id) LEFT JOIN uzivatel AS uz1 ON (uz1.id = uu.zakladajici_uzivatel) LEFT JOIN uzivatel AS uz2 ON (uz2.id = uu.uzavirajici_uzivatel) WHERE uu.stul_id = ".$stul." AND uu.id = ".$ucet." GROUP BY uu.id;<br />";exit; 
    $stulUctyNacet = query("SELECT uu.*, SUM(uup.cena_celkem) AS cena, IFNULL(uz1.username,'--chyba--') AS zalozil, IFNULL(uz2.username,'--chyba--') AS uzavrel, UNIX_TIMESTAMP(cas_zalozeni) AS zalozeno, UNIX_TIMESTAMP(cas_uzavreni) AS uzavreno FROM uzavrene_ucty AS uu LEFT JOIN uzavrene_ucty_polozky AS uup ON (uup.id_uctu = uu.ou_id) LEFT JOIN uzivatel AS uz1 ON (uz1.id = uu.zakladajici_uzivatel) LEFT JOIN uzivatel AS uz2 ON (uz2.id = uu.uzavirajici_uzivatel) WHERE uu.stul_id = ".$stul." AND uu.id = ".$ucet." GROUP BY uu.id;");
    if(existujeVDB($stulUctyNacet)){
      $stulUctyVyber = assoc($stulUctyNacet);
      $stulUcty[$stulUctyVyber['id']]['cislo'] = $stulUctyVyber['cislo_uctu'];    
      $stulUcty[$stulUctyVyber['id']]['cena'] = $stulUctyVyber['cena'];    
      $stulUcty[$stulUctyVyber['id']]['zalozil'] = $stulUctyVyber['zalozil'];    
      $stulUcty[$stulUctyVyber['id']]['zalozeno'] = $stulUctyVyber['zalozeno'];
      $stulUcty[$stulUctyVyber['id']]['uzavrel'] = $stulUctyVyber['uzavrel'];    
      $stulUcty[$stulUctyVyber['id']]['uzavreno'] = $stulUctyVyber['uzavreno'];
      $stulUcty[$stulUctyVyber['id']]['platba'] = $stulUctyVyber['typ_platby'];    
      $stulUcty[$stulUctyVyber['id']]['kplatbe'] = $stulUctyVyber['cena_kplatbe'];    
      $stulUcty[$stulUctyVyber['id']]['celkem'] = $stulUctyVyber['celkova_cena'];    
      $stulUcty[$stulUctyVyber['id']]['ouid'] = $stulUctyVyber['ou_id'];
        $kasa = kasa($stulUctyVyber['pokladna_zalozeni']);    
      $kasa2 = kasa($stulUctyVyber['pokladna_uzavreni']);    
      $stulUcty[$stulUctyVyber['id']]['kasa'] = $kasa;    
      $stulUcty[$stulUctyVyber['id']]['kasa2'] = $kasa2;    
      mysql_free_result($stulUctyNacet);
      }
    else{
      $ucet = null;
      }
    }
  $vrat['ucet'] = $ucet;
  $vrat['oup'] = $oup; 
  $vrat['stulUcty'] = $stulUcty;
  return $vrat;
  }

function ucetNaStole($page, $stul, $stulUcty, $ucet = null, $uzavrene = false){
  $stulnazev = stul($stul);
  $oup = "";
  $i = 0;
  if(empty($ucet)){
    $ucet = null;
    }
  //printrko($stulUcty);
  /***************************************/
  /***** VYBER UCTU Z JEDNOHO STOLU ******/
  echo "<div id='tmavepozadi'></div>";
  echo "<div class='nadpis'>Výběr účtu ze stolu: <span class='tucne'>".$stulnazev."</span></div>\n";
  echo "<div id='vybeructu'>\n";
  echo "  <a href=\"javascript: posun('uctydiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "  <a href=\"javascript: posun('uctydiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  <div id='uctydiv'>\n";
  echo "    <div>\n";
  foreach($stulUcty AS $key => $data){
    echo "    <div class='ucet'>\n";
    echo "      <a href=\"javascript: ukazobsah('obsahuctu', 'obsahuctu".$i."');\" class='ucet'>\n";
    echo "        <div>Číslo účtu:<span class='tucne'>".$data['cislo']."</span></div>\n";
    echo "        <div>Cena celkem:<span class='tucne'>".$data['cena']."</span></div>\n";
    echo "        <div>Založil:<span class='tucne'>".$data['zalozil']."</span></div>\n";
    echo "        <div>Založeno:<span class='tucne'>".datumUnix($data['zalozeno'])."</span></div>\n";
    echo "        <div>Pokladna:<span class='tucne'>".$data['kasa']['kasa']."</span></div>\n";
    echo "      </a>";
    echo "      <a href=\"?page=".$page."&amp;stul=".$stul."&amp;ucet=".$key."\" class='odkaz'><div>Vybrat účet</div><div class='tucne'>".$data['cislo']."</div></a>";
    echo "    </div>\n";
    if(!$uzavrene){
      $polPole = polozkyUctu($key);
      }
    else{
      $polPole = polozkyUzavrenehoUctu($data['ouid']);
      }
    
    if(!empty($polPole)){
      $oup .= "<div id='obsahuctu".$i."' class='obsahuctu'>\n";
      $oup .= "  <div>\n";
      $oup .= "    <a href=\"javascript: schovejobsah();\" class='posun'>Skrýt</a>\n";
      $oup .= "    <a href=\"javascript: posun('obsahuctudiv".$i."', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
      $oup .= "    <a href=\"javascript: posun('obsahuctudiv".$i."', 'dolu', 200);\" class='posun'>Dolu</a>\n";
      $oup .= "  </div>\n";
      $oup .= "  <div>Číslo účtu:<span class='tucne'>".$data['cislo']."</span></div>\n";
      $oup .= "  <div>Cena celkem:<span class='tucne'>".cenaKC($data['cena'])."</span></div>\n";
      //$oup .= "  <div><hr /></div>\n";
      $oup .= "  <div id='obsahuctudiv".$i."' class='obsahuctudiv'>\n";
      $oup .= "  <table>\n";         
      $oup .= "    <tr class='nadpis'>\n";
      $oup .= "      <td>Množství</td>\n";
      $oup .= "      <td>Položka</td>\n";
      $oup .= "      <td>Vložil</td>\n";
      $oup .= "      <td>Vloženo</td>\n";
      $oup .= "      <td>Poloviční</td>\n";
      $oup .= "      <td>Pokladna</td>\n";
      $oup .= "      <td class='prava'>Cena &nbsp;&nbsp;</td>\n";
      $oup .= "    </tr>\n";
      foreach($polPole AS $polKey => $polData){
        $oup .= "    <tr>\n";
        $oup .= "      <td>".$polData['mnoz']."</td>\n";
        $oup .= "      <td>".$polData['rec']."</td>\n";
        $oup .= "      <td>".$polData['vlozil']."</td>\n";
        $oup .= "      <td>".$polData['vlozeno']."</td>\n";
        $polovicni = "ne";
        if($polData['polovicni'] == 1){
          $polovicni = "ano";
          }
        $oup .= "      <td>".$polovicni."</td>\n";
        $oup .= "      <td>".$polData['pokladna']."</td>\n";
        $oup .= "      <td class='prava'>".cenaKC($polData['cena'])."</td>\n";
        $oup .= "    </tr>\n";
        }
      $oup .= "  </table>\n";
      $oup .= "  </div>\n";
      $oup .= "</div>\n";
      }
    $i++;
    }
  echo "    </div>\n";
  echo "  </div>\n";
  echo "  <div>".$oup."</div>\n";
  echo "</div>\n";
  echo "<script type='text/javascript'>
    $('#tmavepozadi').click(function(){
      $('#tmavepozadi').hide();
      $('.obsahuctu').hide();
      });
    function schovejobsah(){
      $('#tmavepozadi').hide();
      $('.obsahuctu').hide();
      }
    function ukazobsah(trida, div){
      ukaz(trida, div, '', '');
      $('#tmavepozadi').show().height($('#stred').outerHeight()).width($('#stred').outerWidth()).css('background-color', '#000000').css('opacity',0.75).css('filter','alpha(Opacity=75)');  
      }
    </script>\n";
  }

function vyberStolu($link, $ucet = null){
  // vytvoreni noveho uctu
  $nazev = $vypis = $vypisSKST = $ucetURL = "";
  if(($link == "typplatby")||($link == "dotisk")){
    $stoly = stoly('', false);
    }
  else{
    $stoly = stoly();
    }
  $skstid = null;
  if(!empty($ucet)){
    $ucetURL = "&amp;ucet=".$ucet;
    }
  if(!empty($stoly)){
    foreach($stoly AS $skst => $skupina){
      $vypisSKST .= "  <a href=\"javascript: ukaz('stolyclass', 'stuldiv".$skst."', 'stolynazev', '".$skupina['nazev']."');\" onclick=\"\" class='posun'>".$skupina['nazev']."</a>\n";
      $styl = "";
      if(empty($skstid)){
        $styl = " style='display:block';' ";
        $nazev = $skupina['nazev'];
        }
      $vypis .= "<div id='stuldiv".$skst."' class='stolyclass' ".$styl.">";
      if(!empty($skupina['stoly'])){
        foreach($skupina['stoly'] AS $st => $stul){
          $ucty = "bez účtu";
          $trida = "bez";
          $pocet = 0;
          if(isset($stul['ucty'])){
            $pocet = $stul['ucty'];
            }
          if($pocet > 4){
            $ucty = $pocet." účtů";
            $trida = 2; 
            }
          elseif($pocet > 1){  
            $ucty = $pocet." účty";
            $trida = 2;
            }
          elseif($pocet == 1){
            $ucty = "1 účet";
            $trida = 1;
            }
          if(($pocet > 0)||($link == 'novyucet')||(!empty($ucet) && ($link == 'zmenanastul'))){
            $vypis .= "<a href=\"?page=".$link."&amp;stul=".$st.$ucetURL."\" class='stoly".$trida."'>".$stul['nazev']."<br />".$ucty."</a>";
            }
          else{
            $vypis .= "<span class='stoly".$trida."'>".$stul['nazev']."<br />".$ucty."</span>";
            }
          }
        }    
      $vypis .= "</div>";
      $skstid = $skst;
      }
    }
  echo "<div id='stoly'>\n";
  echo "  <div id='stolynazev'>".$nazev."</div>\n";
  echo "  <div id='stolydiv'>\n";
  //echo "    <div class='scrollbar'><div class='track'><div class='thumb'><div class='end'></div></div></div></div>";
  //echo "    <div class=\"viewport\">";
  echo "      <div class=\"overview\">".$vypis."</div>\n";
  //echo "    </div>\n";
  echo "  </div>\n";
  echo "  <div class='dole'>";
  echo "    <a href=\"javascript: posun('stolydiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "    <a href=\"javascript: posun('stolydiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  </div>\n";
  echo "</div>\n";
  echo "<div id='skupiny'>\n";
  echo "  <div id='skupinydiv'>\n";
  //echo "    <div class='scrollbar'><div class='track'><div class='thumb'><div class='end'></div></div></div></div>";
  //echo "    <div class=\"viewport\">";
  echo "      <div class=\"overview\">".$vypisSKST."</div>\n";
  //echo "    </div>\n";
  echo "  </div>\n";
  echo "  <div class='dole'>";
  echo "    <a href=\"javascript: posun('skupinydiv', 'nahoru', 200);\" class='posun'>Nahoru</a>\n";
  echo "    <a href=\"javascript: posun('skupinydiv', 'dolu', 200);\" class='posun'>Dolu</a>\n";
  echo "  </div>";
  echo "</div>";
  echo "    <script type='text/javascript'>
  function upravacasti() {
    var sirkastred = $('#stred').outerWidth();
    var sirkaskupiny = parseInt($('#skupiny').outerWidth());
    var mezeraskupiny = 2*($('#skupiny').css('right').replace('px', ''));
    var sirkanova = (sirkastred - sirkaskupiny - mezeraskupiny); 
    //alert($('#stoly').outerWidth()+' == '+$('#stoly .dole').outerWidth()+' == '+sirkastred+ ' :: '+sirkaskupiny+' == '+sirkanova);
    $('#stoly').width(sirkanova);
    $('#stoly .dole').width(sirkanova);
    //alert($('#stoly').outerWidth()+' == '+$('#stoly .dole').outerWidth()+' == '+sirkastred+ ' :: '+sirkaskupiny+' == '+sirkanova);
    /*****/
    var vyskastred = $('#stred').outerHeight();
    $('#skupiny').height(vyskastred - 20);
    //var vyskaskupiny = parseInt($('#skupiny').outerHeight());
    var vyskaskupinydole = parseInt($('#skupiny .dole').outerHeight());
    $('#skupinydiv').height((vyskastred - 35 - vyskaskupinydole));
    }
    </script>";
  
  }
?>