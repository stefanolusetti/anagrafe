<div class="padded">
  <h1>Anteprima</h1>
  Controllare attentamente i dati inseriti.
</div>
<form class="preview">
  <?php
/*
██████  ██ ██████  ████████ ██   ██
██   ██ ██ ██   ██    ██    ██   ██
██████  ██ ██████     ██    ███████
██   ██ ██ ██   ██    ██    ██   ██
██████  ██ ██   ██    ██    ██   ██
titolare_nome
titolare_cognome
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
  f_text_print($titolare_nome, 'Nome');
  f_text_print($titolare_cognome, 'Cognome');
  f_text_print($titolare_nascita_comune, 'Comune di nascita');
  f_text_print($titolare_nascita_provincia, 'Provincia di nascita');
  f_text_print($titolare_nascita_nazione, 'Nazione di nascita (se diversa da Italia)');
  f_text_print(date('d/m/Y', strtotime($titolare_nascita_data)), 'Data di nascita (nel formato gg/mm/aaaa)*');

  f_text_print($titolare_res_comune, 'Comune di residenza');
  f_text_print($titolare_res_provincia, 'Provincia di residenza');
  f_text_print($titolare_res_via, 'Via/Piazza');
  f_text_print($titolare_res_civico, 'Numero civico');
  f_text_print($titolare_res_cap, 'CAP');

  ?><hr class="resizer" /><h2>Anagrafica dell'azienda</h2><?php
/*
██████  ██████  ███    ███ ██████   █████  ███    ██ ██    ██      ██
██      ██    ██ ████  ████ ██   ██ ██   ██ ████   ██  ██  ██      ██
██      ██    ██ ██ ████ ██ ██████  ███████ ██ ██  ██   ████       ██
██      ██    ██ ██  ██  ██ ██      ██   ██ ██  ██ ██    ██       ██
██████  ██████  ██      ██ ██      ██   ██ ██   ████    ██        ██
titolare_rappresentanza
titolare_rappresentanza_altro
ragione_sociale
partita_iva
codice_fiscale
impresa_forma_giuridica_altro / forma_giuridica_id
*/

  if ( !empty($titolare_rappresentanza_altro) ) {
    f_text_print($titolare_rappresentanza_altro, 'Tipo di rappresentanza');
  }
  else {
    f_text_print($titolare_rappresentanza, 'Tipo di rappresentanza');
  }

  f_text_print($ragione_sociale, 'Ragione sociale');
  f_text_print($partita_iva, 'Partita IVA');
  f_text_print($codice_fiscale, 'Codice Fiscale');
  if ( !empty($impresa_forma_giuridica_altro) ) {
    f_text_print($impresa_forma_giuridica_altro, 'Forma giuridica dell\'impresa');
  }
  else {
    $shapes = opzioni_forma_giuridica_id();
    f_text_print($shapes[$forma_giuridica_id], 'Forma giuridica dell\'impresa');
  }
  ?><h3>Sede legale</h3>
  <?php
/*
 ██████  ██████  ███    ███ ██████   █████  ███    ██ ██    ██     ██████
██      ██    ██ ████  ████ ██   ██ ██   ██ ████   ██  ██  ██           ██
██      ██    ██ ██ ████ ██ ██████  ███████ ██ ██  ██   ████        █████
██      ██    ██ ██  ██  ██ ██      ██   ██ ██  ██ ██    ██        ██
 ██████  ██████  ██      ██ ██      ██   ██ ██   ████    ██        ███████
sl_comune
sl_prov
sl_cap
sl_via
sl_civico
sl_telefono
sl_mobile
sl_fax
impresa_altre_sedi
*/

  f_text_print($sl_comune, 'Comune');
  f_text_print($sl_prov, 'Provincia');
  f_text_print($sl_cap, 'CAP');
  f_text_print($sl_via, 'Via');
  f_text_print($sl_civico, 'Civico');
  f_text_print($sl_telefono, 'Telefono');
  f_text_print($sl_mobile, 'Tel. mobile');
  f_text_print($sl_fax, 'Fax');
  f_textarea_print($impresa_altre_sedi, 'Sedi secondarie e Unità Locali');
  f_text_print($impresa_pec, 'Casella PEC');
?><h3>Iscrizione nel Registro delle Imprese presso la C.C.I.A.A.</h3><?php
/*
██████  ███████  █████
██   ██ ██      ██   ██
██████  █████   ███████
██   ██ ██      ██   ██
██   ██ ███████ ██   ██
$rea_ufficio
$rea_num
$impresa_data_costituzione
*/
  f_text_print($rea_ufficio, 'Registro delle Imprese di');
  f_text_print($rea_num, 'Numero di R.E.A.*');
  f_text_print(date('d/m/Y', strtotime($impresa_data_costituzione)), 'Data costituzione società (nel formato gg/mm/aaaa)*');
  f_textarea_print($impresa_soggetto_sociale, 'Oggetto Sociale');
