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
  f_select('residence_province', 'Provincia di nascita*',
    opzioni_provincie(),
    array('input' => array('class' => 'required maxlen'))
  );
  f_text('residence_city', 'Città di residenza*',
    array('input' => array('class' => 'required maxlen'))
  );
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
/*
  f_select(
    'company_role',
    'Tipo di rappresentanza',
    array('Legale rappresentante' => 'Legale rappresentante', 'Procuratore' => 'Procuratore')
  );
  */


  f_text('company_name', 'Ragione sociale*',
    array('input' => array('class' => 'required maxlen'))
  );

  f_text(
    'company_birthdate',
    'Data costituzione società (nel formato gg/mm/aaaa)*',
    array('input' => array('class' => 'data'))
  );

  f_select(
    'company_shape',
    "Forma giuridica dell'impresa",
    opzioni_company_shape());

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
  echo '<div class="hidden controlla_tipo_impresa">';
  f_text(
    'company_type_more',
    'se selezionato Altro specificare'
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

  f_text(
    'company_vat',
    'Partita IVA*',
    array('input' => array('class' => 'required piva'))
  );

  f_textarea(
    'company_offices',
    'Sedi secondarie e Unità Locali',
    array('input' => array('style' => 'width: 100%', 'rows' => 3))
  );

  f_text(
    'company_cf',
    'Codice Fiscale*',
    array('input' => array('class' => 'required cf'))
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
  echo '<h4>Iscrizione nel Registro delle Imprese presso la C.C.I.A.A.</h4>';
  f_text('rea_location', 'Registro delle Imprese *', array('input' => array('class' => 'required maxlen')));
  f_text('rea_subscription', 'Numero di iscrizione*', array('input' => array('class' => 'required maxlen')));
  f_text('rea_number', 'Numero di R.E.A.*', array('input' => array('class' => 'required maxlen')));
  echo '<h4>Oggetto Sociale</h4>';
  f_textarea(
    'company_social_subject',
    '',
    array('input' => array('style' => 'width:100%', 'rows' => 4))
  );


  echo '<h2>Attività</h2>';
  echo '<div id="stmt_wl_interest_more" class="">';

  f_checkbox(
    'sake_work_flag',
    '<b>Lavori</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_work_wrapper'))
  );
  echo '<div id="sake_work_wrapper" class="hidden">';
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

  f_checkbox(
    'sake_service_flag',
    '<b>Servizi</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_service_wrapper'))
  );
  echo '<div id="sake_service_wrapper" class="hidden">';
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

  f_checkbox(
    'sake_supply_flag',
    '<b>Forniture</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_supply_wrapper'))
  );
  echo '<div id="sake_supply_wrapper" class="hidden">';
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

  f_checkbox(
    'sake_fix_flag',
    '<b>Interventi di immediata riparazione ex art.8, commi 1 e 5 del decreto legge n.189/2016</b>',
    array('input' => array('class' => 'elToggler ', 'data-el' => 'sake_fix_wrapper'))
  );
  echo '<div id="sake_fix_wrapper" class="hidden">';
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
  <h4>Partecipazioni (anche minoritarie) in altre imprese o società (anche fiduciarie)</h4>
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


    <div class="field_antimafia">
  <h2> Anagrafiche dei componenti<br><br>
  Inserire i dati richiesti per tutti i soggetti previsti dal DLgs. n. 159/2011 art.85 e ss.mm.ii.</h2>


  <div class="field">
    <label  title =" <?php
  echo '<p>Per tutti i tipi di imprese, società, associazioni, anche prive di personalità
  giuridica, la documentazione antimafia deve sempre riferirsi al <b>direttore tecnico</b>
  ove previsto ed inoltre ai <b>membri del collegio sindacale</b> o, nei casi di cui all’art
  2477 c.c, al <b>sindaco o ai soggetti che svolgono compiti di vigilanza</b> di cui all’art
  6 comma 1 lettera b) del d.lgs 231 del 8 giugno 2001.
  In aggiunta poi, sono soggetti a verifica le cariche indicate a fianco di ciascun tipo
  di impresa.</p>';
    echo'<table><tbody><tr><td>1-Imprese individuali</td><td>Titolare</td></tr><tr><td>2-Per le Società di capitali, anche consortili, le Società cooperative,Consorzi cooperativi, Consorzi di cui al Libro V, Titolo X, Capo II, Sezione II del c.c, Associazioni e società di qualunque tipo, anche prive di personalità giuridica </td><td>Legale rappresentante,ed eventuali altri Componenti organo di amministrazione, Ciascuno dei consorziati che nei consorzi e nelle società consortili detenga una partecipazione superiore al 10% oppure detenga una partecipazione inferiore al 10% e che abbia stipulato un patto parasociale riferibile a una partecipazione pari o superiore al 10%,ed ai Soci o consorziati per conto dei quali le società consortili o i consorzi operino in modo esclusivo nei confronti della pubblica amministrazione </td></tr><tr><td>3-Società di capitali </td><td>Socio di maggioranza in caso di società con un numero di soci pari o inferiore a quattro, ovvero al Socio in caso di società con socio unico </td></tr><tr><td>4-Consorzi di cui l’Art 2602 del c.c e per i Gruppi europei di interesse economico(GEIE) </td><td>Chi ne ha la rappresentanza ed agli Imprenditori o Società consorziate </td></tr><tr><td>5-Società semplice e in nome collettivo</td><td>Tutti i soci</td></tr><tr><td>6-Società in accomandita semplice</td><td>Soci accomandatari</td></tr><tr><td>7-Società estere con sede secondaria in territorio statale (Art 2508 c.c)</td><td>Coloro che rappresentano stabilmente nel territorio dello Stato</td></tr><tr><td>8-Società costituite all’estero,prive di una sede secondaria con rappresentanza stabile nel territorio statale</td><td>Coloro che esercitano poteri di amministrazione,di rappresentanza o di direzione dell’impresa</td></tr><tr><td>9-Raggruppamenti temporanei di imprese</td><td>Imprese costituenti il raggruppamento anche se aventi sede all’estero,di rappresentanza o di direzione dell’impresa</td></tr><tr><td>10-Società personali</td><td>Soci persone fisiche delle società personali o di capitali che ne siano socie</td></tr>  <tr><td>11-Società capitali concessionarie nel settore dei giochi pubblici di cui alle lettere b) e c) del comma 2 dell’ Art 85 del D.Lgs 159/2011</td><td>Oltre ai Soggetti indicati nei precedenti punti 2 e 3, ai Soci persone fisiche che detengono,anche indirettamente,una partecipazione al capitale o al patrimoniosuperiore al 2%,nonché ai Direttori generali e Soggetti responsabili delle sedi secondarie o delle stabili organizzazioni inItalia di soggetti non residenti. Nell’ipotesi in cui i soci persone fisiche detengano la partecipazione superiore alla predetta soglia mediante altre società di capitali,la documentazione deve riferirsi anche al legale rappresentante e agli eventuali componenti dell’organo di amministrazione della società socia,alle persone fisiche che,direttamente o indirettamente,controllano tale società,nonché ai direttori generali e ai soggetti responsabili delle sedi secondarie o delle stabili organizzazioni in Italia di soggetti non residenti. La documentazione di cui al periodo precedente deve riferirsi anche al coniuge non separato </td></tr></tbody></table>';
    ?>" >Per avere maggiori informazioni</label></div><div class="container">
    <a name="anagrafiche"></a>
    <a href="#anagrafiche" class="add addAnagrafica">Aggiungi anagrafica</a> | <a href="#" class="reset resetAnagrafiche">Cancella tutto</a><br>
    <div class="inputs">
    <?php if (!$antimafias) {} ?>
    </div>
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

  f_checkbox(
    'stmt_wl',
    'di essere iscritto alle white list della Prefettura',
    array('input' => array('class' => 'elToggler', 'data-el' => 'stmt_wl_more'))
  );

  echo '<div id="stmt_wl_more" class="hidden">';
  f_text(
    'stmt_wl_name',
    'Prefettura di',
    array('input' => array('class' => 'maxlen'))
  );
  f_text(
    'stmt_wl_date',
    'Data iscrizione (nel formato gg/mm/aaaa)',
    array('input' => array('class' => 'data'))
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
