<?php


function list_soas($dichiarazione_id)
{
    $CI =& get_instance();
    $soas = $CI -> admin_model ->elenco_soas($dichiarazione_id);
    if($soas):
    echo "<ul>";
    foreach ($soas as $soa): ?>
        <li class="soas"><strong><?php echo $soa['codice']; ?></strong> <?php echo $soa['denominazione']; ?>, classe <?php echo $soa['classe']; ?></div>
    <?php endforeach;
    echo "</ul>";
    endif;
}

function set_search_querystring_esecutori () {
	$CI =& get_instance();
	$item_null = 'codice_istanza';
    $items = array('ragione_sociale', 'partita_iva', 'codice_fiscale');
    $items_dropdown = array ('stato','uploaded','is_digital','stmt_wl','protocollato');
	$params = array();
	$params_dropdown = array();
    $date_array= array('uploaded_at','iscritti_at','iscritti_prov_at','iscritti_scadenza','iscritti_prov_scadenza','created_at');
    foreach ($items as $item) {
        $parameter = $CI -> input -> get($item);
        $params[$item] = empty($parameter) ? "" : $parameter;
        if(in_array($item, $date_array))
        {
            $params[$item] = partial_parse_date($parameter);
        }

    }
	
	$parameter_null = $CI -> input -> get($item_null);
	if ($parameter_null!="") {
		$params[$item_null] = $parameter_null;
	}
	
	foreach ($items_dropdown as $item_dropdown) {
	$parameter_dropdown = $CI -> input -> get($item_dropdown);
	if($parameter_dropdown == "")
	$params[$item_dropdown]="";
	else
	$params[$item_dropdown]= $parameter_dropdown;
	}

    $order_by = $CI -> input -> get('order_by');
    $order_by = empty($order_by) ? "is_digital desc,uploaded_at desc" : $order_by;
	if (($order_by=="ragione_sociale asc") or ($order_by=="ragione_sociale desc") or ($order_by=="codice_istanza desc") or ($order_by=="codice_istanza asc") or ( $order_by=="uploaded_at desc") or ( $order_by=="uploaded_at asc") 
		or ( $order_by=="iscritti_at desc") or ( $order_by=="iscritti_at asc") or ( $order_by=="iscritti_prov_at desc") or ( $order_by=="iscritti_prov_at asc") or ( $order_by=="iscritti_scadenza desc") or ( $order_by=="iscritti_scadenza asc")  
	or ( $order_by=="iscritti_prov_scadenza desc") or ( $order_by=="iscritti_prov_scadenza asc") or ( $order_by=="created_at desc") or ( $order_by=="created_at asc") or ( $order_by=="is_digital desc") or ( $order_by=="is_digital asc") )  {
    $qs = array_merge($params,$params_dropdown,array('order_by' => $order_by));
    return array('params' => $params,'order_by' => $order_by, 'qs' => $qs);
   }
   else {
   $qs = array_merge($params, $params_dropdown, array('order_by' => $order_by));
   return array('params' => $params,'order_by' => "is_digital desc,uploaded_at desc", 'qs' => $qs);

   }
}

function list_atecos($dichiarazione_id)
{
    $CI =& get_instance();
    $atecos= $CI -> admin_model ->elenco_atecos($dichiarazione_id);
    if($atecos):
    echo "<ul>";
    foreach ($atecos as $ateco): ?>
        <li class="atecos"><strong><?php echo $ateco['codice']; ?></strong> <?php echo $ateco['denominazione']; ?></li>
    <?php endforeach;
    echo "</ul>";
    endif;
}


function current_controller($now){
    $CI =& get_instance();
    $controller = $CI->router->fetch_class();
    if($now == $controller){
        return TRUE;
    }
}

function current_action($now){
  $CI =& get_instance();
  $action = $CI->router->fetch_method();
  if($now == $action){
      return TRUE;
  }
}

function logged_in(){
    $CI =& get_instance();
    return $CI -> session->userdata('logged_in');
}

