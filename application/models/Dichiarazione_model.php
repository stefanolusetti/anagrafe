<?php

/*
* Metodi per salvataggio e recupero dei dati elenco di merito.
*/
class Dichiarazione_model extends CI_Model
{
  public function __construct() {
    $this->load->database();
    $this->load->helper('fastform');
    $this->load->helper('pdf');
  }

  public function get_items($id = false) {
    if (false === $id) {
      $query = $this->db->get('esecutori');
      return $query->result_array();
    }
    return $this->get_item($id);
  }

  public function _partita_iva_exists($piva) {
    $query = $this->db->select('*')->from('esecutori')->where('partita_iva', $piva)->get();
    return 0 == $query->num_rows();
  }

  // Load and return the full document.
  public function get_document($id) {
    try{
      $doc = $this->get_item($id);
      $doc['anagrafiche_antimafia'] = $this->get_doc_antimafia($id);
      foreach ($doc['anagrafiche_antimafia'] AS $i => $antimafia) {
        $doc['anagrafiche_antimafia'][$i]['familiari'] = $this->get_item_familiars($doc['anagrafiche_antimafia'][$i]['anagrafica_id']);
      }
      $doc['imprese_partecipate'] = $this->get_item_imprese_partecipate($id);
      return $doc;
    } catch(Exception $e){
      return false;
    }
  }

  // Load and return the full document.
  public function get_tmp_document($id) {
    try{
      $doc = $this->get_tmp_item($id);
      $doc['anagrafiche_antimafia'] = $this->get_tmp_doc_antimafia($id);
      foreach ($doc['anagrafiche_antimafia'] AS $i => $antimafia) {
        $doc['anagrafiche_antimafia'][$i]['familiari'] = $this->get_tmp_item_familiars($doc['anagrafiche_antimafia'][$i]['anagrafica_id']);
      }
      $doc['imprese_partecipate'] = $this->get_tmp_imprese_partecipate($id);
      return $doc;
    } catch(Exception $e){
      return false;
    }
  }
  // Load and return the full document.
  public function get_tmp_document_by_hash($hash) {
    try{
      $doc = $this->get_tmp_item_by_hash($hash);
      if ( $doc ) {
        $doc['anagrafiche_antimafia'] = $this->get_tmp_doc_antimafia($doc['ID']);
        foreach ($doc['anagrafiche_antimafia'] AS $i => $antimafia) {
          $doc['anagrafiche_antimafia'][$i]['familiari'] = $this->get_tmp_item_familiars($doc['anagrafiche_antimafia'][$i]['anagrafica_id']);
        }
        $doc['imprese_partecipate'] = $this->get_tmp_imprese_partecipate($doc['ID']);
        return $doc;
      }
      else {
        throw new Exception('Hash is not valid.');
      }
    } catch(Exception $e){
      return false;
    }
  }

  public function get_item($id) {
    $qhr = $this->db->get_where('esecutori', array('ID' => $id));
    return $qhr->row_array();
    /*
    $item['ateco_lista'] = build_other_fields($item['id'], 'ateco', 0);
    $item['ateco_lista_r'] = build_other_fields($item['id'], 'ateco', 1);
    $item['soa_lista'] = build_other_fields($item['id'], 'soa', null);
    $item['antimafia_lista'] = build_anagrafica_antimafia($item['id']);
    $item['titolare_data_nascita'] = format_date($item['titolare_data_nascita']);
    $item['id_anno'] = get_year($item['data_firma']);
    $item['data_firma'] = format_date($item['data_firma']);
    $item['id_progressivo'] = $item['id'];
    return $item;
    */
  }

  public function get_tmp_item($id) {
    $qhr = $this->db->get_where('tmp_esecutori', array('ID' => $id));
    return $qhr->row_array();
  }

  public function get_tmp_item_by_hash($hash) {
    $qhr = $this->db->get_where('tmp_esecutori', array('hash' => $hash));
    return $qhr->row_array();
  }


  public function get_items_antimafia($id = false) {
    if (false === $id) {
      $query = $this->db->get('anagrafiche_antimafia');
      return $query->result_array();
    }
    return $this->get_doc_antimafia($id);
  }

