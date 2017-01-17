<?php

/*
 *  Carica gli ateco e le SOA per la navigazione dell'elenco iscritti
 */

class Elenco_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
 public function get_atecos_elenco() {
        $query = $this -> db
                -> select('codice')
                -> from('atecos')
                -> order_by('codice ASC')
                -> distinct()
                -> get();
        $result = $query->result_array();
        $options = array();
        
		foreach ($result as $item) {
            $key = $item['codice'];
            $value = "{$item['codice']}";
            $options[$key] = $value;
			
        }
        return $options;
    }	
	
		
 public function get_soas_elenco() {
        $query = $this -> db
                -> select('codice')
                -> from('soas')
                -> order_by('codice ASC')
                -> distinct()
                -> get();
        $result = $query->result_array();
        $options = array();
        
		foreach ($result as $item) {
            $key = $item['codice'];
            $value = "{$item['codice']}";
            $options[$key] = $value;
			
        }
        return $options;
    }
	

	
}