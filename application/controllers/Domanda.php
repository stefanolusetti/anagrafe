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

    public function index()
    {
        $this->nuova();
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

        $data['title'] = 'Iscrizione Elenco di Merito';

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
          $id = $this->dichiarazione_model->set_statement();
          if ($id) {
            $document_data = $this->dichiarazione_model->get_document($id);
          }
          $data['submitted'] = true;
          $stato = send_welcome_email($id);

          if (true === $stato) {
            $data['mittente'] = $document_data['titolare_nome'] . ' ' . $document_data['titolare_cognome'];
            $data['sl_pec'] = $document_data['impresa_pec'];

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
        else {
          //redirect('/domanda/nuova', 'refresh');
          redirect('/domanda/nuovamentequitato', 'refresh');
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
        $config['allowed_types'] = 'jpg|pdf|tiff|tif|png|jpeg';
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
    public function _forma_giuridica_id_check($val){
      if (1 == (int)$val) {
        $this->form_validation->set_message('forma_giuridica_id', 'Il campo <em>%s</em> è obbligatorio.');
        return false;
      }
      return true;
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
      $sakes = array('work' => 'Lavori', 'service' => 'Servizi', 'supply' => 'Forniture', 'fix' => 'Interventi di immediata riparazione');
      $els = array('type', 'amount');

      $__post = $this->input->post();
      $abemus_data = false;
      foreach ($sakes as $sake_type => $sake_label) {
        $correct_data = true;

        if(isset($__post['sake_' . $sake_type . '_flag']) && 'Yes' == $__post['sake_' . $sake_type . '_flag']) {
          $abemus_data = true;
          /*
          foreach($els AS $el){
            if (empty($__post['sake_' . $sake_type . '_' . $el])){
              $this->form_validation->set_message('_sake', 'Il campo <em>'.$sake_label.'</em> contiene campi vuoti.');
              $correct_data = false;
              return false;
            }
            if(!is_numeric($__post['sake_' . $sake_type . '_amount'])){
              $this->form_validation->set_message('_sake', 'L\'importo deve essere numerico.');
              $correct_data = false;
              return false;
            }
          }
          */
        }
      }

      if(false == $abemus_data){
        $this->form_validation->set_message('_sake', 'Il campo <em>%s</em> &egrave; vuoto. Deve essere compilato <em>almeno un campo</em>.');
      }
      if(false == $correct_data){
        return false;
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
