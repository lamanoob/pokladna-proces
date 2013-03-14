<?PHP
include_once(_tema_.'nastaveni.php');

$temaLoginStranka = _loginStranka_;
$temaLoginKod = _loginKod_;
$temaLoginTrvale = _loginTrvale_;
if(defined("_temaLoginStranka_")){
  $temaLoginStranka = _temaLoginStranka_;
  }
if(defined("_temaLoginKod_")){
  $temaLoginKod = _temaLoginKod_;  
  }
if(defined("_temaLoginTrvale_")){
  $temaLoginTrvale = _temaLoginTrvale_;  
  }


$lastUser = "";
$loginVysledek = 0;
if($temaLoginStranka){
  echo "<!DOCTYPE html  PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n"; 
  echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  ?> 
   <head>
    <meta http-equiv="Content-type" content="text/html;charset=windows-1250" />
    <META http-equiv="cache-control" content="no-cache">
    <title> Přihlášení do Pokladny </title>
    <link href="<?PHP echo _tema_; ?>login/login.css" rel="stylesheet" type="text/css" />
  <?PHP  
  echo " <body>\n";
  echo "\n";
  }
echo "  <div id='login'>\n";
//<script type="text/javascript" src="js/screenfull.js"></script>
  //<script type="text/javascript" src="js/jquery.fullscreen.js"></script>
  
?>
  <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
  <script language="javascript" type="text/javascript">
  <!--
  $('#hesloid').ready(function() {
    // focus on the first text input field in the first field on the page
    $("#hesloid").focus();
    });
  function kontrola(){
    var stav = false;
    var nick = self.document.forms.login.nick.value;
    var heslo = self.document.forms.login.heslo.value;
    if((nick.length > 0)&&(heslo.length > 0)){
      stav = true;
      }
    else if((nick.length > 0)&&(heslo.length <= 0)){
      alert('Musíte zadat i heslo.');
      self.document.forms.login.heslo.focus();
      } 
    else if((nick.length <= 0)&&(heslo.length > 0)){
      alert('Musíte zadat i přihlašovací jméno.');
      self.document.forms.login.nick.focus();
      }
    else{
      alert('Musíte zadat přihlašovací jméno i heslo.');
      self.document.forms.login.nick.focus();
      }
    return stav; 
    }
  -->
  </script>
<?PHP
//echo "    <form name=\"login\" action=\"".$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER["QUERY_STRING"],ENT_QUOTES)."\" method=\"post\" onsubmit=\"return kontrola(login)\">\n";
echo "    <form name=\"login\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" onsubmit=\"return kontrola(login)\">\n";
if(!empty($vysledekLogin['chyba'])){
  //echo "posledni:".date("H:i:s d.m.Y", sess("posledni"))."; nyni: ".date("H:i:s d.m.Y")."; rozdil: ".(time() - sess("posledni"))."; "._aktivni_."<br>";
  echo "      <div class=\"chyba\">".$vysledekLogin['chyba']."</div>";
  } 
echo "      <div class=\"loginnadpis\"><h1>Přihlášení do pokladny</h1></div>\n";       
if(!$temaLoginKod){
  echo "      <div class='logintext'>".label("Přihlašovací jméno:", "nickid")."</div>";
  echo "      <div class='logininput'>".input("nick", $lastUser, "input", "nickid")."</div>";
  }
echo "      <div class='logintext'>".label("Heslo:", "hesloid")."</div>";
echo "      <div class='logininput'>".input("heslo", "", "input", "hesloid", "password")."</div>";
if($temaLoginTrvale){
  echo "      <div class='logintext'>".label("Trvalé přihlášení:", "trvaleid")."</div>";
  echo "      <div class='logininput'>".check("trvale", 1, "", "check", "trvaleid")."</div>";
  }
echo "      <div class='tlacitka'><input type='submit' value='Příhlásit' /></div>\n";
echo "      <div class='tlacitka'><input type='reset' value='Vymazat' /></div>\n";
echo "    </form>\n";
if(_loginKod_){
  ?>
  <script language="javascript" type="text/javascript">
  <!--
  function kontrola(){
    var stav = false;
    var nick = self.document.forms.login.nick.value;
    var heslo = self.document.forms.login.heslo.value;
    if((nick.length > 0)&&(heslo.length > 0)){
      stav = true;
      }
    else if((nick.length > 0)&&(heslo.length <= 0)){
      alert('Musíte zadat i heslo.');
      self.document.forms.login.heslo.focus();
      } 
    else if((nick.length <= 0)&&(heslo.length > 0)){
      alert('Musíte zadat i přihlašovací jméno.');
      self.document.forms.login.nick.focus();
      }
    else{
      alert('Musíte zadat přihlašovací jméno i heslo.');
      self.document.forms.login.nick.focus();
      }
    return stav; 
    }
  -->
  </script> 
  <?PHP
  echo "    <div id='klavesnice'></div>\n";
  }
echo "  </div>\n";
if($temaLoginStranka){
  echo " </body>\n"; 
  echo "</html>\n";
  }
?>