<?PHP
echo "<!DOCTYPE html  PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n"; 
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
?> 
 <head>
  <meta http-equiv="Content-type" content="text/html;charset=windows-1250" />
  <META http-equiv="cache-control" content="no-cache">
  <title> Přihlášení do Control Centra </title>
  <link href="<?PHP echo _tema_; ?>css/login.css" rel="stylesheet" type="text/css" />
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
 </head>
<?PHP
echo " <body>\n";
echo "\n";
echo "    <div id=\"login\">\n";
$loginVysledek = 0;
if($loginVysledek != "0"){
  echo "    <div class=\"aktualni\">".$loginVysledek."</div>";
  } 
echo "      <div id=\"loginForm\">\n"; 
echo "        <form name=\"login\" action=\"".$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER["QUERY_STRING"],ENT_QUOTES)."\" method=\"post\" onsubmit=\"return kontrola(login)\">\n";
  //aktualizujZMastera();  
?>
<table>
  <tr>
    <td colspan="2">
      <div class="nadpis">Přihlašovací formulář do Adminské části</div>
    </td>
  </tr>
  <tr>
    <td>
      <label for="nick">Přihlašovací jméno:</label> 
    </td>
    <td>
<?PHP
echo " <input type=\"text\" id=\"nick\" name=\"nick\" value=\"".$lastUser."\" />";
?>
      
    </td>
  </tr>
  <tr>
    <td>
      <label for="heslo">Heslo:</label> 
    </td>
    <td>
      <input type="password" id="heslo" name="heslo" />
    </td>
  </tr>
  <tr>
    <td>
      <label for="porad">Přihlásit na trvalo:</label> 
    </td>
    <td>
      <input type="checkbox" name="porad">
    </td>
  </tr>
  <tr>
    <td class="tlacitka">
      <input type="submit" value="Příhlásit" />
    </td>
    <td class="tlacitka">
      <input type="reset" value="Vymazet" />
    </td>
  </tr>
</table>

<?PHP   
echo "        </form>\n";
echo "      </div>\n";
echo "    </div>\n";
echo " </body>\n"; 
echo "</html>\n";
?>