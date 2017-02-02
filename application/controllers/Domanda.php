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
      $this -> nuova();
      //$this -> load -> view('templates/header');
      //$this -> load -> view('templates/headbar');
      //$this -> load -> view('domanda/temp');
      //$this -> load -> view('templates/footer');
    }

/*
███    ██ ██    ██  ██████  ██    ██  █████
████   ██ ██    ██ ██    ██ ██    ██ ██   ██
██ ██  ██ ██    ██ ██    ██ ██    ██ ███████
██  ██ ██ ██    ██ ██    ██  ██  ██  ██   ██
██   ████  ██████   ██████    ████   ██   ██
*/
    public function nuova()
    { #azione che mostra form vuota e salva i dati
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(true);
        }

        $data['title'] = 'Iscrizione Anagrafe Antimafia degli Esecutori';

        #le regole di validazione sono contenute nel file config/form_validation.php

        $sessione = $this->session->all_userdata();

        if (($this->form_validation->run('domanda') == false)) {
          $errors = $this->form_validation->error_array();
          $this->session->set_userdata('start', '1');

          // Hidden conditional things.
          $data['show_other_shape'] = '0' == $this->input->post('forma_giuridica_id') ? true : false;
          $data['choosen_shape'] = $this->input->post('forma_giuridica_id');
          $data['interesse_servizi_tipo_class'] = 'Yes' == $this->input->post('interesse_servizi_flag') ? '' : 'hidden';
          $data['interesse_lavori_tipo_class'] = 'Yes' == $this->input->post('interesse_lavori_flag') ? '' : 'hidden';
          $data['interesse_forniture_tipo_class'] = 'Yes' == $this->input->post('interesse_forniture_flag') ? '' : 'hidden';
          $data['interesse_interventi_tipo_class'] = 'Yes' == $this->input->post('interesse_interventi_flag') ? '' : 'hidden';

          $data['stmt_wl_si_checked'] = 'Yes' == $this->input->post('stmt_wl') ? ' checked="checked" ' : '';
          $data['stmt_wl_no_checked'] = 'No' == $this->input->post('stmt_wl') ? ' checked="checked" ' : '';

          $data['antimafias'] = false;
          $data['istanza_data_default'] = $this->input->post('istanza_data') ? $this->input->post('istanza_data') : date('d/m/Y');

          $offices = $this->input->post('office');
          $data['stmt_wl_class'] = 'Yes' == $this->input->post('stmt_wl') ? '' : 'hidden';

          $data['titolare_rappresentanza_more_class'] = $this->input->post('titolare_rappresentanza') == 'Altro' ? '' : 'hidden';

          $data['offices'] = $this->input->post('office');
          /*
          else {
            $data['offices'] = array(
              array(
                'office_titolare_nome' => '',
                'office_cf' => '',
                'office_piva' => '',
              )
            );
          }
          */
          $data['anagrafiche'] = $this->input->post('anagrafica');


          $data['submitted'] = false;
          $this->load->view('templates/header', $data);
          $this->load->view('templates/headbar');
          $this->parser->parse('domanda/new', $data);
          $this->load->view('templates/footer');
        }
        elseif (array_key_exists('start', $sessione)) {
          $temp = $this->dichiarazione_model->save_preview();
          if ($temp) {
            redirect('/domanda/anteprima/' . $temp['hash'] . '/' . $temp['id']);
          }
        }
        else {
          //redirect('/domanda/nuova', 'refresh');
          redirect('/domanda/nuovamentequitato', 'refresh');
        }
    }

/*
 █████  ███    ██ ████████ ███████ ██████  ██████  ██ ███    ███  █████
██   ██ ████   ██    ██    ██      ██   ██ ██   ██ ██ ████  ████ ██   ██
███████ ██ ██  ██    ██    █████   ██████  ██████  ██ ██ ████ ██ ███████
██   ██ ██  ██ ██    ██    ██      ██      ██   ██ ██ ██  ██  ██ ██   ██
██   ██ ██   ████    ██    ███████ ██      ██   ██ ██ ██      ██ ██   ██
*/
  public function anteprima ( $hash, $id ) {
    $data = $this->dichiarazione_model->get_temp_document($id);
    $this->load->view('templates/header', $data);
    $this->load->view('templates/headbar');
    $this->parser->parse('domanda/anteprima', $data);
    $this->load->view('templates/footer');
  }

