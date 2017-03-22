<?php

class Domanda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dichiarazione_model');
        $this->load->model('comuni_model');
        $this->load->model('admin_model');
        $this->load->model('protocollo_model');

        $this->load->helper('pdf');
        $this->load->helper('url');
        $this->load->helper('fastform');
        $this->load->helper('form');
        $this->load->helper('captcha');
        $this->load->helper('application');
        $this->load->helper('protocollo');

        $this->load->library('email');
        $this->load->library('session');
        $this->load->library('basic401auth');
        $this->load->library('form_validation');
        $this->load->library('parser');

        $this->load->helper('form');
        $this->load->helper('captcha');
        $this->load->helper('pdf');
    }

    public function check_piva() {
      //add the header here
      $piva = $this->input->post('partita_iva');
      header('Content-Type: application/json');
      /*
      $is_valid = $this->dichiarazione_model->_partita_iva_exists($piva);
      if ( ! $is_valid ) {
        $response = 'La partita IVA inserita è già presente. Non è possibile presentare due richieste per la stessa impresa.';
      }

      else  */
      //if ( 11 != strlen($piva) ) {
      if( empty($piva) ) {
        $response = 'La partita IVA è obbligatoria';
      }
      else {
        $response = true;
      }
      echo json_encode( $response );
    }

    public function index() {
      $this->upsert();
      //$this -> load -> view('templates/header');
      //$this -> load -> view('templates/headbar');
      //$this -> load -> view('domanda/temp');
      //$this -> load -> view('templates/footer');
    }

/*
 █████  ███    ██ ████████ ███████ ██████  ██████  ██ ███    ███  █████
██   ██ ████   ██    ██    ██      ██   ██ ██   ██ ██ ████  ████ ██   ██
███████ ██ ██  ██    ██    █████   ██████  ██████  ██ ██ ████ ██ ███████
██   ██ ██  ██ ██    ██    ██      ██      ██   ██ ██ ██  ██  ██ ██   ██
██   ██ ██   ████    ██    ███████ ██      ██   ██ ██ ██      ██ ██   ██
*/
  public function anteprima ( $hash, $id ) {
    if (ENVIRONMENT == 'development') {
      $this->output->enable_profiler(true);
    }
    $data = $this->dichiarazione_model->get_tmp_document($id);
    $this->load->view('templates/header', $data);
    $this->load->view('templates/headbar');
    $this->parser->parse('domanda/anteprima', $data);
    $this->load->view('templates/footer');
  }

/*
 ██████  ██████  ███    ██ ███████ ██ ██████  ███    ███
██      ██    ██ ████   ██ ██      ██ ██   ██ ████  ████
██      ██    ██ ██ ██  ██ █████   ██ ██████  ██ ████ ██
██      ██    ██ ██  ██ ██ ██      ██ ██   ██ ██  ██  ██
 ██████  ██████  ██   ████ ██      ██ ██   ██ ██      ██
*/
  public function confirm ( $hash, $id ) {
    try {
      $tmp_doc = $this->dichiarazione_model->get_tmp_document_by_hash($hash);
      if ( $tmp_doc ) {
        $confirm = $this->dichiarazione_model->confirm_doc($tmp_doc);
        if ( false !== $confirm ) {
          $this->dichiarazione_model->markEmailSent($confirm);
          $doc = $this->dichiarazione_model->get_document($confirm);
          $sent = send_welcome_email($doc['ID']);

          if ( !$sent ) {
            $mail_error = $this->email->print_debugger();
            send_error_mail(
              'errore send_thanks_mail',
              array(
                'the error' => $mail_error,
                'id (tmp)' => $doc['id'],
                'impresa_pec' => $doc['impresa_pec'],
                'partita_iva' => $doc['partita_iva'],
                'ragione_sociale' => $doc['ragione_sociale'],
                'titolare_cognome' => $doc['titolare_cognome'],
                'titolare_nome' => $doc['titolare_nome']
              )
            );
          }

          $this->load->view('templates/header', array());
          $this->load->view('templates/headbar');
          $this->parser->parse(
            'domanda/sent',
            array(
              'mittente' => $doc['titolare_nome'] . ' ' . $doc['titolare_cognome'],
              'email' => $doc['impresa_pec']
            )
          );
          $this->load->view('templates/footer');
        }
        else {
          $this->load->view('templates/header', array());
          $this->load->view('templates/headbar');
          $this->parser->parse( 'domanda/confirm_sent_error', array() );
          $this->load->view('templates/footer');
        }
      }
    }
    catch (Exception $e) {
      $this->load->view('templates/header', array());
      $this->load->view('templates/headbar');
      $this->parser->parse( 'domanda/confirm_exception', array() );
      $this->load->view('templates/footer');
    }
  }

