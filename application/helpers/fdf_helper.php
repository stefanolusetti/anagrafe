<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/*
$myFdf->makeFDF();
$myFdf->fillForms();
$fileinfo = $myFdf->mergeAll();
$myFdf->removePages();
*/

class CerthideaFDF {
  const TMP_PATH = FCPATH . 'pdf/xfdf/';
  const SOURCE_PATH = FCPATH . 'pdf/sources/';
  const OUTPUT_PATH = FCPATH . 'pdf/outputs/';

  private $_pages, $_tmpHash, $_outputFileName;
/*
                 ██████  ██████  ███    ██ ███████ ████████ ██████  ██    ██  ██████ ████████
                ██      ██    ██ ████   ██ ██         ██    ██   ██ ██    ██ ██         ██
                ██      ██    ██ ██ ██  ██ ███████    ██    ██████  ██    ██ ██         ██
                ██      ██    ██ ██  ██ ██      ██    ██    ██   ██ ██    ██ ██         ██
███████ ███████  ██████  ██████  ██   ████ ███████    ██    ██   ██  ██████   ██████    ██
*/
  public function __construct($name = false) {
    $this->_pages = array();
    // Hash che vale solo per una singola chiamata.
    $this->_tmpHash = uniqid();
    // Stabilisco il path sul server dove i file di questo documento saranno ospitati
    $this->_currPath = self::TMP_PATH . $this->_tmpHash;
    // Filename arrandom.
    if ( false == $name ) {
      $this->_outputFileName = md5(uniqid()) . '.pdf';
    }
    else {
      $this->_outputFileName = $name . '.pdf';
    }
  }

/*
███████ ████████ ██████  ██ ██████      █████   ██████  ██████ ███████ ███    ██ ████████ ██
██         ██    ██   ██ ██ ██   ██    ██   ██ ██      ██      ██      ████   ██    ██    ██
███████    ██    ██████  ██ ██████     ███████ ██      ██      █████   ██ ██  ██    ██    ██
     ██    ██    ██   ██ ██ ██         ██   ██ ██      ██      ██      ██  ██ ██    ██    ██
███████    ██    ██   ██ ██ ██ ███████ ██   ██  ██████  ██████ ███████ ██   ████    ██    ██
*/
  static public function strip_accenti($var) {
    $search = array('ò', 'à', 'è', 'é', 'ù', 'ì');
    $replace = array('o\'', 'a\'', 'e\'', 'e\'', 'u\'', 'i\'');
    return str_replace($search, $replace, $var);
  }

/*
 █████  ██████  ██████  ██████   █████   ██████  ███████
██   ██ ██   ██ ██   ██ ██   ██ ██   ██ ██       ██
███████ ██   ██ ██   ██ ██████  ███████ ██   ███ █████
██   ██ ██   ██ ██   ██ ██      ██   ██ ██    ██ ██
██   ██ ██████  ██████  ██      ██   ██  ██████  ███████
*/
  public function addPage($template, $dati, $flatten = 'flatten') {
    //controllo che il template PDF esista.
    if(is_file(self::SOURCE_PATH . $template)){
      $filename = 'page-' . count($this->_pages);
      if(is_array($dati)){
        $fdf = $filename . '.fdf';
      }
      else{
        $fdf = '';
        $dati = '';
      }
      $this->_pages[] = array(
        'fdf' => $fdf,
        'template' => $template,
        'output' => $filename . '.pdf',
        //'dati' => is_array($dati) ? array_map('CerthideaFDF::strip_accenti', $dati) : '',
        'dati' => $dati,
        'flatten' => $flatten
      );
      return true;
    }
    else {
      return false;
    }
  }

/*
███    ███  █████  ██   ██ ███████ ███████ ██████  ███████
████  ████ ██   ██ ██  ██  ██      ██      ██   ██ ██
██ ████ ██ ███████ █████   █████   █████   ██   ██ █████
██  ██  ██ ██   ██ ██  ██  ██      ██      ██   ██ ██
██      ██ ██   ██ ██   ██ ███████ ██      ██████  ██
*/
  public function makeFDF(){
    if (!is_writable($this->_currPath)) {
      if (!mkdir($this->_currPath, 0777)) {
        die("cant create fdf folder: " . $this->_currPath);
      }
    }
    //creo la cartella che li conterrà tutti
    foreach($this->_pages AS $page){
      //creo il file FDF
      if ($page['fdf'] != '') {
        if(!$fh = fopen($this->_currPath . '/' . $page['fdf'], 'w')) {
          die("cant create fdf file");
        }
        chmod($this->_currPath . '/' . $page['fdf'], 0777);
        if(!fwrite($fh, $this->_createXFDF(self::SOURCE_PATH . $page['template'], $page['dati']))) {
          die("cant write file");
        }
        fclose($fh);
      }
    }
  }

/*
         ██████ ██████  ███████  █████  ████████ ███████ ███████ ██████  ███████
        ██      ██   ██ ██      ██   ██    ██    ██      ██      ██   ██ ██
        ██      ██████  █████   ███████    ██    █████   █████   ██   ██ █████
        ██      ██   ██ ██      ██   ██    ██    ██      ██      ██   ██ ██
███████  ██████ ██   ██ ███████ ██   ██    ██    ███████ ██      ██████  ██
*/
  private function _createFdf($file, $info){
    $data = "%FDF-1.2\n%âãÏÓ\n1 0 obj\n<< \n/FDF << /Fields [ ";
    foreach ($info as $field => $val) {
      if(is_array($val)){
        $data.='<</T('.$field.')/V[';
        foreach($val as $opt)
          $data.='('.trim($opt).')';
          $data.=']>>';
        }else{
          $data.='<</T('.$field.')/V('.trim($val).')>>';
        }
      }
      $data .= "] \n/F (".$file.") /ID [ <".md5(time()).">\n] >>"
        . " \n>> \nendobj\ntrailer\n"
        . "<<\n/Root 1 0 R \n\n>>\n%%EOF\n";
      return $data;
  }

/*
         ██████ ██████  ███████  █████  ████████ ███████ ██   ██ ███████ ██████  ███████
        ██      ██   ██ ██      ██   ██    ██    ██       ██ ██  ██      ██   ██ ██
        ██      ██████  █████   ███████    ██    █████     ███   █████   ██   ██ █████
        ██      ██   ██ ██      ██   ██    ██    ██       ██ ██  ██      ██   ██ ██
███████  ██████ ██   ██ ███████ ██   ██    ██    ███████ ██   ██ ██      ██████  ██
*/
  private function _createXFDF($file, $info, $enc='UTF-8'){
    $data = '<?xml version="1.0" encoding="' . $enc . '"?>' . "\n".
      '<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
      '<fields>' . "\n";
    foreach ($info as $field => $val) {
      $data .= '<field name="' . $field . '">' . "\n";
      if (is_array($val)) {
        foreach ($val as $opt) {
          $data .= '<value>' . htmlentities($opt) . '</value>' . "\n";
        }
      }
      else {
        $data .= '<value>' . htmlentities($val) . '</value>' . "\n";
      }
      $data .= '</field>' . "\n";
    }
    $data .= '</fields>' . "\n".
      '<ids original="' . md5($file) . '" modified="' . time() . '" />' . "\n"
      . '<f href="'.$file.'" />' . "\n"
      . "</xfdf>\n";
    return $data;
  }


/*
███████ ██ ██      ██      ███████  ██████  ██████  ███    ███ ███████
██      ██ ██      ██      ██      ██    ██ ██   ██ ████  ████ ██
█████   ██ ██      ██      █████   ██    ██ ██████  ██ ████ ██ ███████
██      ██ ██      ██      ██      ██    ██ ██   ██ ██  ██  ██      ██
██      ██ ███████ ███████ ██       ██████  ██   ██ ██      ██ ███████
*/
  public function fillForms(){
    foreach($this->_pages AS $page){
      if($page['fdf'] != '') {
        $command = sprintf(
          "pdftk %s fill_form %s output %s %s",
          self::SOURCE_PATH . $page['template'],
          $this->_currPath . '/' . $page['fdf'],
          $this->_currPath . '/' . $page['output'],
          $page['flatten']
        );
        passthru($command);
      }
      else {
        // Blank PDF? without forms?
        copy(self::SOURCE_PATH . $page['template'], $this->_currPath . '/' . $page['output']);
      }
      if (file_exists($this->_currPath . '/' . $page['output'])) {
        chmod($this->_currPath . '/' . $page['output'], 0777);
      }
      else {
        echo "filled file not created: '$command'<br />";
      }
    }
    return true;
  }

/*
███    ███ ███████ ██████   ██████  ███████  █████  ██      ██
████  ████ ██      ██   ██ ██       ██      ██   ██ ██      ██
██ ████ ██ █████   ██████  ██   ███ █████   ███████ ██      ██
██  ██  ██ ██      ██   ██ ██    ██ ██      ██   ██ ██      ██
██      ██ ███████ ██   ██  ██████  ███████ ██   ██ ███████ ███████
*/
  public function mergeAll(){
    $filelist = '';
    foreach ($this->_pages AS $page) {
      if (is_file($this->_currPath . '/' . $page['output'])) {
        $filelist .= sprintf(
          "%s/%s ", // THE END SPACE IS NEEDED!!!
          $this->_currPath,
          $page['output']
        );
      }
    }

    if (!is_dir(self::OUTPUT_PATH)) {
      mkdir(self::OUTPUT_PATH, 0777);
      chmod(self::OUTPUT_PATH, 0777);
    }

    if ($filelist != '') {
      $command = sprintf(
        "pdftk %s cat output %s",
        $filelist,
        self::OUTPUT_PATH . $this->_outputFileName
      );
      passthru($command);
      chmod(self::OUTPUT_PATH . $this->_outputFileName, 0777);
      return array(
        'folder' => self::OUTPUT_PATH,
        'file' => $this->_outputFileName,
        'path' => self::OUTPUT_PATH . $this->_outputFileName
      );
    }
    else {
      throw new Exception('List of files is empty..');
    }
    return false;
  }

/*
 ██████ ██      ███████  █████  ███    ██
██      ██      ██      ██   ██ ████   ██
██      ██      █████   ███████ ██ ██  ██
██      ██      ██      ██   ██ ██  ██ ██
 ██████ ███████ ███████ ██   ██ ██   ████
*/
  //
  public function clean() {
    $this->_rrmdir($this->_currPath);
  }

/*
        ██████  ██████  ███    ███ ██████  ██ ██████
        ██   ██ ██   ██ ████  ████ ██   ██ ██ ██   ██
        ██████  ██████  ██ ████ ██ ██   ██ ██ ██████
        ██   ██ ██   ██ ██  ██  ██ ██   ██ ██ ██   ██
███████ ██   ██ ██   ██ ██      ██ ██████  ██ ██   ██
*/
// @source http://www.php.net/rmdir
  private function _rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != '.' && $object != '..') {
          if (is_dir($dir . '/' . $object)) {
            $this->_rrmdir($dir . '/' . $object);
          }
          else {
            unlink($dir . '/' . $object);
          }
        }
      }
      rmdir($dir);
    }
  }
}
