<div class="padded" style="text-align: center;">
  <a href="/domanda/help" class="helplink" target="_blank">Istruzioni per la compilazione della domanda di iscrizione all'Anagrafe Antimafia degli Esecutori</a>
</div>
<?php
if ( isset($formdata['hash']) ) {
  echo form_open('domanda/upsert/' . $formdata['hash'], array('class' => 'preview'));
  echo form_hidden('hash', $formdata['hash']);
}
else {
  echo form_open('domanda/upsert/', array('class' => 'preview'));
  echo form_hidden('hash', '');
}

if ( isset($formdata['we_have_some_errors']) ) {
  ?><div class="resizer hint error">Vi sono errori nella compilazione del modulo. Controllare i dati inseriti.</div><?php
}

echo '<input type="hidden" name="istanza_id" value="' . $formdata['istanza_id'] . '" id="istanza_id" />';

/*
████████ ██ ████████  ██████  ██       █████  ██████  ███████
   ██    ██    ██    ██    ██ ██      ██   ██ ██   ██ ██
   ██    ██    ██    ██    ██ ██      ███████ ██████  █████
   ██    ██    ██    ██    ██ ██      ██   ██ ██   ██ ██
   ██    ██    ██     ██████  ███████ ██   ██ ██   ██ ███████
titolare_nome
titolare_cognome
titolare_cf
titolare_nascita_comune
titolare_nascita_provincia
titolare_nascita_nazione
titolare_nascita_data
titolare_res_comune
titolare_res_provincia
titolare_res_via
titolare_res_civico
titolare_res_cap
*/
  ?><h2>Anagrafica del richiedente</h2><?php

  f_text_edit($formdata, 'titolare_nome', 'Nome*', 'required maxlen');
  f_text_edit($formdata, 'titolare_cognome', 'Cognome*', 'required maxlen');
  f_text_edit($formdata, 'titolare_cf', 'Codice Fiscale*', 'required _pivacf _cf_soft_check');
  f_text_edit($formdata, 'titolare_nascita_comune', 'Comune di nascita*', 'required maxlen');
  f_select_edit(
    $formdata,
    'titolare_nascita_provincia',
    'Provincia di nascita*',
    opzioni_provincie(),
    'required maxlen'
  );
  f_text_edit($formdata, 'titolare_nascita_nazione', 'Nazione di nascita (se diversa da Italia)', 'maxlen');
  f_text_edit($formdata, 'titolare_nascita_data', 'Data di nascita (nel formato gg/mm/aaaa)*', 'required data');

  f_text_edit($formdata, 'titolare_res_comune', 'Comune di residenza*', 'required maxlen');
  f_select_edit(
    $formdata,
    'titolare_res_provincia',
    'Provincia di residenza*',
    opzioni_provincie(),
    'required maxlen'
  );
  f_text_edit($formdata, 'titolare_res_via', 'Via/Piazza*', 'required maxlen');
  f_text_edit($formdata, 'titolare_res_civico', 'Numero civico*', 'required maxlen');
  f_text_edit($formdata, 'titolare_res_cap', 'CAP*', 'required maxlen');
?><hr class="resizer" /><h2>Anagrafica dell'azienda</h2><?php

  f_select_edit(
    $formdata,
    'titolare_rappresentanza',
    'Tipo di rappresentanza*',
    array(
      'Legale rappresentante' => 'Legale rappresentante',
      'Titolare' => 'Titolare',
      'Altro' => 'Altro'
    ),
    'required'
  );

  echo '<div id="titolare_rappresentanza_more" class="' . $classes['titolare_rappresentanza_more'] . '">';
  f_text_edit($formdata, 'titolare_rappresentanza_altro', 'se selezionato <b>Altro</b> specificare:', 'maxlen _titolare_rappresentanza');
  echo '</div>';

  f_text_edit($formdata, 'ragione_sociale', 'Ragione sociale*', 'required maxlen');
  f_text_edit($formdata, 'partita_iva', 'Partita IVA*', 'required maxlen _pivacf');
  f_text_edit($formdata, 'codice_fiscale', 'Codice Fiscale*', 'required maxlen _pivacf');

  f_select_edit(
    $formdata,
    'forma_giuridica_id',
    "Forma giuridica dell'impresa*",
    $forme_giuridiche,
    'required'
  );

  echo '<div id="forma_giuridica_more" class="' . $classes['forma_giuridica_more'] . '">';
  f_text_edit($formdata, 'impresa_forma_giuridica_altro', 'se selezionato <b>Altro</b> specificare:', 'maxlen _forma_giuridica');
  echo '</div>';
