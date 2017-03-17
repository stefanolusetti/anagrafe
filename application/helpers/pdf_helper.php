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
      Gentile {$doc['titolare_nome']} {$doc['titolare_cognome']},<br />Per completare la domanda di iscrizione all'Anagrafe Antimafia degli Esecutori, La preghiamo di collegarsi al seguente indirizzo web:</p>
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


function the_test_mail() {
  $CI =& get_instance();
  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  $CI->email->to('certhidea@pec.it');
  $CI->email->subject('TEST LAYOUT');
  $CI->email->message('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  <head>
  <meta name="viewport" content="width=device-width" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Actionable emails e.g. reset password</title>
  <link href="http://parcoinnovazione.it/email/styles.css" media="all" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,700" rel="stylesheet" />
  </head>

  <body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6">

  <table style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
  	<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  		<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td>
  		<td width="900" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;" valign="top">
  			<div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 800px; display: block; margin: 0 auto; padding: 0;">
  				<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
            <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; background-color: #00264D; margin: 0;" bgcolor="#00264D">
              <td width="140" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: middle; text-align: center; margin: 0; padding: 10px 20px 10px 10px;" align="center" valign="middle">
                <img src="http://parcoinnovazione.it/email/images/logo-gov.svg" width="60" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 100%; margin: 0;" />
              </td>
  						<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 33px; vertical-align: middle; text-align: left; color: #FFFFFF; margin: 0;" align="left" valign="middle">
  							Ministero dell\'interno
                <div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: lighter; font-style: italic; margin: 0;">Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</div>
  						</td>
  					</tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
  					<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  						<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 10px;" valign="top">
  							<meta itemprop="name" content="Confirm Email" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;" />
  							<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  								<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  									<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
  										<strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">OGGETTO:</strong> Avvio istruttoria per richiesta di iscrizione nell’ “Anagrafe Antimafia degli Esecutori” istituita dall’art. 30, comma 6 del d.l. n.189 del 2016 convertito in Legge n. 229 del 2016.
                      <ul style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">
                          <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">Art. 8 (Interventi di immediata esecuzione)</strong>
                        </li>
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">
                          Società: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">BONAFACCIA DAMIANO</strong>
                        </li>
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">
                          Sede: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">VIALE DE IULIIS G. 15D, RIETI (RI)</strong>
                        </li>
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">
                          Codice Fiscale / Partita IVA: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">01091970572</strong>
                        </li>
                      </ul>
  									</td>
  								</tr>
                  <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  									<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
  										TESTO LIBERO
  									</td>
  								</tr>
  								<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  									<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                      <p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Con Circolare n.15006/2(1) del 28 novembre 2016, recante prime indicazioni operative concernenti le modalità di iscrizione nell’Anagrafe Antimafia degli Esecutori, il Ministero dell’Interno ha, tra l’altro, richiamato l’attenzione sull’art. 8 del D.L. in oggetto (ora convertito in Legge 15 dicembre 2016 n.229) che detta disposizioni volte ad agevolare il rientro dei cittadini nelle unità immobiliari interessate da danni lievi che necessitano, quindi, soltanto di “interventi di immediata riparazione”.
                      </p><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Le prime Linee Guida adottate dal Comitato di cui all’art. 203 del d.lgs. n.50 del 2016 (in corso di pubblicazione) prevedono che, nel caso in cui l’operatore economico non sia già censito in Banca Dati Nazionale Unica ovvero sia iscritto nelle White List delle Prefetture in data anteriore a tre mesi precedenti l’entrata in vigore del D.L. n. 189 (19 ottobre 2016) e, comunque, quando si rendano necessari approfondimenti istruttori, questa Struttura avvii una procedura speditiva per la verifica dell’esistenza o meno delle situazioni ex artt. 67 e 84, comma 4, lett. a), b) e c) del D. Lgs. n. 159 del 2011 (Codice Antimafia) nei confronti dell’impresa esaminata attraverso la consultazione della Banca Dati Nazionale Unica richiedendo al contempo:
                      </p>
                      <ol style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">alla D.I.A. un riscontro sulla attualità delle eventuali segnalazioni di tentativi di infiltrazioni mafiose attraverso la consultazione della banca dati del Sistema Informatico Rilevamento Accesso Cantieri (SIRAC) e del Sistema di indagine delle Forze di Polizia (SDI);</li>
                        <li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">alla Prefettura competente per territorio le eventuali risultanze esistenti agli atti nei confronti dei soggetti sottoposti alla verifica antimafia.</li>
                      </ol>
                      <p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">
                       Tanto premesso, si trasmettono in allegato i prospetti riepilogativi relativi all’impresa da verificare (un estratto dalla B.D.N.U. in formato “pdf” ed un file in formato “csv”), con preghiera di far pervenire la risposta a questa Struttura entro 10 giorni dalla data del ricevimento della presente all’indirizzo PEC “antimafiasisma@pec.interno.it” al fine di consentire a questa medesima Struttura l’iscrizione provvisoria in Anagrafe (la mancata risposta della DIA entro il predetto termine è considerata come comunicazione negativa).</p>
                      <p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Per la successiva iscrizione definitiva nell’Anagrafe in oggetto, si prega, inoltre, codesta Prefettura di fornire, allegando copia della relativa documentazione, ogni ulteriore elemento utile di valutazione, desunto dagli atti e acquisito per il tramite delle Forze di polizia territoriali (ad esclusione del locale Centro Operativo DIA in quanto già attivato dagli Uffici centrali della DIA di Roma), entro 30 giorni sempre decorrenti dalla data del ricevimento della presente nota.
                      </p>
  									</td>
  								</tr>
  								<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  									<td itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
  										<a href="http://www.mailgun.com" itemprop="url" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Confirm email address</a>
  									</td>
  								</tr>
  								<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  									<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
  										— The Mailgunners
  									</td>
  								</tr>
  							</table>
  						</td>
  					</tr>
  				</table>
  				<div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
  					<table width="100%" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  						<tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">
  							<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">Follow <a href="http://twitter.com/mail_gun" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">@Mail_Gun</a> on Twitter.</td>
  						</tr>
  					</table>
  				</div></div>
  		</td>
  		<td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td>
  	</tr>
  </table>

  </body>
  </html>
');
  $esito = $CI->email->send();
}
