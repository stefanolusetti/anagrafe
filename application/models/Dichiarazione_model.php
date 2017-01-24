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
        $doc['anagrafiche_antimafia'][$i]['familiari'] = $this->get_item_familiars($doc['anagrafiche_antimafia'][$i]['aaid']);
      }
      $doc['offices'] = $this->get_item_offices($id);
      return $doc;
    } catch(Exception $e){
      return false;
    }
  }

  public function get_item($id) {
    $qhr = $this->db->get_where('docs', array('did' => $id));
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
    $query_antimafia = $this->db->get_where('anagrafiche_antimafia', array('did' => $id));
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id anagrafiche_antimafia
  public function get_item_familiars($id) {
    $query_antimafia = $this->db->get_where('anagrafiche_familiari', array('aaid' => $id));
    $item_antimafia = $query_antimafia->result_array();
    return $item_antimafia;
  }

  // id docs
  public function get_item_offices($id) {
    $query_offices = $this->db->get_where('doc_offices', array('did' => $id));
    $item_offices = $query_offices->result_array();
    return $item_offices;
  }

  public function set_statement() {
    $this->load->helper('url');
    $this->load->helper('fastform');


    $data = list_fields();
    $data['crd'] = date('Y-m-d H:i:s');
    $data['birth_date'] = parse_date($data['birth_date']);
    $data['stmt_wl_date'] = parse_date($data['stmt_wl_date']);
    $data['doc_date'] = parse_date($data['doc_date']);
    $data['company_birthdate'] = parse_date($data['company_birthdate']);
    $data['residence_city'] = $data['residence_locality']; // WTF? @TODO FIX!

    $data['stmt_wl'] = $data['stmt_wl'] == 'Yes' ? 1 : 0;
    $data['stmt_wl_interest'] = $data['stmt_wl_interest'] == 'Yes' ? 1 : 0;
    $data['sake_work'] = $this->input->post('sake_work_flag') == 'Yes' ? 1 : 0;
    $data['sake_service'] = $this->input->post('sake_service_flag') == 'Yes' ? 1 : 0;
    $data['sake_supply'] = $this->input->post('sake_supply_flag') == 'Yes' ? 1 : 0;
    $data['sake_fix'] = $this->input->post('sake_fix_flag') == 'Yes' ? 1 : 0;
    $data['stmt_eligible'] = $this->input->post('sake_eligible_flag') == 'Yes' ? 1 : 0;

    $data['sake_work_amount'] = parse_decimal($data['sake_work_amount']);
    $data['sake_service_amount'] = parse_decimal($data['sake_service_amount']);
    $data['sake_supply_amount'] = parse_decimal($data['sake_supply_amount']);
    $data['sake_fix_amount'] = parse_decimal($data['sake_fix_amount']);

    $data['company_num_admins'] = parse_decimal($data['company_num_admins']);
    $data['company_num_attorney'] = parse_decimal($data['company_num_attorney']);
    $data['company_num_majors'] = parse_decimal($data['company_num_majors']);
    $data['company_num_majors_tmp'] = parse_decimal($data['company_num_majors_tmp']);

    $data['stato'] = NULL;
    $data['uploaded'] = 0;
    $data['uploaded_date'] = NULL;

    $this->db->trans_start();
    $curr_error_handler = set_error_handler('db_transaction_error_handler');
    try {
      if ($this->db->insert('docs', $data)) {
        $doc_id = $this->db->insert_id();
        $anagrafica = $this->input->post('anagrafica');
        $offices = $this->input->post('office');

        if(!empty($offices) && is_array($offices)){
          foreach ($offices as $office) {
            $this->db->insert(
              'doc_offices',
              array(
                'did' => $doc_id,
                'name' => $office['name'],
                'cf' => $office['cf'],
                'vat' => $office['vat'],
              )
            );
          }
        }

        if ($anagrafica) {
          foreach ($_POST['anagrafica'] as $aidx => $anagrafica) {
            $data_anagrafiche = array(
              'did' => $doc_id,
              'antimafia_nome' => $anagrafica['antimafia_nome'],
              'antimafia_cognome  ' => $anagrafica['antimafia_cognome'],
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
                  'did' => $doc_id,
                  'aaid' => $id_anagrafica,
                  'nome' => $familiar['name'],
                  'cognome' => $familiar['lastname'],
                  'comune' => $familiar['comune'],
                  'data_nascita' => parse_date($familiar['data_nascita']),
                  'cf' => $familiar['cf'],
                  'rid' => $familiar['role']
                );
                $this->db->insert('anagrafiche_familiari', $dati_familiare);
              }
            }
          }
        }
      }
      $this->db->trans_commit();
    } catch(Exception $e) {
      $this->db->trans_rollback();
      // @Todo Messaggio?
      echo '<h1>SI È VERIFICATO UN ERRORE.</h1>';
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
      $return[$v['rid']] = $v['value'];
    }
    return $return;
  }

  public function get_company_shape_label($id) {
    $query = $this->db->where('csid', (int)$id)->get('company_shapes');
    $r = $query->result_array();
    return $r;
  }

  public function get_company_shapes() {
    $query = $this->db->get('company_shapes');
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
