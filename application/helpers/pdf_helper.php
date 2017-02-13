<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function hello($item){
	return "Hello " . $item;
}

function make_xfdf($item){
	$xfdf = new SimpleXMLElement("<xfdf></xfdf>");
	$xfdf->addAttribute('xmlns', 'http://ns.adobe.com/xfdf/');
	$xfdf->addAttribute('xml:space', 'preserve');
	$fields = $xfdf->addChild('fields');
	foreach ($item as $key => $value) {
		$name = $fields->addChild('field');
		$name->addAttribute('name', $key);
		$name->addChild('value', htmlspecialchars($value));
	}
	$f = $xfdf->addChild('f');
	$f -> addAttribute('href', 'template.pdf');
	$test =  $xfdf->asXML();
	return $test;
}

function merge_pdf($item){
	$blank_pdf_form = "./assets/others/template.pdf";
	//$blank_pdf_form = "./assets/others/template_merito_mod.pdf";
	$cmd = "/usr/bin/pdftk $blank_pdf_form fill_form - output - flatten";

	$descriptorspec = array(
	   0 => array("pipe", "r"),
	   1 => array("pipe", "w")
	);

	$process = proc_open($cmd, $descriptorspec, $pipes);

	if (is_resource($process)) {

	    fwrite($pipes[0], make_xfdf($item));
	    fclose($pipes[0]);

	    $pdf_content = stream_get_contents($pipes[1]);
	    fclose($pipes[1]);

	    $return_value = proc_close($process);

	    return $pdf_content;
	}
}

function send_pdf($item) {

	$CI =& get_instance();

	$attachment = merge_pdf($item);
	$filename = './assets/domanda-iscrizione-edm_' . $item['id_anno'] . '_' .$item['id'] . '.pdf';
	$status = file_put_contents($filename, $attachment);

	if(!($status === FALSE))
	{
        //genero e scrivo MD5
        $hash = md5_file($filename);
        $CI->db
                ->where('id', $item['id'])
                ->update('dichiaraziones', array('hash' => $hash));

		//$CI->email->from('elencomeritocostruzioni@postacert.regione.emilia-romagna.it', 'Nucleo operativo elenco merito');
		$CI->email->from('stefano.lusetti@certhidea.it', 'Struttura di Missione del Ministero dell\'Interno');
		$CI->email->to($item['sl_pec']);
		$CI->email->subject('Domanda di iscrizione Anagrafe Antimafia degli Esecutori');
		$CI->email->message(email_message($item, $hash));
		$CI->email->attach($filename);
		$esito = $CI->email->send();
		unlink($filename);
		//log_message('debug', $CI->email->print_debugger());
	} else
	{
		$esito = FALSE;
	}
	return $esito;
}


