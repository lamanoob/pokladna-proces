<?PHP
function hlavicka(){
  echo "<!DOCTYPE html  PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n"; 
  echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  echo " <head>\n";
  echo "  <meta http-equiv='Content-type' content='text/html;charset=utf-8' />\n";
  echo "  <meta http-equiv='Cache-control' content='no-cache' />\n";
  echo "  <title> Pokladna </title>\n";
  echo "  <link href='"._tema_."css/index.css' rel='stylesheet' type='text/css' />\n";
  echo "  <link href='"._tema_."css/hodiny.css' rel='stylesheet' type='text/css' />\n";
  echo "  <script src='js/jquery-1.7.1.min.js' type='text/javascript'></script>\n";
  echo "  <script src='js/hodiny.js' type='text/javascript'></script>\n";
  echo "  <script src='js/pokladna.js' type='text/javascript'></script>\n";
  //echo "  <script src='js/jquery.fullscreen2.js' type='text/javascript'></script>\n";
  //echo "  <script src='js/fullscreen.js' type='text/javascript'></script>\n";
  echo " </head>\n";
  } 

function paticka(){
  echo " </body>\n";
  echo "</html>\n";
  }
?>