/*
██    ██ ██████  ███████ ███████ ██████  ████████
██    ██ ██   ██ ██      ██      ██   ██    ██
██    ██ ██████  ███████ █████   ██████     ██
██    ██ ██           ██ ██      ██   ██    ██
 ██████  ██      ███████ ███████ ██   ██    ██
*/
  public function upsert ( $temp_hash = false ) {
    // Weekdays.
    if ( 0 == date('w') || 6 == date('w') ) {
      $this->load->view('templates/header', array());
      $this->load->view('templates/headbar');
      $this->parser->parse( 'domanda/weekend', array() );
      $this->load->view('templates/footer');
      return;
    }

    if (ENVIRONMENT == 'development') {
      $this->output->enable_profiler(true);
    }
    $classes = array();
    $render = false;
    $data = array(
      'istanza_data' => date('d/m/Y'),
      'interesse_lavori' => 'no',
      'interesse_forniture' => 'no',
      'interesse_servizi' => 'no',
      'interesse_interventi' => 'no',
      'istanza_id' => 0,
      'has_partecipazioni' => 0
    );
    if ( empty($this->input->post('submit')) ) {
      // no form submission to check/validate.
      if ( !empty($temp_hash) ) {
        // Carico il documento da modificare.
        $data = $this->dichiarazione_model->get_tmp_document_by_hash($temp_hash);
        $data['istanza_id'] = $data['ID'];
      }
      else {
        // Nuovo documento, valori di default.
        $data = array(
          'istanza_data' => date('d/m/Y'),
          'interesse_lavori' => 'no',
          'interesse_forniture' => 'no',
          'interesse_servizi' => 'no',
          'interesse_interventi' => 'no',
          'has_soas' => 'no',
          'istanza_id' => 0,
          'has_partecipazioni' => 0
        );
        $data['soas'] = array();
      }
      $render = true;
    }
    else {
      // Abbiamo un form, è valido?
      if ( false === $this->form_validation->run('domanda_upsert') ) {
        // Dati errati
        $data = $this->input->post();
        $data['we_have_some_errors'] = true;
        $render = true;
      }
      else {
        $istanza_id = $this->input->post('istanza_id');
        $temp = $this->dichiarazione_model->save_preview($istanza_id);
        if ($temp) {
          redirect('/domanda/anteprima/' . $temp['hash'] . '/' . $temp['id']);
        }
      }
    }

    if ( true == $render ) {
      $classes['titolare_rappresentanza_more'] = isset($data['titolare_rappresentanza']) ? 'Altro' == $data['titolare_rappresentanza'] ? '' : 'hidden' : 'hidden';

      if ( isset($data['forma_giuridica_id']) ) {
        $classes['forma_giuridica_more'] = 0 == $data['forma_giuridica_id'] ? '' : 'hidden';
      }
      else {
        $classes['forma_giuridica_more'] = 'hidden';
      }


      // Elementi data
      polish_date($data);

      $classes['interesse_lavori'] = is_checked($data, 'interesse_lavori') ? '' : 'hidden';
      $classes['interesse_servizi'] = '1' == is_checked($data, 'interesse_servizi') ? '' : 'hidden';
      $classes['interesse_forniture'] = '1' == is_checked($data, 'interesse_forniture') ? '' : 'hidden';
      $classes['interesse_interventi'] = '1' == is_checked($data, 'interesse_interventi') ? '' : 'hidden';
      $classes['soas_list'] = '1' == is_checked($data, 'has_soas') ? '' : 'hidden';

      $data['soas_list'] = $this->dichiarazione_model->get_soas();

      if ( !empty( $data['soas'] ) ) {
        foreach ( $data['soas'] AS $sidx => $sValue ) {
          $data['soa[' . $sValue['id'] . ']'] = 1;
          $data['soas_value_' . $sValue['id']] = $sValue['valore'];
          unset($data['soas'][$sidx]);
        }
      }

      $data['soas_values'] = array(
        'I fino a euro 258.000' => 'I fino a euro 258.000',
        'II fino a euro 516.000' =>'II fino a euro 516.000',
        'III fino a euro 1.033.000' => 'III fino a euro 1.033.000',
        'III bis fino a euro 1.500.000' => 'III bis fino a euro 1.500.000',
        'IV fino a euro 2.582.000' => 'IV fino a euro 2.582.000',
        'IV bis fino a euro 3.500.000' => 'IV bis fino a euro 3.500.000',
        'V fino a euro 5.165.000' => 'V fino a euro 5.165.000',
        'VI fino a euro 10.329.000' => 'VI fino a euro 10.329.000',
        'VII fino a euro 15.494.000' => 'VII fino a euro 15.494.000',
        'VIII oltre euro 15.494.000' => 'VIII oltre euro 15.494.000'
      );

      $shapes = opzioni_forma_giuridica_id();
      $shapes[0] = 'Altro';

      $this->load->view('templates/header_upsert', array('formdata' => $data, 'classes' => $classes, 'forme_giuridiche' => $shapes));
      $this->load->view('templates/headbar');
      $this->parser->parse('domanda/upsert', array('formdata' => $data));
      $this->load->view('templates/footer');
    }
  }

