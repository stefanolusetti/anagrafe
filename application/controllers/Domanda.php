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

    public function nuova()
    { #azione che mostra form vuota e salva i dati compilati
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
          $data['show_other_shape'] = 'Altro' == $this->input->post('company_shape') ? true : false;
          $data['choosen_shape'] = $this->input->post('company_shape');
          $data['sake_service_type_class'] = 'Yes' == $this->input->post('sake_service_flag') ? '' : 'hidden';
          $data['sake_work_type_class'] = 'Yes' == $this->input->post('sake_work_flag') ? '' : 'hidden';
          $data['sake_supply_type_class'] = 'Yes' == $this->input->post('sake_supply_flag') ? '' : 'hidden';
          $data['sake_fix_type_class'] = 'Yes' == $this->input->post('sake_fix_flag') ? '' : 'hidden';

          $data['antimafias'] = false;

          $offices = $this->input->post('office');
          $data['stmt_wl_class'] = 'Yes' == $this->input->post('stmt_wl') ? '' : 'hidden';

          $data['company_role_more_class'] = $this->input->post('company_role') == 'Altro' ? '' : 'hidden';

          $data['offices'] = $this->input->post('office');
          /*
          else {
            $data['offices'] = array(
              array(
                'office_name' => '',
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
          /*
          $data['submitted'] = true;
          $stato = send_pdf($this->dichiarazione_model->get_items($id));

          $data['mittente'] = $this->input->post('titolare_nome');
          $data['sl_pec'] = $this->input->post('sl_pec');

          $this->session->unset_userdata('start');

          $this->load->view('templates/header', $data);
          $this->load->view('templates/headbar');
          if (!$stato) {
              $data['errore'] = ENVIRONMENT == 'development' ? $this->email->print_debugger() : 'si &egrave; verificato un errore nella spedizione del messaggio';
              $this->parser->parse('domanda/errore_invio', $data);
          } else {
              $this->parser->parse('domanda/sent', $data);
          }
          $this->load->view('templates/footer');
          */
        }
        else {
          //redirect('/domanda/nuova', 'refresh');
          redirect('/domanda/nuovamentequitato', 'refresh');
        }
    }

    public function upload($hash = false)
    {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(true);
        }
        $item = valid_hash($hash);
        if ($item) {
            if ($this->input->post()) {
                $config['allowed_types'] = 'p7m';
                $config['upload_path'] = './uploads/';
                $config['overwrite'] = true;
                $config['file_name'] = $item['id'].'_'.get_year($item['data_firma']).'.p7m';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('userfile')) {
                    $dati = $this->protocollo_model->get_items($hash);
                    $protocollo_config = $this->protocollo_model->get_protocollo_items();
                    $object = ws_crea_anagrafica($dati, $protocollo_config, $config['file_name']);

                    $codice = $object->Stato[0]->Codice;
                    $item['messaggio'] = $object->Stato[0]->Messaggio;

                    if ($codice == '0') {
                        $this->db
                            ->where('id', $item['id'])
// Da attivare la prima o la seconda opzione, a seconda se si voglia pubblicare subito una azienda o meno
//                            ->update('dichiaraziones', array('uploaded' => 1, 'pubblicato' => 1,'uploaded_at' => date("Y-m-d H:i:s")));
                            ->update('dichiaraziones', array('uploaded' => 1, 'pubblicato' => 0, 'uploaded_at' => date('Y-m-d H:i:s')));

                        $this->load->view('templates/header');
                        $this->load->view('templates/headbar');
                        $this->parser->parse('domanda/uploaded', $item);
                        $this->load->view('templates/footer');
                    } else {
                        $this->db
                            ->where('id', $item['id'])
                            ->update('dichiaraziones', array('uploaded' => 2, 'uploaded_at' => date('Y-m-d H:i:s')));
                        $this->load->view('templates/header');
                        $this->load->view('templates/headbar');
                        $this->parser->parse('domanda/uploaded', $item);
                        $this->load->view('templates/footer');
                    }
                } else {
                    $item['error'] = $this->upload->display_errors();
                    $this->load->view('templates/header');
                    $this->load->view('templates/headbar');
                    $this->parser->parse('domanda/upload', $item);
                    $this->load->view('templates/footer');
                }
            } else {
                $item['error'] = '';
                $this->load->view('templates/header');
                $this->load->view('templates/headbar');
                $this->parser->parse('domanda/upload', $item);
                $this->load->view('templates/footer');
            }
        } else {
            $this->load->view('templates/header');
            $this->load->view('templates/headbar');
            $this->load->view('domanda/errore');
            $this->load->view('templates/footer');
        }
    }
/*
███████  ██████  ██████  ███    ███      ██████  █████  ██      ██      ██████   █████   ██████ ██   ██ ███████
██      ██    ██ ██   ██ ████  ████     ██      ██   ██ ██      ██      ██   ██ ██   ██ ██      ██  ██  ██
█████   ██    ██ ██████  ██ ████ ██     ██      ███████ ██      ██      ██████  ███████ ██      █████   ███████
██      ██    ██ ██   ██ ██  ██  ██     ██      ██   ██ ██      ██      ██   ██ ██   ██ ██      ██  ██       ██
██       ██████  ██   ██ ██      ██      ██████ ██   ██ ███████ ███████ ██████  ██   ██  ██████ ██   ██ ███████
*/
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

    public function _company_type_more(){
      $shape = $this->input->post('company_shape');
      if ('Altro' == $shape && empty($this->input->post('company_type_more'))){
        $this->form_validation->set_message('_company_type_more', 'Se indicato <em>Altro</em>, inserire il valore.');
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