?><hr class="resizer" /><h2>Sede Legale</h2><?php
/*
███████ ███████ ██████  ███████     ██      ███████  ██████   █████  ██      ███████
██      ██      ██   ██ ██          ██      ██      ██       ██   ██ ██      ██
███████ █████   ██   ██ █████       ██      █████   ██   ███ ███████ ██      █████
     ██ ██      ██   ██ ██          ██      ██      ██    ██ ██   ██ ██      ██
███████ ███████ ██████  ███████     ███████ ███████  ██████  ██   ██ ███████ ███████
*/
  f_text_edit($formdata, 'sl_comune', 'Comune*', 'required maxlen');
  f_select_edit(
    $formdata,
    'sl_prov',
    'Provincia*',
    opzioni_provincie(),
    'required maxlen'
  );
  f_text_edit($formdata, 'sl_cap', 'CAP*', 'required maxlen');
  f_text_edit($formdata, 'sl_via', 'Via*', 'required maxlen');
  f_text_edit($formdata, 'sl_civico', 'Civico*', 'required maxlen');
  f_text_edit($formdata, 'sl_telefono', 'Telefono*', 'required maxlen');
  f_text_edit($formdata, 'sl_mobile', 'Telefono mobile', 'maxlen');
  f_text_edit($formdata, 'sl_fax', 'Fax', 'maxlen');
  f_text_edit($formdata, 'impresa_pec', "Casella PEC*", 'required email');

  f_textarea_edit(
    $formdata,
    'impresa_altre_sedi',
    'Sedi secondarie e Unità Locali',
    'maxlen500'
  );
?><hr class="resizer" /><h2>Iscrizione nel Registro delle Imprese presso la C.C.I.A.A.</h2><?php
/*
██████  ███████  █████
██   ██ ██      ██   ██
██████  █████   ███████
██   ██ ██      ██   ██
██   ██ ███████ ██   ██
*/
  f_text_edit($formdata, 'rea_ufficio', 'Registro delle Imprese di *', 'required maxlen');
  f_text_edit($formdata, 'rea_num', 'Numero di R.E.A.*', 'required maxlen');
  f_text_edit($formdata, 'impresa_data_costituzione', 'Data costituzione società (nel formato gg/mm/aaaa)*', 'required data');
  f_textarea_edit(
    $formdata,
    'impresa_soggetto_sociale',
    'Oggetto Sociale',
    'maxlen500'
  );
?><hr class="resizer" /><h2>L'impresa opera in uno dei seguenti settori:</h2><?php
/*
███████ ███████ ████████ ████████  ██████  ██████  ██
██      ██         ██       ██    ██    ██ ██   ██ ██
███████ █████      ██       ██    ██    ██ ██████  ██
     ██ ██         ██       ██    ██    ██ ██   ██ ██
███████ ███████    ██       ██     ██████  ██   ██ ██
*/
  echo form_hidden('check_settori', '');
  echo mostra_errore('check_settori');
  f_checkbox_edit(
    $formdata,
    'impresa_settore_trasporto',
    'Trasporto di materiali a discarica conto terzi',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_rifiuti',
    'Trasporto e smaltimento di rifiuti',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_terra',
    'Estrazione, fornitura e trasporto di terra e materiali inerti',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_bitume',
    'Confezionamento, fornitura e trasporto di calcestruzzo e di bitume',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_nolo',
    'Noli a freddo e a caldo di macchinari',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_ferro',
    'Fornitura di ferro lavorato',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_autotrasporto',
    'Autotrasporto conto terzi',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_guardiana',
    'Guardiania dei cantieri',
    'company_fields'
  );
  f_checkbox_edit(
    $formdata,
    'impresa_settore_nessuno',
    'Nessuna delle precedenti',
    '_thefields'
  );