/*
████████ ███████ ███████ ████████ ██    ██████  ███████ ██████  ██    ██  ██████
   ██    ██      ██         ██    ██    ██   ██ ██      ██   ██ ██    ██ ██
   ██    █████   ███████    ██ ████████ ██   ██ █████   ██████  ██    ██ ██   ███
   ██    ██           ██    ██ ██  ██   ██   ██ ██      ██   ██ ██    ██ ██    ██
   ██    ███████ ███████    ██ ██████   ██████  ███████ ██████   ██████   ██████
*/

    public function test($id = false) {
      $cioccia = $this->config->item('email');
      /*
      ██████  ███████ ██████  ██    ██  ██████
      ██   ██ ██      ██   ██ ██    ██ ██
      ██   ██ █████   ██████  ██    ██ ██   ███
      ██   ██ ██      ██   ██ ██    ██ ██    ██
      ██████  ███████ ██████   ██████   ██████
      */
      echo "<h7>CIOCCIA! debug@" .__FILE__.":".__LINE__."</h7><pre>";
      var_dump($cioccia);
      echo "</pre>";
    }

/*
██    ██ ██████  ██       ██████   █████  ██████
██    ██ ██   ██ ██      ██    ██ ██   ██ ██   ██
██    ██ ██████  ██      ██    ██ ███████ ██   ██
██    ██ ██      ██      ██    ██ ██   ██ ██   ██
 ██████  ██      ███████  ██████  ██   ██ ██████
*/
  public function upload($hash = false) {
    if (ENVIRONMENT == 'development') {
      $this->output->enable_profiler(true);
    }
    $item = valid_hash_new($hash);
    if ($item) {
      if ($this->input->post()) {
        $config['allowed_types'] = array('jpg','pdf','tiff','tif','png','jpeg');
        $config['max_size'] = '2700';
        $config['upload_path'] = './uploads/';
        $config['overwrite'] = true;
        $config['file_name'] = 'CI_' . $item['codice_istanza'];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('userfile')) {
          $this->db->where('ID', $item['ID'])->update( 'esecutori',
            array('stato' => 0, 'uploaded' => 1, 'uploaded_at' => date('Y-m-d H:i:s'))
          );
          //$sendmail = send_thanks_mail($item);
          $sendmail = the_test_mail();
          if ( true == $sendmail ) {
            $this->db->where('ID', $item['ID'])->update( 'esecutori',
              array('is_sent_last' => 1, 'is_sent_last_date' => date('Y-m-d H:i:s'))
            );
            $this->load->view('templates/header');
            $this->load->view('templates/headbar');
            $this->parser->parse('domanda/uploaded', $item);
            $this->load->view('templates/footer');
          }
          else {
            $mail_error = $this->email->print_debugger();
            send_error_mail(
              'errore send_thanks_mail',
              array(
                'mail_error' => $mail_error,
                'item' => $item['codice_istanza']
              )
            );
            $this->load->view('templates/header');
            $this->load->view('templates/headbar');
            $this->parser->parse('domanda/uploaded-system-error', $item);
            $this->load->view('templates/footer');
          }
        }
        else {
          $item['error'] = $this->upload->display_errors();
          $this->load->view('templates/header');
          $this->load->view('templates/headbar');
          $this->parser->parse('domanda/upload', $item);
          $this->load->view('templates/footer');
        }
      }
      else {
        $item['error'] = '';
        $this->load->view('templates/header');
        $this->load->view('templates/headbar');
        $this->parser->parse('domanda/upload', $item);
        $this->load->view('templates/footer');
      }
    }
    else {
      $this->load->view('templates/header');
      $this->load->view('templates/headbar');
      $this->load->view('domanda/errore');
      $this->load->view('templates/footer');
    }
  }

  public function help(){
    $this->load->view('templates/header');
    $this->load->view('templates/headbar');
    $this->load->view('domanda/help');
    $this->load->view('templates/footer');
  }