function is_admin(){
    $CI =& get_instance();
    $result = $CI -> session->userdata('logged_in') && $CI -> session->userdata('admin');
    return $result;
}

function set_search_querystring(){
    $CI =& get_instance();
    $items = array('id', 'name', 'ragione_sociale', 'data_firma', 'sl_piva', 'created_at', 'uploaded_at', 'sl_cf');
    $items_dropdown = array ('uploaded','durc','protesti','antimafia','pubblicato');
	$params = array();
	$params_dropdown = array();
    $date_array= array('created_at', 'uploaded_at', 'data_firma');
    foreach ($items as $item) {
        $parameter = $CI -> input -> get($item);
        $params[$item] = empty($parameter) ? "" : $parameter;
        if(in_array($item, $date_array))
        {
            $params[$item] = partial_parse_date($parameter);
        }

    }

	foreach ($items_dropdown as $item_dropdown) {
	$parameter_dropdown = $CI -> input -> get($item_dropdown);
	if($parameter_dropdown == "")
	$params[$item_dropdown]="";
	else
	$params[$item_dropdown]= $parameter_dropdown;
	}

    $order_by = $CI -> input -> get('order_by');
    $order_by = empty($order_by) ? "id desc" : $order_by;
	if (($order_by=="ragione_sociale asc") or ($order_by=="ragione_sociale desc") or ($order_by=="id desc") or ($order_by=="id asc") or ($order_by=="name desc") or ($order_by=="name asc") or
	($order_by=="data_firma asc") or ($order_by=="data_firma desc") or ($order_by=="sl_piva asc") or ($order_by=="sl_piva desc") or ($order_by=="created_at asc") or ($order_by=="created_at desc") or ($order_by=="sl_cf asc") or ($order_by=="sl_cf desc")
	or ($order_by=="5bis asc") or ($order_by=="5bis desc") or ($order_by=="pubblicato asc") or ($order_by=="pubblicato desc") or ($order_by=="durc asc") or ($order_by=="durc desc") or ($order_by=="protesti asc") or ($order_by=="protesti desc")
	or ($order_by=="antimafia asc") or ($order_by=="antimafia desc") or ($order_by=="uploaded_at asc") or ($order_by=="uploaded_at desc"))  {
    $qs = array_merge($params,$params_dropdown,array('order_by' => $order_by));
    return array('params' => $params,'order_by' => $order_by, 'qs' => $qs);
   }
   else {
   $qs = array_merge($params, $params_dropdown, array('order_by' => $order_by));
   return array('params' => $params,'order_by' => "id desc", 'qs' => $qs);

   }
   }



function query_link($href, $name){
    $base = current_url();
    $criteria = set_search_querystring_esecutori();

    $class = preg_match("/^{$href}/", $criteria['order_by']) ? "selected" : "unselected";

    $order_by = "{$href} desc" == $criteria['order_by'] ? "{$href} asc" : "{$href} desc";
    $qs = array_merge($criteria['params'], array('order_by' => $order_by));

    $url = htmlentities($base . "?" . http_build_query($qs));
    echo "<a href='{$url}' class='{$class}'>{$name}</a>";
}

function valid_hash($hash){
    if($hash == FALSE){
        return FALSE;
    }
    $CI =& get_instance();
    $query = $CI -> db -> get_where('dichiaraziones', array('hash' => $hash, 'uploaded != ' => '1'), 1, 0);

    $results = $query->result_array();
    log_message('debug', print_r($results, TRUE));
    return $results ? $results[0] : FALSE;
}

/*
 ██████ ██   ██ ███████  ██████ ██   ██ ██   ██  █████  ███████ ██   ██
██      ██   ██ ██      ██      ██  ██  ██   ██ ██   ██ ██      ██   ██
██      ███████ █████   ██      █████   ███████ ███████ ███████ ███████
██      ██   ██ ██      ██      ██  ██  ██   ██ ██   ██      ██ ██   ██
 ██████ ██   ██ ███████  ██████ ██   ██ ██   ██ ██   ██ ███████ ██   ██
*/
function valid_hash_new($hash){
  if($hash == FALSE){
    return FALSE;
  }
  $CI =& get_instance();
  $query = $CI -> db -> get_where('esecutori', array('hash' => $hash, 'uploaded != ' => '1'), 1, 0);
  $results = $query->result_array();
  return $results ? $results[0] : FALSE;
}