?><hr class="resizer" /><h2>Partecipazioni (anche minoritarie) in altre imprese o società (anche fiduciarie)</h2>
<?php
mostra_errore('check_imprese_upsert');
?>
<?php
if( isset($formdata['has_partecipazioni']) && 1 == $formdata['has_partecipazioni']) {
  $partecipazioni_si = ' checked="checked" ';
  $partecipazioni_no = '';
  $partecipazioni_class = '';
}
else {
  $partecipazioni_si = '';
  $partecipazioni_no = ' checked="checked" ';
  $partecipazioni_class = ' hidden ';
}
?>
<div class="field inpreview checkbox">
  <input type="radio" id="partecipazioni_no" name="has_partecipazioni" value="0" class="preview-field " <?php echo $partecipazioni_no; ?> />
  <label for="partecipazioni_no">Dichiaro di non avere partecipazioni</label>
  <div class="resizer"></div>
</div>

<div class="field inpreview checkbox">
  <input type="radio" id="partecipazioni_si" name="has_partecipazioni" value="1" class="preview-field " <?php echo $partecipazioni_si; ?> />
  <label for="partecipazioni_si">Dichiaro di avere partecipazioni</label>
  <div class="resizer"></div>
</div>


<div class="resizer"></div>
<div class="offices-wrapper <?php echo $partecipazioni_class; ?>">
  <div class="resizer"></div>
<?php f_textbox_edit($formdata, 'numero_partecipazioni', "Numero di imprese partecipate", 'required digits'); ?>
<a href="#" class="add addOffice">Aggiungi Impresa Partecipata</a>
<div class="resizer"></div>
<div class="offices">
<?php
/*
██████   █████  ██████  ████████ ███████  ██████ ██ ██████   █████  ███████ ██  ██████  ███    ██ ██
██   ██ ██   ██ ██   ██    ██    ██      ██      ██ ██   ██ ██   ██    ███  ██ ██    ██ ████   ██ ██
██████  ███████ ██████     ██    █████   ██      ██ ██████  ███████   ███   ██ ██    ██ ██ ██  ██ ██
██      ██   ██ ██   ██    ██    ██      ██      ██ ██      ██   ██  ███    ██ ██    ██ ██  ██ ██ ██
██      ██   ██ ██   ██    ██    ███████  ██████ ██ ██      ██   ██ ███████ ██  ██████  ██   ████ ██
*/
  if (isset($formdata['imprese_partecipate']) AND !empty($formdata['imprese_partecipate'])) {
    foreach ($formdata['imprese_partecipate'] as $key => $imprese_partecipata) {
      echo '<div class="imprese_partecipate container odder" id="ofel-'.$key.'">';
      f_text_edit(
        $formdata,
        'imprese_partecipate[' . $key . '][nome]',
        'Ragione Sociale impresa partecipata <a href="#" data-elid="'.$key.'" class="rm removeOffice" data-victim="ofel-'.$key.'">Rimuovi Impresa</a>',
        'required maxlen'
      );
      f_text_edit(
        $formdata,
        'imprese_partecipate[' . $key . '][piva]',
        'Partita IVA impresa partecipata*',
        'required maxlen _pivacf'
      );
      f_text_edit(
        $formdata,
        'imprese_partecipate[' . $key . '][cf]',
        'Codice Fiscale impresa partecipata*',
        'required maxlen _pivacf'
      );
      echo '<div class="resizer"></div></div>';
    }
  }
?></div>
</div>

