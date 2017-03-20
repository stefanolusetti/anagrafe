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
        $this -> load -> helper('admin_mailer');
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

		//$this->db->where('id',$id);
		//$this->db->update('esecutori',$data_hidden);
        $this -> lists();




    }

    public function mailtemplate($idDichiarazione, $type = 0, $idTemplate = 0) {
      $esecutore = $this->admin_model->get_item((int)$idDichiarazione);
      $template = $this->admin_model->get_mail_template($type, $idTemplate);


      if ( isset( $template[0]['body'] ) ) {
        $placeholders = array();
        $values = array();
        foreach ( array_keys($esecutore) AS $col ) {
          $placeholders[] = "%$col%";
          $values[] = $esecutore[$col];
        }
        $body = str_replace($placeholders, $values, $template[0]['body']);
      }
      else {
        $body = '';
      }

      if ( isset( $template[0]['subject'] ) ) {
        $placeholders = array();
        $values = array();
        foreach ( array_keys($esecutore) AS $col ) {
          $placeholders[] = "%$col%";
          $values[] = $esecutore[$col];
        }
        $subject = str_replace($placeholders, $values, $template[0]['subject']);
      }
      else {
        $subject = '';
      }


      $body = '';
      if ( 0 == $type ) {
        $pec_prefettura = $this->admin_model->get_mail_tos($esecutore['sl_prov']);
        if ( isset($pec_prefettura[0]) ) {
          $prefettura = sprintf(
            "Prefettura di %s <%s>",
            $pec_prefettura[0]['nome'],
            $pec_prefettura[0]['pec']
          );
        }
        else {
          $prefettura = '';
        }
      }
      else {
        $prefettura = $esecutore['impresa_pec'];
      }

      //  var_dump(array_keys($template[0]));

      $data = array(
        'subject' => $subject,
        'text' => $body,
        'to' => $prefettura,
        'info' => array(
          'avvio_proc_oe' => $esecutore['avvio_proc_oe'],
          'avvio_proc_dia' => $esecutore['avvio_proc_dia'],
          'dia_scadenza' => date('d/m/Y', strtotime('today + 11 days'))
        )
      );
      header('Content-Type: application/json');
      echo json_encode( $data );
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
     * caricamento-impresa
     *
     * Pagina caricamento imprese da parte di Struttura (Modificato SL)
     */

  /**
   * file-upload
   *
   * Funzione Caricamento imprese da parte di Struttura (Modificato SL)
   * */
  public function caricamento_bdna() {
    $this -> simpleuserauth -> require_admin();
    $data = array();
    if(ENVIRONMENT == 'development') {
      $this->output->enable_profiler(TRUE);
    }
    if ($this->input->post() ) {
      $config['allowed_types'] = '*';
      $config['upload_path'] = './file_bdna/';
      $config['overwrite'] = FALSE;
      //$config['file_name'] = $item['id']. "_" . get_year($item['data_firma']) . ".xlsx";
      $this->load->library('upload', $config);

      if( $this->upload->do_upload('userfile') ) {
        $file = $_FILES['userfile']['tmp_name'];
        $importCsvErrors = array();
        $fh = fopen($file, 'rb');
        // dont like fgetcsv
        while ( $line = fgetss($fh) ) {
          $row = explode('|', $line);
          if ( 'CODICE-FISCALE' != $row[0] ) {
            $codice_fiscale =     $row[0];
            $partita_iva =        $row[1];
            $tipo_societa =       $row[2];
            $ragione_sociale =    $row[3];
            $nazione =            $row[4];
            $provincia =          $row[5];
            $comune =             $row[6];
            $indirizzo =          $row[7];
            $cap =                $row[8];
            $bdna =               $row[9];
            $stato =             $row[10];
            $uploaded_at = date("Y-m-d H:i:s");
            $created_at = date("Y-m-d H:i:s");
            try { // everythings bad will be catched.
              if ( isset($partita_iva) && isset($stato) && isset($ragione_sociale) ) {
                $data_user = array(
                  'created_at' => $created_at,
                  'codice_fiscale' => $codice_fiscale,
                  'partita_iva' => $partita_iva,
                  'ragione_sociale' => $ragione_sociale,
                  'sl_comune' => $comune,
                  'sl_cap' => $cap,
                  'bdna_protocollo' => $bdna
                );
                // Grind values
                $forma_giuridica = $this->admin_model->match_tipo_societa( $tipo_societa );
                if ( !empty( $forma_giuridica ) ) {
                  $data_user['forma_giuridica_id'] = $forma_giuridica[0]['forma_giuridica_id'];
                }
                else {
                  $data_user['forma_giuridica_id'] = NULL;
                }

                $get_provincia = $this->admin_model->match_provincia( $provincia );
                if ( !empty( $get_provincia ) ) {
                  $data_user['sl_prov'] = $get_provincia[0]['sigla'];
                }
                else {
                  $data_user['sl_prov'] = NULL;
                }

                // Sede legale.
                preg_match_all('@(.*)(,)( [0-9]+(\/[a-zA-Z]+)?)@', $indirizzo, $computed_address);
                if ( isset($computed_address[2][0]) && !empty( $computed_address[2][0] ) ) {
                  $data_user['sl_via'] = $computed_address[1][0];
                  $data_user['sl_civico'] = $computed_address[3][0];
                }
                else {
                  $data_user['sl_via'] = $indirizzo;
                  $data_user['sl_civico'] = 'SNC';
                }
                $data_user['stato'] = 0;


                $check_upload = $this->admin_model->check_upload($data_user);
                $array_shift = array_shift($check_upload);
                if ( $array_shift['partita_iva'] == NULL ) {
                  $new_esecutore_id = $this->admin_model->add_data($data_user);
                  if ( $new_esecutore_id ) {
                    $this->admin_model->save_bdna_log(array(
                      'created_at' => date("Y-m-d H:i:s"),
                      'esecutore_id' => $new_esecutore_id,
                      'csv_data' => $line,
                      'event' => 'insert'
                    ));
                  }
                  $data['msg_ok'] = 'Il caricamento è avvenuto con successo';
                }
                else {
                  $this->admin_model->save_bdna_log(array(
                    'created_at' => date("Y-m-d H:i:s"),
                    'esecutore_id' => 0,
                    'csv_data' => $line,
                    'event' => 'duplicate'
                  ));
                  $importCsvErrors[] =  sprintf(
                    'Attenzione! Operatore economico %s con partita IVA %s già presente in banca dati',
                    $array_shift['ragione_sociale'],
                    $array_shift['partita_iva']
                  );
                }
              }
              else {
                $this->admin_model->save_bdna_log(array(
                  'created_at' => date("Y-m-d H:i:s"),
                  'esecutore_id' => 0,
                  'csv_data' => $line,
                  'event' => 'missing fields'
                ));
                $importCsvErrors[] = sprintf(
                  "<p>Attenzione! Verificare il file caricato. Alcuni dati obbligatori risultano non presenti</p><pre>Partita Iva: %s\nRagione Sociale: %s\nStato: %s</pre>",
                  isset($partita_iva) ? $partita_iva : '-',
                  isset($ragione_sociale) ? $ragione_sociale : '-',
                  isset($stato) ? $stato : '-'
                );
              }
            } catch (Exception $e){
              $this->admin_model->save_bdna_log(array(
                'created_at' => date("Y-m-d H:i:s"),
                'esecutore_id' => 0,
                'csv_data' => $line,
                'event' => 'exception'
              ));
              $importCsvErrors[] = $e->getMessage();
            }
          }
        }


        if ( empty($importCsvErrors) ) {
          $data['msg_ok'] = 'Il caricamento è avvenuto con successo.';
        }
        else {
          $data['msg'] = sprintf(
            "<p>Attenzione! Sono presenti alcuni errori:</p><ul><li>%s</li></ul>",
            implode('</li><li>', $importCsvErrors)
          );
        }
      }
      else {
        $data['msg'] = 'File non corretto';
      }
    }
    $this->load->view('templates/header');
    $this->load->view('templates/headbar');
    //$this -> load -> view('domanda/uploaded');
    $this->load->view('admin/caricamento_bdna', $data);
    $this->load->view('templates/footer');
  }

	/**
     * file-upload
     *
     * Funzione Caricamento imprese da parte di Struttura (Modificato SL)
     */
	public function caricamento_esecutore() {
		$this -> simpleuserauth -> require_admin();
		$data = array();

        if(ENVIRONMENT == 'development')
            $this->output->enable_profiler(TRUE);

            if($this -> input -> post())
            {
                $config['allowed_types'] = 'xlsx|xls';
                $config['upload_path'] = './file_esecutori/';
                $config['overwrite'] = FALSE;
                //$config['file_name'] = $item['id']. "_" . get_year($item['data_firma']) . ".xlsx";
                $this->load->library('upload', $config);

				 if($this->upload->do_upload('userfile')) {


					 $file = $_FILES['userfile']['tmp_name'];
						//load the excel library
						$this->load->library('excel');
						//read file from path
						$objPHPExcel = PHPExcel_IOFactory::load($file);

						$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
						$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);


					 for($i=2;$i<=$highestRow;$i++)
          {

			$codice_fiscale=$objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
			$partita_iva =$objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
			$interesse_lavori =$objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
			$interesse_servizi =$objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
			$interesse_forniture =$objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
			$interesse_interventi =$objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
			$forma_giuridica_id=$objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
			$ragione_sociale = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
			$sl_via=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue();
			$sl_civico=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
			$sl_comune=$objWorksheet->getCellByColumnAndRow(10,$i)->getValue();
			$sl_prov=$objWorksheet->getCellByColumnAndRow(11,$i)->getValue();
			$sl_cap=$objWorksheet->getCellByColumnAndRow(12,$i)->getValue();
			$bdna_protocollo=$objWorksheet->getCellByColumnAndRow(13,$i)->getValue();
			$stmt_wl=$objWorksheet->getCellByColumnAndRow(14,$i)->getValue();
			$uploaded_at = date("Y_m_d H:i:s");
			$created_at = date("Y_m_d H:i:s");
			//$dia_scadenza = date(("Y_m_d H:i:s"), strtotime("+10 days"));
			//$pref_scadenza_30 = date(("Y_m_d H:i:s"), strtotime("+30 days"));
			//$pref_scadenza_75 = date(("Y_m_d H:i:s"), strtotime("+75 days"));
			//$data_iscrizione_temp=$objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
			//$data_iscrizione = PHPExcel_Shared_Date::ExcelToPHPObject($data_iscrizione_temp)->format('Y-m-d');
			$stato=$objWorksheet->getCellByColumnAndRow(15,$i)->getValue();
			//$istanza_luogo=NULL;


			$data_user = array('codice_fiscale' => $codice_fiscale,'partita_iva' => $partita_iva,'interesse_lavori' => $interesse_lavori,'interesse_servizi' => $interesse_servizi,'interesse_forniture' => $interesse_forniture,'interesse_interventi' => $interesse_interventi,'forma_giuridica_id'=>$forma_giuridica_id, 'ragione_sociale' => $ragione_sociale,'sl_via' => $sl_via,
			'sl_civico' => $sl_civico,'sl_comune' => $sl_comune,'sl_prov' => $sl_prov,'sl_cap' => $sl_cap,'bdna_protocollo' => $bdna_protocollo,'stmt_wl'=> $stmt_wl,
			'uploaded_at' => $uploaded_at,'created_at' => $created_at,'stato' => $stato
			);

			if (isset($codice_fiscale)) {
			if (isset($partita_iva) && isset($stato) && isset($ragione_sociale)  ) {

			$check_upload = $this->admin_model->check_upload($data_user);
			$array_shift = array_shift($check_upload);
			if ($array_shift['partita_iva'] == NULL){
			$this->admin_model->add_data($data_user);
			$data['msg_ok'] = 'Il caricamento è avvenuto con successo';
			}
			else
			{
			$data['msg'] = 'Attenzione! Operatore economico '.$array_shift['ragione_sociale'].' con partita IVA '.$array_shift['partita_iva'].' già presente in banca dati';
			}

			}
			else
			{
			$data['msg'] = 'Attenzione! Verificare il file caricato. Alcuni dati obbligatori risultano non presenti';
			}

		  }
		  }


				 }


			}
					        $this -> load -> view('templates/header');
							$this -> load -> view('templates/headbar');
							//$this -> load -> view('domanda/uploaded');
							$this -> load -> view('admin/caricamento_esecutore', $data);
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

		$search_params = $this->input->get(NULL, TRUE);

		/*
		if ($this -> input -> get('mostra') == ""){
		if ($this -> input -> get('per_page') == "")
		$limit = 25;


		else $limit = $this -> input -> get('per_page');
		}
		else
        $limit = $this -> input -> get('mostra');

		*/
		if ($this -> input -> get('mostra') == "")
		$limit=25;
		else
		$limit = $this -> input -> get('mostra');

        $criteria = set_search_querystring_esecutori();

        $offset = $this -> input -> get('per_page');

        $data = $this -> admin_model -> search_esecutori($criteria['params'],$criteria['order_by'], $offset, $limit);

            if ( isset( $_POST['irm-to'] ) ) {
              // non usiamo $this->input->post('irm-to') perchè fa casino con l'email tra <>
              $mailToSend = array(
                'to' => $_POST['irm-to'],
                'from' => 'ninzo',
                'subject' => $this->input->post('irm-subject'),
                'text' => $this->input->post('irm-text'),
                'attachments' => $_FILES['irm_attachments']
              );
              $invio = sendMailRequest($mailToSend);
              if ( true == $invio ) {
                $this->admin_model->impostaAvvioProcDia(
                  (int)$this->input->post('irm-esid'),
                  date('Y-m-d 00:00:42', strtotime('today + 11 days'))
                );
                $data['actionMessage'] = array(
                  'type' => 'irm success',
                  'msg' => 'Email inviata correttamente'
                );
              }
              else {
                $data['actionMessage'] = array(
                  'type' => 'irm error',
                  'msg' => 'Si è verificato un errore nell\'invio della mail. Controllare i dati inseriti e riprovare.'
                );
              }
            }

            if ( isset( $_POST['psm-to'] ) ) {
              $esecutore = $this->admin_model->get_item((int)$this->input->post('psm-esid'));
              // non usiamo $this->input->post('psm-to') perchè fa casino con l'email tra <>
              $mailToSend = array(
                'to' => $_POST['psm-to'],
                'from' => 'ninzo',
                'subject' => $this->input->post('psm-subject'),
                'text' => $this->input->post('psm-text'),
                'attachments' => $_FILES['psm_attachments'],
                'esecutore' => $esecutore
              );
              $invio = sendMailRequest($mailToSend, 2);
              if ( true == $invio ) {
                $this->admin_model->impostaAvvioProcOE(
                  (int)$this->input->post('psm-esid'),
                  date('Y-m-d 00:00:42')
                );
                $data['actionMessage'] = array(
                  'type' => 'psm success',
                  'msg' => 'Email inviata correttamente'
                );
              }
              else {
                $data['actionMessage'] = array(
                  'type' => 'psm error',
                  'msg' => 'Si è verificato un errore nell\'invio della mail. Controllare i dati inseriti e riprovare.'
                );
              }
            }

            $data['irm_templates'] = array();
            $templates = $this->admin_model->get_mail_templates(0);
            // Addittional things?
            foreach($templates AS $t) {
              $data['irm_templates'][$t['id']] = $t['name'];
            }

            $data['psm_templates'] = array();
            $templates = $this->admin_model->get_mail_templates(1);
            // Addittional things?
            foreach($templates AS $t) {
              $data['psm_templates'][$t['id']] = $t['name'];
            }

            $tos = array();
            $pec_list = $this->admin_model->get_mail_tos();
            foreach ( $pec_list AS $pec ) {
              $tos[] = sprintf(
                "Prefettura di %s <%s>",
                $pec['nome'],
                $pec['pec']
              );
            }

            $tos[] = 'DIA <dia@example.com>';
            $tos[] = 'GICERIC <giceric@example.com>';

            $data['pecs'] = $tos;


            $config['per_page'] = $limit;
            $config['uri_segment'] = 3;
            $config['page_query_string'] = true;
            $config['base_url'] = site_url('/admin/lists/?'.htmlentities(http_build_query($criteria['qs']).'&mostra='.$limit));
            $config['total_rows'] = $data['count'];
            $this->pagination->initialize($config);
            unset($data['statements']['rowcount']);
            $this->load->view('elenco/header');
            $this->load->view('templates/headbar');
            $this->load->view('admin/mailer-js');
            $this->load->view('admin/index', $data);
            $this->load->view('templates/footer');
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

        $pdf = create_pdf($item['ID']);
        $filename = $item['codice_istanza'];
        $this -> output -> set_content_type('application/pdf');
        $this -> output -> set_header('Content-Disposition: inline; filename="' . $filename . '"');
        $this -> output -> set_output(file_get_contents($pdf['path']));
		unlink($pdf['path']);
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
	* Costruisce la finestra di dialogo
	*/

	public function costruisci_finestra() {

	$campo = $this->input->post('note');
		$CI =& get_instance();
	if($campo)
		 foreach ($campo as $key => $value) {
		$query = $this->db->get_where('esecutori', array('id' => $key));
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
        $actions = array('protocollo_struttura','fascicolo_struttura','stato','uploaded','is_digital','protocollato','stmt_wl','iscritti_at','iscritti_prov_at','iscritti_scadenza','iscritti_prov_scadenza','dia_scadenza');
        foreach ($actions as $action) {
            $campo = $this->input->post($action);
            if($campo)
            {


                foreach ($campo as $key => $value)
                {
				if (($action == 'iscritti_at') || ($action == 'iscritti_prov_at') || ($action == 'iscritti_scadenza') || ($action == 'iscritti_prov_scadenza') || ($action == 'dia_scadenza')  ){
				$value_db = parse_date($value);
				}
				else
				$value_db = $value;

                    $this -> db
                            ->where('ID', $key)
                            ->update('esecutori', array($action => $value_db));


				if ($action == "stato" && $value == 1 ) {
				$this -> db
                            ->where('ID', $key)
                            ->update('esecutori', array('iscritti_at' => date("Y-m-d H:i:s")));

				}


				if ($action == "stato" && $value == 2 ) {
				$this -> db
                            ->where('ID', $key)
                            ->update('esecutori', array('iscritti_prov_at' => date("Y-m-d H:i:s")));

				}

				if ($action == 'protocollo_struttura' ||  $action == 'fascicolo_struttura' ) {
				$this -> db
                            ->where('ID', $key)
                            ->update('esecutori', array($action => $value_db,'protocollato'=> 1 ));

				}





                    $records = $this->db->get_where('esecutori', array('ID' => $key), 1, 0);
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
	*Salvataggio protocollo
	*/

	public function update_protocollo () {

	$actions = array('protocollo_struttura','fascicolo_struttura');
		foreach ($actions as $action) {
			$campo = $this->input->post($action);
				if($campo)
					{


                foreach ($campo as $key => $value)
                {
					$value_db = $value;
							$this -> db
                            ->where('ID', $key)
                            ->update('esecutori', array($action => $value_db,'protocollato'=> 1 ));





			   }

				  }
		}
	}



	public function sblocca() {
	 $this -> simpleuserauth -> require_login();
	 $actions = array('stato','parere_dia','parere_prefettura','avvio_proc_sent','dia_scadenza','pref_scadenza_30','pref_scadenza_75');
        foreach ($actions as $action) {
            $campo = $this->input->post($action);
            if($campo)
            {
                foreach ($campo as $key => $value)
                {

				 $query = $this->db->get_where('esecutori', array('key' => $key));
				 $item =  $query->row_array();
				 //echo $this->output->set_output($item['antimafia']);

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
