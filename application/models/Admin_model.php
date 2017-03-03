<?php

/*
 * Metodi ricerca e visualizzazione elenco di merito
 */

class Admin_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('fastform');
        $this->load->helper('pdf');
    }

  public function get_mail_tos($provincia = false) {
    if ( false == $provincia ) {
      $prefetture = $this->db->from('province_pec')->get();
    }
    else {
      $prefetture = $this->db->from('province_pec')->where('sigla', $provincia)->get();
    }
    return $prefetture->result_array();
  }

  public function get_mail_template ($type = 0, $idTemplate = 0) {
    $type = (int)$type;
    $idTemplate = (int)$idTemplate;
    if ( 0 != $idTemplate ) {
      $query = $this->db->from('mail_templates')->where('id', $idTemplate)->limit(1)->get();
    }
    else {
      $query = $this->db->from('mail_templates')->where('type', $type)->order_by('weight')->limit(1)->get();
    }
    return $query->result_array();
  }

  public function get_mail_templates ($type = 0) {
    $type = (int)$type;
    $query = $this->db->from('mail_templates')->where('type', $type)->order_by('weight')->get();
    return $query->result_array();
  }

  public function get_item($id) {
    $qhr = $this->db->get_where('esecutori', array('ID' => $id));
    return $qhr->row_array();
  }

	public function add_data ($data_user) {
		$this->db->insert('esecutori', $data_user);
	}

	public function check_upload ($data_user) {
		 $query = $this->db
                        ->from('esecutori')
						->where('partita_iva',$data_user['partita_iva'])
                        ->get();
		$result = $query->result_array();
		return ($result);
	}


	public function find_items_esecutori_iscritti ($ask, $offset = 0, $limit = 25) {
		$id_stato = array('1', '2');
		 if ($ask == FALSE)
        {
            $rowcount = $this->db
                        ->from('esecutori')
						->where_in('stato',$id_stato)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->count_all_results();

            $query = $this->db
                        ->from('esecutori')
						->where_in('stato',$id_stato)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->get();

        }



	else if ($ask['tipo_attivita'] != FALSE) {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where_in('stato',$id_stato)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where_in('stato',$id_stato)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();

						}





	else {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where_in('stato',$id_stato)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where_in('stato',$id_stato)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();

						}






		$result = $query->result_array();
        $result['rowcount'] = $rowcount;
        log_message('error', "totale record: " . $result['rowcount']);
        return $result;

	}


	public function find_items_esecutori_iscritti_provv ($ask, $offset = 0, $limit = 25) {

		if ($ask == FALSE)
        {
            $rowcount = $this->db
                        ->from('esecutori')
						->where('stato',2)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->count_all_results();

            $query = $this->db
                        ->from('esecutori')
						->where('stato',2)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->get();

        }



	else if ($ask['tipo_attivita'] != FALSE) {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',2)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',2)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();

						}
	else {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',2)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',2)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();







	}

		$result = $query->result_array();
        $result['rowcount'] = $rowcount;
        log_message('error', "totale record: " . $result['rowcount']);
        return $result;

	}

		public function find_items_esecutori_richiesta ($ask, $offset = 0, $limit = 25) {

		if ($ask == FALSE)
        {
            $rowcount = $this->db
                        ->from('esecutori')
						->where('stato',0)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->count_all_results();

            $query = $this->db
                        ->from('esecutori')
						->where('stato',0)
                        ->order_by('uploaded_at', 'ASC')
                        ->limit($limit, $offset)
                        ->get();

        }


	else if ($ask['tipo_attivita'] != FALSE) {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',0)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',0)
						->where($ask['tipo_attivita'],1)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();

						}



	else {

		$rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',0)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('esecutori')
						->where('stato',0)
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('partita_iva', $ask['partita_iva'])
						->like('codice_fiscale', $ask['codice_fiscale'])
                        ->limit($limit, $offset)
                        ->get();

						}

		$result = $query->result_array();
        $result['rowcount'] = $rowcount;
        log_message('error', "totale record: " . $result['rowcount']);
        return $result;

	}















     public function find_items($ask, $offset = 0, $limit = 10){
        $province_cratere = array('BO','MO','PR','RA','RN','FE','RE','RO','MN','FC','PC');
        if ($ask == FALSE)
        {
            $rowcount = $this->db
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->count_all_results();

            $query = $this->db
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();

        } else {

			if ($ask['provincia']=='altre')
				if ($ask['soa'] && $ask['ateco'] )
{
			$rowcount = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
						->join('soa_tags','dichiaraziones.id = soa_tags.dichiarazione_id')
						->join('soas','soa_tags.soa_id=soas.id ')
                        ->where('pubblicato', 1)

						->where('atecos.codice',$ask['ateco'])
						->where('soas.codice',$ask['soa'])
						->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
						->join('soa_tags','dichiaraziones.id = soa_tags.dichiarazione_id')
						->join('soas','soa_tags.soa_id=soas.id ')
                        ->where('pubblicato', 1)

						->where('atecos.codice',$ask['ateco'])
						->where('soas.codice',$ask['soa'])
						 ->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }
else if  ($ask['soa'] )
			{
			$rowcount = $this->db
                        ->select('*')
                        ->from('soa_tags')
						->join('soas','soa_tags.soa_id=soas.id ')
						->join('dichiaraziones','soa_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('soas.codice',$ask['soa'])
						 ->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
						->select('*')
                        ->from('soa_tags')
						->join('soas','soa_tags.soa_id=soas.id ')
						->join('dichiaraziones','soa_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('soas.codice',$ask['soa'])
						 ->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }


else if  ($ask['ateco'] )
			{
			$rowcount = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id ')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('atecos.codice',$ask['ateco'])
						->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id ')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('atecos.codice',$ask['ateco'])
						->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }


else
			{
			$rowcount = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
						->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
						->where_not_in('sl_prov', $province_cratere)
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();

        } else {


if ($ask['soa'] && $ask['ateco'] )
{
			$rowcount = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
						->join('soa_tags','dichiaraziones.id = soa_tags.dichiarazione_id')
						->join('soas','soa_tags.soa_id=soas.id ')
                        ->where('pubblicato', 1)

						->where('atecos.codice',$ask['ateco'])
						->where('soas.codice',$ask['soa'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
						->join('soa_tags','dichiaraziones.id = soa_tags.dichiarazione_id')
						->join('soas','soa_tags.soa_id=soas.id ')
                        ->where('pubblicato', 1)

						->where('atecos.codice',$ask['ateco'])
						->where('soas.codice',$ask['soa'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }
else if  ($ask['soa'] )
			{
			$rowcount = $this->db
                        ->select('*')
                        ->from('soa_tags')
						->join('soas','soa_tags.soa_id=soas.id ')
						->join('dichiaraziones','soa_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('soas.codice',$ask['soa'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
						->select('*')
                        ->from('soa_tags')
						->join('soas','soa_tags.soa_id=soas.id ')
						->join('dichiaraziones','soa_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('soas.codice',$ask['soa'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }


else if  ($ask['ateco'] )
			{
			$rowcount = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id ')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('atecos.codice',$ask['ateco'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('ateco_tags')
						->join('atecos','ateco_tags.ateco_id=atecos.id ')
						->join('dichiaraziones','ateco_tags.dichiarazione_id=dichiaraziones.id')
                        ->where('pubblicato', 1)
						->where('atecos.codice',$ask['ateco'])
						 ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();


        }









else {










            $rowcount = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
                        ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->count_all_results();

            $query = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->where('pubblicato', 1)
                        ->like('sl_prov', $ask['provincia'])
                        ->like('tipo_contratto', $ask['tipo_contratto'])
                        ->like('ragione_sociale', $ask['ragione_sociale'])
                        ->like('sl_piva', $ask['sl_piva'])
                        ->order_by('valore_soa', 'DESC')
                        ->limit($limit, $offset)
                        ->get();

						}



        }


		}

        $result = $query->result_array();
        $result['rowcount'] = $rowcount;
        log_message('error', "totale record: " . $result['rowcount']);
        return $result;
    }

	public function find_scadenze_durc() {

	  $query = $this->db
                        ->select('*')
						->distinct()
                        ->from('dichiaraziones')
						->where('durc_scadenza !=','0000-00-00 00:00:00')
						->where('durc_scadenza <',date("Y-m-d H:i:s"))
						->where('hidden =','0')
						->where('durc <>','1')
						->group_by('sl_piva')
						->order_by('durc_scadenza', 'ASC')
                        ->get();

	  $result = $query->result_array();
	  return $result;
	}



	public function find_scadenze_protesti() {

	  $query = $this->db
                        ->select('*')
						->distinct()
                        ->from('dichiaraziones')
						->where('protesti_scadenza !=','0000-00-00 00:00:00')
						->where('protesti_scadenza <',date("Y-m-d H:i:s"))
						->where('hidden =','0')
						->group_by('sl_piva')
						->order_by('protesti_scadenza', 'ASC')
                        ->get();

	  $result = $query->result_array();
	  return $result;
	}

	public function find_scadenze_antimafia() {

	  $query = $this->db
                        ->select('*')
						->distinct()
                        ->from('dichiaraziones')
						->where('antimafia_scadenza !=','0000-00-00 00:00:00')
						->where('antimafia_scadenza <',date("Y-m-d H:i:s"))
					    ->where('hidden =','0')
						->group_by('sl_piva')
						->order_by('antimafia_scadenza', 'ASC')
                        ->get();

	  $result = $query->result_array();
	  return $result;
	}

	public function find_partite_iva_doppie_pubblicate() {
	  $query_db = "SELECT * FROM dichiaraziones WHERE pubblicato = '1 'GROUP BY sl_piva HAVING COUNT( * ) >1 ORDER BY id";
	  $query = $this->db->query($query_db);
	  $result = $query->result_array();
	  return $result;
	}


	public function find_partite_iva_doppie() {
	  $query_db = "SELECT * FROM dichiaraziones padre
	  WHERE EXISTS( SELECT NULL
	  FROM dichiaraziones figlio
	  WHERE figlio.sl_piva=padre.sl_piva
	  GROUP BY sl_piva HAVING COUNT( * ) >1 order by id)

	  ORDER BY ragione_sociale ";
	  $query = $this->db->query($query_db);
	  $result = $query->result_array();
	  return $result;
	}


	public function search_nascoste($params,$sort, $offset, $limit){
		$query = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
					    ->like($params)
						->where('hidden',1)
                        ->order_by($sort)
                        ->limit($limit, $offset)
                        ->get();

        $result = $query->result_array();
        return $result;
    }







	public function find_imprese_nascoste() {
	  $query_db = $this->db
						->select('*')
						->from('dichiaraziones')
	                    ->where('hidden =','1')

						->order_by('id', 'DESC')
						->get();

	  $result = $query_db->result_array();
	  return $result;
	}





	public function list_items($offset = 0, $limit = 10){
        $query = $this->db
                    -> order_by('id', 'desc')
                    -> get('dichiaraziones', $limit, $offset);
        $this->db->cache_off();
        return $query->result_array();
    }

    public function bonifica_province(){
        $output = array();

        $query = $this -> db
                -> select('provincia, provincia_nome')
                -> from('comuni')
                -> order_by('provincia ASC, provincia_nome ASC')
                -> distinct()
                -> get();

        $prov_array = $query->result_array();


        $query =$this -> db
                        ->select('*')
                        ->from('dichiaraziones')
                        /*->where('zona_sl', NULL)*/
                        ->get();

        $results = $query->result_array();

        foreach ($results as $item) {
            log_message('debug', "controllo: " . $item['sl_prov']);
            foreach ($prov_array as $prov) {
                $querystring = "/^{$prov['provincia']}$|{$prov['provincia_nome']}/i";
                $val = $prov['provincia'];
				$elencocampi = array('sl_prov', 'so_provincia', 'titolare_prov_nascita');
				foreach ($elencocampi as $province) {
	                if(preg_match($querystring, $item[$province])){
	                    if($item[$province] != $val){
	                        log_message('debug', "provincia: " . $val);
	                        $data = array($province => $prov['provincia']);
	                        $this->db
	                            ->where('id', $item['id'])
	                            ->update('dichiaraziones', $data);

	                        $output[] = array(
	                                        'id' => $item['id'],
	                                        'ragione_sociale' => $item['ragione_sociale'],
	                                        'provincia_inserita' => $item[$province],
	                                        'provincia_bonificata' => $prov['provincia']
	                                        );
	                    }

	                }
				}
            }

        }

        return $output;
    }

    public function inserisci_valore(){

        $soa_classi = array(
            "0" => "0",
            "1" => "258000",
            "2" => "516000",
            "3" => "1033000",
            "3bis" => "1500000",
            "4" => "2582000",
            "4bis" => "3500000",
            "5" => "5165000",
            "6" => "10329000",
            "7" => "15494000",
            "8" => "15494001",
        );


        $query =$this -> db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->where('valore_soa', NULL)
                        ->get();

        $results = $query->result_array();

        foreach ($results as $item)
        {
            $query = $this -> db
                        -> select('classe')
                        -> from ('soa_tags')
                        -> where ('dichiarazione_id', $item['id'])
                        -> get();

            $soas = $query->result_array();
            $value = 0;

            foreach ($soas as $soa) {
                $value += $soa_classi[$soa['classe']];
            }

            $data = array('valore_soa' => $value);
            $this->db->where('id', $item['id']);
            $this->db->update('dichiaraziones', $data);
            log_message('debug', $value);
        }

    }

    public function elenco_soas($item_id){
        $this->db->select('*');
        $this->db->from('soa_tags');
        $this->db->join('soas', 'soa_tags.soa_id = soas.id');
        $this->db->where('soa_tags.dichiarazione_id', $item_id);
        $query = $this->db->get();
        return $query->result_array();
    }

  public function elenco_soas_open_data($item_id){
        $this->db->select('gruppo,codice,denominazione');
        $this->db->from('soa_tags');
        $this->db->join('soas', 'soa_tags.soa_id = soas.id');
        $this->db->where('soa_tags.dichiarazione_id', $item_id);
        $query = $this->db->get();
        return $query->result_array();
    }




    public function elenco_atecos($item_id){
        $this->db->select('*');
        $this->db->from('ateco_tags');
        $this->db->join('atecos', 'ateco_tags.ateco_id = atecos.id');
        $this->db->where('ateco_tags.dichiarazione_id', $item_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function elenco_atecos_open_data($item_id){
        $this->db->select('gruppo,codice,denominazione');
        $this->db->from('ateco_tags');
        $this->db->join('atecos', 'ateco_tags.ateco_id = atecos.id');
        $this->db->where('ateco_tags.dichiarazione_id', $item_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function search($params,$sort, $offset, $limit){

        $result = array();

        $rowcount = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->like($params)
                        ->count_all_results();

        $query = $this->db
                        ->select('*')
                        ->from('dichiaraziones')
                        ->like($params)
                        ->order_by($sort)
                        ->limit($limit, $offset)
                        ->get();

        $result['statements'] = $query->result_array();
        $result['count'] = $rowcount;
        return $result;
    }

	/*Funzione di visualizzazione degli esecutori in istruttoria */

	    public function search_esecutori($params,$sort, $offset, $limit){

        $result = array();

        $rowcount = $this->db
                        ->select('*')
                        ->from('esecutori')
                        ->like($params)
                        ->count_all_results();

        $query = $this->db
                        ->select('*')
                        ->from('esecutori')
                        ->like($params)
                        ->order_by($sort)
                        ->limit($limit, $offset)
                        ->get();

        $result['statements'] = $query->result_array();
        $result['count'] = $rowcount;
        return $result;
    }


    public function setMD5(){
        $query =$this -> db
                ->select('*')
                ->from('dichiaraziones')
                ->where('hash', NULL)
                ->get();

        $results = $query->result_array();
        $output = array();
        foreach ($results as $result) {
            $hash = pdfmd5($result['id']);
            $output[] = array('id' => $result['id'], 'hash' => $hash);
            $this->db
                ->where('id', $result['id'])
                ->update('dichiaraziones', array('hash' => $hash));
        }
        return $output;
    }

    public function findlog($params, $offset = 0, $limit = 50){
        $result = array();

        if($params == FALSE)
        {
             $rowcount = $this->db
                        ->select('*')
                        ->from('logs')
                        ->count_all_results();

            $query = $this->db
                            ->select('*')
                            ->from('logs')
                            ->order_by('created_at DESC')
                            ->limit($limit, $offset)
                            ->get();
        } else {
            $rowcount = $this->db
                        ->select('*')
                        ->from('logs')
                        ->like($params)
                        ->count_all_results();

            $query = $this->db
                            ->select('*')
                            ->from('logs')
                            ->like($params)
                            ->order_by('created_at DESC')
                            ->limit($limit, $offset)
                            ->get();
        }
        $result['logs'] = $query->result_array();
        $result['rowcount'] = $rowcount;
        return $result;
    }
}
