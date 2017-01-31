<h3><a href="/domanda/help" target="_blank">Istruzioni per la compilazione della domanda di iscrizione all'Anagrafe Antimafia degli Esecutori</a></h3>
<?php
echo form_open('domanda/nuova', array('target' => '_blank')); //@debug
?>

  <h2>Anagrafica del richiedente</h2>
  <?php
/*
  ██████  ██ ██████  ████████ ██   ██
  ██   ██ ██ ██   ██    ██    ██   ██
  ██████  ██ ██████     ██    ███████
  ██   ██ ██ ██   ██    ██    ██   ██
  ██████  ██ ██   ██    ██    ██   ██
  titolare_nome
  titolare_cognome
  titolare_nascita_data
  titolare_nascita_provincia
  birth_city
*/

  f_text('titolare_nome', 'Nome*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('titolare_cognome', 'Cognome*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('titolare_nascita_comune', 'Comune di nascita*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_select('titolare_nascita_provincia', 'Provincia di nascita*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('titolare_nascita_nazione', 'Nazione di nascita (se diversa da Italia)');

  f_text('titolare_nascita_data', 'Data di nascita (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'required data'))
  );

/*
  ██████  ███████ ███████ ██ ██████  ███████ ███    ██  ██████ ███████
  ██   ██ ██      ██      ██ ██   ██ ██      ████   ██ ██      ██
  ██████  █████   ███████ ██ ██   ██ █████   ██ ██  ██ ██      █████
  ██   ██ ██           ██ ██ ██   ██ ██      ██  ██ ██ ██      ██
  ██   ██ ███████ ███████ ██ ██████  ███████ ██   ████  ██████ ███████
  titolare_res_comune
  titolare_res_provincia
  residence_city
  titolare_res_via
  titolare_res_cap
  titolare_res_civico
*/
  f_text('titolare_res_comune', 'Comune di residenza*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_select('titolare_res_provincia', 'Provincia di residenza*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );
  /*
  f_text('residence_city', 'Città di residenza*',
    array('input' => array('class' => 'required maxlen'))
  );
  */
  f_text('titolare_res_via', 'Via/Piazza*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_text('titolare_res_civico', 'Numero civico*',
    array('input' => array('class' => 'required maxlen'))
  );
  f_text('titolare_res_cap', 'CAP*',
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
   titolare_rappresentanza
   ragione_sociale
   impresa_data_costituzione
   forma_giuridica_id // Forma giuridica...
   company_typeAAAAAAAAAAAAAAA
   impresa_forma_giuridica_altro // campo di testo opzionale
   impresa_num_amministratori
   impresa_num_procuratori
   impresa_num_sindaci
   impresa_num_sindaci_tmp
   rea_ufficio
   rea_num_iscrizione
   rea_num
*/

  f_select(
    'titolare_rappresentanza',
    'Tipo di rappresentanza',
    array(
      'Legale rappresentante' => 'Legale rappresentante',
      'Titolare' => 'Titolare',
      'Altro' => 'Altro'
    )
  );


  echo '<div id="titolare_rappresentanza_more" class="' . $titolare_rappresentanza_more_class . '">';
  f_text(
    'ruolo_richiedente',
    'se selezionato <b>Altro</b> specificare:',
    array(
      'field' => array('class' => 'indent'),
      'input' => array('class' => '_titolare_rappresentanza')
    )
  );
  echo '</div>';


  f_text('ragione_sociale', 'Ragione sociale*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'partita_iva',
    'Partita IVA*',
    array('input' => array('class' => '_piva_unique'))
  );
  f_text(
    'codice_fiscale',
    'Codice Fiscale*',
    array('input' => array('class' => 'required cf'))
  );


  $shapes = opzioni_forma_giuridica_id();

  //array_unshift($shapes, ' - - - SELEZIONARE - - - ');
  // Inject 'Altro' value.
  $shapes['0'] = 'Altro';
  f_select(
    'forma_giuridica_id',
    "Forma giuridica dell'impresa*",
    $shapes, array('input' => array('class' => 'required')));
  /*
  f_select(
    'company_typeAAAAAAAAAAAAAAA',
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
    'impresa_forma_giuridica_altro',
    'se selezionato <b>Altro</b> specificare:',
    array('field' => array('class' => 'indent'), 'input' => array('class' => '_impresa_forma_giuridica_altro'))
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
  sl_comune
  sl_prov
  sl_via
  sl_cap
  sl_civico
  sl_telefono
  sl_mobile
  sl_fax
  codice_fiscale
  partita_iva
  impresa_email
  impresa_pec
*/

  f_text(
    'sl_comune',
    'Comune*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_select(
    'sl_prov',
    'Provincia*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'sl_cap',
    'CAP*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'sl_via',
    'Via*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'sl_civico',
    'Civico*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'sl_telefono',
    'Telefono*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text('sl_mobile', 'Tel. mobile');
  f_text('sl_fax', 'Fax');



  f_textarea(
    'impresa_altre_sedi',
    'Sedi secondarie e Unità Locali',
    array('input' => array('style' => 'width: 100%', 'rows' => 3))
  );


/*
  f_text(
    'impresa_email',
    'Casella e-mail*',
    array('input' => array('class' => 'required email'))
  );
*/
  f_text(
    'impresa_pec',
    "Casella PEC*",
    array('input' => array('class' => 'required email'))
  );

  echo '<h4>Iscrizione nel Registro delle Imprese presso la C.C.I.A.A.</h4>';
  f_text('rea_ufficio', 'Registro delle Imprese di *', array('input' => array('class' => 'required maxlen')));
  //f_text('rea_num_iscrizione', 'Numero di iscrizione', array('input' => array('class' => 'required maxlen')));
  f_text('rea_num', 'Numero di R.E.A.*', array('input' => array('class' => 'required maxlen')));
  f_text(
    'impresa_data_costituzione',
    'Data costituzione società (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'data'))
  );
  f_textarea(
    'impresa_soggetto_sociale',
    'Oggetto Sociale',
    array('input' => array('style' => 'width:100%', 'rows' => 4))
  );
?>
<h4>L'impresa opera in uno dei seguenti settori:</h4>
<?php
f_checkbox(
  'check_settori',
  'Settori',
  array('input' => array('class' => 'hidden'), 'field' => array('class' => 'labelhidden'))
);
f_checkbox('impresa_settore_trasporto', 'Trasporto di materiali a discarica conto terzi', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_rifiuti', 'Trasporto e smaltimento di rifiuti', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_terra', 'Estrazione, fornitura e trasporto di terra e materiali inerti', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_bitume', 'Confezionamento, fornitura e trasporto di calcestruzzo e di bitume', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_nolo', 'Noli a freddo e a caldo di macchinari', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_ferro', 'Fornitura di ferro lavorato', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_autotrasporto', 'Autotrasporto conto terzi', array('input' => array('class' => 'company_fields')));
f_checkbox('impresa_settore_guardiana', 'Guardiania dei cantieri', array('input' => array('class' => 'company_fields')));

f_checkbox('impresa_settore_nessuno', 'Nessuna delle precedenti', array('input' => array('class' => '_thefields')));
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
      'Partita IVA impresa partecipata*',
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      'office[' . $key . '][cf]',
      'Codice Fiscale impresa partecipata*',
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
    'impresa_num_amministratori',
    'Numero componenti in carica',
    array('input' => array())
  );

  echo '<h4>Procuratori e Procuratori Speciali</h4>';
  f_text(
    'impresa_num_procuratori',
    'Numero componenti in carica',
    array('input' => array())
  );

  echo '<h4>Collegio Sindacale</h4>';
  f_text(
    'impresa_num_sindaci',
    'Numero sindaci effettivi',
    array('input' => array())
  );
  f_text(
    'impresa_num_sindaci_tmp',
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
  f_checkbox(
    'check_attivita',
    'Attività',
    array('input' => array('class' => 'hidden'), 'field' => array('class' => 'labelhidden'))
  );
  echo f_hidden('stmt_wl_interest');
  // Work
  echo '<div id="stmt_wl_interest_more" class="">';
  f_checkbox(
    'interesse_lavori_flag',
    '<b>Lavori</b>',
    array('input' => array('class' => 'elToggler _interessi', 'data-el' => 'interesse_lavori_wrapper'))
  );
  echo '<div id="interesse_lavori_wrapper" class="' . $interesse_lavori_tipo_class . '">';
  f_text(
    'interesse_lavori_tipo',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'interesse_lavori_importo',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Service
  f_checkbox(
    'interesse_servizi_flag',
    '<b>Servizi</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'interesse_servizi_wrapper'))
  );
  echo '<div id="interesse_servizi_wrapper" class="' . $interesse_servizi_tipo_class . '">';
  f_text(
    'interesse_servizi_tipo',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'interesse_servizi_importo',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Supply
  f_checkbox(
    'interesse_forniture_flag',
    '<b>Forniture</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'interesse_forniture_wrapper'))
  );
  echo '<div id="interesse_forniture_wrapper" class="' . $interesse_forniture_tipo_class . '">';
  f_text(
    'interesse_forniture_tipo',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'interesse_forniture_importo',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  echo '</div>';

  // Fix
  f_checkbox(
    'interesse_interventi_flag',
    '<b>Interventi di immediata riparazione ex art.8, commi 1 e 5 del decreto legge n.189/2016</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'interesse_interventi_wrapper'))
  );
  echo '<div id="interesse_interventi_wrapper" class="' . $interesse_interventi_tipo_class . '">';
  f_text(
    'interesse_interventi_tipo',
    'Tipologia',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_text(
    'interesse_interventi_importo',
    'Importo',
    array('input' => array('class' => 'maxlen'), 'field' => array('class' => 'w50'))
  );
  f_checkbox(
    'interesse_interventi_checkbox',
    'Dichiara che nei propri confronti e nei confronti di tutti I soggetti di cui all’art 85. del decreto legislativo n. 159/2011 non sussistono le cause di divieto, di decadenza o di sospensione di cui all\'art.67 del D.Lgs. 06/09/2011, n.159',
    array('input' => array('class' => 'stmt__eligible'))
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
<?php
f_checkbox(
  'check_anagrafiche',
  'Anagrafiche',
  array('input' => array('class' => 'hidden'), 'field' => array('class' => 'labelhidden'))
);
?>
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
      'Comune Residenza*',
      array('input' => array('class' => 'required'))
    );
    f_select(
      $prefix . 'antimafia_provincia_residenza]',
      'Provincia di residenza*',
      opzioni_provincie(),
      array('input' => array('class' => 'required maxlen'))
    );
    f_text(
      $prefix . 'antimafia_via_residenza]',
      'Via Residenza*',
      array('input' => array('class' => 'required'))
    );
    f_text(
      $prefix . 'antimafia_civico_residenza]',
      'Civico Residenza*',
      array('input' => array('class' => 'required'))
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
          array('input' => array('class' => 'required'), 'field' => array('class' => 'hidden'))
        );
        f_text(
          $fam_prefix . 'name]',
          'Nome*',
          array('input' => array('class' => 'required maxlen'))
        );
        f_text(
          $fam_prefix . 'titolare_cognome]',
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
        f_select(
          $fam_prefix . 'provincia]',
          'Provincia di nascita*',
          opzioni_provincie(),
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
  white_list_prefettura
  white_list_data
  stmt_wl_interest
  interesse_lavori
  interesse_lavori_tipo
  interesse_lavori_importo
  interesse_servizi
  interesse_servizi_tipo
  interesse_servizi_importo
  interesse_forniture
  interesse_forniture_tipo
  interesse_forniture_importo
  interesse_interventi
  interesse_interventi_tipo
  interesse_interventi_importo
  interesse_interventi_checkbox
*/


  echo '<div class="checkbox field "><label for="stmt_wl_si">di essere iscritto alle white list della Prefettura</label><input ' . $stmt_wl_si_checked . ' class="required" type="radio" name="stmt_wl" value="Yes" id="stmt_wl_si">
</div><div class="checkbox field "><label for="stmt_wl_no">di <b>NON</b> essere iscritto alle white list della Prefettura</label><input ' . $stmt_wl_no_checked . ' class="required" type="radio" name="stmt_wl" value="No" id="stmt_wl_no">
</div>';

  echo '<div id="stmt_wl_more" class="'.$stmt_wl_class.'">';
  f_text(
    'white_list_prefettura',
    'Prefettura di*',
    array('input' => array('class' => 'maxlen required'))
  );
  f_text(
    'white_list_data',
    'Data iscrizione (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'data required'))
  );
  echo '</div>';

  ?>
  <h4>Informativa privacy</h4>
  <div class="field fixed">
    <?php $this->load->view('pages/privacy'); ?>
  </div>
  <?php
  f_text_custom_value(
    'istanza_data',
    'Data*',
    $istanza_data_default,
    array('input' => array('class' => 'required data'))
  );
  f_text('istanza_luogo', 'Luogo*', array('input' => array('class' => 'required')));
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