?><hr class="resizer" /><h3>L'impresa opera in uno dei seguenti settori:</h3><?php
f_checkbox_print($impresa_settore_trasporto, 'Trasporto di materiali a discarica conto terzi');
f_checkbox_print($impresa_settore_rifiuti, 'Trasporto e smaltimento di rifiuti');
f_checkbox_print($impresa_settore_terra, 'Estrazione, fornitura e trasporto di terra e materiali inerti');
f_checkbox_print($impresa_settore_bitume, 'Confezionamento, fornitura e trasporto di calcestruzzo e di bitume');
f_checkbox_print($impresa_settore_nolo, 'Noli a freddo e a caldo di macchinari');
f_checkbox_print($impresa_settore_ferro, 'Fornitura di ferro lavorato');
f_checkbox_print($impresa_settore_autotrasporto, 'Guardiania dei cantieri');
f_checkbox_print($impresa_settore_nessuno, 'Nessuna delle precedenti');

?><hr class="resizer" /><h2>Partecipazioni (anche minoritarie) in altre imprese o società (anche fiduciarie)</h2><?php
if( isset($imprese_partecipate) &&  !empty($imprese_partecipate) ) {
  ?><div class="warning">Sono state inserite <em><?php echo count($imprese_partecipate); ?></em> imprese partecipate:</div><?php
  foreach ( $imprese_partecipate AS $n => $impresa ) {
?><div class="impresa"><?php
    f_checkbox_print($n + 1, $impresa['nome'], true);
    f_text_print($impresa['piva'], 'Partita IVa');
    f_text_print($impresa['cf'], 'Codice Fiscale');
?><div class="resizer"></div></div><?php
  }
}
else {
  ?><div class="warning">Non sono state inserite imprese partecipate.</div><?php
}
?><hr class="resizer" />
<?php
echo '<h2>Consiglio di amministrazione</h2>';
f_checkbox_print($impresa_num_amministratori, 'Numero componenti in carica', true, 2);

echo '<h2>Procuratori e Procuratori Speciali</h2>';
f_checkbox_print($impresa_num_procuratori, 'Numero componenti in carica', true, 2);

echo '<h2>Collegio Sindacale</h2>';
f_checkbox_print($impresa_num_sindaci, 'Numero sindaci effettivi', true, 2);
f_checkbox_print($impresa_num_sindaci_supplenti, 'Numero sindaci supplenti', true, 2);
?><hr class="resizer" /><h2>Interessato allo svolgimento delle seguenti attività</h2><?php
/*
 █████  ████████ ████████ ██ ██    ██ ██ ████████  █████
██   ██    ██       ██    ██ ██    ██ ██    ██    ██   ██
███████    ██       ██    ██ ██    ██ ██    ██    ███████
██   ██    ██       ██    ██  ██  ██  ██    ██    ██   ██
██   ██    ██       ██    ██   ████   ██    ██    ██   ██
*/
  f_checkbox_print($interesse_lavori, 'Lavori');
  if ( 1 == (int)$interesse_lavori ) {
?><div class="half"><?php
    f_text_print($interesse_lavori_tipo, 'Tipologia');
    f_text_print($interesse_lavori_importo, 'Importo');
?></div><?php
  }
?><hr class="resizer" /><?php
  f_checkbox_print($interesse_servizi, 'Servizi');
  if ( 1 == (int)$interesse_servizi ) {
?><div class="half"><?php
    f_text_print($interesse_servizi_tipo, 'Tipologia');
    f_text_print($interesse_servizi_importo, 'Importo');
?></div><?php
  }
?><hr class="resizer" /><?php
  f_checkbox_print($interesse_forniture, 'Forniture');
  if ( 1 == (int)$interesse_forniture ) {
?><div class="half"><?php
    f_text_print($interesse_forniture_tipo, 'Tipologia');
    f_text_print($interesse_forniture_importo, 'Importo');
?></div><?php
  }
?><hr class="resizer" /><?php
  f_checkbox_print($interesse_interventi, 'Interventi di immediata riparazione ex art.8, commi 1 e 5 del decreto legge n.189/2016');
  if ( 1 == (int)$interesse_interventi ) {
?><div class="half"><?php
    f_text_print($interesse_interventi_tipo, 'Tipologia');
    f_text_print($interesse_interventi_importo, 'Importo');
    echo '</div>';
    f_checkbox_print($interesse_interventi_checkbox, 'Dichiara che nei propri confronti e nei confronti di tutti I soggetti di cui all’art 85. del decreto legislativo n. 159/2011 non sussistono le cause di divieto, di decadenza o di sospensione di cui all\'art.67 del D.Lgs. 06/09/2011, n.159');

  }
  ?><hr class="resizer" /><h2> Anagrafiche dei componenti</h2>
  <p class="padded">
    Soggetti previsti dal DLgs. n. 159/2011 art.85 e ss.mm.ii.
  </p>
