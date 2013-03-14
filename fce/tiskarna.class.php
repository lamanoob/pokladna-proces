<?php
ini_set('error_reporting',E_ALL); 
ini_set("display_errors","on"); 
ini_set("display_errors",1); 
ini_set('display_startup_errors',1);  
error_reporting(E_ALL);  
	/**
	 * Třída pro tisk v Windows pomocí knihovny printer.dll
	 * a https://github.com/jiminald/PHP-Printer   
	 *
	 * @access       public
	 * @author       LamA.nOOb <gejbo@seznam.cz>
	 * @copyright    LamA.nOOb Únor 2013    
	 * @version      1.0
	 */

    class Tiskarna {
      //Public 
      public $vypis = NULL;
      public $tiskarny = array();
      public $ignorovat = array();
	    public $sirka = 0;
      public $vlevo = 0;
      public $vpravo = 0;
      public $zarovnani = "leva";
	    //Private 
      private $ovladac = FALSE;
      private $vybrane = FALSE;
      private $kopie = 1;
      private $orientace = 'Portrait';
      private $titulek = '';
      private $pamet = "\r\n ";
      /*** Set ignorovat list and scan for tiskarny - @return void */
      public function __construct() {
        //Nastaveni ignorovanych tiskaren
        $this->ignorovat = array('Fax', 'Microsoft XPS Document Writer', 'Microsoft Office Document Image Writer');
        //Uchopeni seznamu tiskaren
        $this->vyjmenuj();
        } 
      
      /**
       * Show Printers Found Connected to this PC
       * @return array 
       */
      public function vyjmenuj() {
            /* If there are no printers, find them */
            if (count($this->printers) == 0) {
                $printer_list = printer_list(PRINTER_ENUM_LOCAL);
                
                foreach ($printer_list as $printer) {
                    if (in_array($printer['NAME'], $this->ignorovat) == FALSE) {
                        $this->tiskarny[] = $printer;
                    }
                }
            }
            
            return $this->tiskarny;
        }
      
      /**
       * Select a Printer to Print to
       * @param string $printer_name Name of Printer
       * @return string 
       */
      public function vyber($jmeno_tiskarny = '') {
        /* Pokud tiskarna neni urcena, return FALSE */
        if ($jmeno_tiskarny == '') { return FALSE; }
        
        /* Scan the Printers array, if the printer asked for is found, then select it, reset values to default and return TRUE */
        foreach ($this->tiskarny as $tiskarna) {
          if ($tiskarna['NAME'] == $jmeno_tiskarny) {
            $this->vybrane = $jmeno_tiskarny;
            $this->kopie = 1;
            $this->orientace = 'Portrait';
            $this->titulek = '';
            }
          }
        /* Return Printer Name, or return FALSE */
        return $this->vybrane;
        }
      
      /**
       * Set copy amount
       * 
       * @param integer $kopie Copy Amount
       * @return integer 
       */
      public function kopie($kopie = 0) {
        /* If it is not 0, set the kopie */
        if ($kopie <> 0) {
          $this->kopie = $kopie;
          }
        
        /* Return the current value, changed or not */
        return $this->kopie;
        } 
      
      /**
       * Set Page Orientation
       * @param string $orientace Page Orientation
       * @return string
       */
      public function orientace($orientace = '') {
        /* If it is not blank, set the orientace */
        if ($orientace <> '') {
          $this->orientace = ucwords(strtolower($orientace));
          }
        /* Return the current value, changed or not */
        return $this->orientace;
        }
      
      /**
       * Document Title, Or Filename if printing to PDF Printer
       * 
       * @param string $title Page title
       * @return string 
       */
      public function titulek($title = '') {
        /* If it is not blank, set the document Title */
        if ($title <> '') {
          $this->titulek = $title;
          }
        /* Return the current value, changed or not */
        return $this->titulek;
        }
      
      /**
       * Write string to Print Buffer
       * 
       * @param string $string Data to save in pamet
       * @return boolean 
       */
      public function zapis($retezec, $format = null) {
        if ($retezec == '') { return FALSE; }
        //Replace <br /> to CRLF
        if((!empty($format))&&(function_exists($format))){
          $string = call_user_func($format, $retezec);
          }
        $string = str_replace(array('<br>', '<br />'), "\r\n ", $retezec);
        $this->pamet .= $retezec;
        return TRUE;
        } 
        //End of function "write"
      

      public function vypln($vypln, $delka = 0) {
        $text = "";
        $sirka = $this->sirka;
        $vlevo = $this->vlevo;
        if($sirka > 0){
          $zleva = $zprava = 0;
          $vpravo = $this->vpravo;
          $zarovnani = $this->zarovnani; 
          if($zarovnani == "prava"){
            $vlevo = $sirka - $delka - $vpravo;
            $zleva = $vpravo;
            }
          elseif($zarovnani == "stred"){
            $vlozeni = $sirka - $zleva - $zprava;
            $vlozeni = ceil(($vlozeni - $delka)/2);
            if($vlozeni < 0){
              $vlozeni = 0;
              }
            $zleva += $vlozeni; 
            }
          else{
            $zleva = $vlevo; 
            }
          $sirka -= $vpravo;
          for($i = 0; $i < $zleva;$i++){
            $text .= " "; 
            }
          for($j = 0; $j < $delka;$j++){
            $text .= $vypln; 
            }
          $this->pamet .= $text;
          }
        else{
          for($i = 0; $i < $vlevo;$i++){
            $text .= " "; 
            }
          $this->pamet .= $text;
          }
        }
      
      public function zalom() {
        $this->pamet .= "\n";
        }
      
      public function vloz($vlozeno) {
        $text = "";
        $sirka = $this->sirka;
        $vlevo = $this->vlevo;
        $vpravo = $this->vpravo;
        $zarovnani = $this->zarovnani;
        $delka = strlen(utf8_decode($vlozeno));
        if($sirka > 0){
          if($zarovnani == "prava"){
            $vlevo = $sirka - $delka - $vpravo;
            $zleva = $vpravo;
            }
          elseif($zarovnani == "stred"){
            $vlozeni = $sirka - $zleva - $zprava;
            $vlozeni = ceil(($vlozeni - $delka)/2);
            if($vlozeni < 0){
              $vlozeni = 0;
              }
            $zleva += $vlozeni; 
            }
          else{
            $zleva = $vlevo; 
            }
          
          }
        else{
          
          }
        for($i = 0; $i < $vlevo;$i++){
          $text .= " "; 
          }
        $this->pamet .= $text;
         
        /************/
        
        /************/ 
        $this->pamet .= $text;
        }
      /**
       * Print out whats in the pamet
       * 
       * @return boolean 
       */
      public function vytiskni_pamet() {
          $connect = $this->_connect();
          if ($connect <> FALSE) {
              $this->_vypis_pamet();
              $this->_zavri();
              return TRUE;
          }
          
          return FALSE;
      } //End of function "print_pamet"
      
      /**
       * Print file, this adds the file to the pamet and prints
       * 
       * @param string $file Filename to print
       * @return boolean
       */
      public function vytiskni_soubor($file) {
        /* Get file contents  */
        $fh = fopen($file, "rb"); 
        $content = fread($fh, filesize($file)); 
        fclose($fh); 
        
        $connect = $this->_connect();
        if ($connect <> FALSE) {
          // Send File to printer
          $this->zapis($content); 
          $this->_vypis_pamet();
          $this->_zavri();
          return TRUE;
          }
        }
      
      /**
       * Open Printer Connection and set preferences
       * 
       * @return boolean|resource 
       */
      private function _connect() {
        //Check if the printer is already open, if it is, return the ovladac
        if ($this->ovladac <> FALSE) { return $this->ovladac; }
        
        /* Open the Printer Connetion */
        $this->ovladac = printer_open($this->vybrane);
        if ($this->ovladac == FALSE) {
          return FALSE;
          }
        
        /* Set Copies */
        $this->option(PRINTER_COPIES, $this->kopie());
        
        /* Set Orientation */
        if ($this->orientace() == 'Landscape') {
          $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_LANDSCAPE);
          }
        else {
          $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_PORTRAIT);
          }
        
        /* Set Title */
        if ($this->titulek() <> '') {
          $this->option(PRINTER_TITLE, $this->titulek());
          }
        
        return $this->ovladac;
        }
      
      /**
       * Set printer option, printer must be open for this to work
       * 
       * @param integer $option Option to Set
       * @param integer $value Value to give
       * @return boolean 
       */
      public function nastaveni($varianta, $hodnota) {
        return printer_set_option($this->ovladac, $varianta, $hodnota);
        } 
      
      /**
       * Write pamet contents to Printer
       * 
       * @return void 
       */
      private function _vypis_pamet() {
        //printer_draw_text($this->ovladac, "PHP is simply cool", 40, 40);
        //$font = printer_create_font("Arial", 12, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        //printer_select_font($this->ovladac, $font);
        
        printer_set_option($this->ovladac, PRINTER_MODE, "text");
        printer_write($this->ovladac, $this->pamet);
        //printer_delete_font($font);
        return;
        }
      
      /**
       * Close printer connection
       * 
       * @return boolean 
       */
      private function _zavri() {
        printer_close($this->ovladac);
        $this->ovladac = FALSE;
        return TRUE;
        }
      
      
      //public function 
      }
?>