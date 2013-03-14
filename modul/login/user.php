<?PHP
class User{
  var $id = null;
  var $nick = null;
  var $prava = null;
  var $prihlasen = false;
  var $ad = null;
  
  function User() {
    $this->prihlasen = false;
    }
    
  function loginUser($id, $nick, $prava, $ad = false) {
		$this->id = $id;
    $this->nick = $nick;
    $this->prava = $prava;
    $this->ad = $ad;
		$this->prihlasen = true;   
    }
  function getIDUser(){
    return $this->id;
    } 
  function getUser(){
    return $this->nick;
    }
  function nick(){
    return $this->nick;
    }
  /* funkce práv */
  function getPravo($int){
    $kVraceni = 0;
    if(substr($this->prava, ($int-1), 1) == 1){
      $kVraceni = 1;
      }
    return $kVraceni;
    } 
  function getPrava(){
    return $this->prava;
    }
  function smazPrava(){
    $this->prava = null;
    }
  function setPrava($prava){
    $this->prava = $prava;
    }
  /* konec práv */ 
  function getPrihlasen(){
    return $this->prihlasen;
    }
  function getKlic(){
    return $this->klic;
    }
  function getDB(){
    return $this->db;
    }
  function getAD(){
    return $this->ad;
    }
  function setAD($ad){
    $this->ad = $ad;
    }
  }


?>