/*
 ██████ ██████  ███████  █████  ████████ ███████ ██████  ██████  ███████
██      ██   ██ ██      ██   ██    ██    ██      ██   ██ ██   ██ ██
██      ██████  █████   ███████    ██    █████   ██████  ██   ██ █████
██      ██   ██ ██      ██   ██    ██    ██      ██      ██   ██ ██
 ██████ ██   ██ ███████ ██   ██    ██    ███████ ██      ██████  ██
*/
function create_pdf($id) {
  $_pages = array();
  $CI =& get_instance();
  $doc = $CI->dichiarazione_model->get_document($id);
  if ($doc) {
    $id_anno = substr($doc['istanza_data'], 0, 4);
    $CI->load->helper('fdf');

    if('Altro' == $doc['titolare_rappresentanza'] || empty($doc['titolare_rappresentanza'])) {
      $role_label = $doc['titolare_rappresentanza_altro'];
    }
    else {
      $role_label = $doc['titolare_rappresentanza'];
    }

    if ( 0 == $doc['forma_giuridica_id']) {
      $shape_label = $doc['impresa_forma_giuridica_altro'];
    }
    else {
      $shapes = $CI->dichiarazione_model->get_forma_giuridica_label($doc['forma_giuridica_id']);
      if (!empty($shapes)) {
        $shape_label = $shapes[0]['valore'];
      }
    }

    $titolare_nascita_comune = $doc['titolare_nascita_comune'];
    if ( !empty($doc['titolare_nascita_nazione']) ) {
      $titolare_nascita_comune .= sprintf(" (%s)", $doc['titolare_nascita_nazione']);
    }

    $index_data = array(
      'id_istanza' => $id,
      'id_anno' => $id_anno,
      'codice_istanza' => $doc['codice_istanza'],

      'nome_cognome' => $doc['titolare_nome'] . ' ' . $doc['titolare_cognome'],
      'titolare_cf' => $doc['titolare_cf'],
      'titolare_nascita_comune' => $titolare_nascita_comune,
      'titolare_nascita_provincia' => $doc['titolare_nascita_provincia'],
      'titolare_nascita_data' => format_date($doc['titolare_nascita_data']),
      'titolare_res_provincia' => $doc['titolare_res_provincia'],
      'titolare_res_comune' => $doc['titolare_res_comune'],
      'titolare_res_cap' => $doc['titolare_res_cap'],
      'titolare_res_via' => $doc['titolare_res_via'],

      'titolare_rappresentanza_label' => $role_label,
      'ragione_sociale' => $doc['ragione_sociale'],
      'impresa_data_costituzione' => format_date($doc['impresa_data_costituzione']),
      'forma_giuridica_id_label' => $shape_label,

      'sl_comune' => $doc['sl_comune'],
      'sl_cap' => $doc['sl_cap'],
      'sl_prov' => $doc['sl_prov'],
      'sl_via' => $doc['sl_via'],
      'sl_civico' => $doc['sl_civico'],
      'sl_telefono' => $doc['sl_telefono'],
      'sl_mobile' => $doc['sl_mobile'],
      'sl_fax' => $doc['sl_fax'],
      'partita_iva' => $doc['partita_iva'],
      'codice_fiscale' => $doc['codice_fiscale'],
      'impresa_pec' => $doc['impresa_pec'],
      'impresa_email' => $doc['impresa_email'],
      'impresa_altre_sedi' => $doc['impresa_altre_sedi'],

      'rea_ufficio' => $doc['rea_ufficio'],
      'rea_num_iscrizione' => $doc['rea_num_iscrizione'],
      'rea_num' => $doc['rea_num'],
      'impresa_soggetto_sociale' => $doc['impresa_soggetto_sociale'],

      'impresa_num_amministratori' => $doc['impresa_num_amministratori'],
      'impresa_num_procuratori' => $doc['impresa_num_procuratori'],
      'impresa_num_sindaci' => $doc['impresa_num_sindaci'],
      'impresa_num_sindaci_supplenti' => $doc['impresa_num_sindaci_supplenti'],

      'istanza_luogo_data' => $doc['istanza_luogo'] . ', ' . format_date($doc['istanza_data']),
/*
 ██████ ██   ██ ███████  ██████ ██   ██ ██████   ██████  ██   ██ ███████ ███████
██      ██   ██ ██      ██      ██  ██  ██   ██ ██    ██  ██ ██  ██      ██
██      ███████ █████   ██      █████   ██████  ██    ██   ███   █████   ███████
██      ██   ██ ██      ██      ██  ██  ██   ██ ██    ██  ██ ██  ██           ██
 ██████ ██   ██ ███████  ██████ ██   ██ ██████   ██████  ██   ██ ███████ ███████
*/
      'checkbox_checked' => 'Yes',
      'stmt_wl' => $doc['stmt_wl'] == 1 ? 'Yes' : 'No',
      'stmt_wl_no' => $doc['stmt_wl'] == 0 ? 'Yes' : 'No',
      'white_list_prefettura' => $doc['white_list_prefettura'],
      'white_list_data' => empty($doc['white_list_data']) ? '' : format_date($doc['white_list_data']),
      'interesse_interventi_checkbox' => $doc['interesse_interventi_checkbox'] == 1 ? 'Yes' : 'No',

      'stmt_interest' => 'Yes',
      'interesse_lavori' => $doc['interesse_lavori'] == 1 ? 'Yes' : 'No',
      'interesse_lavori_tipo' => $doc['interesse_lavori_tipo'],
      'interesse_lavori_importo' => $doc['interesse_lavori_importo'],

      'interesse_servizi' => $doc['interesse_servizi'] == 1 ? 'Yes' : 'No',
      'interesse_servizi_tipo' => $doc['interesse_servizi_tipo'],
      'interesse_servizi_importo' => $doc['interesse_servizi_importo'],

      'interesse_forniture' => $doc['interesse_forniture'] == 1 ? 'Yes' : 'No',
      'interesse_forniture_tipo' => $doc['interesse_forniture_tipo'],
      'interesse_forniture_importo' => $doc['interesse_forniture_importo'],

      'interesse_interventi' => $doc['interesse_interventi'] == 1 ? 'Yes' : 'No',
      'interesse_interventi_tipo' => $doc['interesse_interventi_tipo'],
      'interesse_interventi_importo' => $doc['interesse_interventi_importo'],

      'impresa_settore_nessuno' => $doc['impresa_settore_nessuno'] == 1 ? 'Yes' : 'No',

      'impresa_settore_trasporto' => $doc['impresa_settore_trasporto'] == 1 ? 'Yes' : 'No',
      'impresa_settore_rifiuti' => $doc['impresa_settore_rifiuti'] == 1 ? 'Yes' : 'No',
      'impresa_settore_terra' => $doc['impresa_settore_terra'] == 1 ? 'Yes' : 'No',
      'impresa_settore_bitume' => $doc['impresa_settore_bitume'] == 1 ? 'Yes' : 'No',
      'impresa_settore_nolo' => $doc['impresa_settore_nolo'] == 1 ? 'Yes' : 'No',
      'impresa_settore_ferro' => $doc['impresa_settore_ferro'] == 1 ? 'Yes' : 'No',
      'impresa_settore_autotrasporto' => $doc['impresa_settore_autotrasporto'] == 1 ? 'Yes' : 'No',
      'impresa_settore_guardiana' => $doc['impresa_settore_guardiana'] == 1 ? 'Yes' : 'No',
      'note' => $doc['note']
    );

    $fdf = new CerthideaFDF($doc['codice_istanza']);
    //$fdf->addPage('indice.pdf', $index_data);
    $_pages[] = array('file' => 'indice.pdf', 'data' => $index_data);

/*
 ██████  ███████ ███████ ██  ██████ ███████ ███████
██    ██ ██      ██      ██ ██      ██      ██
██    ██ █████   █████   ██ ██      █████   ███████
██    ██ ██      ██      ██ ██      ██           ██
 ██████  ██      ██      ██  ██████ ███████ ███████
*/
    $doc_offices = $CI->dichiarazione_model->get_item_imprese_partecipate($id);
    $num_offices_inseriti = 0;
    if(!empty($doc_offices)) {
      // 12 per pagina
      $num_offices_inseriti = count($doc_offices);
      $_num_pages = ceil(count($doc_offices) / 12);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $office_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno,
          'codice_istanza' => $doc['codice_istanza'],
        );
        for ( $j = 0; $j < 12; $j++ ) {
          if (!empty($doc_offices)) {
            $office = array_shift($doc_offices);
            $office_data["name_$j"] = $office['nome'];
            $office_data["piva_$j"] = $office['piva'];
            $office_data["cf_$j"] = $office['cf'];
          }
        }
        //$fdf->addPage('imprese-partecipate.pdf', $office_data);
        $_pages[] = array('file' => 'imprese-partecipate.pdf', 'data' => $office_data);
      }
    }

