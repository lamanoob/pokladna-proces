<?PHP

vypisVse();

function vypisVse(){
  printRko(printer_list(PRINTER_ENUM_LOCAL));
  printRko(printer_list(PRINTER_ENUM_NAME));
  printRko(printer_list(PRINTER_ENUM_SHARED));
  printRko(printer_list(PRINTER_ENUM_DEFAULT));
  printRko(printer_list(PRINTER_ENUM_CONNECTIONS));
  printRko(printer_list(PRINTER_ENUM_NETWORK));
  printRko(printer_list(PRINTER_ENUM_REMOTE));
  }
function printRko($pole){
  echo "<pre>";
  print_r($pole);
  echo "</pre>";
  }
  
?>