/*
███████  ██████  ██████  ███    ███      ██████  █████  ██      ██      ██████   █████   ██████ ██   ██ ███████
██      ██    ██ ██   ██ ████  ████     ██      ██   ██ ██      ██      ██   ██ ██   ██ ██      ██  ██  ██
█████   ██    ██ ██████  ██ ████ ██     ██      ███████ ██      ██      ██████  ███████ ██      █████   ███████
██      ██    ██ ██   ██ ██  ██  ██     ██      ██   ██ ██      ██      ██   ██ ██   ██ ██      ██  ██       ██
██       ██████  ██   ██ ██      ██      ██████ ██   ██ ███████ ███████ ██████  ██   ██  ██████ ██   ██ ███████
*/
  public function titolare_rappresentanza($val){
    if ( 'Altro' == $val && empty($this->input->post('ruolo_richiedente')) ) {
      $this->form_validation->set_message('titolare_rappresentanza', 'Se indicato Altro, specificare');
      return false;
    }

    if ( is_null($this->input->post('stmt_wl')) ) {
      $this->form_validation->set_message('stmt_wl', 'Indicare.');
      return false;
    }

    return true;
  }

  public function check_wl($val) {
    if ( null == $this->input->post('stmt_wl') ) {
      $this->form_validation->set_message('check_wl', '<label class="errormsg">Indicare un valore</label>');
      return false;
    }
    return true;
  }

  public function interesse_interventi_flag($val) {
    if ( 'Yes' == $this->input->post('interesse_interventi_flag') && 'Yes' != $this->input->post('interesse_interventi_checkbox')) {
      $this->form_validation->set_message('interesse_interventi_flag', 'La dichiarazione è obbligatoria.');
      return false;
    }
    return true;
  }
  public function check_attivita_upsert($val) {
    $attivita = array(
      'interesse_lavori',
      'interesse_servizi',
      'interesse_forniture'
      //'interesse_interventi'
    );
    $any_selected = false;
    foreach ( $attivita AS $att ) {
      if ( '1' == $this->input->post($att) ) {
        $any_selected = true;
      }
    }
    if ( false == $any_selected ) {
      $this->form_validation->set_message('check_attivita_upsert', 'Indicare almeno un valore tra Lavori, Servizi o Forniture.');
      return false;
    }
    return true;
  }
  public function check_attivita($val) {
    $attivita = array(
      'interesse_lavori_flag',
      'interesse_servizi_flag',
      'interesse_forniture_flag',
      'interesse_interventi_flag'
    );
    $any_selected = false;
    foreach ( $attivita AS $att ) {
      if ( 'Yes' == $this->input->post($att) ) {
        $any_selected = true;
      }
    }
    if ( false == $any_selected ) {
      $this->form_validation->set_message('check_attivita', 'Indicare almeno un valore.');
      return false;
    }
    return true;
  }

  public function check_settori($val){
    $settori = array(
      'impresa_settore_trasporto',
      'impresa_settore_rifiuti',
      'impresa_settore_terra',
      'impresa_settore_bitume',
      'impresa_settore_nolo',
      'impresa_settore_ferro',
      'impresa_settore_autotrasporto',
      'impresa_settore_guardiana',
    );
    //impresa_settore_nessuno
    $any_selected = false;
    foreach ($settori AS $settore) {
      if ( '1' == $this->input->post($settore) || 'Yes' == $this->input->post($settore) ) {
        $any_selected = true;
      }
    }
    if ( false == $any_selected && '1' != $this->input->post('impresa_settore_nessuno') ) {
      $this->form_validation->set_message('check_settori', 'Indicare almeno un valore.');
      return false;
    }
    return true;
  }


  public function check_imprese_upsert ( $val ) {
    if ( 0 == $this->input->post('has_partecipazioni') ) {
      return true;
    }

    $imprese = $this->input->post('imprese_partecipate');
    $num_imprese_dichiarate = $this->input->post('numero_partecipazioni');
    $num_imprese = 0;
    if ( !empty($imprese) ) {
      foreach ( $imprese AS $n => $impresa ) {
        $num_imprese++;
        if ( empty($impresa['nome']) ) {
          $this->form_validation->set_message('check_imprese_upsert', 'La Ragione Sociale delle imprese partecipate è obbligatoria.');
          return false;
        }
        if ( empty($impresa['piva']) ) {
          $this->form_validation->set_message('check_imprese_upsert', 'La partita iva delle imprese partecipate è obbligatoria.');
          return false;
        }
        else if ( 11 > strlen($impresa['piva']) || 17 < strlen($impresa['piva']) ) {
          $this->form_validation->set_message('check_imprese_upsert', 'La partita iva deve essere tra 11 e 16 caratteri.');
          return false;
        }

        if ( empty($impresa['cf']) ) {
          $this->form_validation->set_message('check_imprese_upsert', 'Codice Fiscale delle imprese partecipate è obbligatorio.');
          return false;
        }
        else if ( 11 > strlen($impresa['cf']) || 17 < strlen($impresa['cf']) ) {
          $this->form_validation->set_message('check_imprese_upsert', 'Codice Fiscale deve essere tra 11 e 16 caratteri.');
          return false;
        }
      }
    }
    if ( $num_imprese_dichiarate != $num_imprese ) {
      $this->form_validation->set_message(
        'check_imprese_upsert',
        sprintf(
          "Sono state inserite <strong>%s</strong> partecipazioni ma ne sono state dichiarate <strong>%s</strong>",
          $num_imprese,
          $num_imprese_dichiarate
        )
      );
      return false;
    }
    return true;
  }
  /**
   * Anagrafiche, usiamo un singolo campo (check_anagrafiche) per mostrare l'errore di validazione.
   **/
  public function check_anagrafiche_upsert ( $val ) {
    $anagrafiche = $this->input->post('anagrafiche_antimafia');
    $num_anagrafiche_dichiarato = $this->input->post('numero_anagrafiche');
    $num_anagrafiche = 0;
    $num_tot_familiari = 0;
    $num_tot_familiari_dichiarati = 0;
    if ( !empty($anagrafiche) ) {
      foreach ( $anagrafiche AS $ai => $anagrafica ) {
        $num_tot_familiari_dichiarati += $anagrafica['antimafia_numero_familiari'];
        $num_anagrafiche++;
        if ( empty($anagrafica['role_id']) ) {
          $this->form_validation->set_message('check_anagrafiche_upsert', 'Ruolo componente è obbligatorio');
          return false;
        }

        if ( 1 == $anagrafica['is_giuridica'] ) {
          if ( empty($anagrafica['giuridica_ragione_sociale']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Ragione sociale persona giuridica è obbligatorio.');
            return false;
          }
          if ( empty($anagrafica['giuridica_partita_iva']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Partita IVA persona giuridica è obbligatorio.');
            return false;
          }
          if ( empty($anagrafica['giuridica_codice_fiscale']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Codice Fiscale persona giuridica è obbligatorio.');
            return false;
          }
        }
        else {
          if ( empty($anagrafica['antimafia_nome']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Nome componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_cognome']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Cognome componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_data_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Data di nascita componente è obbligatorio');
            return false;
          }
          else {
            $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
            if (preg_match($format, $anagrafica['antimafia_data_nascita']) == false) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Data di nascita componente deve contenere una data nel formato gg/mm/yyyy');
              return false;
            }
          }
          if ( empty($anagrafica['antimafia_comune_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Comune di nascita  componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_provincia_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Provincia di nascita componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_cf']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Codice Fiscale componente è obbligatorio.');
            return false;
          }
          else if ( 11 > strlen($anagrafica['antimafia_cf']) || 17 < strlen($anagrafica['antimafia_cf']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Codice Fiscale deve essere tra 11 e 16 caratteri.');
            return false;
          }
          if ( empty($anagrafica['antimafia_comune_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Comune residenza componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_provincia_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Provincia residenza componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_via_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Via residenza  componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_civico_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche_upsert', 'Civico residenza componente è obbligatorio');
            return false;
          }
        }
/*
███████  █████  ███    ███ ██ ██      ██  █████  ██████  ██
██      ██   ██ ████  ████ ██ ██      ██ ██   ██ ██   ██ ██
█████   ███████ ██ ████ ██ ██ ██      ██ ███████ ██████  ██
██      ██   ██ ██  ██  ██ ██ ██      ██ ██   ██ ██   ██ ██
██      ██   ██ ██      ██ ██ ███████ ██ ██   ██ ██   ██ ██
*/
        $num_familiari = 0;
        $num_familiari_dichiarati = $anagrafica['antimafia_numero_familiari'];
        if ( !empty($anagrafica['familiari']) ) {
          foreach ( $anagrafica['familiari'] AS $familiare ) {
            $num_tot_familiari++;
            $num_familiari++;
            if ( empty($familiare['role_id']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Ruolo familiare è obbligatorio');
              return false;
            }
            if ( empty($familiare['nome']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Nome del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['cognome']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Cognome del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['data_nascita']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Data di nascita del familiare convivente è obbligatorio');
              return false;
            }
            else {
              $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
              if (preg_match($format, $familiare['data_nascita']) == false) {
                $this->form_validation->set_message('check_anagrafiche_upsert', 'Data di nascita familiare convivente deve contenere una data nel formato gg/mm/yyyy');
                return false;
              }
            }
            if ( empty($familiare['comune']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Comune di nascita del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['provincia_nascita']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Provincia di nascita del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['comune_residenza']) ) {
                $this->form_validation->set_message('check_anagrafiche_upsert', 'Comune di residenza del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['provincia_residenza']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Provincia di residenza del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['via_residenza']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Via di residenza del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['civico_residenza']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Civico di residenza del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['cf']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Codice Fiscale del familiare convivente è obbligatorio');
              return false;
            }
            else if ( 11 > strlen($familiare['cf']) || 17 < strlen($familiare['cf']) ) {
              $this->form_validation->set_message('check_anagrafiche_upsert', 'Codice Fiscale deve essere tra 11 e 16 caratteri.');
              return false;
            }
          }
        }
      }
    }
    else {
      $this->form_validation->set_message('check_anagrafiche_upsert', 'Inserire almeno un\'anagrafica.');
      return false;
    }

    if ( $num_anagrafiche_dichiarato != $num_anagrafiche ) {
      $this->form_validation->set_message(
        'check_anagrafiche_upsert',
        sprintf(
          "Sono stati inseriti <strong>%s</strong> componenti ma ne sono stati dichiarati <strong>%s</strong>",
          $num_anagrafiche,
          $num_anagrafiche_dichiarato
        )
      );
      return false;
    }
    if ( $num_tot_familiari_dichiarati != $num_tot_familiari ) {
      $this->form_validation->set_message(
        'check_anagrafiche_upsert',
        " "
      );
      return false;
    }
    return true;
  }
    /**
     * Anagrafiche, usiamo un singolo campo (check_anagrafiche) per mostrare l'errore di validazione.
     **/
    public function check_anagrafiche ( $val ) {
      $anagrafiche = $this->input->post('anagrafica');
      if ( !empty($anagrafiche) ) {
        foreach ( $anagrafiche AS $anagrafica ) {
          if ( empty($anagrafica['antimafia_role']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Ruolo componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_nome']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Nome componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_cognome']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Cognome componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_data_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Data di nascita componente è obbligatorio');
            return false;
          }
          else {
            $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
            if (preg_match($format, $anagrafica['antimafia_data_nascita']) == false) {
              $this->form_validation->set_message('check_anagrafiche', 'Data di nascita componente deve contenere una data nel formato gg/mm/yyyy');
              return false;
            }
          }
          if ( empty($anagrafica['antimafia_comune_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Comune di nascita  componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_provincia_nascita']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Provincia di nascita componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_cf']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Codice Fiscale componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_comune_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Comune residenza componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_provincia_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Provincia residenza componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_via_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Via residenza  componente è obbligatorio');
            return false;
          }
          if ( empty($anagrafica['antimafia_civico_residenza']) ) {
            $this->form_validation->set_message('check_anagrafiche', 'Civico residenza componente è obbligatorio');
            return false;
          }
          if ( !empty($anagrafica['f']) ) {
            foreach ( $anagrafica['f'] AS $familiare ) {
              if ( empty($familiare['role']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Ruolo familiare è obbligatorio');
                return false;
              }
              if ( empty($familiare['name']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Nome del familiare convivente è obbligatorio');
                return false;
              }
              if ( empty($familiare['titolare_cognome']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Cognome del familiare convivente è obbligatorio');
                return false;
              }
              if ( empty($familiare['data_nascita']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Data di nascita del familiare convivente è obbligatorio');
                return false;
              }
              else {
                $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
                if (preg_match($format, $familiare['data_nascita']) == false) {
                  $this->form_validation->set_message('check_anagrafiche', 'Data di nascita familiare convivente deve contenere una data nel formato gg/mm/yyyy');
                  return false;
                }
              }

              if ( empty($familiare['comune']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Comune di nascita del familiare convivente è obbligatorio');
                return false;
              }
              if ( empty($familiare['provincia']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Provincia di nascita del familiare convivente è obbligatorio');
                return false;
              }
              if ( empty($familiare['cf']) ) {
                $this->form_validation->set_message('check_anagrafiche', 'Codice Fiscale del familiare convivente è obbligatorio');
                return false;
              }
            }
          }
        }
      }
      else {
        $this->form_validation->set_message('check_anagrafiche', 'Inserire almeno un\'anagrafica.');
        return false;
      }
      return true;
    }

    public function _forma_giuridica_id_check($val){
      if ( 1 == (int)$val ) {
        $this->form_validation->set_message('_forma_giuridica_id_check', 'Il campo <em>%s</em> è obbligatorio.');
        return false;
      }
      else if ( 0 == (int)$val && empty($this->input->post('impresa_forma_giuridica_altro')) ) {
        $this->form_validation->set_message('impresa_forma_giuridica_altro', 'Inserire la forma giuridica.');
        return false;
      }
      else {
        return true;
      }
    }

    public function _titolare_rappresentanza ( $val ) {
      if ( 'Altro' == $val || empty($val) ) {
        if ( empty($this->input->post('titolare_rappresentanza_altro')) ) {
          $this->form_validation->set_message('titolare_rappresentanza', 'Indicare il tipo di Rappresentanza.');
          return false;
        }
      }
      return true;
    }

    public function white_list_prefettura($val) {
      $is = $this->input->post('stmt_wl');
      if ( TRUE && 'Yes' == $is && empty($val) ) {
        $this->form_validation->set_message('white_list_prefettura', 'Inserire il campo <em>%s</em>');
        return false;
      }
      return true;
    }

    public function white_list_data($val) {
      $is = $this->input->post('stmt_wl');
      if ( 'Yes' == $is ) {
        $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
        if (preg_match($format, $val) == false) {
          $this->form_validation->set_message('white_list_data', 'Il campo <em>%s</em> deve contenere una data nel formato gg/mm/yyyy');
          return false;
        }
        else {
          return true;
        }
      }
      else {
        return true;
      }
    }

    public function _stmt_eligible($val){
      $__post = $this->input->post();
      if ( isset($__post['interesse_interventi_flag']) && 'Yes' == $__post['interesse_interventi_flag'] ) {
        if ( ! (isset($__post['interesse_interventi_checkbox']) && 'Yes' == $__post['interesse_interventi_checkbox']) ) {
          $this->form_validation->set_message('interesse_interventi_checkbox', 'Campo obbligatorio.');
          return false;
        }
      }
      return true;
    }

    public function _controlla_data($str)
    {
        $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
        if (preg_match($format, $str) == false) {
            $this->form_validation->set_message('_controlla_data', 'Il campo <em>%s</em> deve contenere una data nel formato gg/mm/yyyy');

            return false;
        } else {
          preg_match($format, $str, $dateInfo);
          $check_date = mktime(0, 0, 0, $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
          $check_date_back = date('d/m/Y', $check_date);
          if ( $str != $check_date_back ) {
            $this->form_validation->set_message('_controlla_data', 'Controllare il valore inserito.');
            return false;
          }
          return true;
        }
    }
    public function _controlla_data_opzionale($str)
    {
      if(empty($str)){
        return true;
      }
      $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
      if (preg_match($format, $str) == false) {
          $this->form_validation->set_message('_controlla_data', 'Il campo <em>%s</em> deve contenere una data nel formato gg/mm/yyyy');

          return false;
      } else {
          return true;
      }
    }
    public function _controlla_soa($str)
    {
        $esito = true;
        if ($str == 'Yes') {
            $soas = $this->input->post('soa');
            $esito = false;
            $this->form_validation->set_message('_controlla_soa', 'Deve essere specificata la <em>certificazione SOA</em> di cui si è in possesso');
            if (is_array($soas)) {
                foreach ($soas as $key => $value) {
                    if ($value |= '0') {
                        $esito = true;
                    }
                }
            }
        }

        return $esito;
    }

    public function _sake(){
      $sakes = array('lavori' => 'Lavori', 'servizi' => 'Servizi', 'forniture' => 'Forniture', 'interventi' => 'Interventi di immediata riparazione');

      $__post = $this->input->post();
      $abemus_data = false;
      foreach ($sakes as $sake_type => $sake_label) {
        if(isset($__post['interesse_' . $sake_type . '_flag']) && 'Yes' == $__post['interesse_' . $sake_type . '_flag']) {
          $abemus_data = true;
        }
      }

      if(false == $abemus_data){
        $this->form_validation->set_message('_sake', 'Il campo <em>%s</em> &egrave; vuoto. Deve essere compilato <em>almeno un campo</em>.');
      }
      return true;
    }

    public function _impresa_forma_giuridica_altro(){
      $shape = $this->input->post('forma_giuridica_id');
      if (0 == $shape && empty($this->input->post('impresa_forma_giuridica_altro'))){
        $this->form_validation->set_message('_impresa_forma_giuridica_altro', 'Se indicato <em>Altro</em>, inserire il valore.');
        return false;
      }
      return true;
    }

    public function _controlla_impiegati()
    {
        $addetti_n_dipendenti = $this->input->post('addetti_n_dipendenti');
        $addetti_n_socilav = $this->input->post('addetti_n_socilav');
        $addetti_n_artigiani = $this->input->post('addetti_n_artigiani');
        $this->form_validation->set_message('_controlla_impiegati', 'Il campo <em>%s</em> &egrave; vuoto. Deve essere compilato <em>almeno un campo</em> riguardante il numero di dipendenti');
        if ($addetti_n_dipendenti || $addetti_n_socilav ||  $addetti_n_artigiani) {
            return true;
        } else {
            return false;
        }
    }

    public function comune($comune = false)
    {
        $result = $this->comuni_model->get_items(urldecode($comune));
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    public function _controlla_captcha() //non utilizzata
    {
        $captcha = $this->input->post('captcha');
        $this->form_validation->set_message('_controlla_captcha', 'Non è stata inserita la sequenza di verifica corretta');

        $expiration = time() - 7200; // Two hour limit
        $this->db->query('DELETE FROM captcha WHERE captcha_time < '.$expiration);

        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($captcha, $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0) {
            log_message('debug', 'captcha errato');

            return false;
        } else {
            log_message('debug', 'captcha corretto');

            return true;
        }
    }

    public function _controlla_cassadenominazione()
    {
        $tipo_contratto = $this->input->post('tipo_contratto');
        $pos_cassa_denominazione = $this->input->post('pos_cassa_denominazione');
        if ($tipo_contratto == 'Edilizia') {
            $this->form_validation->set_message('_controlla_cassadenominazione', 'Il campo <em>%s</em> &egrave; vuoto');
            if ($pos_cassa_denominazione) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function _controlla_cassanumero()
    {
        $tipo_contratto = $this->input->post('tipo_contratto');
        $pos_cassa_n = $this->input->post('pos_cassa_n');
        if ($tipo_contratto == 'Edilizia') {
            $this->form_validation->set_message('_controlla_cassanumero', 'Il campo <em>%s</em> &egrave; vuoto');
            if ($pos_cassa_n) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function _controlla_cassasede()
    {
        $tipo_contratto = $this->input->post('tipo_contratto');
        $pos_cassa_sede = $this->input->post('pos_cassa_sede');
        if ($tipo_contratto == 'Edilizia') {
            $this->form_validation->set_message('_controlla_cassasede', 'Il campo <em>%s</em> &egrave; vuoto');
            if ($pos_cassa_sede) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