/*
 █████  ███    ██  █████   ██████  ██████   █████  ███████ ██  ██████ ██   ██ ███████
██   ██ ████   ██ ██   ██ ██       ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
███████ ██ ██  ██ ███████ ██   ███ ██████  ███████ █████   ██ ██      ███████ █████
██   ██ ██  ██ ██ ██   ██ ██    ██ ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
██   ██ ██   ████ ██   ██  ██████  ██   ██ ██   ██ ██      ██  ██████ ██   ██ ███████
*/
    $num_tot_familiars = 0;
    $numero_componenti_inseriti = 0;
    $familiars = array();
    if ( !empty($doc['anagrafiche_antimafia']) ) {
      $role_list = $CI->dichiarazione_model->get_roles();
      // 4 per pagina
      $numero_componenti_inseriti = count($doc['anagrafiche_antimafia']);
      $_num_pages = ceil(count($doc['anagrafiche_antimafia']) / 4);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $anagrafica_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno,
          'codice_istanza' => $doc['codice_istanza'],
        );
        for ( $j = 0; $j < 4; $j++ ) {
          if ( empty($doc['anagrafiche_antimafia']) ) {
            break;
          }
          $anagrafica = array_shift($doc['anagrafiche_antimafia']);
          if ( isset($anagrafica['familiari']) && !empty($anagrafica['familiari'])) {
            foreach($anagrafica['familiari'] AS $familiar) {
              // inject parent name.
              $familiar['parent'] = $anagrafica['antimafia_nome'] . ' ' . $anagrafica['antimafia_cognome'];
              $familiars[] = $familiar;
            }
          }

          $anagrafica_data["ruolo_$j"] = $role_list[$anagrafica['role_id']];
          $num_tot_familiars += $anagrafica['antimafia_numero_familiari'];
          if ( 1 == $anagrafica['is_giuridica'] ) {
            $anagrafica_data["is_giuridica_$j"] = 'Yes';
            $anagrafica_data["nome_cognome_$j"] = $anagrafica['giuridica_ragione_sociale'];
            $anagrafica_data["cf_$j"] = sprintf(
              "%s / %s",
              $anagrafica['giuridica_codice_fiscale'],
              $anagrafica['giuridica_partita_iva']
            );
          }
          else {
            $anagrafica_data["nome_cognome_$j"] = sprintf(
              "%s %s",
              $anagrafica['antimafia_nome'],
              $anagrafica['antimafia_cognome']
            );
            $anagrafica_data["cf_$j"] = $anagrafica['antimafia_cf'];
            $anagrafica_data["birth_locality_$j"] = $anagrafica['antimafia_comune_nascita'];
            $anagrafica_data["birth_province_$j"] = $anagrafica['antimafia_provincia_nascita'];
            $anagrafica_data["birth_date_$j"] = format_date($anagrafica['antimafia_data_nascita']);

            $anagrafica_data["residence_city_$j"] = $anagrafica['antimafia_comune_residenza'];
            $anagrafica_data["residence_province_$j"] = $anagrafica['antimafia_provincia_residenza'];
            $anagrafica_data["residence_street_$j"] = $anagrafica['antimafia_via_residenza'];
            $anagrafica_data["residence_number_$j"] = $anagrafica['antimafia_civico_residenza'];
          }

        }
        //$fdf->addPage('anagrafiche-componenti.pdf', $anagrafica_data);
        $_pages[] = array('file' => 'anagrafiche-componenti.pdf', 'data' => $anagrafica_data);
      }
    }