<hr class="resizer" /><h4>Consiglio di amministrazione</h4><?php
f_text_edit($formdata, 'impresa_num_amministratori', "Numero componenti in carica");
?><h4>Procuratori e Procuratori Speciali</h4><?php
f_text_edit($formdata, 'impresa_num_procuratori', "Numero componenti in carica");
?><h4>Collegio Sindacale</h4><?php
f_text_edit($formdata, 'impresa_num_sindaci', "Numero sindaci effettivi");
f_text_edit($formdata, 'impresa_num_sindaci_supplenti', "Numero sindaci supplenti");
?><hr class="resizer" /><h2>Interessato allo svolgimento delle seguenti attività (è necessario selezionare almeno una voce)</h2><?php
/*
 █████  ████████ ████████ ██ ██    ██ ██ ████████  █████
██   ██    ██       ██    ██ ██    ██ ██    ██    ██   ██
███████    ██       ██    ██ ██    ██ ██    ██    ███████
██   ██    ██       ██    ██  ██  ██  ██    ██    ██   ██
██   ██    ██       ██    ██   ████   ██    ██    ██   ██
*/
mostra_errore('check_attivita_upsert');
f_checkbox_edit(
  $formdata,
  'interesse_lavori',
  'Lavori',
  'elToggler _interessi',
  'data-el="interesse_lavori_wrapper"'
);
echo '<div id="interesse_lavori_wrapper" class="' . $classes['interesse_lavori'] . '">';
echo '<div class="padded">Inserire la tipologia e gli importi. È possibile inserire più valori separati da una virgola.</div>';
echo '<div class="half">';
f_textarea_edit(
  $formdata,
  'interesse_lavori_tipo',
  'Tipologia',
  'maxlen250'
);
f_textarea_edit(
  $formdata,
  'interesse_lavori_importo',
  'Importo',
  'maxlen250'
);
//f_text_edit($formdata, 'interesse_lavori_tipo', "Tipologia");
//f_text_edit($formdata, 'interesse_lavori_importo', "Importo");
echo '</div>';
echo '</div>';

f_checkbox_edit(
  $formdata,
  'interesse_servizi',
  'Servizi',
  'elToggler ',
  'data-el="interesse_servizi_wrapper"'
);
echo '<div id="interesse_servizi_wrapper" class="' . $classes['interesse_servizi'] . '">';
echo '<div class="padded">Inserire la tipologia e gli importi. È possibile inserire più valori separati da una virgola.</div>';
echo '<div class="half">';
f_textarea_edit(
  $formdata,
  'interesse_servizi_tipo',
  'Tipologia',
  'maxlen250'
);
f_textarea_edit(
  $formdata,
  'interesse_servizi_importo',
  'Importo',
  'maxlen250'
);
//f_text_edit($formdata, 'interesse_servizi_tipo', "Tipologia");
//f_text_edit($formdata, 'interesse_servizi_importo', "Importo");
echo '</div>';
echo '</div>';

f_checkbox_edit(
  $formdata,
  'interesse_forniture',
  'Forniture',
  'elToggler ',
  'data-el="interesse_forniture_wrapper"'
);
echo '<div id="interesse_forniture_wrapper" class="' . $classes['interesse_forniture'] . '">';
echo '<div class="padded">Inserire la tipologia e gli importi. È possibile inserire più valori separati da una virgola.</div>';
echo '<div class="half">';
f_textarea_edit(
  $formdata,
  'interesse_forniture_tipo',
  'Tipologia',
  'maxlen250'
);
f_textarea_edit(
  $formdata,
  'interesse_forniture_importo',
  'Importo',
  'maxlen250'
);
//f_text_edit($formdata, 'interesse_forniture_tipo', "Tipologia");
//f_text_edit($formdata, 'interesse_forniture_importo', "Importo");
echo '</div>';
echo '</div>';

