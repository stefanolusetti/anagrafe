<?php

/*
 *  Supporta la funzione di autocompletamento dei campi COMUNE.
 */

class Comuni_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_items($comune = FALSE)
	{
		if ($comune === FALSE)
		{
			$query = $this->db->get('comuni');
			return $query->result_array();
		}
		$this->db->cache_off();
		$this->db->from('comuni');
		$this->db->like("comune", $comune, 'after');
		$query = $this->db->get();
        log_message('debug', $this->db->last_query());
		return  $query->result_array();
		
	}
    
    public function get_province() {
        $query = $this -> db
                -> select('provincia, provincia_nome')
                -> from('comuni')
                -> order_by('provincia ASC, provincia_nome ASC')
                -> distinct()
                -> get();
        $result = $query->result_array();
        $options = array();
        
		foreach ($result as $item) {
            $key = $item['provincia'];
            $value = "{$item['provincia']} - {$item['provincia_nome']} ";
            $options[$key] = $value;
			
        }
        return $options;
    }
	
}