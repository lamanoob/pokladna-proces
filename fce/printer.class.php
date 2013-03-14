<?php
	/**
	 * Print straight to a local printer
	 *
	 * To see more information or get updates, visit the GitHub
     * https://github.com/jiminald/PHP-Printer
	 *
	 * @access       public
	 * @author       Jiminald <code@jiminald.co.uk>
	 * @copyright    Jiminald October 2011
	 * @version      1.0
	 */

    class Printer {
        //Global Variables
        //Public Variables
        public $Output = NULL;
        public $printers = array();
        public $ignore = array();
        public $left = 0;
        public $right = 0;
        public $width = 0;
        public $align = "left";
		    //Private Variables
        private $handle = FALSE;
        private $selected = FALSE;
        private $copies = 1;
        private $format = "";
        private $orientation = 'Portrait';
        private $document_title = '';
        private $buffer = "\r\n";
        /*** Set ignore list and scan for printers - @return void */
        public function __construct() {
            //Set ignore list
            $this->ignore = array('Fax', 'Microsoft XPS Document Writer', 'Microsoft Office Document Image Writer');
            
            //Grab printer list
            $this->enumerate();
        } //End of function "__construct"
        
        /**
         * Show Printers Found Connected to this PC
         * 
         * @return array 
         */
        public function enumerate() {
            /* If there are no printers, find them */
            if (count($this->printers) == 0) {
                $printer_list = printer_list(PRINTER_ENUM_LOCAL);
                
                foreach ($printer_list as $printer) {
                    if (in_array($printer['NAME'], $this->ignore) == FALSE) {
                        $this->printers[] = $printer;
                    }
                }
            }
            
            return $this->printers;
        } //End of function "enumerate"
        
        /**
         * Select a Printer to Print to
         * 
         * @param string $printer_name Name of Printer
         * @return string 
         */
        public function select($printer_name = '') {
            /* If no printer specified, return FALSE */
            if ($printer_name == '') { return FALSE; }
            
            /* Scan the Printers array, if the printer asked for is found, then select it, reset values to default and return TRUE */
            foreach ($this->printers as $printer) {
                if ($printer['NAME'] == $printer_name) {
                    $this->selected = $printer_name;
                    $this->copies = 1;
                    $this->orientation = 'Portrait';
                    $this->document_title = '';
                }
            }
            
            /* Return Printer Name, or return FALSE */
            return $this->selected;
        } //End of function "select"
        
        /**
         * Set copy amount
         * 
         * @param integer $copies Copy Amount
         * @return integer 
         */
        public function copies($copies = 0) {
            /* If it is not 0, set the copies */
            if ($copies <> 0) {
                $this->copies = $copies;
            }
            
            /* Return the current value, changed or not */
            return $this->copies;
        } //End of function "copies"
        
        /**
         * Set Page Orientation
         * 
         * @param string $orientation Page Orientation
         * @return string
         */
        public function orientation($orientation = '') {
            /* If it is not blank, set the orientation */
            if ($orientation <> '') {
                $this->orientation = ucwords(strtolower($orientation));
            }
            
            /* Return the current value, changed or not */
            return $this->orientation;
        } //End of function "orientation"
        
        /**
         * Document Title, Or Filename if printing to PDF Printer
         * 
         * @param string $title Page title
         * @return string 
         */
        public function document_title($title = '') {
            /* If it is not blank, set the document Title */
            if ($title <> '') {
                $this->document_title = $title;
            }
            
            /* Return the current value, changed or not */
            return $this->document_title;
        } //End of function "document_title"
        
        /**
         * Write string to Print Buffer
         * 
         * @param string $string Data to save in buffer
         * @return boolean 
         */
        public function write($string, $format = null) {
          if ($string == '') { return FALSE; }
          //Replace <br /> to CRLF
          if((!empty($format))&&(function_exists($format))){
            $string = call_user_func($format, $string);
            }
          $string = str_replace(array('<br>', '<br />'), "\r\n ", $string);
          $this->buffer .= $string;
          return TRUE;
          } 
          //End of function "write"
        
        /**
         * Print out whats in the buffer
         * 
         * @return boolean 
         */
        public function print_buffer() {
            $connect = $this->_connect();
            if ($connect <> FALSE) {
                $this->_write_buffer();
                $this->_close();
                return TRUE;
            }
            
            return FALSE;
        } //End of function "print_buffer"
        public function show_buffer() {
          return $this->buffer;
          }
        /**
         * Print file, this adds the file to the buffer and prints
         * 
         * @param string $file Filename to print
         * @return boolean
         */
        public function print_file($file) {
            /* Get file contents  */
            $fh = fopen($file, "rb"); 
            $content = fread($fh, filesize($file)); 
            fclose($fh); 
            
            $connect = $this->_connect();
            if ($connect <> FALSE) {
                // Send File to printer
                $this->write($content); 
                $this->_write_buffer();
                $this->_close();
                return TRUE;
            }
        } //End of function "print_file"
        
        /**
         * Open Printer Connection and set preferences
         * 
         * @return boolean|resource 
         */
        private function _connect() {
            //Check if the printer is already open, if it is, return the handle
            if ($this->handle <> FALSE) { return $this->handle; }
            
            /* Open the Printer Connetion */
            $this->handle = printer_open($this->selected);
            if ($this->handle == FALSE) {
                return FALSE;
            }
            
            /* Set Copies */
            $this->option(PRINTER_COPIES, $this->copies());
            
            /* Set Orientation */
            if ($this->orientation() == 'Landscape') {
                $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_LANDSCAPE);
            } else {
                $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_PORTRAIT);
            }
            
            /* Set Title */
            if ($this->document_title() <> '') {
                $this->option(PRINTER_TITLE, $this->document_title());
            }
            
            return $this->handle;
        } //End of function "_connect"
        
        /**
         * Set printer option, printer must be open for this to work
         * 
         * @param integer $option Option to Set
         * @param integer $value Value to give
         * @return boolean 
         */
        public function option($option, $value) {
            return printer_set_option($this->handle, $option, $value);
        } //End of function "option"
        
        /**
         * Write buffer contents to Printer
         * 
         * @return void 
         */
        private function _write_buffer() {
            //printer_draw_text($this->handle, "PHP is simply cool", 40, 40);
            //$font = printer_create_font("Arial", 12, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            //printer_select_font($this->handle, $font);
            $buffer = $this->buffer;
            $format = $this->format;
            if((!empty($format))&&(function_exists($format))){
              $buffer = call_user_func($format, $buffer);
              }
          printer_set_option($this->handle, PRINTER_MODE, "text");
            printer_write($this->handle, $buffer);
            return;
        } //End of function "_write_buffer"
        
        /**
         * Close printer connection
         * 
         * @return boolean 
         */
        private function _close() {
            printer_close($this->handle);
            $this->handle = FALSE;
            return TRUE;
        } //End of function "_close"
        
    /**********************
     **********************
     **********************
     **********************/                  
    public function format($format) {
      if((!empty($format))&&(function_exists($format))){
        $this->format = $format;
        }
      }
    public function width($width = null) {
      if(((!empty($width))&&(is_numeric($width)))||($width === 0)){
        $this->width = $width;
        }
      else{
        return $this->width;
        }
      }
    public function setMargin($left = 0, $right = 0) {
      $this->left = $left;
      $this->right = $right;
      }
    public function left($left = null) {
      if((!empty($left))&&(is_numeric($left))){
        $this->left = $left;
        }
      else{
        return $this->left;
        }
      }
    public function right($right = null) {
      if((!empty($right))&&(is_numeric($right))){
        $this->right = $right;
        }
      else{
        return $this->right;
        }
      }
    public function align($align = null) {
      if(!empty($align)){
        $this->align = $align;
        }
      else{
        return $this->align;
        }
      }
    public function wrap(){
      $this->buffer .= "\n";
      }
    public function fill($char, $length = 0, $fromLeft = null, $fromRight = null){
      $text = "";
      $width = $this->width;
      $left = $this->left;
      $right = $this->right;
      $align = $this->align;
      if(empty($fromLeft)){
        $fromLeft = $left;
        }
      if(empty($fromRight)){
        $fromRight = $right;
        }
      $continue = 0;
      if($width > 0){
        if(($length <= 0)||($length > ($width - $fromLeft - $fromRight))){ 
          $length = $width - $fromLeft - $fromRight;
          }
        /***/
        if($align == "right"){
          $fromLeft = $width - $fromRight - $length;
          }
        elseif($align == "center"){
          $margin = ceil(($width - $fromLeft - $fromRight - $length)/2);
          $fromLeft += $margin;
          }
        $continue = 1;
        }
      elseif($length > 0){
        $continue = 1;
        }
      if($continue == 1){
        for($i = 0; $i < $fromLeft;$i++){
          $text .= " "; 
          }
        for($j = 0; $j < $length;$j++){
          $text .= $char; 
          }
        $this->buffer .= $text;
        }
      }
    public function fillSpace($length = 0, $start = 0){
      if($length > 0){
        $text = "";
        for($i = $start; $i < $length;$i++){
          $text .= " ";
          }  
        $this->buffer .= $text;
        }
      }
    public function insert($string){
      $text = $textLeft = "";
      $textArray = explode("\n", $string);
      $width = $this->width;
      $left = $this->left;
      $right = $this->right;
      $align = $this->align;
      $length = strlen(utf8_decode($string));
      $fromLeft = $fromRight = $marginText = $space = 0;
      if(!empty($textArray)){
        $space = $width;
        if($length > 0){
          $fromLeft = $left;
          if($width > 0){
            $space = $width - $left - $right;
            }
          }
        for($i = 0; $i < $fromLeft;$i++){
          $textLeft .= " "; 
          }
        foreach($textArray AS $key => $data){
          if($key > 0){
            $text .= "\n";
            }
          $j = 0;
          $remain = $data;
          do{
            $part = substr($remain, 0, $space);
            $remain = substr($remain, $space);
            $spaceLength = ($space - strlen(utf8_decode($part))); 
            if($spaceLength>0){
              if($align == "right"){
                $marginText = $spaceLength;
                }
              elseif($align == "center"){
                $marginText = ceil($spaceLength/2);
                }
              for($k = 0; $k < $marginText; $k++){
                $part = " ".$part;
                }
              }
            /***/
            if($j > 0){
              $text .= "\n";
              }
            $text .= $textLeft.$part;
            $j++; 
            }
          while((!empty($remain))&&(strlen(utf8_decode($remain))>0));
          }
        $this->buffer .= $text;
        }
      /************/
      }
    
    public function insertTable($textArray, $sizeArray = null, $alignArray = null, $marginArray = null){
      if(!empty($textArray)){
        $width = $this->width;
        $left = $this->left;
        $right = $this->right;
        $width -= ($left + $right);
        $align = $this->align;
        $space = $zeroNumbers = 0;
        $checkSize = false;
        $lengthMax = array();
        if(empty($sizeArray)){
          $sizeArray = array();
          $checkSize = true;
          }
        if((!($width > 0))||(sizeof($sizeArray) < sizeof($textArray[0]))){
          $checkSize = true;
          }
        if(empty($alignArray)){
          $alignArray = array();
          }
        //load data
        foreach($textArray AS $line => $lineData){
          foreach($lineData AS $lineKey => $field){
            // make size array value based on max length of array fields 
            if($checkSize){
              $lenghtField = mb_strlen($field, 'utf-8');
              if(!isset($sizeArray[$lineKey])){
                $sizeArray[$lineKey] = $lenghtField;
                }
              elseif($sizeArray[$lineKey] < $lenghtField){
                $sizeArray[$lineKey] = $lenghtField;
                }
              }
            }
          for($i = 0; $i < sizeof($lineData);$i++){
            // check aling array if have all align values 
            if(empty($alignArray[$i])){
              $alignArray[$i] = $align;
              }
            // check margin array if have all margin values; first value is for all
            $indexI = $i+1;  
            if(empty($marginArray[$indexI])){
              $marginArray[$indexI] = $marginArray[0];
              }
            }
          if((isset($indexI))&&($indexI >= sizeof($lineData))){
            unset($marginArray[$indexI]);
            }
          }
        //printrko($sizeArray);
        //printrko($alignArray);
        //printrko($marginArray);
        if($width > 0){
          $marginUsed = 0;
          $sizeEmpty = array();
          $spaceUsed = array_sum($sizeArray);
          $sizeOfSizeArray = sizeof($sizeArray);
          $valuesCount = array_count_values($sizeArray);
          unset($marginArray[0]);
          $marginUsed = array_sum($marginArray);
          $sizeEmpty = array_keys($sizeArray, 0);
          if(!empty($sizeEmpty)){
            $sizeOfSizeEmpty = $valuesCount[0];
            $freespace = $width - $spaceUsed - $marginUsed;
            //echo "FC:".$freespace.";".$sizeEmpty.";".$sizeOfSizeEmpty.";<br />"; 
            $addSize = ceil($freespace / $sizeOfSizeEmpty);  
            foreach($sizeEmpty AS $sizeEmptyField){
              if($sizeOfSizeEmpty == 1){
                $addSize = $width - $spaceUsed - $marginUsed;
                }
              $sizeArray[$sizeEmptyField] = $addSize;
              $spaceUsed += $addSize;  
              $sizeOfSizeEmpty--;
              }
            }
          }
         
        //procesed data into string
        foreach($textArray AS $line => $lineData){
          $pretext = array();
          $maxLines = 0;
          foreach($lineData AS $lineKey => $field){
            $max = 0;
            $alignAktual = $alignArray[$lineKey];
            $pretext[$lineKey] = array();
            $before = $after = "";
            $lenghtField = ($sizeArray[$lineKey] - mb_strlen($field, 'utf-8'));
            /***/
            $remain = $field;
            $space = $sizeArray[$lineKey];
            do{
              $max++; 
              $marginPre = $marginAfter = 0;
              //$text[$lineKey]
              $part = mb_substr($remain, 0, $space, 'utf-8');
              $remain = mb_substr($remain, $space, mb_strlen($remain, 'utf-8'), 'utf-8');
              $spaceLength = ($space - mb_strlen($part, 'utf-8')); 
              if($spaceLength>0){
                if($alignAktual == "right"){
                  $marginPre = $spaceLength;
                  }
                elseif($alignAktual == "center"){
                  $marginPre = ceil($spaceLength/2);
                  $marginAfter = $spaceLength - $marginPre;  
                  }
                else{
                  $marginAfter = $spaceLength;
                  }
                for($k = 0; $k < $marginPre; $k++){
                  $part = " ".$part;
                  }
                for($k = 0; $k < $marginAfter; $k++){
                  $part .= " ";
                  }
                }
              /***/
              $pretext[$lineKey][] = $part;
              }
            while((!empty($remain))&&(strlen($remain)>0));
            if($max > $maxLines){
              $maxLines = $max;
              }
            }
          if(!empty($pretext)){
            $pretextLength = sizeof($pretext);
            for($i = 0; $i < $maxLines; $i++){
              $this->fillSpace($left);
              for($j = 0; $j < $pretextLength; $j++){
                if(isset($pretext[$j][$i])){
                  $this->write($pretext[$j][$i]);    
                  }
                else{
                  $this->fillSpace($sizeArray[$j]);
                  }
                if(isset($marginArray[($j+1)])){
                  $this->fillSpace($marginArray[($j+1)]);  
                  }
                }
              $this->fillSpace($right);
              $this->wrap();
              }
            }
          }
        //echo "T:<br />\n".win2utf($this->show_buffer()).";<br />";
        }
      /************/
      }
    } //End of Class "Printer"
    
?>