  // id docs
  public function get_doc_antimafia($id) {
    $params = array('esecutore_id' => $id);
    $query_antimafia = $this->db->get_where('anagrafiche_antimafia', $params);
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }
  // id docs
  public function get_tmp_doc_antimafia($id) {
    $params = array('esecutore_id' => $id);
    $query_antimafia = $this->db->get_where('tmp_anagrafiche_antimafia', $params);
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id anagrafiche_antimafia
  public function get_item_familiars($id) {
    $params = array('anagrafica_id' => $id);
    $query_antimafia = $this->db->get_where('anagrafiche_familiari', $params);
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }
  // id anagrafiche_antimafia
  public function get_tmp_item_familiars($id) {
    $params = array('anagrafica_id' => $id);
    $query_antimafia = $this->db->get_where('tmp_anagrafiche_familiari', $params);
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id docs
  public function get_item_imprese_partecipate($id) {
    $params = array(
      'esecutore_id' => $id,
    );
    $query_offices = $this->db->get_where('esecutori_imprese_partecipate', $params);
    $item_offices = $query_offices->result_array();
    return $item_offices;
  }
  // id docs
  public function get_tmp_imprese_partecipate($id) {
    $params = array( 'esecutore_id' => $id );
    $query_offices = $this->db->get_where('tmp_esecutori_imprese_partecipate', $params);
    $item_offices = $query_offices->result_array();
    return $item_offices;
  }

/*
██████  ██████  ███████ ██    ██ ██ ███████ ██     ██
██   ██ ██   ██ ██      ██    ██ ██ ██      ██     ██
██████  ██████  █████   ██    ██ ██ █████   ██  █  ██
██      ██   ██ ██       ██  ██  ██ ██      ██ ███ ██
██      ██   ██ ███████   ████   ██ ███████  ███ ███
*/
  public function save_preview($istanza_id = 0) {
    $istanza_id = (int)$istanza_id;
    $this->load->helper('url');
    $this->load->helper('fastform');

    $data = list_tmp_fields();
    $data['partita_iva'] = strtoupper($data['partita_iva']);
    $data['codice_fiscale'] = strtoupper($data['codice_fiscale']);
    $data['impresa_email'] = '';
    $data['rea_num_iscrizione'] = '';

    $data['created_at'] = date('Y-m-d H:i:s');
    $data['titolare_nascita_data'] = parse_date($data['titolare_nascita_data']);
    $data['white_list_data'] = parse_date($data['white_list_data']);
    $data['istanza_data'] = parse_date($data['istanza_data']);
    $data['impresa_data_costituzione'] = parse_date($data['impresa_data_costituzione']);
    if ( 'Altro' == $data['titolare_rappresentanza'] ) {
      $data['titolare_rappresentanza_altro'] = $this->input->post('titolare_rappresentanza_altro');
    }
    else {
      $data['titolare_rappresentanza_altro'] = '';
    }
    $data['numero_partecipazioni'] = $data['numero_partecipazioni'] == '' ? 0 : $data['numero_partecipazioni'];
    $data['numero_anagrafiche'] = $this->input->post('numero_anagrafiche') != null ? $this->input->post('numero_anagrafiche') : 0;
    $data['stmt_wl'] = $data['stmt_wl'] == '1' ? 1 : 0;
    $data['interesse_lavori'] = $this->input->post('interesse_lavori') == '1' ? 1 : 0;
    $data['interesse_servizi'] = $this->input->post('interesse_servizi') == '1' ? 1 : 0;
    $data['interesse_forniture'] = $this->input->post('interesse_forniture') == '1' ? 1 : 0;
    $data['interesse_interventi'] = $this->input->post('interesse_interventi') == '1' ? 1 : 0;
    $data['interesse_interventi_checkbox'] = $this->input->post('interesse_interventi_checkbox') == '1' ? 1 : 0;

    $data['interesse_lavori_importo'] = $data['interesse_lavori_importo'];
    $data['interesse_servizi_importo'] = $data['interesse_servizi_importo'];
    $data['interesse_forniture_importo'] = $data['interesse_forniture_importo'];
    $data['interesse_interventi_importo'] = $data['interesse_interventi_importo'];

    $data['impresa_num_amministratori'] = (int)$data['impresa_num_amministratori'];
    $data['impresa_num_procuratori'] = (int)$data['impresa_num_procuratori'];
    $data['impresa_num_sindaci'] = (int)$data['impresa_num_sindaci'];
    $data['impresa_num_sindaci_supplenti'] = (int)$data['impresa_num_sindaci_supplenti'];

    $data['impresa_settore_nessuno'] = $this->input->post('impresa_settore_nessuno') == '1' ? 1 : 0;
    $data['impresa_settore_trasporto'] = $this->input->post('impresa_settore_trasporto') == '1' ? 1 : 0;
    $data['impresa_settore_rifiuti'] = $this->input->post('impresa_settore_rifiuti') == '1' ? 1 : 0;
    $data['impresa_settore_terra'] = $this->input->post('impresa_settore_terra') == '1' ? 1 : 0;
    $data['impresa_settore_bitume'] = $this->input->post('impresa_settore_bitume') == '1' ? 1 : 0;
    $data['impresa_settore_nolo'] = $this->input->post('impresa_settore_nolo') == '1' ? 1 : 0;
    $data['impresa_settore_ferro'] = $this->input->post('impresa_settore_ferro') == '1' ? 1 : 0;
    $data['impresa_settore_autotrasporto'] = $this->input->post('impresa_settore_autotrasporto') == '1' ? 1 : 0;
    $data['impresa_settore_guardiana'] = $this->input->post('impresa_settore_guardiana') == '1' ? 1 : 0;

    $data['stato'] = 0;
    $data['is_digital'] = 1;
    $data['uploaded'] = 0;
    $data['uploaded_at'] = NULL;
    $data['updated_at'] = NULL;


    $data['parere_dia'] = 0;
    $data['parere_prefettura'] = 0;
    $data['mail_prefettura'] = NULL;
    $data['avvio_proc_sent'] = 0;
    $data['pref_soll_30_sent'] = 0;
    $data['pref_soll_75_sent'] = 0;
    $data['dia_scadenza'] = NULL;
    $data['pref_scadenza_30'] = NULL;
    $data['pref_scadenza_75'] = NULL;
    $data['bdna_protocollo'] = NULL;
    $data['protocollo_struttura'] = NULL;

    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
      $data['ip'] = ':' . $_SERVER['HTTP_X_FORWARDED_FOR'];
    }


    $this->db->trans_start();
    $curr_error_handler = set_error_handler('db_transaction_error_handler');
    try {
      if ( '0' == $istanza_id ) {
        $upsert_exec = $this->db->insert('tmp_esecutori', $data);
        $doc_id = $this->db->insert_id();
      }
      else {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $upsert_exec = $this->db->update(
          'tmp_esecutori',
          $data,
          "ID = " . $istanza_id
        );
        $doc_id = $istanza_id;
      }

      if ( $upsert_exec ) {
        //$doc_id = $this->db->insert_id();
        $confirm_hash = hash('sha256', uniqid());

        $this->db->where('ID', $doc_id)->update(
          'tmp_esecutori',
          array('hash' => $confirm_hash)
        );
        //
        $this->db->delete(
          'tmp_anagrafiche_antimafia',
          array('esecutore_id' => $doc_id)
        );
        $this->db->delete(
          'tmp_anagrafiche_familiari',
          array('esecutore_id' => $doc_id)
        );
        $this->db->delete(
          'tmp_esecutori_imprese_partecipate',
          array('esecutore_id' => $doc_id)
        );

        $anagrafiche_antimafia = $this->input->post('anagrafiche_antimafia');
        $offices = $this->input->post('imprese_partecipate');

        if(!empty($offices) && is_array($offices) && 1 == $data['has_partecipazioni']){
          foreach ($offices as $office) {
            $this->db->insert(
              'tmp_esecutori_imprese_partecipate',
              array(
                'esecutore_id' => $doc_id,
                'nome' => $office['nome'],
                'cf' => strtoupper($office['cf']),
                'piva' => strtoupper($office['piva']),
              )
            );
          }
        }

        if ($anagrafiche_antimafia) {
          foreach ($anagrafiche_antimafia as $aidx => $anagrafica) {
            $data_anagrafiche = array(
              'esecutore_id' => $doc_id,
              'antimafia_nome' => $anagrafica['antimafia_nome'],
              'antimafia_cognome' => $anagrafica['antimafia_cognome'],
              'antimafia_comune_nascita' => $anagrafica['antimafia_comune_nascita'],
              'antimafia_data_nascita' => parse_date($anagrafica['antimafia_data_nascita']),
              'antimafia_comune_residenza' => $anagrafica['antimafia_comune_residenza'],
              'antimafia_provincia_residenza' => $anagrafica['antimafia_provincia_residenza'],
              'antimafia_provincia_nascita' => $anagrafica['antimafia_provincia_nascita'],
              'antimafia_via_residenza' => $anagrafica['antimafia_via_residenza'],
              'antimafia_civico_residenza' => $anagrafica['antimafia_civico_residenza'],
              'antimafia_cf' => strtoupper($anagrafica['antimafia_cf']),
              'antimafia_numero_familiari' => strtoupper($anagrafica['antimafia_numero_familiari']),

              'is_giuridica' => strtoupper($anagrafica['is_giuridica']),
              'giuridica_ragione_sociale' => strtoupper($anagrafica['giuridica_ragione_sociale']),
              'giuridica_partita_iva' => strtoupper($anagrafica['giuridica_partita_iva']),
              'giuridica_codice_fiscale' => strtoupper($anagrafica['giuridica_codice_fiscale']),

              'role_id' => $anagrafica['role_id']
            );

            $this->db->insert('tmp_anagrafiche_antimafia', $data_anagrafiche);
            $id_anagrafica = $this->db->insert_id();

            if (isset ($anagrafica['familiari']) AND !empty($anagrafica['familiari'])){
              foreach ($anagrafica['familiari'] AS $familiar) {
                $dati_familiare = array(
                  'esecutore_id' => $doc_id,
                  'anagrafica_id' => $id_anagrafica,
                  'nome' => $familiar['nome'],
                  'cognome' => $familiar['cognome'],
                  'comune' => $familiar['comune'],
                  'comune_residenza' => $familiar['comune_residenza'],
                  'provincia_residenza' => $familiar['provincia_residenza'],
                  'via_residenza' => $familiar['via_residenza'],
                  'civico_residenza' => $familiar['civico_residenza'],
                  'cap_residenza' => $familiar['cap_residenza'],
                  'provincia_nascita' => isset($familiar['provincia_nascita']) ? $familiar['provincia_nascita'] : '',
                  'data_nascita' => parse_date($familiar['data_nascita']),
                  'cf' => strtoupper($familiar['cf']),
                  'role_id' => $familiar['role_id']
                );

                $this->db->insert('tmp_anagrafiche_familiari', $dati_familiare);
              }
            }
          }
        }
      }
      $this->db->trans_commit();
    }
    catch(Exception $e) {
      $this->db->trans_rollback();
      // @Todo Messaggio?
      echo '<h1>SI È VERIFICATO UN ERRORE</h1>';
      //echo $e->getMessage() . '<br />';
      //echo $e->getLine() . '<br />';
      //echo $e->getFile() . '<br />';
    }
    set_error_handler($curr_error_handler);
    return array('id' => $doc_id, 'hash' => $confirm_hash);
  }

  public function markEmailSent($id) {
    $doc_id = (int)$id;
    $this->db->update(
      'esecutori',
      array('is_sent' => 1, 'is_sent_date' => date('Y-m-d H:i:s')),
      "ID = " . $doc_id
    );
  }

/*
 ██████  ██████  ███    ██ ███████ ██ ██████  ███    ███         ██████   ██████   ██████
██      ██    ██ ████   ██ ██      ██ ██   ██ ████  ████         ██   ██ ██    ██ ██
██      ██    ██ ██ ██  ██ █████   ██ ██████  ██ ████ ██         ██   ██ ██    ██ ██
██      ██    ██ ██  ██ ██ ██      ██ ██   ██ ██  ██  ██         ██   ██ ██    ██ ██
 ██████  ██████  ██   ████ ██      ██ ██   ██ ██      ██ ███████ ██████   ██████   ██████
*/
  public function confirm_doc ($doc) {
    // Copy the tmp_* data into real tables.
    unset($doc['ID']);
    unset($doc['created_at']);
    unset($doc['updated_at']);
    unset($doc['hash']);
    $doc['created_at'] = date('Y-m-d H:i:s');
    $_anagrafiche = $doc['anagrafiche_antimafia'];
    $_imprese_partecipate = $doc['imprese_partecipate'];
    unset($doc['anagrafiche_antimafia']);
    unset($doc['imprese_partecipate']);

    $doc['ip'] = $_SERVER['REMOTE_ADDR'];
    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
      $doc['ip'] = ':' . $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $this->db->trans_start();
    $curr_error_handler = set_error_handler('db_transaction_error_handler');
    try {
      //$this->db->update('tmp_esecutori')
      $this->db->insert('esecutori', $doc);
      $doc_id = $this->db->insert_id();
      $code = create_codice_istanza(array('ID' => $doc_id, 'istanza_data' => $doc['istanza_data']));
      $upload_hash = hash('md5', $doc_id . '-anagrafe');
      $this->db->update(
        'esecutori',
        array('codice_istanza' => $code, 'hash' => $upload_hash),
        "ID = " . $doc_id
      );
      // anagrafiche
      if ( !empty($_anagrafiche) ) {
        foreach ( $_anagrafiche AS $anagrafica ) {
          $anagrafica['esecutore_id'] = $doc_id;
          unset($anagrafica['anagrafica_id']);
          $_familiari = isset( $anagrafica['familiari'] ) ? $anagrafica['familiari'] : array();
          unset($anagrafica['familiari']);
          if ( $this->db->insert('anagrafiche_antimafia', $anagrafica) ) {
            $anagrafica_id = $this->db->insert_id();
            if ( !empty($_familiari) ) {
              foreach ( $_familiari AS $familiare ) {
                unset($familiare['familiare_id']);
                $familiare['anagrafica_id'] = $anagrafica_id;
                $familiare['esecutore_id'] = $doc_id;
                $this->db->insert('anagrafiche_familiari', $familiare);
              }
            }
          }
        }
      }
      // imprese partecipate
      if ( !empty($_imprese_partecipate) ) {
        foreach ( $_imprese_partecipate AS $impresa_partecipata ) {
          unset($impresa_partecipata['eip_id']);
          $impresa_partecipata['esecutore_id'] = $doc_id;
          $this->db->insert('esecutori_imprese_partecipate', $impresa_partecipata);
        }
      }
      $this->db->trans_commit();
      set_error_handler($curr_error_handler);
      return $doc_id;
    }
    catch(Exception $e) {
      $this->db->trans_rollback();
      // @Todo Messaggio?
      echo '<h1>SI È VERIFICATO UN ERRORE</h1>';
      //echo $e->getMessage() . '<br />';
      //echo $e->getLine() . '<br />';
      //echo $e->getFile() . '<br />';
      /*
      ██████  ███████ ██████  ██    ██  ██████
      ██   ██ ██      ██   ██ ██    ██ ██
      ██   ██ █████   ██████  ██    ██ ██   ███
      ██   ██ ██      ██   ██ ██    ██ ██    ██
      ██████  ███████ ██████   ██████   ██████
      */
      echo "<h7>Errore debug@" .__FILE__.":".__LINE__."</h7>";
      echo $e->getMessage() . '<br />';
      echo $e->getLine() . '<br />';
      echo $e->getFile() . '<br />';
      set_error_handler($curr_error_handler);
      return false;
    }
  }


  public function get_roles($type = null) {
    if (is_null($type)) {
      $query = $this->db->get('ruolo_anagrafiche');
    }
    else {
      $query = $this->db->where('type', (int)$type)->get('ruolo_anagrafiche');
    }
    $results = $query->result_array();
    $return = array();
    foreach($results AS $n => $v){
      $return[$v['role_id']] = $v['value'];
    }
    return $return;
  }

  public function get_forma_giuridica_label($id) {
    $query = $this->db->where('forma_giuridica_id', (int)$id)->get('imprese_forme_giuridiche');
    $r = $query->result_array();
    return $r;
  }

  public function get_forme_giuridiche() {
    $query = $this->db->get('imprese_forme_giuridiche');
    $r = $query->result_array();
    return $query->result_array();
  }

  public function get_soas() {
    $query = $this->db->get('soas');
    return $query->result_array();
  }

  public function get_atecos() {
    $query = $this->db->get('atecos');
    return $query->result_array();
  }
}