function gestione_dropdown($nomecampo, $options)
{
    global $item;
    echo form_dropdown("{$nomecampo}[{$item['id']}]", $options, $item['"{$nomecampo}"'], "class='{$nomecampo}'");
}

function legenda(){
    $result = array(
                    'uploaded' => array('0' => 'no', '1' => 'si', '2' => 'non conforme'),
                    'durc' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'regolare', '3' => 'non regolare','4' => 'scaduto'),
                    'antimafia' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'nulla osta', '3' => 'ostativa','4' => 'interdittiva','5' => 'scaduta','6' => 'iscritta White List'),
                    'protesti' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'protestato', '3' => 'non protestato', '4' => 'scaduto'),
                    '5bis' => array('0' => 'non soggetto', '1' => 'soggetto', '2' => 'in attesa', '3' => 'pubblicato prefettura'),
                    'pubblicato' => array('1' => 'pubblicato', '0' => 'non pubblicato'),
                    'login' => array('1' => 'login', '2' => 'cred. errate', '3' => 'sospeso', '4' => 'logout'),
					'stato' => array('1' => 'iscritto', '2' => 'iscritto provvisoriamente', '0' => 'in richiesta'),
					'parere_dia' => array ('0'=>'no','1'=>'si'),
					'parere_prefettura' => array ('0'=>'no','1'=>'si'),
					'avvio_proc_sent' => array ('0'=>'no','1'=>'si'),
					'pref_soll_30_sent' => array ('0'=>'no','1'=>'si'),
					'pref_soll_75_sent' => array ('0'=>'no','1'=>'si')

    );
    return $result;
}



function durc_description ($id) {
	$CI =& get_instance();
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();
	switch ( $item['durc']) {
	case 0:
	$message = "Il controllo DURC è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo DURC è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo DURC è in stato di REGOLARE.";
	break;
	case 3:
	$message = "Il controllo DURC è in stato di NON REGOLARE.";
	break;
	case 4:
	$message = "Il controllo DURC è in stato di SCADUTO.";
	break;
	}
	return $message;
	}


function protesti_description ($id) {
	$CI =& get_instance();
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();
	switch ( $item['protesti']) {
	case 0:
	$message = "Il controllo PROTESTI è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo PROTESTI è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo PROTESTI è in stato di PROTESTATO.";
	break;
	case 3:
	$message = "Il controllo PROTESTI è in stato di NON PROTESTATO.";
	break;
	case 4:
	$message = "Il controllo PROTESTI è in stato di SCADUTO.";
	break;
	}
	return $message;
	}



function antimafia_description ($id) {
	$CI =& get_instance();
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();
	switch ( $item['antimafia']) {
	case 0:
	$message = "Il controllo ANTIMAFIA è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo ANTIMAFIA è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo ANTIMAFIA è in stato di NULLA OSTA.";
	break;
	case 3:
	$message = "Il controllo ANTIMAFIA è in stato di OSTATIVA.";
	break;
	case 4:
	$message = "Il controllo ANTIMAFIA è in stato di INTERDITTIVA.";
	break;
	case 5:
	$message = "Il controllo ANTIMAFIA è in stato di SCADUTO.";
	break;
	case 6:
	$message = "Il controllo antimafia è in stato di ISCRITTO ALLA WHITE LIST";
	break;
	}
	return $message;
	}



function create_codice_istanza($doc) {
  return sprintf(
    "%s_%s_%s",
    'AE',
    str_pad($doc['ID'], 6, '0', STR_PAD_LEFT),
    substr($doc['istanza_data'], 0, 4)
  );
}