/*
██    ██ ██████  ███████ ███████ ██████  ████████
██    ██ ██   ██ ██      ██      ██   ██    ██
██    ██ ██████  ███████ █████   ██████     ██
██    ██ ██           ██ ██      ██   ██    ██
 ██████  ██      ███████ ███████ ██   ██    ██
*/
  public function upsert ( $temp_hash = false ) {
    $classes = array();
    $render = false;
    $data = array();
    if ( empty($this->input->post('submit')) ) {
      // no form submission to check/validate.
      if ( !empty($temp_hash) ) {
        // Carico il documento da modificare.
        $data = $this->dichiarazione_model->get_temp_document_by_hash($temp_hash);
      }
      else {
        // Nuovo documento, valori di default.
        $data = array(
          'istanza_data' => date('d/m/Y'),
          'interesse_lavori' => 'no',
          'interesse_forniture' => 'no',
          'interesse_servizi' => 'no',
          'interesse_interventi' => 'no',
        );
      }
      $render = true;
    }
    else {
      // Abbiamo un form, è valido?
      if ( false === $this->form_validation->run('domanda_upsert') ) {
        // Dati errati
        /*
        ██████  ███████ ██████  ██    ██  ██████
        ██   ██ ██      ██   ██ ██    ██ ██
        ██   ██ █████   ██████  ██    ██ ██   ███
        ██   ██ ██      ██   ██ ██    ██ ██    ██
        ██████  ███████ ██████   ██████   ██████
        */
        echo "<h7>FORM NOPE debug@" .__FILE__.":".__LINE__."</h7><pre>";
        var_dump($this->form_validation->error_array());
        echo "</pre>";
        $data = $this->input->post();
        $render = true;
      }
      else {
        /*
        ██████  ███████ ██████  ██    ██  ██████
        ██   ██ ██      ██   ██ ██    ██ ██
        ██   ██ █████   ██████  ██    ██ ██   ███
        ██   ██ ██      ██   ██ ██    ██ ██    ██
        ██████  ███████ ██████   ██████   ██████
        */
        echo "<h7>FORM OK! debug@" .__FILE__.":".__LINE__."</h7>";
        $temp = $this->dichiarazione_model->save_preview();
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

      $classes['interesse_lavori'] = '1' == $data['interesse_lavori'] ? '' : 'hidden';
      $classes['interesse_servizi'] = '1' == $data['interesse_servizi'] ? '' : 'hidden';
      $classes['interesse_forniture'] = '1' == $data['interesse_forniture'] ? '' : 'hidden';
      $classes['interesse_interventi'] = '1' == $data['interesse_interventi'] ? '' : 'hidden';

      $shapes = opzioni_forma_giuridica_id();
      $shapes[0] = 'Altro';

      $this->load->view('templates/header_upsert', array('formdata' => $data, 'classes' => $classes, 'forme_giuridiche' => $shapes));
      $this->load->view('templates/headbar');
      $this->parser->parse('domanda/upsert', array('formdata' => $data));
      $this->load->view('templates/footer');
    }

    /*
    if ( !empty($temp_hash) ) {
      $data = $this->dichiarazione_model->get_temp_document_by_hash($temp_hash);
    }
    $data = $this->dichiarazione_model->get_temp_document($id);
    $data['titolare_nascita_data'] = empty($data['titolare_nascita_data']) ? '' : date('d/m/Y', strtotime($data['titolare_nascita_data']));

    $this->load->library('session');
    $this->session->set_userdata(array('formdata' => $data));

    $this->load->view('templates/header', array('formdata' => $data));
    $this->load->view('templates/headbar');
    $this->parser->parse('domanda/edit', array('formdata' => $data));
    $this->load->view('templates/footer');
    */
  }

  public function conferma_modulo ( $hash, $id ) {
    $real_id = $this->dichiarazione_model->confirm_module( $id, $hash );
    if ( false === $real_id ) {
      $document_data = $this->dichiarazione_model->get_document($real_id);
      $stato = send_welcome_email($real_id);
      if (true === $stato) {
        $data = array(
          'mittente' => $document_data['titolare_nome'] . ' ' . $document_data['titolare_cognome'],
          'sl_pec' => $document_data['impresa_pec']
        );

        $this->session->unset_userdata('start');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/headbar');
        $this->parser->parse('domanda/sent', $data);
      }
      else {
        $data['errore'] = ENVIRONMENT == 'development' ? $this->email->print_debugger() : 'si &egrave; verificato un errore nella spedizione del messaggio';
        $this->parser->parse('domanda/errore_invio', $data);
      }
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

    public function test($id = false){
      $fields = $this->db->list_fields('esecutori');
      /*
      ██████  ███████ ██████  ██    ██  ██████
      ██   ██ ██      ██   ██ ██    ██ ██
      ██   ██ █████   ██████  ██    ██ ██   ███
      ██   ██ ██      ██   ██ ██    ██ ██    ██
      ██████  ███████ ██████   ██████   ██████
      */
      echo "<h7>FIELDS! debug@" .__FILE__.":".__LINE__."</h7><pre>";
      var_dump($fields);
      echo "</pre>";
      /*
      $csv = export_antimafia_components($id);
      echo "<h7>CSV debug@" .__FILE__.":".__LINE__."</h7><pre>";
      var_dump($csv);
      echo "</pre>";

      $pdf = create_pdf($id);
      echo "<h7>PDF debug@" .__FILE__.":".__LINE__."</h7><pre>";
      var_dump($pdf);
      echo "</pre>";

      $codice = create_codice_istanza($id);
      echo "<h7>CODICE debug@" .__FILE__.":".__LINE__."</h7><pre>";
      var_dump($codice);
      echo "</pre>";
      */
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
          if ( send_thanks_mail($item) ) {
            $this->db->where('ID', $item['ID'])->update( 'esecutori',
              array('stato' => 0, 'uploaded' => 1, 'uploaded_at' => date('Y-m-d H:i:s'))
            );
            $this->load->view('templates/header');
            $this->load->view('templates/headbar');
            $this->parser->parse('domanda/uploaded', $item);
            $this->load->view('templates/footer');
          }
          else {
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

  public function interesse_interventi_flag($val) {
    if ( 'Yes' == $this->input->post('interesse_interventi_flag') && 'Yes' != $this->input->post('interesse_interventi_checkbox')) {
      $this->form_validation->set_message('interesse_interventi_flag', 'La dichiarazione è obbligatoria.');
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
    if ( false == $any_selected && ( '1' != $this->input->post('impresa_settore_nessuno') || 'Yes' != $this->input->post('impresa_settore_nessuno')) ) {
      $this->form_validation->set_message('check_settori', 'Indicare almeno un valore.');
      return false;
    }
    return true;
  }

  /**
   * Anagrafiche, usiamo un singolo campo (check_anagrafiche) per mostrare l'errore di validazione.
   **/
  public function check_anagrafiche_upsert ( $val ) {
    $anagrafiche = $this->input->post('anagrafiche_antimafia');
    if ( !empty($anagrafiche) ) {
      foreach ( $anagrafiche AS $anagrafica ) {
        if ( empty($anagrafica['role_id']) ) {
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
        if ( !empty($anagrafica['familiari']) ) {
          foreach ( $anagrafica['familiari'] AS $familiare ) {
            if ( empty($familiare['role_id']) ) {
              $this->form_validation->set_message('check_anagrafiche', 'Ruolo familiare è obbligatorio');
              return false;
            }
            if ( empty($familiare['nome']) ) {
              $this->form_validation->set_message('check_anagrafiche', 'Nome del familiare convivente è obbligatorio');
              return false;
            }
            if ( empty($familiare['cognome']) ) {
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