<?php
/*
 █████  ███    ██  █████   ██████  ██████   █████  ███████ ██  ██████ ██   ██ ███████
██   ██ ████   ██ ██   ██ ██       ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
███████ ██ ██  ██ ███████ ██   ███ ██████  ███████ █████   ██ ██      ███████ █████
██   ██ ██  ██ ██ ██   ██ ██    ██ ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
██   ██ ██   ████ ██   ██  ██████  ██   ██ ██   ██ ██      ██  ██████ ██   ██ ███████
*/
$num_anagrafiche = isset($anagrafiche_antimafia) ? count($anagrafiche_antimafia) : 0;
$num_familiari = 0;
$anagrafiche_class = 'error';
if ( 0 != $num_anagrafiche ) {
  $anagrafiche_class = 'warning';
  foreach ( $anagrafiche_antimafia AS $anagrafica ) {
    if ( isset($anagrafica['familiari']) ) {
      $num_familiari += count($anagrafica['familiari']);
    }
  }
}
?>
<div class="<?php echo $anagrafiche_class; ?>">Sono state inserite <em><?php echo $num_anagrafiche; ?></em> anagrafiche di componenti e <em><?php echo $num_familiari; ?></em> anagrafiche di familiari maggiorenni conviventi</div>

<?php
if ( 0 != $num_anagrafiche ) {
  $key_roles_options = opzioni_ruoli_anagrafiche(0);
  $fam_roles_options = opzioni_ruoli_anagrafiche(1);
  foreach ( $anagrafiche_antimafia AS $na => $anagrafica ) {
    $class = $na % 2 ? 'even' : 'odd';
    echo '<div class="preview-anagrafica '.$class.'">';
    if ( isset($key_roles_options[$anagrafica['role_id']]) ) {
      f_checkbox_print($na + 1, $key_roles_options[$anagrafica['role_id']], true, 2);
    }
    else {
      f_checkbox_print($na + 1, '<div class="error">Nessun valore inserito</div>', true, 2);
    }

    echo '<div class="half">';
    f_text_print($anagrafica['antimafia_nome'], 'Nome');
    f_text_print($anagrafica['antimafia_cognome'], 'Cognome');
    f_text_print($anagrafica['antimafia_cf'], 'Codice Fiscale');

    f_text_print(date('d/m/Y', strtotime($anagrafica['antimafia_data_nascita'])), 'Data di nascita');
    f_text_print($anagrafica['antimafia_comune_nascita'] . ' (' . $anagrafica['antimafia_provincia_nascita'] . ')', 'Comune di nascita');


    f_text_print($anagrafica['antimafia_comune_residenza'] . ' (' . $anagrafica['antimafia_provincia_residenza'] . ')', 'Comune di residenza');
    f_text_print($anagrafica['antimafia_via_residenza'], 'Via residenza');
    f_text_print($anagrafica['antimafia_civico_residenza'], 'Civico residenza');
    echo '</div><div class="resizer"></div>';

    if ( isset($anagrafica['familiari']) && !empty($anagrafica['familiari']) ) {
      echo '<div class="preview-familiari"><h3>Familiari maggiorenni conviventi</h3>';
      $nff = 'A';
      foreach ( $anagrafica['familiari'] AS $nf => $familiare ) {
        echo '<div class="preview-familiare">';
        if ( isset($fam_roles_options[$familiare['role_id']]) ) {
          f_checkbox_print(
            ($na + 1) . ' ' . $nff,
            '<em>' . $fam_roles_options[$familiare['role_id']] . ' di ' . $anagrafica['antimafia_nome'] . ' ' . $anagrafica['antimafia_cognome'] . '</em>',
            true,
            2);
        }
        else {
          f_checkbox_print($nf + 1, '<div class="error">Nessun valore inserito</div>', true, 2);
        }

        echo '<div class="half">';
        f_text_print($familiare['nome'], 'Nome');
        f_text_print($familiare['cognome'], 'Cognome');
        f_text_print($familiare['cf'], 'Codice Fiscale');

        f_text_print($familiare['comune'], 'Comune di nascita');
        f_text_print($familiare['data_nascita'], 'Data di nascita');
        f_text_print($familiare['cf'], 'Codice Fiscale');
        echo '</div>';
        echo '<div class="resizer"></div></div>';
        $nff++;
      }
      echo '</div>';
    }
    echo '</div><hr class="resizer" />';
  }
}
?><h2>Iscrizione alla White List</h2>
<?php
if ( 1 == (int)$stmt_wl ) {
  f_checkbox_print($stmt_wl, 'Iscritto alla White List');
  echo '<div class="half">';
  f_text_print($white_list_prefettura, 'Prefettura di');
  f_text_print(date('d/m/Y', strtotime($white_list_data)), 'Data iscrizione');
  echo '</div>';
}
else {
  f_checkbox_print(0, 'Non iscritto alla White List');
}
?><hr class="resizer" /><h2>Dati Istanza</h2><div class="half"><?php
f_text_print(date('d/m/Y', strtotime($istanza_data)), 'Data istanza');
f_text_print($istanza_luogo, 'Luogo istanza');
?></div>
<div class="resizer"></div>
</form>