function export_antimafia_components($id){
  $CI =& get_instance();
  $CI->load->helper('file');
  $role_list = $CI->dichiarazione_model->get_roles();
  $documento = $CI->dichiarazione_model->get_document($id);
  if ( 0 == $documento['forma_giuridica_id'] ) {
    $shape_label = $documento['impresa_forma_giuridica_altro'];
  }
  else {
    $shapes = $CI->dichiarazione_model->get_forma_giuridica_label($documento['forma_giuridica_id']);
    if (!empty($shapes)) {
      $shape_label = $shapes[0]['valore'];
    }
  }
  $columns = array(
    array( 'label' => 'Prog.', 'field' => 'n' ),
    array( 'label' => 'CODICE-FISCALE-IMPRESA', 'field' => 'codice_fiscale_azienda' ),
    array( 'label' => 'PARTITA-IVA-IMPRESA', 'field' => 'partita_iva'),
    array( 'label' => 'TIPO-SOCIETA\'', 'field' => 'forma_giuridica_id'),
    array( 'label' => 'RAGIONE-SOCIALE', 'field' => 'ragione_sociale' ),
    array( 'label' => 'PROVINCIA', 'field' => 'sl_prov' ),
    array( 'label' => 'SEDE-LEGALE', 'field' => 'sede_legale' ),
    array( 'label' => 'COGNOME', 'field' => 'cognome' ),
    array( 'label' => 'NOME', 'field' => 'nome' ),
    array( 'label' => 'DATA-NASCITA', 'field' => 'data_nascita' ),
    array( 'label' => 'LUOGO-NASCITA', 'field' => 'luogo_nascita' ),
    array( 'label' => 'CODICE-FISCALE-PERSONA', 'field' => 'codice_fiscale' ),
    array( 'label' => 'SOCIO-DI-MAGG', 'field' => 'socio_maggioranza' ),
    array( 'label' => 'RUOLO', 'field' => 'ruolo' ),
    array( 'label' => 'RIFERIMENTO SOGGETTO', 'field' => 'riferimento' ),
  );

  $csv = new CthCsvExporter($columns);
  $csv->setDelimeter('|');

  $soggetti = array();
  $n = 1;
  if ( !empty($documento['anagrafiche_antimafia']) ) {
    foreach ($documento['anagrafiche_antimafia'] AS $anagrafica) {
      $is_socio_maggioranza = $anagrafica['role_id'] == 24 ? 'X' : '';
      $csv->addRow(array(
        'n' => $n,

        'codice_fiscale_azienda' => $documento['codice_fiscale'],
        'partita_iva' => $documento['partita_iva'],
        'ragione_sociale' => $documento['ragione_sociale'],
        'forma_giuridica_id' => $shape_label,
        'sl_prov' => $documento['sl_prov'],
        'sede_legale' => sprintf("%s %s", $documento['sl_via'], $documento['sl_civico']),

        'cognome' => $anagrafica['antimafia_cognome'],
        'nome' => $anagrafica['antimafia_nome'],
        'data_nascita' => format_date($anagrafica['antimafia_data_nascita']),
        'luogo_nascita' => $anagrafica['antimafia_comune_nascita'],
        'codice_fiscale' => $anagrafica['antimafia_cf'],
        'ruolo' => $role_list[$anagrafica['role_id']],

        'socio_maggioranza' => $is_socio_maggioranza,

        'riferimento' => ''
      ));
      $parent_n = $n;
      $n++;
      if ( isset($anagrafica['familiari']) && !empty($anagrafica['familiari']) ) {
        foreach ($anagrafica['familiari'] AS $familiare) {
          $csv->addRow(array(
            'n' => $n,

            'codice_fiscale_azienda' => $documento['codice_fiscale'],
            'partita_iva' => $documento['partita_iva'],
            'ragione_sociale' => $documento['ragione_sociale'],
            'forma_giuridica_id' => $shape_label,
            'sl_prov' => $documento['sl_prov'],
            'sede_legale' => sprintf("%s %s", $documento['sl_via'], $documento['sl_civico']),

            'cognome' => $familiare['nome'],
            'nome' => $familiare['cognome'],
            'data_nascita' => format_date($familiare['data_nascita']),
            'luogo_nascita' => $familiare['comune'],
            'codice_fiscale' => $familiare['cf'],
            'ruolo' => $role_list[$familiare['role_id']],

            'socio_maggioranza' => '',

            'riferimento' => $parent_n
          ));
          $n++;
        }
      }
    }
    $csv->create();
    return $csv->writeToFile($documento['codice_istanza'] . '.csv');
  }
}


