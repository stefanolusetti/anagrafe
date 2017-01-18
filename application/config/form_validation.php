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
      'label' => 'Nome del dichiarante',
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
      'field' => 'residence_city',
      'label' => 'Città di residenza del dichiarante',
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
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_type_more',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_locality',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_province',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_zip',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_street',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_num',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_phone',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_mobile',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_fax',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_vat',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_offices',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_cf',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_mail',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_pec',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_num_admins',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
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
    array(
      'field' => 'rea_location',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'rea_subscription',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'rea_number',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'company_social_subject',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_work_flag',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_work_type',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_work_amount',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_service_flag',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_service_type',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_service_amount',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_supply_type',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_supply_amount',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_fix_type',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'sake_fix_amount',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'stmt_wl',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'stmt_wl_name',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'stmt_wl_date',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'stmt_eligible',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'doc_date',
      'label' => 'Nome del dichiarante',
      'rules' => 'required|max_length[200]',
    ),
    array(
      'field' => 'doc_location',
      'label' => 'Nome del dichiarante',
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