f_checkbox_edit(
  $formdata,
  'interesse_interventi',
  'Interventi di immediata riparazione ex art.8, commi 1 e 5 del decreto legge n.189/2016',
  'elToggler ',
  'data-el="interesse_interventi_wrapper"'
);
echo '<div id="interesse_interventi_wrapper" class="' . $classes['interesse_interventi'] . '">';
echo '<div class="padded">Inserire la tipologia e gli importi. È possibile inserire più valori separati da una virgola.</div>';
echo '<div class="half">';
f_textarea_edit(
  $formdata,
  'interesse_interventi_tipo',
  'Tipologia',
  'maxlen250'
);
f_textarea_edit(
  $formdata,
  'interesse_interventi_importo',
  'Importo',
  'maxlen250'
);
//f_text_edit($formdata, 'interesse_interventi_tipo', "Tipologia");
//f_text_edit($formdata, 'interesse_interventi_importo', "Importo");
echo '</div>';
f_checkbox_edit(
  $formdata,
  'interesse_interventi_checkbox',
  '<div class="smallpls">Dichiara che nei propri confronti e nei confronti di tutti I soggetti di cui all’art 85. del decreto legislativo n. 159/2011 non sussistono le cause di divieto, di decadenza o di sospensione di cui all\'art.67 del D.Lgs. 06/09/2011, n.159</div>',
  'stmt__eligible'
);
echo '</div>';
?><hr class="resizer" /><h2> Anagrafiche dei componenti</h2>
<p class="padded">
  <a href="http://www.gazzettaufficiale.it/atto/serie_generale/caricaDettaglioAtto/originario?atto.dataPubblicazioneGazzetta=2011-09-28&atto.codiceRedazionale=011G0201" target="_blank">Soggetti previsti dal DLgs. n. 159/2011 art.85 e ss.mm.ii.</a>
<?php
f_textbox_edit($formdata, 'numero_anagrafiche', "Numero di componenti", 'required digits');
?>
  <br class="resizer" />
  <br />
  <a href="#anagrafiche" class="add addAnagrafica">Inserisci Anagrafica Componente</a> | <a href="#" class="reset resetAnagrafiche">Cancella tutto</a>
</p>
<?php

