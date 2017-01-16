<?php

/*
 * Metodi per salvataggio e recupero dei dati elenco di merito.
 */

class Dichiarazione_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper('fastform');
		$this->load->helper('pdf');
	}
	
	public function get_items($id = FALSE)
	{
		if ($id === FALSE)
		{
			$query = $this->db->get('dichiaraziones');
			return $query->result_array();
		}

		$query = $this->db->get_where('dichiaraziones', array('id' => $id));
		$item =  $query->row_array();
		$item['ateco_lista'] = build_other_fields($item['id'], 'ateco',0);
		$item['ateco_lista_r'] = build_other_fields($item['id'], 'ateco',1);
		$item['soa_lista'] = build_other_fields($item['id'], 'soa',null);
		$item['antimafia_lista'] = build_anagrafica_antimafia($item['id']);
		$item['titolare_data_nascita']  = format_date($item['titolare_data_nascita']);
		$item['id_anno'] = get_year($item['data_firma']);
		$item['data_firma']  = format_date($item['data_firma']);
		$item['id_progressivo'] = $item['id'];
		return $item;
	}
	
	public function get_items_antimafia ($id =FALSE) 
	{
	if ($id === FALSE)
		{
			$query = $this->db->get('anagrafiche_antimafia');
			return $query->result_array();
		}

		$query_antimafia = $this->db->get_where('anagrafiche_antimafia',array('dichiarazione_id' => $id));
		$item_antimafia = $query_antimafia->result_array();
		return $item_antimafia;
	}
	
	public function set_statement()
	{
		$this->load->helper('url');
		
		$data = list_fields();
		$data['titolare_data_nascita']  = parse_date($data['titolare_data_nascita']);
		$data['data_firma']  = parse_date($data['data_firma']);
        //$data['created_at'] = time();
        $data['created_at'] = date("Y-m-d H:i:s");

		if($this->db->insert('dichiaraziones', $data))
		{
			$dichiarazione_id = $this->db->insert_id();
			
			$anagrafica = $this->input->post('anagrafica');
			
			//if ($_POST['anagrafica']) {
				if ($anagrafica) {
				foreach ($_POST['anagrafica'] as $anagrafica) 
				{ 
					$data_anagrafiche = array('dichiarazione_id' => $dichiarazione_id,
							'antimafia_nome' => $anagrafica['antimafia_nome'],
							'antimafia_comune_nascita' => $anagrafica['antimafia_comune_nascita'],
							'antimafia_data_nascita' => parse_date($anagrafica['antimafia_data_nascita']),
							'antimafia_comune_residenza' => $anagrafica['antimafia_comune_residenza'],
							'antimafia_provincia_residenza' => $anagrafica['antimafia_provincia_residenza'],
							'antimafia_via_residenza' => $anagrafica['antimafia_via_residenza'],
							'antimafia_civico_residenza' => $anagrafica['antimafia_civico_residenza'],
							'antimafia_cf' => $anagrafica['antimafia_cf'],
						    'antimafia_carica_sociale' => $anagrafica['antimafia_carica_sociale']
							);
						$this->db->insert('anagrafiche_antimafia', $data_anagrafiche);
			
				//$this->db->insert('anagrafiche_antimafia', $anagrafica); 
				    
			
				}
			}
			
			
			
			$soas = $this->input->post('soa');
			if ($soas) {
				foreach ($soas as $key => $value) 
				{
					if($value != '0'):
						$data = array('soa_id' => $key, 'dichiarazione_id' => $dichiarazione_id, 'classe' => $value);
						$this->db->insert('soa_tags', $data);
					endif;
				}
			}
			
			$atecos = $this->input->post('ateco');
			if ($atecos) {
				foreach ($atecos as $key => $value)
				{
					if(!empty($value)):
						$data = array('ateco_id' => $key, 'dichiarazione_id' => $dichiarazione_id);
						$this->db->insert('ateco_tags', $data);
                        
                        if ($key == '15') {
                            $this->db
                                    ->where('id', $dichiarazione_id)
                                    ->update('dichiaraziones', array('5bis' => 1));
                        }
                        
					endif;
				}
			}
			
		}
		return $dichiarazione_id;
	}
	
	public function get_soas(){
		$query = $this->db->get('soas');
		return $query->result_array();
	}
	
	public function get_atecos(){
		$query = $this->db->get('atecos');
		return $query->result_array();
	}
	
}