/*
███████  █████  ███    ███ ██ ██      ██  █████  ██████  ██
██      ██   ██ ████  ████ ██ ██      ██ ██   ██ ██   ██ ██
█████   ███████ ██ ████ ██ ██ ██      ██ ███████ ██████  ██
██      ██   ██ ██  ██  ██ ██ ██      ██ ██   ██ ██   ██ ██
██      ██   ██ ██      ██ ██ ███████ ██ ██   ██ ██   ██ ██
*/
    $numero_familiari_inseriti = 0;
    if ( 0 != count($familiars)) {
      // 5 per pagina.
      $_num_pages = ceil(count($familiars) / 5);
      $numero_familiari_inseriti = count($familiars);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $familiari_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno,
          'codice_istanza' => $doc['codice_istanza'],
        );
        for ( $j = 0; $j < 5; $j++ ) {
          if ( empty($familiars) ) {
            break;
          }
          $familiare = array_shift($familiars);

          $familiari_data["role_$j"] = $role_list[$familiare['role_id']];
          $familiari_data["parent_$j"] = $familiare['parent'];

          $familiari_data["nome_cognome_$j"] = $familiare['nome'] . ' ' . $familiare['cognome'];
          $familiari_data["cf_$j"] = $familiare['cf'];
          $familiari_data["birth_locality_$j"] = $familiare['comune'];
          $familiari_data["birth_date_$j"] = format_date($familiare['data_nascita']);
          $familiari_data["residenza_$j"] = sprintf(
            "%s (%s) %s n.%s %s",
            $familiare['comune_residenza'],
            $familiare['provincia_residenza'],
            $familiare['via_residenza'],
            $familiare['civico_residenza'],
            $familiare['cap_residenza']
          );
        }
        //$fdf->addPage('anagrafiche-familiari.pdf', $familiari_data);
        $_pages[] = array('file' => 'anagrafiche-familiari.pdf', 'data' => $familiari_data);
      }
    }

