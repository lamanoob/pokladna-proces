function posun(kde, kam, kolik){
  var div = $('#'+kde);
  var pozice = div.scrollTop();
  if(kam == "dolu"){
    pozice = pozice + kolik;
    }
  else if(kam == "nahoru"){
    pozice = pozice - kolik;
    }
  if(posun < 0){
    pozice = 0;
    }
  if(posun > div.height()){
    pozice = div.height();
    }
  div.scrollTop(pozice);
  }
function ukaz(trida, vyber, divnazev, nazev){
  $('.'+trida).hide();
  $('#'+vyber).show();
  if((divnazev)&&(nazev)){
    if((divnazev.length > 0)&&(nazev.length > 0)){
      var cil = $('#'+divnazev);
      if(cil){
        cil.html(nazev);
        }
      }
    }
  }

function klikni(typ){
  var posli = '';
  var mnoz = $('#klavmnoz');
  if(mnoz){
    var hodnota = mnoz.html();
    if(typ == 'BS'){
      hodnota = hodnota.slice(0, -1)
      mnoz.html(hodnota);
      }
    else if(typ == 'ZR'){
      $('#klavesnicepozadi').hide();
      $('#klavesnice').hide();
      mnoz.html('');
      }
    else if(typ == 'RS'){
      mnoz.html('');
      }
    else if(typ == '/'){
      var pol = $('#klavpol');
      if(pol){
        if(pol.html() == 'ne'){
          pol.html('ano');
          }
        else{
          pol.html('ne');
          }
        }
      /* polovicni porce */ 
      }
    else if(typ == '--'){
      var pocetDivu = $('#pocetDivu');
      var pocetvdivu = parseInt(pocetDivu.val())+1;
      var newdiv = document.createElement('table');
      newdiv.setAttribute('id','trpolozky'+ pocetvdivu);
      newdiv.setAttribute('class','objednavky');
      newdiv.setAttribute('onclick','smazaniVyber(\"trpolozky'+ pocetvdivu+'\");');
      newdiv.innerHTML=''
        + '</tr>'
        + '<td class=\'naz\' colspan=\"3\">'
        + '  <span style=\'display:none\'>'
        + '    <input type=\"hidden\" class=\"recid\" name=\"recid[]\" value=\"===\"/>'
        + '    <input type=\"hidden\" class=\"mnozstvi\" name=\"mnozstvi[]\" value=\"===\"/>'
        + '    <input type=\"hidden\" class=\"cena\" name=\"cena[]\" value=\"0\"/>'
        + '    <input type=\"hidden\" class=\"polovicni\" name=\"polovicni[]\" value=\"0\"/>'
        + '  </span>'
        + '    <div>===============================<div>'
        + '</td>'
        + '</tr>'; 
      pocetDivu.val(pocetvdivu);
      $('#objednano').append(newdiv);
      var vyskaStare = $('#objednavkadiv #stareveci').outerHeight();
      var vyskaNove = $('#objednavkadiv #objednano').outerHeight();
      $('#objednavkadiv').scrollTop((vyskaStare + vyskaNove));
      }
    else if(typ == 'OK'){
      if(hodnota > 0){
        var recid = $('#klavrecid').val();
        var rec = $('#klavrec').html();
        var cena = $('#klavcena').val();
        var pocetDivu = $('#pocetDivu');
        var pocetvdivu = parseInt(pocetDivu.val())+1;
        var newdiv = document.createElement('div');
        newdiv.setAttribute('id','trpolozky'+ pocetvdivu);
        newdiv.setAttribute('class','objednavky');
        newdiv.setAttribute('onclick','smazaniVyber(\"trpolozky'+ pocetvdivu+'\");');
        newdiv.innerHTML=''
          + '<span style=\'display:none\'>'
          + '  <input type=\"hidden\" class=\"recid\" name=\"recid[]\" value=\"'+recid+'\"/>'
          + '  <input type=\"hidden\" class=\"mnozstvi\" name=\"mnozstvi[]\" value=\"'+hodnota+'\"/>'
          + '  <input type=\"hidden\" class=\"cena\" name=\"cena[]\" value=\"'+(hodnota * cena)+'\"/>'
          + '</span>'
          + '<span class=\'mnoz\'>'+parseInt(hodnota)+'x</span>'
          + '<span class=\'naz\'>'+rec+'</span>'
          + '<span class=\'pra\'>'+(hodnota * cena)+'</span>'; 
        pocetDivu.val(pocetvdivu);
        $('#objednano').append(newdiv);
        spoctiCelkem();
        mnoz.html('');
        $('#klavesnicepozadi').hide();
        $('#klavesnice').hide();
        }
      else{
        alert('Musíte zadat množství!');
        }
      }
    else{
      mnoz.html(hodnota+typ);
      }
    }
  }
/************************/
/**** vloz a zmen *******/
function vlozAZmen(input, trida, hodnota){
  $('#'+input).val(hodnota);
  $('a.'+trida+'akt').removeClass(trida+'akt').addClass(trida);
  $('#'+trida+hodnota).addClass(trida+'akt');
  if(typeof zapisSlevu == 'function'){
    zapisSlevu(0);
    }
  }

