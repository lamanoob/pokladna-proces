function hodiny(div){ 
  datum = new Date(); 
  den = datum.getDate(); 
  mesic = (datum.getMonth()+1); 
  rok = datum.getFullYear(); 
  hodina = datum.getHours(); 
  minuta = datum.getMinutes(); 
  vterina = datum.getSeconds(); 
  if( hodina < 10 )hodina = '0'+hodina; 
  if( minuta < 10 )minuta = '0'+minuta; 
  if( vterina < 10 )vterina = '0'+vterina; 
  $('#'+div).html( den + '. ' + mesic + '. ' + rok + '\n' + hodina + ':' + minuta + ':' + vterina );           
  setTimeout("hodiny('"+div+"')", 1000); 
  }   