mostra_errore('check_anagrafiche_upsert');
?>
<div id="elenco_anagrafiche">
<?php
/*
█████  ███    ██  █████   ██████  ██████   █████  ███████ ██  ██████ ██   ██ ███████
██   ██ ████   ██ ██   ██ ██       ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
███████ ██ ██  ██ ███████ ██   ███ ██████  ███████ █████   ██ ██      ███████ █████
██   ██ ██  ██ ██ ██   ██ ██    ██ ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
██   ██ ██   ████ ██   ██  ██████  ██   ██ ██   ██ ██      ██  ██████ ██   ██ ███████
*/
$autocomplete_to_enable = array();
if (isset($formdata['anagrafiche_antimafia']) AND !empty($formdata['anagrafiche_antimafia'])) {
  $a_head = '<div id="anel-!!!" class="anagrafica preview-anagrafica +++ anagrafica-box el-!!!" data-elid="!!!"><a href="#anagrafiche" class="rm removeFamiliar" data-victim="anel-!!!" data-elid="!!!">Rimuovi Anagrafica Componente</a>';
  $a_foot = '<a href="#anagrafiche" class="add addFamiliar" data-elid="!!!">Aggiungi Familiare Convivente Maggiorenne</a><div class="resizer"></div><div class="familiars" data-elid="!!!">';
  $f_head = '<div class="box-familiare" id="fam-###-@@@">
    <a href="#anagrafiche" class="rm removeThisFamiliar" data-victim="fam-###-@@@" data-elid="###" data-elfid="@@@">Rimuovi Componente Familiare Maggiorenne</a>';
  $f_foot = '<div class="resizer"></div></div>';

  $options_key_users = opzioni_ruoli_anagrafiche(0);
  $options_fam_users = opzioni_ruoli_anagrafiche(1);

  foreach ($formdata['anagrafiche_antimafia'] AS $i => $anagrafica) {
    echo str_replace(
      array('!!!', '+++'),
      array($i, $i % 2 ? 'odd' : 'even'),
      $a_head
    );
    $prefix = 'anagrafiche_antimafia[' . $i . '][';

    $giuridica_radio_name = $prefix . 'is_giuridica]';
    $giuridica_radio_id = grind_key($giuridica_radio_name);

    $giuridica_ragione_sociale_name = $prefix . 'is_giuridica]';
    $giuridica_ragione_sociale_id = grind_key($giuridica_ragione_sociale_name);

    $giuridica_partita_iva_name = $prefix . 'is_giuridica]';
    $giuridica_partita_iva_id = grind_key($giuridica_partita_iva_name);

    $giuridica_codice_fiscale_name = $prefix . 'is_giuridica]';
    $giuridica_codice_fiscale_id = grind_key($giuridica_codice_fiscale_name);

    if ( 1 == $anagrafica['is_giuridica'] ) {
      $is_giuridica_selected = ' checked="checked" ';
      $is_not_giuridica_selected = '';
      $info_giuridiche_class = ' info_giuridiche_' . $i;
      $fisica_class = ' hidden ';
    }
    else {
      $is_giuridica_selected = '';
      $is_not_giuridica_selected = ' checked="checked" ';
      $info_giuridiche_class = ' hidden info_giuridiche_' . $i;
      $fisica_class = '';
    }

    if ( isset($anagrafica['anagrafica_id']) ) {
      echo '<input type="hidden" name="anagrafica['.$i.'][anagrafica_id]" value="' . $anagrafica['anagrafica_id'] . '" id="anagrafica_' . $i . '_id" />';
    }

    f_select_edit(
      $formdata,
      $prefix . 'role_id]',
      'Ruolo*',
      $options_key_users,
      'required trigger-giuridica',
      array('data-elid' => $i)
    );

    if ( 24 == $anagrafica['role_id'] ) {
      echo '<div class="giuridica_wrapper giuridica_wrapper_' . $i . '">';
    }
    else {
      echo '<div class="giuridica_wrapper_' . $i . ' giuridica_wrapper hidden">';
    }

    echo '<div class="half">';
?>
<div class="field inpreview checkbox">
  <input type="radio" id="anagrafiche_antimafia_<?php echo $i; ?>_is_giuridica_no" data-elid="<?php echo $i; ?>" name="anagrafiche_antimafia[<?php echo $i; ?>][is_giuridica]" value="0" class="preview-field is_giuridica_radio_no " <?php echo $is_not_giuridica_selected; ?> />
  <label for="anagrafiche_antimafia_<?php echo $i; ?>_is_giuridica_no">Persona Fisica</label>
  <div class="resizer"></div>
</div>
<div class="field inpreview checkbox">
  <input type="radio" id="anagrafiche_antimafia_<?php echo $i; ?>_is_giuridica_si" data-elid="<?php echo $i; ?>" name="anagrafiche_antimafia[<?php echo $i; ?>][is_giuridica]" value="1" class="preview-field is_giuridica_radio_si " <?php echo $is_giuridica_selected; ?> />
  <label for="anagrafiche_antimafia_<?php echo $i; ?>_is_giuridica_si">Persona Giuridica</label>
  <div class="resizer"></div>
</div>
<?php
    echo '</div><div class="resizer"></div>';

    echo '<div class="' . $info_giuridiche_class . '">';

    f_text_edit(
      $formdata,
      $prefix . 'giuridica_ragione_sociale]',
      'Ragione Sociale*',
      'maxlen'
    );
    f_text_edit(
      $formdata,
      $prefix . 'giuridica_partita_iva]',
      'Partita IVA*',
      'maxlen'
    );
    f_text_edit(
      $formdata,
      $prefix . 'giuridica_codice_fiscale]',
      'Codice Fiscale*',
      'maxlen'
    );

    echo '<div class="resizer"></div></div>';

    echo '</div>';

    printf('<div class="fisica_wrapper fisica_wrapper_%s %s">', $i, $fisica_class);
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_nome]',
      'Nome*',
      'required maxlen'
    );
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_cognome]',
      'Cognome*',
      'required maxlen'
    );
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_data_nascita]',
      'Data di nascita (nel formato gg/mm/aaaa)*',
      'required data'
    );
    $acn_ID = f_text_edit(
      $formdata,
      $prefix . 'antimafia_comune_nascita]',
      'Comune di nascita*',
      'required'
    );

    $apn_ID = f_select_edit(
      $formdata,
      $prefix . 'antimafia_provincia_nascita]',
      'Provincia di nascita*',
      opzioni_provincie(),
      'required'
    );
    $autocomplete_to_enable[ $acn_ID ] = $apn_ID;
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_cf]',
      'Codice Fiscale*',
      'required _pivacf _cf_soft_check'
    );
    $acr_ID = f_text_edit(
      $formdata,
      $prefix . 'antimafia_comune_residenza]',
      'Comune Residenza*',
      'required'
    );

    $apr_ID = f_select_edit(
      $formdata,
      $prefix . 'antimafia_provincia_residenza]',
      'Provincia di Residenza*',
      opzioni_provincie(),
      'required'
    );
    $autocomplete_to_enable[ $acr_ID ] = $apr_ID;
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_via_residenza]',
      'Via Residenza*',
      'required'
    );
    f_text_edit(
      $formdata,
      $prefix . 'antimafia_civico_residenza]',
      'Civico Residenza*',
      'required'
    );
    f_textbox_edit($formdata, $prefix . 'antimafia_numero_familiari]', "Numero di familiari maggiorenni conviventi", 'required digits');
    echo '</div>';

    echo str_replace(
      array('!!!'),
      array($i),
      $a_foot
    );