/*
██████  ██ ███████ ██████  ██ ██       ██████   ██████   ██████
██   ██ ██ ██      ██   ██ ██ ██      ██    ██ ██       ██    ██
██████  ██ █████   ██████  ██ ██      ██    ██ ██   ███ ██    ██
██   ██ ██ ██      ██      ██ ██      ██    ██ ██    ██ ██    ██
██   ██ ██ ███████ ██      ██ ███████  ██████   ██████   ██████
*/
    $_pages[] = array(
      'file' => 'riepilogo.pdf',
      'data' => array(
        'id_istanza' => $id,
        'id_anno' => $id_anno,
        'codice_istanza' => $doc['codice_istanza'],
        'numero_imprese' => $doc['numero_partecipazioni'],
        'numero_imprese_inserite' => $num_offices_inseriti,
        'numero_componenti' => $doc['numero_anagrafiche'],
        'numero_componenti_inseriti' => $numero_componenti_inseriti,
        'numero_familiari' => $num_tot_familiars,
        'numero_familiari_inseriti' => $numero_familiari_inseriti
      )
    );

    // Num pages.
    $tot_num_pages = count($_pages) + 2;
    $ip = 3;
    foreach($_pages AS $_page) {
      $_page['data']['tot_pages__1'] = sprintf("Pagina 1 di %s", $tot_num_pages);
      $_page['data']['tot_pages__2'] = sprintf("Pagina 2 di %s", $tot_num_pages);
      $_page['data']['tot_pages__3'] = sprintf("Pagina 3 di %s", $tot_num_pages);
      $_page['data']['tot_pages'] = sprintf("Pagina %s di %s", $ip, $tot_num_pages);
      $fdf->addPage($_page['file'], $_page['data']);
      $ip++;
    }

    $fdf->makeFDF();
    $fdf->fillForms();
    $fileinfo = $fdf->mergeAll();
    $fdf->clean(); // remove ONLY the tmp files&folders!
    return $fileinfo;
  }
  return false;
}

