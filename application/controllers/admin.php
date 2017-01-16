<?php

/**
 * Questo controller gestisce le azioni di amministrazione.
 * 
 * I metodi esposti dovrebbero essere tutti protetti da autenticazione, tramite le classi:
 * 
 * * Basic401auth.php
 * * Simpleuserauth.php
 * 
 * @author Certhidea
 * 
 * @since 0.1
 * 
 */
 
class Admin extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        
        $this -> load -> library('parser');
        $this -> load -> library('session');
        $this -> load -> library('pagination');
        $this -> load -> library('simpleuserauth');
        
        $this -> load -> model('admin_model');
        $this -> load -> model('comuni_model');
        $this -> load -> model('dichiarazione_model');
		$this -> load -> model('protocollo_model');
        
        $this -> load -> helper('url');
        $this -> load -> helper('pdf');
        $this -> load -> helper('form');
        $this -> load -> helper('application');
		$this->load->library('email');
			
    } 
    
    /**
     * 
     * index
     * 
     * Il metodo index è alias del del metodo lists
     */
    
    public function index($id=null) 
    {
		$data_hidden = array(
		'hidden'=>'0'
		);
		$this->db->where('id',$id);
		$this->db->update('dichiaraziones',$data_hidden);
        $this -> lists();
		
		
		
		
    }
    
    /**
     * logout
     * 
     * Logout esplicito
     */
    
    public function logout() 
    {
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        $this -> simpleuserauth -> force_logout();
        $data['msg'] = "Hai effettutato il logout.";
        $this -> load -> view('elenco/header');
        $this -> load -> view('admin/login', $data);
        $this -> load -> view('templates/footer');
    }

    /**
     * login
     * 
     * Autenticazione
     */
    
    public function login() {
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        if($this -> input -> post()){
            if($this -> simpleuserauth -> authorize()){
                redirect('/admin', 'refresh');
                exit();
            }
            
            $data = array("msg" => "credenziali errate");
        } else {
            $data = array("msg" => "inserire le credenziali di accesso");    
        }
        
        $this -> load -> view('elenco/header');
        $this -> load -> view('admin/login', $data);
        $this -> load -> view('templates/footer');
    }
    
    /**
     * lists
     * 
     * Mostra l'elenco delle domande di iscrizione con i relativi controlli di istruttoria
     * 
     * @param int   $offset   Se fornito utilizzato per la paginazione
     * 
     */
    
    public function lists($offset = 0) 
    {
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        $this -> simpleuserauth -> require_login();
        $this->load->library('pagination');
		
		if ($this -> input -> get('mostra') == ""){
		if ($this -> input -> get('per_page') == "")
		$limit = 25;
		else $limit = $this -> input -> get('per_page');
		}
		else
        $limit = $this -> input -> get('mostra');
        //$limit = 25;
        
        $criteria = set_search_querystring();
		
        $offset = $this -> input -> get('per_page');
		
        $data = $this -> admin_model -> search($criteria['params'],$criteria['order_by'], $offset, $limit);
        
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3;
        $config['page_query_string'] = TRUE;
        $config['base_url'] = site_url('/admin/lists/?' . htmlentities(http_build_query($criteria['qs'])));
        $config['total_rows'] = $data['count'];
        $this->pagination->initialize($config); 

        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('admin/index', $data);
        $this -> load -> view('templates/footer');
    }

    /**
     * view
     * 
     * Mostra il PDF di una specifica domanda.
     * 
     * @param int $id ID della domanda.
     * 
     */
    public function view($id) 
    {
        $this -> simpleuserauth -> require_login();
        $item = $this -> dichiarazione_model -> get_items($id);

        if (empty($item)) {
            show_404();
        }

        $pdf = merge_pdf($item);
        $filename = $item['ragione_sociale'];
        $this -> output -> set_content_type('application/pdf');
        $this -> output -> set_header('Content-Disposition: inline; filename="' . $filename . '"');
        $this -> output -> set_output($pdf);
    }

    /**
     * load
     * 
     * Riempie una form di domanda con i dati di una richiesta presente a elenco.
     * 
     * @param int $id ID della domanda.
     */
    
    public function load($id){
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        $this -> simpleuserauth -> require_login();
        
        
        $soas = $this -> admin_model -> elenco_soas($id);
        $soa = array();
        
        foreach ($soas as $item)
        {
            $soa[$item['soa_id']] = $item['classe'];
        }

        $atecos = $this -> admin_model -> elenco_atecos($id);
        $ateco = array();
        
        foreach ($atecos as $item)
        {
            $ateco[$item['ateco_id']] = "Yes";
        }
		
		
		$antimafias = $this -> dichiarazione_model -> get_items_antimafia($id);                    
		
		foreach ($antimafias as $item_antimafia) {
		
		$_POST['antimafia_nome'] = $item_antimafia['antimafia_nome'];
			
		}
		
		
        
        $_POST = $this -> dichiarazione_model -> get_items($id);
        $_POST['soa'] = $soa;
        $_POST['ateco'] = $ateco;
        $data['soas'] = $this -> dichiarazione_model -> get_soas();
        $data['atecos'] = $this -> dichiarazione_model -> get_atecos();
		$data['antimafias'] = $this -> dichiarazione_model -> get_items_antimafia($id);     
        
        $this -> load -> view('templates/header');
        $this -> load -> view('templates/headbar');
        $this -> parser -> parse('domanda/new', $data);
        $this -> load -> view('templates/footer');
    }
    
    
    /**
     * bonifica
     * 
     * Metodo non pubblicizzato nell'interfaccia utente da usare episodicamente per normalizzare
     * i dati del campo PROVINCIA. La funzione resistiuisce una stringa JSON che riepiloga i record modificati.
     * 
     */
     
    public function bonifica()
    {
        $this -> simpleuserauth -> require_login();
        $result = $this -> admin_model -> bonifica_province();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    
    /**
     * MD5
     * 
     * Genera hash MD5 per le domande che ne sono prive. Metodo non esposto nell'interfaccia utente.
     * 
     */
    
    public function MD5()
    {
        $this -> simpleuserauth -> require_login();
        $result = $this -> admin_model -> setMD5();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /*
	*Salvataggio note
	*/
	
	public function update_note () {
		
		$campo = $this->input->post('note');
		
			if($campo)
            {
			
			 foreach ($campo as $key => $value) {
				 
				 
				 $value_db = $value;
				 
				 $this -> db
                            ->where('id', $key)
                            ->update('dichiaraziones', array('note' => $value_db));
				 
			 }
			

			}		
					
				
			
		
		
	}
	
	/*
	* Costruisce la finestra di dialogo
	*/
	
	public function costruisci_finestra() {
		
	$campo = $this->input->post('note');
		$CI =& get_instance();
	if($campo)	
		 foreach ($campo as $key => $value) {
		$query = $this->db->get_where('dichiaraziones', array('id' => $key));
		$item =  $query->row_array();
		
		$message_durc = durc_description($item['id']);
		echo $message_durc;
		echo '<br>';
		echo '<br>';
		$message_protesti = protesti_description($item['id']);
		echo $message_protesti;
		echo'<br>';
		echo'<br>';
		$message_antimafia = antimafia_description($item['id']);
		echo $message_antimafia;
		echo'<br>';
		echo'<br>';
		echo "Sei sicuro di volere procedere con l'invio della PEC?";
		}
	}		
		
	/*
	* Invia mail in seguito a pubblicazione
	*/
	
	
	public function pec_pubblicazione () {
		
        $campo = $this->input->post('note');
		$CI =& get_instance();
		
		
		 foreach ($campo as $key => $value) {
			$query = $this->db->get_where('dichiaraziones', array('id' => $key));
			$item =  $query->row_array();
			$date = date('Y-m-d H:i:s');
			$date_sanitized = format_date($date);
			$CI->email->from('elencomeritocostruzioni@postacert.regione.emilia-romagna.it', 'Nucleo operativo Elenco di merito');
			//$CI->email->to($item['sl_pec']);
			$CI->email->to('stefano.lusetti@certhidea.it');
			$CI->email->subject('Avviso di pubblicazione sul portale Elenco di merito');
			//$CI->email->subject($item['id']);
			$email_body='Si conferma che in data odierna, '.$date_sanitized.' l\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>pubblicata</b> sul portale Elenco di merito.<br><br>Cordiali Saluti<br>Nucleo operativo Elenco di merito';
			$CI->email->message($email_body);
			$esito = $CI->email->send();
			
			if ($esito)
			$this->output->set_output('MESSAGGIO_OK');
			
			}
		}

	public function pec_spubblicazione () {
		
		$campo = $this->input->post('note');
		$CI =& get_instance();
		if($campo)
            {
			
			 foreach ($campo as $key => $value) {
		
	

        $query = $this->db->get_where('dichiaraziones', array('id' => $key));
		$item =  $query->row_array();
		
		
		$CI->email->from('elencomeritocostruzioni@postacert.regione.emilia-romagna.it', 'Nucleo operativo Elenco di merito');
		//$CI->email->to($item['sl_pec']);
		$CI->email->to('stefano.lusetti@certhidea.it');
		$CI->email->subject('Avviso di spubblicazione dal portale Elenco di merito');
		if ($item['durc']=='3'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}

		if ($item['protesti']=='2'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA PROTESTATA<br><br>
	    L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');
		}
		
		if ($item['antimafia']=='4'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');
		}
		
		if (($item['durc']=='3') && ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA PROTESTATA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		if (($item['durc']=='3') && ($item['antimafia']=='4')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');
		}
		
		if (($item['antimafia']=='4')&& ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA PROTESTATA<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');		
		}
		
		if (($item['durc']=='3')&&($item['antimafia']=='4')&& ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA PROTESTATA<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].'</b> è stata <b>spubblicata</b> dal portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');
		}
		
		$esito = $CI->email->send();
		if ($esito)
			$this->output->set_output('MESSAGGIO_OK');
		//log_message('debug', $CI->email->print_debugger());
		
		
		}	
			}
	}
	
	
	public function pec_non_pubblicazione () {
		
		$campo = $this->input->post('note');
		$CI =& get_instance();
	if($campo)
            {
			
			 foreach ($campo as $key => $value) {
	
        $query = $this->db->get_where('dichiaraziones', array('id' => $key));
		$item =  $query->row_array();
		
		
		$CI->email->from('elencomeritocostruzioni@postacert.regione.emilia-romagna.it', 'Nucleo operativo Elenco merito');
		//$CI->email->to($item['sl_pec']);
		$CI->email->to('stefano.lusetti@certhidea.it');
		$CI->email->subject('Avviso di non pubblicazione sul portale Elenco di merito');
		if ($item['durc']=='3'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}

		if ($item['protesti']=='2'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA PROTESTATA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		if ($item['antimafia']=='4'){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');		
		}
		
		if (($item['durc']=='3') && ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA PROTESTATA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		if (($item['durc']=='3') && ($item['antimafia']=='4')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		if (($item['antimafia']=='4')&& ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		IMPRESA PROTESTATA<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		if (($item['durc']=='3')&&($item['antimafia']=='4')&& ($item['protesti']=='2')){
		$CI->email->message(
		'A seguito dei risultati dei seguenti controlli:<br>
		DURC NON REGOLARE<br>
		IMPRESA PROTESTATA<br>
		IMPRESA SOGGETTA AD INTERDITTIVA ANTIMAFIA<br><br>
		L\'impresa <b>'.$item['ragione_sociale'].' non è stata pubblicata</b> nel portale Elenco di merito.<br><br> Cordiali saluti.<br> Nucleo operativo Elenco di merito');	
		}
		
		$esito = $CI->email->send();
		if ($esito)
			$this->output->set_output('MESSAGGIO_OK');
		//log_message('debug', $CI->email->print_debugger());
		
		
		}	
			}
	}
	
	
	
    /**
     * update
     * 
     * Metodo richiamato via ajax che aggiorna i diversi parametri di istruttoria.
     * Le variabili sono fornite tramite POST.
     * 
     * @param array $this->input->post()
     * 
     */
    
    public function update()
    {
        $this -> simpleuserauth -> require_login();
        $actions = array('uploaded', 'pubblicato', 'durc', 'antimafia', 'protesti', '5bis','durc_scadenza','protesti_scadenza','antimafia_scadenza');
        foreach ($actions as $action) {
            $campo = $this->input->post($action);
			
            if($campo)
            {
				
				
                foreach ($campo as $key => $value) 
                {
				if (($action == 'durc_scadenza') || ($action == 'protesti_scadenza') || ($action == 'antimafia_scadenza')){
				$value_db = parse_date($value);
				}
				else 
				$value_db = $value;
				
                    $this -> db
                            ->where('id', $key)
                            ->update('dichiaraziones', array($action => $value_db));
							
							
				if ($action == "pubblicato") {
				$this -> db
                            ->where('id', $key)
                            ->update('dichiaraziones', array('published_at' => date("Y-m-d H:i:s")));
				
				}
				
				
                    
                    $records = $this->db->get_where('dichiaraziones', array('id' => $key), 1, 0);
                    $record_arr = $records->result_array();
                    $endstring = strlen($record_arr[0]['ragione_sociale']) > 20 ? "..." : "";
                    $record_ragione_sociale = substr($record_arr[0]['ragione_sociale'], 0, 20);
                    $target = "({$key}) {$record_ragione_sociale}{$endstring}";
                    $this->_registralog($action, $value, $target);
					
					
                }  
                $this->output->set_output('OK');
            }
        }
    }
	
	
	public function sblocca() {
	 $this -> simpleuserauth -> require_login();
	 $actions = array('uploaded', 'pubblicato', 'durc', 'antimafia', 'protesti', '5bis');
        foreach ($actions as $action) {
            $campo = $this->input->post($action);
            if($campo)
            {
                foreach ($campo as $key => $value) 
                {
                  
				 $query = $this->db->get_where('dichiaraziones', array('key' => $key));
				 $item =  $query->row_array();
				 echo $this->output->set_output($item['antimafia']);
				
	}
	}
	}
	}
	
	
    
    public function _registralog($campo, $valore, $target = "")
    {
        $data = array(
                      'user_id' => $this->session->userdata('user_id'),
                      'campo' => $campo,
                      'valore' => $valore,
                      'created_at' => date("Y-m-d H:i:s"),
                      'target' => $target
                      );
        $this->db->insert('logs', $data); 
    }
	
	
	
	/**
     * protocollo
     * 
     * Permette di gestire i dati di protocollazione
     * 
     * 
     * 
     */
	
	public function protocollo() 
	{
	 $data = array();
	 $this -> simpleuserauth -> require_login();
        $actions = array('SettIn', 'ServIn', 'UOCIn', 'UOSIn', 'PostIn', 'IdInd','AnnoFasc','ProgrFasc','Numsottofasc');
        foreach ($actions as $action) {
            $campo = $this->input->post($action);
            if($campo)
            {
                foreach ($campo as $value) 
                {
                    $this -> db
                            ->update('protocollazione_config', array($action => $value));
                }  
                $data['msg'] = 'Dati salvati correttamente';
            }
        }
		
	 $this -> load -> view('templates/header');
     $this -> load -> view('templates/headbar');
	 $this -> parser -> parse('admin/protocollo', $data);
     $this -> load -> view('templates/footer');
	
	}
	
	
		/**
     * riepilogo
     * 
     * Permette di creare una pagina riepilogativa 
     * 
     * 
     * 
     */
	public function riepilogo() {
	if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
	$this -> simpleuserauth -> require_login();
	
	$this -> load -> view('templates/header');
    $this -> load -> view('templates/headbar');
	$this -> load -> view('admin/riepilogo');
    $this -> load -> view('templates/footer');
	}
	
  	
	public function alert_scadenze() {
	
	if(ENVIRONMENT == 'development')   
		$this->output->enable_profiler(TRUE);
	$this -> simpleuserauth -> require_login();
	
	 $data['items_durc'] = $this -> admin_model -> find_scadenze_durc();
	 $data['items_antimafia'] = $this -> admin_model -> find_scadenze_antimafia();
	 $data['items_protesti'] = $this -> admin_model -> find_scadenze_protesti();
			
		$this -> load -> view('templates/header');
		$this -> load -> view('templates/headbar');
		$this -> load -> view('admin/alert_scadenze',$data);
		$this -> load -> view('templates/footer');
        }
	
	public function alert_piva_pubblicate() {
	
	if(ENVIRONMENT == 'development')   
		$this->output->enable_profiler(TRUE);
	$this -> simpleuserauth -> require_login();
	
	 $data['items'] = $this -> admin_model -> find_partite_iva_doppie_pubblicate();
	
			
		$this -> load -> view('templates/header');
		$this -> load -> view('templates/headbar');
		$this -> load -> view('admin/alert_piva_pubblicate',$data);
		$this -> load -> view('templates/footer');
        
		
}

	public function alert_piva_banca_dati() {
	
	if(ENVIRONMENT == 'development')   
		$this->output->enable_profiler(TRUE);
	$this -> simpleuserauth -> require_login();
	
	 $data['items'] = $this -> admin_model -> find_partite_iva_doppie();
	
			
		$this -> load -> view('templates/header');
		$this -> load -> view('templates/headbar');
		$this -> load -> view('admin/alert_piva_banca_dati',$data);
		$this -> load -> view('templates/footer');
        
		
}

public function nascondi_imprese($id=null) {
if(ENVIRONMENT == 'development')   
		$this->output->enable_profiler(TRUE);
	    $this -> simpleuserauth -> require_login();
		
		if ($this -> input -> get('mostra') == ""){
		if ($this -> input -> get('per_page') == "")
		$limit = 25;
		else $limit = $this -> input -> get('per_page');
		}
		else
        $limit = $this -> input -> get('mostra');
        //$limit = 25;
        
        $criteria = set_search_querystring();
		
        $offset = $this -> input -> get('per_page');
		
		
		$data_hidden = array(
		'hidden'=>'1'
		);
		
		$this->db->where('id',$id);
		$this->db->update('dichiaraziones',$data_hidden);
		$data['items'] = $this -> admin_model -> search_nascoste($criteria['params'],$criteria['order_by'], $offset, $limit);
	
		$this -> load -> view('templates/header');
		$this -> load -> view('templates/headbar');
		$this -> load -> view('admin/nascondi_imprese',$data);
		$this -> load -> view('templates/footer');
}

}