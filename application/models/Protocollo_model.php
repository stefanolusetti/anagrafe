<?php

/*
 * Caricamento dati per chiamata WS Protocollo
 */

class Protocollo_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
public function get_items($hash = FALSE)
	{
		if ($hash === FALSE)
		{
			$query = $this->db->get('dichiaraziones');
			return $query->result_array();
		}

		$query = $this->db->get_where('dichiaraziones', array('hash' => $hash));
		$item =  $query->row_array();
		return $item;
	}
public function get_protocollo_items()
	{
	$query_protocollo = $this->db->get('protocollazione_config');
	$item_protocollo =  $query_protocollo->row_array();
	return $item_protocollo;
	}
	
}
?>