/*
 ██████ ████████ ██   ██  ██████ ███████ ██    ██ ███████ ██   ██ ██████   ██████  ██████  ████████ ███████ ██████
██         ██    ██   ██ ██      ██      ██    ██ ██       ██ ██  ██   ██ ██    ██ ██   ██    ██    ██      ██   ██
██         ██    ███████ ██      ███████ ██    ██ █████     ███   ██████  ██    ██ ██████     ██    █████   ██████
██         ██    ██   ██ ██           ██  ██  ██  ██       ██ ██  ██      ██    ██ ██   ██    ██    ██      ██   ██
 ██████    ██    ██   ██  ██████ ███████   ████   ███████ ██   ██ ██       ██████  ██   ██    ██    ███████ ██   ██
*/
class CthCsvExporter{
  private $_delimeter = ',';
  private $_newline = "\n";
  private $_cols = array();
  private $_data = array();
  private $_header = array();
  private $_buffer = '';
  const OUTPUT_PATH = FCPATH . 'csv/';

  public function __construct($cols){
    if ( is_array($cols) ) {
      $this->_cols = $cols;
      // header
      foreach ($this->_cols AS $col) {
        if ( isset($col['label']) ) {
          // Escaping?
          $this->_header[] = $col['label'];
        }
        else {
          // Error if mandatory?
          $this->_header[] = '';
        }
      }
    }
  }

  public function setDelimeter($char) {
    if ( empty($char) ) {
      throw new Exception("Delimeter char must not be empty.", 1);
    }
    $this->_delimeter = $char;
  }

  public function addRow($data) {
    $_tmpData = array();
    foreach ($this->_cols AS $col) {
      if ( isset($data[$col['field']]) ) {
        // Escaping?
        $_tmpData[$col['field']] = $data[$col['field']];
      }
      else {
        // Error if mandatory?
        $_tmpData[$col['field']] = '';
      }
    }
    $this->_data[] = $_tmpData;
  }

  public function create($headers = true){
    if ( $headers ) {
      $this->_buffer .= sprintf(
        "%s%s",
        implode($this->_delimeter, $this->_header),
        $this->_newline
      );
    }

    foreach ( $this->_data AS $element ) {
      $tmpData = array();
      foreach ($this->_cols AS $col) {
        if ( isset($element[$col['field']]) ) {
          // Escaping?
          $tmpData[] = $element[$col['field']];
        }
        else {
          // Error if mandatory?
          $tmpData[] = '';
        }
      }


      $this->_buffer .= sprintf(
        "%s%s",
        implode($this->_delimeter, $tmpData),
        $this->_newline
      );
    }

  }

  public function debug() {
    echo "<h7>debug@" .__FILE__.":".__LINE__."</h7><pre>";
    var_dump($this->_buffer);
    echo "</pre>";
  }

  public function writeToFile($filename = '') {
    if ( '' == $filename ) {
      $filename = uniqid('csv_');
    }
    $full_path = self::OUTPUT_PATH . $filename;
    $fh = fopen($full_path, 'wb');
    fwrite($fh, pack("CCC",0xef,0xbb,0xbf));
    fwrite($fh, $this->_buffer);
    fclose($fh);
    @chmod($full_path, 0777);
    return array(
      'folder' => self::OUTPUT_PATH,
      'file' => $filename,
      'path' => $full_path
    );
  }
}
