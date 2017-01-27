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
      $query = $this->db->get('docs');
      return $query->result_array();
    }
    return $this->get_item($id);
  }

  // Load and return the full document.
  public function get_document($id) {
    try{
      $doc = $this->get_item($id);
      $doc['anagrafiche_antimafia'] = $this->get_doc_antimafia($id);
      foreach ($doc['anagrafiche_antimafia'] AS $i => $antimafia) {
        $doc['anagrafiche_antimafia'][$i]['familiari'] = $this->get_item_familiars($doc['anagrafiche_antimafia'][$i]['anagrafica_id']);
      }
      $doc['offices'] = $this->get_item_offices($id);
      return $doc;
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

  public function get_items_antimafia($id = false) {
    if (false === $id) {
      $query = $this->db->get('anagrafiche_antimafia');
      return $query->result_array();
    }
    return $this->get_doc_antimafia($id);
  }

  // id docs
  public function get_doc_antimafia($id) {
    $query_antimafia = $this->db->get_where('anagrafiche_antimafia', array('esecutore_id' => $id));
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id anagrafiche_antimafia
  public function get_item_familiars($id) {
    $query_antimafia = $this->db->get_where('anagrafiche_familiari', array('anagrafica_id' => $id));
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id docs
  public function get_item_offices($id) {
    $query_offices = $this->db->get_where('esecutori_imprese_partecipate', array('esecutore_id' => $id));
    $item_offices = $query_offices->result_array();
    return $item_offices;
  }

  public function set_statement() {
    $this->load->helper('url');
    $this->load->helper('fastform');


    $data = list_fields();

    $data['impresa_email'] = '';
    $data['rea_num_iscrizione'] = '';

    $data['created_at'] = date('Y-m-d H:i:s');
    $data['titolare_nascita_data'] = parse_date($data['titolare_nascita_data']);
    $data['white_list_data'] = parse_date($data['white_list_data']);
    $data['istanza_data'] = parse_date($data['istanza_data']);
    $data['impresa_data_costituzione'] = parse_date($data['impresa_data_costituzione']);
    $data['titolare_rappresentanza_altro'] = $this->input->post('ruolo_richiedente');

    $data['stmt_wl'] = $data['stmt_wl'] == 'Yes' ? 1 : 0;
    $data['interesse_lavori'] = $this->input->post('interesse_lavori_flag') == 'Yes' ? 1 : 0;
    $data['interesse_servizi'] = $this->input->post('interesse_servizi_flag') == 'Yes' ? 1 : 0;
    $data['interesse_forniture'] = $this->input->post('interesse_forniture_flag') == 'Yes' ? 1 : 0;
    $data['interesse_interventi'] = $this->input->post('interesse_interventi_flag') == 'Yes' ? 1 : 0;
    $data['interesse_interventi_checkbox'] = $this->input->post('interesse_interventi_checkbox') == 'Yes' ? 1 : 0;

    $data['interesse_lavori_importo'] = parse_decimal($data['interesse_lavori_importo']);
    $data['interesse_servizi_importo'] = parse_decimal($data['interesse_servizi_importo']);
    $data['interesse_forniture_importo'] = parse_decimal($data['interesse_forniture_importo']);
    $data['interesse_interventi_importo'] = parse_decimal($data['interesse_interventi_importo']);

    $data['impresa_num_amministratori'] = parse_decimal($data['impresa_num_amministratori']);
    $data['impresa_num_procuratori'] = parse_decimal($data['impresa_num_procuratori']);
    $data['impresa_num_sindaci'] = parse_decimal($data['impresa_num_sindaci']);
    $data['impresa_num_sindaci_supplenti'] = parse_decimal($data['impresa_num_sindaci_supplenti']);

    $data['impresa_settore_nessuno'] = $this->input->post('impresa_settore_nessuno') == 'Yes' ? 1 : 0;
    $data['impresa_settore_trasporto'] = $this->input->post('impresa_settore_trasporto') == 'Yes' ? 1 : 0;
    $data['impresa_settore_rifiuti'] = $this->input->post('impresa_settore_rifiuti') == 'Yes' ? 1 : 0;
    $data['impresa_settore_terra'] = $this->input->post('impresa_settore_terra') == 'Yes' ? 1 : 0;
    $data['impresa_settore_bitume'] = $this->input->post('impresa_settore_bitume') == 'Yes' ? 1 : 0;
    $data['impresa_settore_nolo'] = $this->input->post('impresa_settore_nolo') == 'Yes' ? 1 : 0;
    $data['impresa_settore_ferro'] = $this->input->post('impresa_settore_ferro') == 'Yes' ? 1 : 0;
    $data['impresa_settore_autotrasporto'] = $this->input->post('impresa_settore_autotrasporto') == 'Yes' ? 1 : 0;
    $data['impresa_settore_guardiana'] = $this->input->post('impresa_settore_guardiana') == 'Yes' ? 1 : 0;

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


    $this->db->trans_start();
    $curr_error_handler = set_error_handler('db_transaction_error_handler');
    try {
      if ($this->db->insert('esecutori', $data)) {
        $doc_id = $this->db->insert_id();
        $codice = create_codice_istanza($doc_id);

        $this->db->where('ID', $doc_id)->update(
          'esecutori',
          array('codice_istanza' => $codice)
        );

        $anagrafica = $this->input->post('anagrafica');
        $offices = $this->input->post('office');

        if(!empty($offices) && is_array($offices)){
          foreach ($offices as $office) {
            $this->db->insert(
              'esecutori_imprese_partecipate',
              array(
                'esecutore_id' => $doc_id,
                'nome' => $office['name'],
                'cf' => $office['cf'],
                'piva' => $office['vat'],
              )
            );
          }
        }

        if ($anagrafica) {
          foreach ($_POST['anagrafica'] as $aidx => $anagrafica) {
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
              'antimafia_cf' => $anagrafica['antimafia_cf'],
              'role_id' => $anagrafica['antimafia_role']
            );
            $this->db->insert('anagrafiche_antimafia', $data_anagrafiche);
            $id_anagrafica = $this->db->insert_id();

            if (isset ($anagrafica['f']) AND !empty($anagrafica['f'])){
              foreach ($anagrafica['f'] AS $familiar) {
                $dati_familiare = array(
                  'esecutore_id' => $doc_id,
                  'anagrafica_id' => $id_anagrafica,
                  'nome' => $familiar['name'],
                  'cognome' => $familiar['titolare_cognome'],
                  'comune' => $familiar['comune'],
                  'data_nascita' => parse_date($familiar['data_nascita']),
                  'cf' => $familiar['cf'],
                  'role_id' => $familiar['role']
                );
                $this->db->insert('anagrafiche_familiari', $dati_familiare);
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
      echo $e->getMessage() . '<br />';
      echo $e->getLine() . '<br />';
      echo $e->getFile() . '<br />';
    }
    set_error_handler($curr_error_handler);
    return $doc_id;
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