/*
███████  █████  ███    ███ ██ ██      ██  █████  ██████  ██
██      ██   ██ ████  ████ ██ ██      ██ ██   ██ ██   ██ ██
█████   ███████ ██ ████ ██ ██ ██      ██ ███████ ██████  ██
██      ██   ██ ██  ██  ██ ██ ██      ██ ██   ██ ██   ██ ██
██      ██   ██ ██      ██ ██ ███████ ██ ██   ██ ██   ██ ██
*/
    if ( !isset($anagrafica['familiari']) ) {
      if ( 0 != $anagrafica['antimafia_numero_familiari'] ) {
        echo '<label class="errormsg"><p>Non sono stati inseriti familiari conviventi ma ne sono stati dichiarati ' . $anagrafica['antimafia_numero_familiari'] . '</p></label>';
      }
    }
    else if ( count($anagrafica['familiari']) != $anagrafica['antimafia_numero_familiari'] ) {
      echo '<label class="errormsg"><p>Sono stati inseriti '.count($anagrafica['familiari']).' familiari ma ne sono stati dichiarati ' . $anagrafica['antimafia_numero_familiari'] . '</p></label>';
    }
    if(isset($anagrafica['familiari']) && !empty($anagrafica['familiari'])){
      // familiari
      foreach($anagrafica['familiari'] AS $fi => $fam){
        $fam_prefix = 'anagrafiche_antimafia['.$i.'][familiari]['.$fi.'][';
        echo str_replace(
          array('###', '@@@'),
          array($i, $fi),
          $f_head
        );
        echo '<div class="familiar_roles">';
        f_select_edit(
          $formdata,
          $fam_prefix . 'role_id]',
          'Ruolo*',
          $options_fam_users,
          'required'
        );
        echo '</div>';
        f_text_edit(
          $formdata,
          $fam_prefix . 'nome]',
          'Nome*',
          'required maxlen'
        );
        f_text_edit(
          $formdata,
          $fam_prefix . 'cognome]',
          'Cognome*',
          'required maxlen'
        );
        f_text_edit(
          $formdata,
          $fam_prefix . 'data_nascita]',
          'Data di nascita (nel formato gg/mm/aaaa)*',
          'required data'
        );

        $facn_ID = f_text_edit(
          $formdata,
          $fam_prefix . 'comune]',
          'Comune di nascita*',
          'required'
        );

        $fapn_ID = f_select_edit(
          $formdata,
          $fam_prefix . 'provincia_nascita]',
          'Provincia di nascita*',
          opzioni_provincie(),
          'required'
        );
        $autocomplete_to_enable[ $facn_ID ] = $fapn_ID;
        f_text_edit(
          $formdata,
          $fam_prefix . 'cf]',
          'Codice Fiscale*',
          'required _pivacf _cf_soft_check'
        );

        $facr_ID = f_text_edit(
          $formdata,
          $fam_prefix . 'comune_residenza]',
          'Comune Residenza*',
          'required'
        );

        $fapr_ID = f_select_edit(
          $formdata,
          $fam_prefix . 'provincia_residenza]',
          'Provincia di Residenza*',
          opzioni_provincie(),
          'required'
        );
        $autocomplete_to_enable[ $facr_ID ] = $fapr_ID;
        f_text_edit(
          $formdata,
          $fam_prefix . 'via_residenza]',
          'Via Residenza*',
          'required'
        );
        f_text_edit(
          $formdata,
          $fam_prefix . 'civico_residenza]',
          'Civico Residenza*',
          'required'
        );
        f_text_edit(
          $formdata,
          $fam_prefix . 'cap_residenza]',
          'CAP Residenza*',
          'required'
        );
        echo str_replace(
          array('###', '@@@'),
          array($i, $fi),
          $f_foot
        );
      }
    }
    echo '</div></div>';
  }
}

