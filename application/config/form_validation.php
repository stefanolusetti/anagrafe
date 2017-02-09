<?php

/*
* regole di validazione della form
*/

$config = array(
  'domanda' => array(
    array(
      'field' => 'titolare_nome',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_cognome',
      'label' => 'Cognome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_comune',
      'label' => 'Comune di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_provincia',
      'label' => 'Provincia di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_data',
      'label' => 'Data nascita dichiarante',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'titolare_res_comune',
      'label' => 'Comune di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_provincia',
      'label' => 'Provincia di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_via',
      'label' => 'Indirizzo di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_civico',
      'label' => 'Numero civico di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_cap',
      'label' => 'CAP di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'ragione_sociale',
      'label' => 'Ragione sociale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'impresa_data_costituzione',
      'label' => 'Data costituzione società',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'forma_giuridica_id',
      'label' => 'Forma giuridica dell\'impresa',
      'rules' => 'callback__forma_giuridica_id_check',
    ),
    array(
      'field' => 'impresa_forma_giuridica_altro',
      'label' => 'Altra forma giuridica',
      'rules' => 'callback__impresa_forma_giuridica_altro',
    ),
    array(
      'field' => 'sl_comune',
      'label' => 'Comune Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_prov',
      'label' => 'Provincia Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_cap',
      'label' => 'CAP  Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_via',
      'label' => 'Indirizzo Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico',
      'label' => 'Civico Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_telefono',
      'label' => 'Telefono Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'partita_iva',
      'label' => 'Partita IVA Società',
      'rules' => 'required|exact_length[11]',
    ),
    array(
      'field' => 'codice_fiscale',
      'label' => 'Codice Fiscale Società',
      'rules' => 'required|max_length[16]|min_length[11]',
    ),
    /*
    array(
      'field' => 'impresa_email',
      'label'   => 'Email sede legale',
      'rules'   => 'required|valid_email'
    ),
    */
    array(
      'field' => 'impresa_pec',
      'label'   => 'Email Certificata PEC sede legale',
      'rules'   => 'required|valid_email'
    ),
    /*
    array(
      'field' => 'sl_civico_admins',
      'label' => 'Nome del dichiarante',
      'rules' => 'callback__controllo_num_dipendenti',
    ),
    array(
      'field' => 'sl_civico_attorney',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico_majors',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico_majors_tmp',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    */
    array(
      'field' => 'rea_ufficio',
      'label' => 'Ufficio Registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    /*
    array(
      'field' => 'rea_num_iscrizione',
      'label' => 'Numero di iscrizione registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    */
    array(
      'field' => 'rea_num',
      'label' => 'Numero R.E.A.',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_rappresentanza',
      'label' => 'Tipo di Rappresentanza',
      'rules' => 'callback_titolare_rappresentanza'
    ),
    array(
      'field' => 'stmt_wl_interest',
      'label' => 'Attività',
      'rules' => 'callback__sake',
    ),
    array(
      'field' => 'white_list_prefettura',
      'label' => 'Nome Prefettura',
      'rules' => 'callback_white_list_prefettura',
    ),
    array(
      'field' => 'interesse_interventi_flag',
      'label' => 'Interventi di immediata riparazione',
      'rules' => 'callback_interesse_interventi_flag',
    ),
    array(
      'field' => 'check_anagrafiche',
      'label' => 'Anagrafiche dei componenti',
      'rules' => 'callback_check_anagrafiche'
    ),
    array(
      'field' => 'check_settori',
      'label' => 'Settori Impresa',
      'rules' => 'callback_check_settori'
    ),
    array(
      'field' => 'check_attivita',
      'label' => 'Attività Impresa',
      'rules' => 'callback_check_attivita'
    ),
    array(
      'field' => 'white_list_data',
      'label' => 'Data iscrizione white list prefettura',
      'rules' => 'callback_white_list_data',
    ),
    array(
      'field' => 'stmt_eligible',
      'label' => 'Nome del dichiarante',
      'rules' => 'callback__stmt_eligible',
    ),
    array(
      'field' => 'istanza_data',
      'label' => 'Data Documento',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'istanza_luogo',
      'label' => 'Luogo Documento',
      'rules' => 'required|max_length[200]',
    ),
  ),
  'domanda_upsert' => array(
    array(
      'field' => 'titolare_nome',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_cognome',
      'label' => 'Cognome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_comune',
      'label' => 'Comune di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_provincia',
      'label' => 'Provincia di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_nascita_data',
      'label' => 'Data nascita dichiarante',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'check_wl',
      'label' => 'Iscrizione alla white list',
      'rules' => 'callback_check_wl'
    ),
    array(
      'field' => 'titolare_res_comune',
      'label' => 'Comune di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_provincia',
      'label' => 'Provincia di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_via',
      'label' => 'Indirizzo di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_civico',
      'label' => 'Numero civico di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_res_cap',
      'label' => 'CAP di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'ragione_sociale',
      'label' => 'Ragione sociale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'impresa_data_costituzione',
      'label' => 'Data costituzione società',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'forma_giuridica_id',
      'label' => 'Forma giuridica dell\'impresa',
      'rules' => 'callback__forma_giuridica_id_check',
    ),
    array(
      'field' => 'sl_comune',
      'label' => 'Comune Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_prov',
      'label' => 'Provincia Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_cap',
      'label' => 'CAP  Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_via',
      'label' => 'Indirizzo Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico',
      'label' => 'Civico Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_telefono',
      'label' => 'Telefono Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'partita_iva',
      'label' => 'Partita IVA Società',
      'rules' => 'required|max_length[16]|min_length[11]',
    ),
    array(
      'field' => 'codice_fiscale',
      'label' => 'Codice Fiscale Società',
      'rules' => 'required|max_length[16]|min_length[11]',
    ),
    /*
    array(
      'field' => 'impresa_email',
      'label'   => 'Email sede legale',
      'rules'   => 'required|valid_email'
    ),
    */
    array(
      'field' => 'impresa_pec',
      'label'   => 'Email Certificata PEC sede legale',
      'rules'   => 'required|valid_email'
    ),
    /*
    array(
      'field' => 'sl_civico_admins',
      'label' => 'Nome del dichiarante',
      'rules' => 'callback__controllo_num_dipendenti',
    ),
    array(
      'field' => 'sl_civico_attorney',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico_majors',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sl_civico_majors_tmp',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    */
    array(
      'field' => 'rea_ufficio',
      'label' => 'Ufficio Registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    /*
    array(
      'field' => 'rea_num_iscrizione',
      'label' => 'Numero di iscrizione registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    */
    array(
      'field' => 'rea_num',
      'label' => 'Numero R.E.A.',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'titolare_rappresentanza',
      'label' => 'Tipo di Rappresentanza',
      'rules' => 'callback__titolare_rappresentanza'
    ),
    array(
      'field' => 'stmt_wl_interest',
      'label' => 'Attività',
      'rules' => 'callback__sake',
    ),
    array(
      'field' => 'white_list_prefettura',
      'label' => 'Nome Prefettura',
      'rules' => 'callback_white_list_prefettura',
    ),
    array(
      'field' => 'interesse_interventi_flag',
      'label' => 'Interventi di immediata riparazione',
      'rules' => 'callback_interesse_interventi_flag',
    ),
    array(
      'field' => 'check_anagrafiche_upsert',
      'label' => 'Anagrafiche dei componenti',
      'rules' => 'callback_check_anagrafiche_upsert'
    ),
    array(
      'field' => 'check_imprese_upsert',
      'label' => 'Imprese Partecipate',
      'rules' => 'callback_check_imprese_upsert'
    ),
    array(
      'field' => 'check_settori',
      'label' => 'Settori Impresa',
      'rules' => 'callback_check_settori'
    ),
    array(
      'field' => 'check_attivita_upsert',
      'label' => 'Attività Impresa',
      'rules' => 'callback_check_attivita_upsert'
    ),
    array(
      'field' => 'white_list_data',
      'label' => 'Data iscrizione white list prefettura',
      'rules' => 'callback_white_list_data',
    ),
    array(
      'field' => 'stmt_eligible',
      'label' => 'Nome del dichiarante',
      'rules' => 'callback__stmt_eligible',
    ),
    array(
      'field' => 'istanza_data',
      'label' => 'Data Documento',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'istanza_luogo',
      'label' => 'Luogo Documento',
      'rules' => 'required|max_length[200]',
    ),
  ),
  'utente' => array(
    array(
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required|min_length[8]',
    ),
  ),
);