function build_other_fields($id, $nomecampo,$tipo)
{
	$CI =& get_instance();
	$result = "";
	$tags = "{$nomecampo}_tags";
	$anagrafica = "{$nomecampo}s";

	$CI->db->from($tags);
	$CI->db->join($anagrafica, "{$anagrafica}.id = {$tags}.{$nomecampo}_id");
	$CI->db->where("{$tags}.dichiarazione_id", $id);

	$query = $CI->db->get();
	$result = "";
	foreach ($query->result() as $row)
	{
		if($nomecampo == "soa") {
			$result .= "{$row->codice} - {$row->denominazione} - classe {$row->classe};  ";
		} else {
		if ($tipo==0) {
		if ($row->codice!='90.03.02')
		$result .= "{$row->codice}-{$row->denominazione};  ";}
	    else {
		if ($row->codice=='90.03.02')
		$result .= "{$row->codice}-{$row->denominazione};  ";
		}
		}

	}
	return $result;

}

function build_anagrafica_antimafia($id) {
$CI =& get_instance();
$query_antimafia = $CI->db->get_where('anagrafiche_antimafia',array('dichiarazione_id' => $id));
$result = "";
foreach ($query_antimafia->result() as $row)
	{
$row_data = format_date($row->antimafia_data_nascita);

			$result .= "{$row->antimafia_nome} nato a {$row->antimafia_comune_nascita} il ".$row_data." residente a {$row->antimafia_comune_residenza} in via {$row->antimafia_via_residenza} {$row->antimafia_civico_residenza} con codice fiscale {$row->antimafia_cf} con carica di {$row->antimafia_carica_sociale} ; ";
		}
			return $result;
}

function pdfmd5($id){
    $CI =& get_instance();
    $item = $CI -> dichiarazione_model -> get_items($id);

    $attachment = merge_pdf($item);
    $filename = './assets/domanda-iscrizione-edm_' . $item['id_anno'] . '_' .$item['id'] . '.pdf';
    $status = file_put_contents($filename, $attachment);

    $md5 = md5_file($filename);
    unlink($filename);
    return $md5;
}

function email_message($item, $hash){
    $url = site_url("domanda/upload/{$hash}");
    $msg = "Gentile {$item['name']}, \nin allegato trova il modulo PDF da Lei compilato.\n".
            "Per completare l'iscrizione all'Anagrafe Antimafia degli Esecutori, La preghiamo di firmare digitalmente ".
            "il file qui allegato e di caricare quindi il file in formato P7M tramite la funzione disponibile ".
            "al seguente indirizzo web:\n\n {$url}".
            "\n\nCordiali Saluti\nIl Nucleo Operativo Gestione Anagrafe Antimafia degli Esecutori";
    return $msg;
}

/*
███████ ███    ███  █████  ██ ██      ███████
██      ████  ████ ██   ██ ██ ██      ██
█████   ██ ████ ██ ███████ ██ ██      ███████
██      ██  ██  ██ ██   ██ ██ ██           ██
███████ ██      ██ ██   ██ ██ ███████ ███████
*/
function send_welcome_email($id) {
  $CI =& get_instance();
  $doc = $CI->dichiarazione_model->get_document($id);
  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  $CI->email->to($doc['impresa_pec']);
  $CI->email->subject('Domanda di iscrizione Anagrafe Antimafia degli Esecutori');
  $CI->email->message(email_message_new($doc));
  /*
  $CI->email->attach(
    $fileinfo['path'],
    'attachment',
    'domanda-iscrizione-anagrafe_antimafia_esecutori-' . $doc['did'] . '-'.date("Y").'.pdf'
  );
  */
  $esito = $CI->email->send();
  return $esito;
}