if ( isset($formdata['stmt_wl']) ) {
  if ( '1' == $formdata['stmt_wl'] ) {
    $stmt_wl_si = ' checked="checked" ';
    $stmt_wl_no = '';
    $stmt_wl_more_class = '';
  }
  else {
    $stmt_wl_si = '';
    $stmt_wl_no = ' checked="checked" ';
    $stmt_wl_more_class = 'hidden';
  }
}
else {
  $stmt_wl_si = '';
  $stmt_wl_no = '';
  $stmt_wl_more_class = 'hidden';
}

?></div><hr /><h2>Iscrizione White List</h2>
<p class="padded">
  <?php mostra_errore('check_wl'); ?>
  <div class="field inpreview checkbox">
    <input type="radio" id="stmt_wl_si" name="stmt_wl" value="1" class="_white_list preview-field" <?php echo $stmt_wl_si; ?> />
    <label for="stmt_wl_si">Dichiaro di essere iscritto alla white list della prefettura</label>
    <div class="resizer"></div>
  </div>
  <div id="stmt_wl_more" class="<?php echo $stmt_wl_more_class; ?>">
<?php
f_text_edit(
  $formdata,
  'white_list_prefettura',
  'Prefettura di*',
  ''
);
f_text_edit(
  $formdata,
  'white_list_data',
  'Data iscrizione (nel formato gg/mm/aaaa)*',
  'data'
);

?>
  </div>
  <div class="field inpreview checkbox">
    <input type="radio" id="stmt_wl_no" name="stmt_wl" value="0" class="preview-field " <?php echo $stmt_wl_no; ?> />
    <label for="stmt_wl_no">Dichiaro di non essere iscritto alla white list della prefettura</label>
    <div class="resizer"></div>
  </div>
</p><hr /><h2>Note aggiuntive</h2>
<?php

f_textarea_edit(
  $formdata,
  'note',
  ''
);
  ?><hr /><h2>Informativa privacy</h2>
  <div class="field fixed privacy">
    <?php $this->load->view('pages/privacy'); ?>
  </div>
  <hr /><h2>Dati Istanza</h2>
<div class="half">


  <?php
f_text_edit($formdata, 'istanza_data', 'Data istanza*', 'required data');
f_text_edit($formdata, 'istanza_luogo', 'Luogo*', 'required');
  ?>
  <div class="resizer"></div>
</div>
  <br />
  <br />
  <br />
  <div style="text-align:center;">
    <input type="submit" name="submit" class="conferma" value="Invia il modulo" />
  </div>
</form>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<?php
$provincie_options = opzioni_provincie();
$key_roles_options = opzioni_ruoli_anagrafiche(0);
$fam_roles_options = opzioni_ruoli_anagrafiche(1);

echo '<script type="text/javascript">';
printf("window.%s = %s;\n", '_provincie', json_encode($provincie_options));
printf("window.%s = %s;\n", '_key_roles', json_encode($key_roles_options));
printf("window.%s = %s;\n", '_fam_roles', json_encode($fam_roles_options));
printf("window.%s = %s;\n", '_autocomplete_to_enable', json_encode($autocomplete_to_enable));
echo '</script>';
