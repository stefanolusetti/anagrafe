<?php
echo form_open('domanda/nuova');
?>

  <h2>Anagrafica del richiedente</h2>
  <?php
/*
  ██████  ██ ██████  ████████ ██   ██
  ██   ██ ██ ██   ██    ██    ██   ██
  ██████  ██ ██████     ██    ███████
  ██   ██ ██ ██   ██    ██    ██   ██
  ██████  ██ ██   ██    ██    ██   ██
  name
  lastname
  birth_date
  birth_province
  birth_city
*/

  f_text('name', 'Nome*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('lastname', 'Cognome*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('birth_locality', 'Comune di nascita*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_select('birth_province', 'Provincia di nascita*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('birth_nation', 'Nazione di nascita (se diversa da Italia)');

  f_text('birth_date', 'Data di nascita (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'required data'))
  );

/*
  ██████  ███████ ███████ ██ ██████  ███████ ███    ██  ██████ ███████
  ██   ██ ██      ██      ██ ██   ██ ██      ████   ██ ██      ██
  ██████  █████   ███████ ██ ██   ██ █████   ██ ██  ██ ██      █████
  ██   ██ ██           ██ ██ ██   ██ ██      ██  ██ ██ ██      ██
  ██   ██ ███████ ███████ ██ ██████  ███████ ██   ████  ██████ ███████
  residence_locality
  residence_province
  residence_city
  residence_street
  residence_zip
  residence_num
*/
  f_text('residence_locality', 'Comune di residenza*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_select('residence_province', 'Provincia di residenza*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );
  /*
  f_text('residence_city', 'Città di residenza*',
    array('input' => array('class' => 'required maxlen'))
  );
  */
  f_text('residence_street', 'Via/Piazza*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_text('residence_num', 'Numero civico*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_text('residence_zip', 'CAP*',
    array('input' => array('class' => 'required maxlen'))
  );
  ?>
  <h2>Anagrafica dell'azienda</h2>
  <?php
/*
  ██████  ██████  ███    ███ ██████   █████  ███    ██ ██    ██      ██
  ██      ██    ██ ████  ████ ██   ██ ██   ██ ████   ██  ██  ██      ██
  ██      ██    ██ ██ ████ ██ ██████  ███████ ██ ██  ██   ████       ██
  ██      ██    ██ ██  ██  ██ ██      ██   ██ ██  ██ ██    ██       ██
  ██████  ██████  ██      ██ ██      ██   ██ ██   ████    ██        ██
   company_role
   company_name
   company_birthdate
   company_shape // Forma giuridica...
   company_type
   company_type_more // campo di testo opzionale
   company_num_admins
   company_num_attorney
   company_num_majors
   company_num_majors_tmp
   rea_location
   rea_subscription
   rea_number
*/

  f_select(
    'company_role',
    'Tipo di rappresentanza',
    array(
      'Legale rappresentante' => 'Legale rappresentante',
      'Titolare' => 'Titolare',
      'Altro' => 'Altro'
    )
  );


  echo '<div id="company_role_more" class="' . $company_role_more_class . '">';
  f_text(
    'ruolo_richiedente',
    'se selezionato <b>Altro</b> specificare:',
    array('field' => array('class' => 'indent'))
  );
  echo '</div>';


  f_text('company_name', 'Ragione sociale*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_vat',
    'Partita IVA*',
    array('input' => array('class' => 'required piva'))
  );
  f_text(
    'company_cf',
    'Codice Fiscale*',
    array('input' => array('class' => 'required cf'))
  );


  $shapes = opzioni_company_shape();

  //array_unshift($shapes, ' - - - SELEZIONARE - - - ');
  // Inject 'Altro' value.
  $shapes['Altro'] = 'Altro';
  f_select(
    'company_shape',
    "Forma giuridica dell'impresa",
    $shapes, $choosen_shape);

  /*
  f_select(
    'company_type',
    'Tipo società',
    array(
      'Società per azioni' => 'Società per azioni',
      'Società in accomandita per azioni' => 'Società in accomandita per azioni',
      'Società a responsabilità limitata' => 'Società a responsabilità limitata',
      'Società cooperativa' => 'Società cooperativa',
      'Società semplice' => 'Società semplice',
      'Società in nome collettivo' => 'Società in nome collettivo',
      'Società in accomandita semplice' => 'Società in accomandita semplice',
      'Società consortile' => 'Società consortile',
      'Impresa individuale' => 'Impresa individuale',
      'Altro' => 'Altro'
    ),
    array('input' => array('class' => 'required'))
  );
  */
  if($show_other_shape){
    echo '<div class="controlla_tipo_impresa">';
  }
  else {
    echo '<div class="hidden controlla_tipo_impresa">';
  }
  f_text(
    'company_type_more',
    'se selezionato <b>Altro</b> specificare:',
    array('field' => array('class' => 'indent'))
  );
  echo '</div>';
  ?>
  <h4>Sede legale</h4>
  <?php
/*
   ██████  ██████  ███    ███ ██████   █████  ███    ██ ██    ██     ██████
  ██      ██    ██ ████  ████ ██   ██ ██   ██ ████   ██  ██  ██           ██
  ██      ██    ██ ██ ████ ██ ██████  ███████ ██ ██  ██   ████        █████
  ██      ██    ██ ██  ██  ██ ██      ██   ██ ██  ██ ██    ██        ██
   ██████  ██████  ██      ██ ██      ██   ██ ██   ████    ██        ███████
  company_locality
  company_province
  company_street
  company_zip
  company_num
  company_phone
  company_mobile
  company_fax
  company_cf
  company_vat
  company_mail
  company_pec
*/

  f_text(
    'company_locality',
    'Comune*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_select(
    'company_province',
    'Provincia*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_zip',
    'CAP*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_street',
    'Via*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_num',
    'Civico*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_phone',
    'Telefono*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('company_mobile', 'Tel. mobile');
  f_text('company_fax', 'Fax');



  f_textarea(
    'company_altre_sedi',
    'Sedi secondarie e Unità Locali',
    array('input' => array('style' => 'width: 100%', 'rows' => 3))
  );



  f_text(
    'company_mail',
    'Casella e-mail*',
    array('input' => array('class' => 'required email'))
  );

  f_text(
    'company_pec',
    "Casella PEC (inserire email valida per l'invio del modulo da firmare digitalmente)*",
    array('input' => array('class' => 'required email'))
  );

  echo '<h4>Iscrizione nel Registro delle Imprese presso la C.C.I.A.A.</h4>';
  f_text('rea_location', 'Registro delle Imprese *', array('input' => array('class' => 'required maxlen')));
  f_text('rea_subscription', 'Numero di iscrizione*', array('input' => array('class' => 'required maxlen')));
  f_text('rea_number', 'Numero di R.E.A.*', array('input' => array('class' => 'required maxlen')));
  f_text(
    'company_birthdate',
    'Data costituzione società (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'data'))
  );
  f_textarea(
    'company_social_subject',
    'Oggetto Sociale',
    array('input' => array('style' => 'width:100%', 'rows' => 4))
  );
?>
<h4>L'impresa opera in uno dei seguenti settori:</h4>
<?php
f_checkbox('company_field_trasporto', 'Trasporto di materiali a discarica conto terzi', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_rifiuti', 'Trasporto e smaltimento di rifiuti', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_terra', 'Estrazione, fornitura e trasporto di terra e materiali inerti', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_bitume', 'Confezionamento, fornitura e trasporto di calcestruzzo e di bitume', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_nolo', 'Noli a freddo e a caldo di macchinari', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_ferro', 'Fornitura di ferro lavorato', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_autotrasporto', 'Autotrasporto conto terzi', array('input' => array('class' => 'company_fields')));
f_checkbox('company_field_guardiana', 'Guardiania dei cantieri', array('input' => array('class' => 'company_fields')));

f_checkbox('company_field_none', 'Nessuna delle precedenti');
?>
<h2>Partecipazioni (anche minoritarie) in altre imprese o società (anche fiduciarie)</h2>
<a href="#" class="add addOffice">Aggiungi Impresa Partecipata</a>
<div class="offices">
<?php
if (isset($offices) AND !empty($offices)) {
  foreach ($offices as $key => $office) {
    echo '<div class="office container" id="ofel-0">';
    f_text(
      'office[' . $key . '][name]',
      'Ragione Sociale impresa partecipata <a href="#" data-elid="0" class="removeOffice" data-victim="ofel-0">Rimuovi Impresa</a>',
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      'office[' . $key . '][vat]',
      'Partita IVA impresa partecipata',
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      'office[' . $key . '][cf]',
      'Codice Fiscale impresa partecipata',
      array('input' => array('class' => 'required maxlen'))
    );
    echo '<div class="resizer"></div></div>';
  }
}
?>
</div>
<?php
  echo '<h4>Consiglio di amministrazione</h4>';
  f_text(
    'company_num_admins',
    'Numero componenti in carica',
    array('input' => array())
  );

  echo '<h4>Procuratori e Procuratori Speciali</h4>';
  f_text(
    'company_num_attorney',
    'Numero componenti in carica',
    array('input' => array())
  );

  echo '<h4>Collegio Sindacale</h4>';
  f_text(
    'company_num_majors',
    'Numero sindaci effettivi',
    array('input' => array())
  );
  f_text(
    'company_num_majors_tmp',
    'Numero sindaci supplenti',
    array('input' => array())
  );



/*
   █████  ████████ ████████ ██ ██    ██ ██ ████████  █████
  ██   ██    ██       ██    ██ ██    ██ ██    ██    ██   ██
  ███████    ██       ██    ██ ██    ██ ██    ██    ███████
  ██   ██    ██       ██    ██  ██  ██  ██    ██    ██   ██
  ██   ██    ██       ██    ██   ████   ██    ██    ██   ██
*/
  echo '<h2>Interessato allo svolgimento delle seguenti attività (è necessario selezionare almeno una voce)</h2>';
  echo f_hidden('stmt_wl_interest');
  // Work
  echo '<div id="stmt_wl_interest_more" class="">';
  f_checkbox(
    'sake_work_flag',
    '<b>Lavori</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_work_wrapper'))
  );
  echo '<div id="sake_work_wrapper" class="' . $sake_work_type_class . '">';
  f_text(
    'sake_work_type',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'sake_work_amount',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Service
  f_checkbox(
    'sake_service_flag',
    '<b>Servizi</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_service_wrapper'))
  );
  echo '<div id="sake_service_wrapper" class="' . $sake_service_type_class . '">';
  f_text(
    'sake_service_type',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'sake_service_amount',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Supply
  f_checkbox(
    'sake_supply_flag',
    '<b>Forniture</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_supply_wrapper'))
  );
  echo '<div id="sake_supply_wrapper" class="' . $sake_supply_type_class . '">';
  f_text(
    'sake_supply_type',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'sake_supply_amount',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Fix
  f_checkbox(
    'sake_fix_flag',
    '<b>Interventi di immediata riparazione ex art.8, commi 1 e 5 del decreto legge n.189/2016</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_fix_wrapper'))
  );
  echo '<div id="sake_fix_wrapper" class="' . $sake_fix_type_class . '">';
  f_text(
    'sake_fix_type',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'sake_fix_amount',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';
  /*
  ██████
       ██
    ▄███
    ▀▀
    ██
  */
  echo '</div>';
  ?>



    <div class="field_antimafia">
  <h2> Anagrafiche dei componenti<br><br>
  Inserire i dati richiesti per tutti i soggetti previsti dal DLgs. n. 159/2011 art.85 e ss.mm.ii.</h2>


<div class="container">
    <a name="anagrafiche"></a>
    <a href="#anagrafiche" class="add addAnagrafica">Aggiungi anagrafica</a> | <a href="#" class="reset resetAnagrafiche">Cancella tutto</a><br>
    <div class="inputs"><?php
/*
 █████  ███    ██  █████   ██████  ██████   █████  ███████ ██  ██████ ██   ██ ███████
██   ██ ████   ██ ██   ██ ██       ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
███████ ██ ██  ██ ███████ ██   ███ ██████  ███████ █████   ██ ██      ███████ █████
██   ██ ██  ██ ██ ██   ██ ██    ██ ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
██   ██ ██   ████ ██   ██  ██████  ██   ██ ██   ██ ██      ██  ██████ ██   ██ ███████
*/

if (isset($anagrafiche) AND !empty($anagrafiche)) {
  $a_head = '<div id="anel-!!!" class="anagrafica anagrafica-box el-!!!" data-elid="!!!">
    <h2>Anagrafica del componente</h2>';
  $a_foot = '<a href="#anagrafiche" class="addFamiliar" data-elid="!!!">Aggiungi Componente Familiare</a> | <a href="#anagrafiche" class="removeFamiliar" data-victim="anel-!!!" data-elid="!!!">Rimuovi Anagrafica</a>
  <div class="familiars" data-elid="!!!">';
  $f_head = '<div class="box-familiare" id="fam-###-@@@"><hr />
    <a href="#anagrafiche" class="removeThisFamiliar" data-victim="fam-###-@@@" data-elid="###" data-elfid="@@@">Rimuovi Componente Familiare</a>';
  $f_foot = '<div class="resizer"></div></div>';

  $options_key_users = opzioni_ruoli_anagrafiche(0);
  $options_fam_users = opzioni_ruoli_anagrafiche(1);

  foreach ($anagrafiche AS $i => $anagrafica) {
    echo str_replace(
      array('!!!'),
      array($i),
      $a_head
    );
    $prefix = 'anagrafica[' . $i . '][';

    f_select(
      $prefix . 'antimafia_role]',
      'Ruolo*',
      $options_key_users,
      array('input' => array('class' => 'required'))
    );
    f_text(
      $prefix . 'antimafia_nome]',
      'Nome*',
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      $prefix . 'antimafia_cognome]',
      'Cognome*',
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      $prefix . 'antimafia_data_nascita]',
      'Data di nascita (nel formato gg/mm/aaaa)*',
      array('input' => array('class' => 'required data'))
    );
    f_text(
      $prefix . 'antimafia_comune_nascita]',
      'Comune di nascita*',
      array('input' => array('class' => 'required maxlen'))
    );
    f_select(
      $prefix . 'antimafia_provincia_nascita]',
      'Provincia di nascita*',
      opzioni_provincie(),
      array('input' => array('class' => 'required maxlen'))
    );

    f_text(
      $prefix . 'antimafia_cf]',
      'Codice Fiscale*',
      array('input' => array('class' => 'required cfp'))
    );

    f_text(
      $prefix . 'antimafia_comune_residenza]',
      'Comune Residenza',
      array('input' => array('class' => ''))
    );
    f_select(
      $prefix . 'antimafia_provincia_residenza]',
      'Provincia di residenza*',
      opzioni_provincie(),
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      $prefix . 'antimafia_via_residenza]',
      'Via Residenza',
      array('input' => array('class' => ''))
    );
    f_text(
      $prefix . 'antimafia_civico_residenza]',
      'Civico Rediernza',
      array('input' => array('class' => ''))
    );

    echo str_replace(
      array('!!!'),
      array($i),
      $a_foot
    );
    if(isset($anagrafica['f']) && !empty($anagrafica['f'])){
      foreach($anagrafica['f'] AS $fi => $fam){
        $fam_prefix = 'anagrafica['.$i.'][f]['.$fi.'][';
        echo str_replace(
          array('###', '@@@'),
          array($i, $fi),
          $f_head
        );

        f_select(
          $fam_prefix . 'role]',
          'Ruolo*',
          $options_fam_users,
          array('input' => array('class' => 'required'))
        );
        f_text(
          $fam_prefix . 'name]',
          'Nome*',
          array('input' => array('class' => 'required maxlen'))
        );
        f_text(
          $fam_prefix . 'lastname]',
          'Cognome*',
          array('input' => array('class' => 'required maxlen'))
        );
        f_text(
          $fam_prefix . 'data_nascita]',
          'Data di nascita (nel formato gg/mm/aaaa)*',
          array('input' => array('class' => 'required data'))
        );
        f_text(
          $fam_prefix . 'comune]',
          'Comune di nascita*',
          array('input' => array('class' => 'required maxlen'))
        );

        f_text(
          $fam_prefix . 'cf]',
          'Codice Fiscale*',
          array('input' => array('class' => 'required cfp'))
        );
        echo str_replace(
          array('###', '@@@'),
          array($i, $fi),
          $f_foot
        );
      }
    }
    // familiari
    echo '</div></div>';
  }
}
?></div>
  </div>
  </div>


  <h2>Dichiara*</h2>
  <?php
/*
  ██████  ██  ██████ ██   ██ ██  █████  ██████   █████  ███████ ██  ██████  ███    ██ ██
  ██   ██ ██ ██      ██   ██ ██ ██   ██ ██   ██ ██   ██    ███  ██ ██    ██ ████   ██ ██
  ██   ██ ██ ██      ███████ ██ ███████ ██████  ███████   ███   ██ ██    ██ ██ ██  ██ ██
  ██   ██ ██ ██      ██   ██ ██ ██   ██ ██   ██ ██   ██  ███    ██ ██    ██ ██  ██ ██ ██
  ██████  ██  ██████ ██   ██ ██ ██   ██ ██   ██ ██   ██ ███████ ██  ██████  ██   ████ ██
  stmt_wl
  stmt_wl_name
  stmt_wl_date
  stmt_wl_interest
  sake_work
  sake_work_type
  sake_work_amount
  sake_service
  sake_service_type
  sake_service_amount
  sake_supply
  sake_supply_type
  sake_supply_amount
  sake_fix
  sake_fix_type
  sake_fix_amount
  stmt_eligible
*/



  echo '<div class="checkbox field "><label for="stmt_wl_si">di essere iscritto alle white list della Prefettura</label><input class="required" type="radio" name="stmt_wl" value="Yes" id="stmt_wl_si">
</div><div class="checkbox field "><label for="stmt_wl_no">di <b>NON</b> essere iscritto alle white list della Prefettura</label><input class="required" type="radio" name="stmt_wl" value="No" id="stmt_wl_no">
</div>';

  echo '<div id="stmt_wl_more" class="'.$stmt_wl_class.'">';
  f_text(
    'stmt_wl_name',
    'Prefettura di*',
    array('input' => array('class' => 'maxlen required'))
  );
  f_text(
    'stmt_wl_date',
    'Data iscrizione (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'data required'))
  );
  echo '</div>';


  f_checkbox(
    'stmt_eligible',
    'Dichiara che nei propri confronti non sussistono le cause di divieto, di decadenza o di sospensione di cui all\'art.67 del D.Lgs. 06/09/2011, n.159',
    array('input' => array('class' => 'required'))
  );








  /*
  f_checkbox('noprotesti_sino', 'di non aver subito protesti cambiari e/o di assegni nell’ultimo quinquennio (ai sensi degli artt. 68-73 legge cambiaria);',
        array('input' => array('class' => 'required')));
  f_checkbox('antimafia_sino', 'che nei propri confronti non sussistono le cause di decadenza, di sospensione o di divieto di cui all’art. 67 del D.Lgs. 6 settembre 2011, n. 159);',
        array('input' => array('class' => 'required')));
  f_checkbox('ateco_sino', 'di essere in possesso dei Codici Identificativi Ateco relativi alla lettera F e/o R, di cui alla “Tabella dei titoli a sei cifre della classificazione delle attività economiche Ateco 2007”, pubblicati nel sito dell’ISTAT',
        array('input' => array('class' => 'required')));

  ?>
  <h2>Si impegna*</h2>
  <?php
  f_checkbox('sopralluoghi_sino', 'a garantire, durante l’esecuzione dei lavori, l’accesso e lo svolgimento dei sopralluoghi da parte degli organismi paritetici di settore presenti sul territorio ove si svolgono i lavori stessi, ai sensi dell’art. 51, comma 6 del D.lgs. 81/2008 e s.m.i. e dell’art. 13, comma 2 della L.R. 11/2010;',
        array('input' => array('class' => 'required')));
  f_checkbox('sico_sino', 'a trasmettere la notifica preliminare agli enti competenti tramite il sistema informatico SICO ai sensi dell’allegato 1) parte integrante alla deliberazione di Giunta regionale n.  637/2011.',
        array('input' => array('class' => 'required')));
  f_checkbox('iscrizionecassa_sino', 'al rispetto, fermo restando gli accordi posti in essere nella regione Emilia-Romagna, degli accordi territoriali ed in particolare all’obbligo dell’iscrizione alla Cassa Edile territorialmente competente rispetto all’ubicazione del cantieri',
        array('input' => array('class' => 'required')));
  f_checkbox('ccnledilizia_sino', 'ad applicare, per le lavorazioni previste nell’ambito della sfera di applicazione del CCNL dell’edilizia, integralmente la contrattazione collettiva dell’edilizia',
        array('input' => array('class' => 'required')));
  f_checkbox('ccnlaltro_sino', 'ad applicare, per le lavorazioni non comprese nell\'ambito della sfera dell’edilizia, il CCNL corrispondente, siglato dalle organizzazioni sindacali confederali maggiormente rappresentative sul piano nazionale',
        array('input' => array('class' => 'required')));
        */
  ?>


  <h4>Informativa privacy</h4>
  <div class="field fixed">
    <?php $this->load->view('pages/privacy'); ?>
  </div>
  <?php
  f_text('doc_date', 'Data*', array('input' => array('class' => 'required data')));
  f_text('doc_location', 'Luogo*', array('input' => array('class' => 'required')));
  ?>


  <div class="submit">
    <input type="submit" name="submit" value="Genera richiesta" />
  </div>

</form>
<?php
$provincie_options = opzioni_provincie();
$key_roles_options = opzioni_ruoli_anagrafiche(0);
$fam_roles_options = opzioni_ruoli_anagrafiche(1);

echo '<script type="text/javascript">';
printf("window.%s = %s;\n", '_provincie', json_encode($provincie_options));
printf("window.%s = %s;\n", '_key_roles', json_encode($key_roles_options));
printf("window.%s = %s;\n", '_fam_roles', json_encode($fam_roles_options));
echo '</script>';
