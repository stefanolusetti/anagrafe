<?php

/*
 * regole di validazione della form
 */

$config = array(
		'domanda' => array(  
			array(
			'field'   => 'titolare_nome', 
			'label'   => 'Nome del dichiarante', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'titolare_comune_nascita', 
			'label'   => 'Comune di nascita', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'titolare_prov_nascita', 
			'label'   => 'Provincia di nascita', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'titolare_data_nascita', 
			'label'   => 'Data di nascita', 
			'rules'   => 'callback__controlla_data'
			),
			array(
			'field'   => 'ragione_sociale', 
			'label'   => 'Ragione Sociale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'tipo_impresa', 
			'label'   => 'Tipo società', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'anno_inizio', 
			'label'   => 'Anno di inizio attività', 
			'rules'   => 'callback__controlla_data'
			),
			array(
			'field'   => 'sl_via', 
			'label'   => 'Via sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_civico', 
			'label'   => 'Civico sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_cap', 
			'label'   => 'CAP sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_comune', 
			'label'   => 'Comune sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_prov', 
			'label'   => 'Provincia sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_tel', 
			'label'   => 'Telefono sede legale', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sl_piva', 
			'label'   => 'Partita IVA', 
			'rules'   => 'required|exact_length[11]'
			),
			array(
			'field'   => 'sl_cf', 
			'label'   => 'Codice fiscale', 
			'rules'   => 'required|max_length[16]|min_length[11]'
			),
			array(
			'field'   => 'sl_email', 
			'label'   => 'Email sede legale', 
			'rules'   => 'required|valid_email'
			),
			array(
			'field'   => 'sl_pec', 
			'label'   => 'PEC', 
			'rules'   => 'required|valid_email'
			),
			array(
			'field'   => 'so_via', 
			'label'   => 'Via sede operativa', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'so_civico', 
			'label'   => 'Civico sede operativa', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'so_cap', 
			'label'   => 'CAP sede operativa', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'so_comune', 
			'label'   => 'Comune sede operativa', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'so_provincia', 
			'label'   => 'Provincia sede operativa', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'pos_inail_n', 
			'label'   => 'Posizione INAIL', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'pos_inail_sede', 
			'label'   => 'Posizione INAIL sede', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'pos_inps_n', 
			'label'   => 'Posizione INPS numero', 
			'rules'   => 'required|exact_length[10]'
			),
			array(
			'field'   => 'pos_inps_sede', 
			'label'   => 'Posizione INPS sede', 
			'rules'   => 'required|max_length[200]'
			),
			
			array(
			'field'   => 'iscrizione_cciaa_sede', 
			'label'   => 'CCIAA sede', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'iscrizione_cciaa_n', 
			'label'   => 'CCIAA numero', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'okdurc_sino', 
			'label'   => 'durc_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'noprotesti_sino', 
			'label'   => 'noprotesti_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'antimafia_sino', 
			'label'   => 'antimafia_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'ateco_sino', 
			'label'   => 'ateco_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sopralluoghi_sino', 
			'label'   => 'sopralluoghi_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'sico_sino', 
			'label'   => 'sico_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'iscrizionecassa_sino', 
			'label'   => 'iscrizionecassa_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'ccnledilizia_sino', 
			'label'   => 'ccnledilizia_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'ccnlaltro_sino', 
			'label'   => 'ccnlaltro_sino', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'luogo_firma', 
			'label'   => 'Luogo compilazione', 
			'rules'   => 'required|max_length[200]'
			),
			array(
			'field'   => 'data_firma', 
			'label'   => 'Data di compilazione', 
			'rules'   => 'callback__controlla_data'
			),
			array(
			'field'   => 'ateco', 
			'label'   => 'Classificazione ateco', 
			'rules'   => 'required'
			),
			array(
			'field'   => 'soa_sino', 
			'label'   => 'Classificazione SOA', 
			'rules'   => 'callback__controlla_soa'
			),
			array(
			'field'   => 'addetti_n_dipendenti', 
			'label'   => 'Numero di dipendenti', 
			'rules'   => 'callback__controlla_impiegati'
			),
			array(
			'field'   => 'addetti_n_socilav', 
			'label'   => 'Numero di soci lavoratori', 
			'rules'   => 'callback__controlla_impiegati'
			),
			array(
			'field'   => 'addetti_n_artigiani', 
			'label'   => 'Numero di soci artigiani', 
			'rules'   => 'callback__controlla_impiegati'
			),
		   
			array(
			'field'   => 'pos_cassa_denominazione', 
			'label'   => 'Cassa edile denominazione', 
			'rules'   => 'callback__controlla_cassadenominazione'
			),
			array(
			'field'   => 'pos_cassa_n', 
			'label'   => 'Cassa edile numero', 
			'rules'   => 'callback__controlla_cassanumero'
			),
			array(
			'field'   => 'pos_cassa_sede', 
			'label'   => 'Cassa edile sede', 
			'rules'   => 'callback__controlla_cassasede'
			)
		),
        'utente' => array(
            array(
            'field'   => 'password', 
            'label'   => 'Password', 
            'rules'   => 'required|min_length[8]'
            )
        )
);     