function email_message_new($doc){
    $url = site_url('domanda/upload/' . $doc['hash']);
    $msg = "<p>
    <b>ATTENZIONE:</b> Non rispondere a questa PEC. Seguire le istruzioni per concludere l’iscrizione.
    </p>
    <p>
      Gentile {$doc['titolare_nome']} {$doc['titolare_cognome']},<br />Per completare la domanda di iscrizione all'Anagrafe Antimafia degli Esecutori, La preghiamo di caricare la scansione di un suo documento di identità in corso di validità tramite la funzione disponibile al seguente indirizzo web: </p>
    <p><a href=\"{$url}\">{$url}</a></p>
    <p>
      <b>Attenzione:</b>
    </p>
    <ul>
      <li>
        Si consiglia di inviare un file in format pdf. Sono comunque accettati i seguenti formati: tiff, jpg, jpeg.
      </li>
      <li>
        Non saranno accettati altri documenti oltre alla carta d’identità.
      </li>
      <li>
        Verranno accettati solamente documenti inferiori ai 2.5 MB di dimensione.
      </li>
      <li>
      È possibile caricare <strong>un unico file</strong> che dovrà contenere la scannerizzazione <strong>fronte e retro</strong> della propria carta d’identità in corso di validità.
      </li>
    </ul>
    <p>
    Se non è possibile fare click sul link o se il link risulta incompleto, copia l’indirizzo completo, incollalo nella barra degli indirizzi del browser e quindi premi invio.
    </p>
    <p>
    La procedura di iscrizione risulterà termiata solo DOPO aver caricato la carta di identità all’indirizzo indicato.
    </p>
    <p>\n\nCordiali Saluti\n <br /><em>Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</em></p>";
    return $msg;
}


function send_thanks_mail($doc) {
  $CI =& get_instance();
  $data_caricamento = date('d/m/Y');
  $codice = $doc['codice_istanza'];
  $url = site_url("elenco");

  $fileinfo = create_pdf($doc['ID']);
  $csv = export_antimafia_components($doc['ID']);

  // Email utente
  $msg ="<p>
  <strong>ATTENZIONE:</strong> Non rispondere a questa PEC. Per avere supporto in fase di compilazione della domanda di iscrizione o per qualsiasi domanda è disponibile il numero di telefono 06/46529517 con i seguenti orari: dalle 9 alle 12 e dalle 15 alle 17 dal lunedì al venerdì.
  </p>
  <p>Gentile {$doc['titolare_nome']} {$doc['titolare_cognome']},</p>
  <p>
    la procedura di presentazione della sua pratica è terminata in data {$data_caricamento} e ha numero {$codice}.</p>".
      "<p>In allegato troverà il suo modulo di iscrizione.</p>".
      "<p>\n\nCordiali Saluti\n <br /><em>Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</em></p>";

  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  $CI->email->to($doc['impresa_pec']);
  $CI->email->subject('Domanda di iscrizione Anagrafe Antimafia degli Esecutori ricevuta');
  $CI->email->message($msg);

  $CI->email->attach(
    $fileinfo['path'],
    'attachment',
    'domanda-iscrizione-anagrafe_antimafia_esecutori-' . $codice . '.pdf'
  );
  $esito = $CI->email->send();

  $CI->email->clear(TRUE);




  // Email interna
  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  //@debug

  if ( 'development' == ENVIRONMENT ) {
    $CI->email->to('dp@certhidea.it');
    $CI->email->subject(sprintf(
      "[DEBUG] - Domanda iscrizione anagrafica antimafia %s %s %s %s",
      $doc['ragione_sociale'],
      $doc['partita_iva'],
      $doc['codice_fiscale'],
      $codice
    ));
  }
  else if ( 'production' == ENVIRONMENT ) {
    $CI->email->to('anagrafeantimafiasisma@pec.interno.it');
    //$CI->email->cc(array('luigi.carbone@interno.it', 'info@certhidea.it'));
    $CI->email->cc(array('info@certhidea.it'));
    $CI->email->subject(sprintf(
      "%s %s %s %s",
      $doc['ragione_sociale'],
      $doc['partita_iva'],
      $doc['codice_fiscale'],
      $codice
    ));
  }

  $CI->email->message("in allegato CSV e PDF");
  $CI->email->attach(
    $fileinfo['path'],
    'attachment',
    $codice . '.pdf'
  );
  $CI->email->attach(
    $csv['path'],
    'attachment',
    $codice . '.csv'
  );
  $esito = $CI->email->send();
  unlink($fileinfo['path']);
  unlink($csv['path']);
  return $esito;
}
