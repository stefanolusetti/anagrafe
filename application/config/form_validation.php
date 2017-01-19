<?php

/*
* regole di validazione della form
*/

$config = array(
  'domanda' => array(
    array(
      'field' => 'name',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'lastname',
      'label' => 'Cognome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'birth_locality',
      'label' => 'Comune di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'birth_province',
      'label' => 'Provincia di nascita del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'birth_date',
      'label' => 'Data nascita dichiarante',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'residence_locality',
      'label' => 'Comune di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'residence_province',
      'label' => 'Provincia di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'residence_street',
      'label' => 'Indirizzo di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'residence_num',
      'label' => 'Numero civico di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'residence_zip',
      'label' => 'CAP di residenza del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_name',
      'label' => 'Ragione sociale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_birthdate',
      'label' => 'Data costituzione società',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'company_shape',
      'label' => 'Forma giuridica dell\'impresa',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_type_more',
      'label' => 'Altra forma giuridica',
      'rules' => 'callback__company_type_more',
    ),
    array(
      'field' => 'company_locality',
      'label' => 'Comune Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_province',
      'label' => 'Provincia Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_zip',
      'label' => 'CAP  Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_street',
      'label' => 'Indirizzo Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_num',
      'label' => 'Civico Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_phone',
      'label' => 'Telefono Sede Legale',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_vat',
      'label' => 'Partita IVA Società',
      'rules' => 'required|exact_length[11]',
    ),
    array(
      'field' => 'company_cf',
      'label' => 'Codice Fiscale Società',
      'rules' => 'required|max_length[16]|min_length[11]',
    ),
    array(
      'field' => 'company_mail',
      'label'   => 'Email sede legale',
      'rules'   => 'required|valid_email'
    ),
    array(
      'field' => 'company_pec',
      'label'   => 'Email Certificata PEC sede legale',
      'rules'   => 'required|valid_email'
    ),
    /*
    array(
      'field' => 'company_num_admins',
      'label' => 'Nome del dichiarante',
      'rules' => 'callback__controllo_num_dipendenti',
    ),
    array(
      'field' => 'company_num_attorney',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_num_majors',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_num_majors_tmp',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    */
    array(
      'field' => 'rea_location',
      'label' => 'Ufficio Registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'rea_subscription',
      'label' => 'Numero di iscrizione registro delle imprese',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'rea_number',
      'label' => 'Numero R.E.A.',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'stmt_wl_interest',
      'label' => 'Attività',
      'rules' => 'callback__sake',
    ),
    array(
      'field' => 'stmt_wl_name',
      'label' => 'Nome Prefettura',
      'rules' => 'max_length[200]',
    ),
    array(
      'field' => 'stmt_wl_date',
      'label' => 'Data iscrizione white list prefettura',
      'rules' => 'callback__controlla_data_opzionale',
    ),
    array(
      'field' => 'stmt_eligible',
      'label' => 'Nome del dichiarante',
      'rules' => 'required',
    ),
    array(
      'field' => 'doc_date',
      'label' => 'Data Documento',
      'rules' => 'callback__controlla_data',
    ),
    array(
      'field' => 'doc_location',
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
