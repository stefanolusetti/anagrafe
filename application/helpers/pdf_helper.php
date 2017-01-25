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
    $id_anno = substr($doc['doc_date'], 0, 4);
    $CI->load->helper('fdf');

    if('Altro' == $doc['company_role']) {
      $role_label = $doc['ruolo_richiedente'];
    }
    else {
      $role_label = $doc['company_role'];
    }

    $shapes = $CI->dichiarazione_model->get_company_shape_label($doc['company_shape']);
    if (!empty($shapes)) {
      $shape_label = $shapes[0]['value'];
    }

    $birth_locality = $doc['birth_locality'];
    if ( !empty($doc['birth_nation']) ) {
      $birth_locality .= sprintf(" (%s)", $doc['birth_nation']);
    }

    $index_data = array(
      'id_istanza' => $id,
      'id_anno' => $id_anno,

      'nome_cognome' => $doc['name'] . ' ' . $doc['lastname'],
      'birth_locality' => $birth_locality,
      'birth_province' => $doc['birth_province'],
      'birth_date' => format_date($doc['birth_date']),
      'residence_city' => $doc['residence_city'],
      'residence_province' => $doc['residence_province'],
      'residence_zip' => $doc['residence_zip'],
      'residence_street' => $doc['residence_street'],

      'company_role_label' => $role_label,
      'company_name' => $doc['company_name'],
      'company_birthdate' => format_date($doc['company_birthdate']),
      'company_shape_label' => $shape_label,

      'company_locality' => $doc['company_locality'],
      'company_zip' => $doc['company_zip'],
      'company_province' => $doc['company_province'],
      'company_street' => $doc['company_street'],
      'company_num' => $doc['company_num'],
      'company_phone' => $doc['company_phone'],
      'company_mobile' => $doc['company_mobile'],
      'company_fax' => $doc['company_fax'],
      'company_vat' => $doc['company_vat'],
      'company_cf' => $doc['company_cf'],
      'company_pec' => $doc['company_pec'],
      'company_mail' => $doc['company_mail'],
      'company_offices' => $doc['company_altre_sedi'],

      'rea_location' => $doc['rea_location'],
      'rea_subscription' => $doc['rea_subscription'],
      'rea_number' => $doc['rea_number'],
      'company_social_subject' => $doc['company_social_subject'],

      'company_num_admins' => $doc['company_num_admins'],
      'company_num_attorney' => $doc['company_num_attorney'],
      'company_num_majors' => $doc['company_num_majors'],
      'company_num_majors_tmp' => $doc['company_num_majors_tmp'],

      'doc_location' => $doc['doc_location'],
      'doc_date' => format_date($doc['doc_date']),
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
      'stmt_wl_name' => $doc['stmt_wl_name'],
      'stmt_wl_date' => empty($doc['stmt_wl_date']) ? '' : format_date($doc['stmt_wl_date']),
      'stmt_eligible' => $doc['stmt_eligible'],

      'stmt_interest' => 'Yes',
      'sake_work' => $doc['sake_work'] == 1 ? 'Yes' : 'No',
      'sake_work_type' => $doc['sake_work_type'],
      'sake_work_amount' => $doc['sake_work_amount'] == 0 ? '' : $doc['sake_work_amount'],

      'sake_service' => $doc['sake_service'] == 1 ? 'Yes' : 'No',
      'sake_service_type' => $doc['sake_service_type'],
      'sake_service_amount' => $doc['sake_service_amount'] == 0 ? '' : $doc['sake_service_amount'],

      'sake_supply' => $doc['sake_supply'] == 1 ? 'Yes' : 'No',
      'sake_supply_type' => $doc['sake_supply_type'],
      'sake_supply_amount' => $doc['sake_supply_amount'] == 0 ? '' : $doc['sake_supply_amount'],

      'sake_fix' => $doc['sake_fix'] == 1 ? 'Yes' : 'No',
      'sake_fix_type' => $doc['sake_fix_type'],
      'sake_fix_amount' => $doc['sake_fix_amount'] == 0 ? '' : $doc['sake_fix_amount'],

      'company_field_none' => $doc['company_field_none'] == 1 ? 'Yes' : 'No',

      'company_field_trasporto' => $doc['company_field_trasporto'] == 1 ? 'Yes' : 'No',
      'company_field_rifiuti' => $doc['company_field_rifiuti'] == 1 ? 'Yes' : 'No',
      'company_field_terra' => $doc['company_field_terra'] == 1 ? 'Yes' : 'No',
      'company_field_bitume' => $doc['company_field_bitume'] == 1 ? 'Yes' : 'No',
      'company_field_nolo' => $doc['company_field_nolo'] == 1 ? 'Yes' : 'No',
      'company_field_ferro' => $doc['company_field_ferro'] == 1 ? 'Yes' : 'No',
      'company_field_autotrasporto' => $doc['company_field_autotrasporto'] == 1 ? 'Yes' : 'No',
      'company_field_guardiana' => $doc['company_field_guardiana'] == 1 ? 'Yes' : 'No',
    );

    $fdf = new CerthideaFDF();
    //$fdf->addPage('indice.pdf', $index_data);
    $_pages[] = array('file' => 'indice.pdf', 'data' => $index_data);

/*
 ██████  ███████ ███████ ██  ██████ ███████ ███████
██    ██ ██      ██      ██ ██      ██      ██
██    ██ █████   █████   ██ ██      █████   ███████
██    ██ ██      ██      ██ ██      ██           ██
 ██████  ██      ██      ██  ██████ ███████ ███████
*/
    $doc_offices = $CI->dichiarazione_model->get_item_offices($id);
    if(!empty($doc_offices)) {
      // 12 per pagina
      $_num_pages = ceil(count($doc_offices) / 12);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $office_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno
        );
        for ( $j = 0; $j < 12; $j++ ) {
          if (!empty($doc_offices)) {
            $office = array_shift($doc_offices);
            $office_data["name_$j"] = $office['name'];
            $office_data["piva_$j"] = $office['vat'];
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
    $familiars = array();
    if ( !empty($doc['anagrafiche_antimafia']) ) {
      $role_list = $CI->dichiarazione_model->get_roles();
      // 4 per pagina
      $_num_pages = ceil(count($doc['anagrafiche_antimafia']) / 4);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $anagrafica_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno
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
          $anagrafica_data["nome_cognome_$j"] = $anagrafica['antimafia_nome'] . ' ' . $anagrafica['antimafia_cognome'];
          $anagrafica_data["cf_$j"] = $anagrafica['antimafia_cf'];
          $anagrafica_data["ruolo_$j"] = $role_list[$anagrafica['role_id']];
          $anagrafica_data["birth_locality_$j"] = $anagrafica['antimafia_comune_nascita'];
          $anagrafica_data["birth_province_$j"] = $anagrafica['antimafia_provincia_nascita'];
          $anagrafica_data["birth_date_$j"] = format_date($anagrafica['antimafia_data_nascita']);

          $anagrafica_data["residence_city_$j"] = $anagrafica['antimafia_comune_residenza'];
          $anagrafica_data["residence_province_$j"] = $anagrafica['antimafia_provincia_residenza'];
          $anagrafica_data["residence_zip_$j"] = $anagrafica['antimafia_civico_residenza'];
          $anagrafica_data["residence_street_$j"] = $anagrafica['antimafia_via_residenza'];
          $anagrafica_data["residence_number_$j"] = $anagrafica['antimafia_civico_residenza'];

        }
        //$fdf->addPage('anagrafiche-componenti.pdf', $anagrafica_data);
        $_pages[] = array('file' => 'anagrafiche-componenti.pdf', 'data' => $anagrafica_data);
      }
    }

    if ( 0 != count($familiars)) {
      // 6 per pagina.
      $_num_pages = ceil(count($familiars) / 6);
      for ( $i = 0; $i < $_num_pages; $i++ ) {
        $familiari_data = array(
          'id_istanza' => $id,
          'id_anno' => $id_anno
        );
        for ( $j = 0; $j < 6; $j++ ) {
          if ( empty($familiars) ) {
            break;
          }
          $familiare = array_shift($familiars);

          $familiari_data["role_$j"] = $role_list[$familiare['rid']];
          $familiari_data["parent_$j"] = $familiare['parent'];

          $familiari_data["nome_cognome_$j"] = $familiare['nome'] . ' ' . $familiare['cognome'];
          $familiari_data["cf_$j"] = $familiare['cf'];
          $familiari_data["birth_locality_$j"] = $familiare['comune'];
          $familiari_data["birth_province_$j"] = $familiare['provincia_nascita'];
          $familiari_data["birth_date_$j"] = format_date($familiare['data_nascita']);
        }
        //$fdf->addPage('anagrafiche-familiari.pdf', $familiari_data);
        $_pages[] = array('file' => 'anagrafiche-familiari.pdf', 'data' => $familiari_data);
      }
    }

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
    $msg = "Gentile {$item['titolare_nome']}, \nin allegato trova il modulo PDF da Lei compilato.\n".
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

  $hash = hash('md5', $doc['company_name']);
  $CI->db->where('did', $doc['did'])->update('docs', array('hash' => $hash));

  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  $CI->email->to($doc['company_pec']);
  $CI->email->subject('Domanda di iscrizione Anagrafe Antimafia degli Esecutori');
  $CI->email->message(email_message_new($doc, $hash));
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

function email_message_new($doc, $hash){
    $url = site_url("domanda/upload/{$hash}");
    $msg = "<p>
    <b>ATTENZIONE:</b> Non rispondere a questa PEC. Seguire le istruzioni per concludere l’iscrizione.
    </p>
    <p>
      Gentile {$doc['name']} {$doc['lastname']},<br />Per completare la domanda di iscrizione all'Anagrafe Antimafia degli Esecutori, La preghiamo di collegarsi al seguente indirizzo web:</p>
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
  $codice = $doc['did'] . '-' . substr($doc['doc_date'], 0, 4);
  $url = site_url("elenco");

  $fileinfo = create_pdf($doc['did']);
  $csv = export_antimafia_components($doc['did']);

  // Email utente
  $msg ="<p>
  <strong>ATTENZIONE:</strong> Non rispondere a questa PEC. Per qualsiasi comunicazione utilizzare l’indirizzo: (XXXXXXXX)
  </p>
  <p>Gentile {$doc['name']} {$doc['lastname']},</p>
  <p>
    la procedura di presentazione della sua pratica è terminata in data {$data_caricamento} e ha numero {$codice}.</p>".
      "<p>In allegato troverà il suo modulo di iscrizione.</p>".
      "<p>\n\nCordiali Saluti\n <br /><em>Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</em></p>";

  $CI->email->from('anagrafeantimafiasisma@pec.interno.it', 'Struttura di Missione del Ministero dell\'Interno');
  $CI->email->to($doc['company_pec']);
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

  $CI->email->to('anagrafeantimafiasisma@pec.interno.it');
  $CI->email->cc('luigi.carbone@interno.it');
  $CI->email->bcc('info@certhidea.it');

  $CI->email->subject(sprintf(
    "Domanda iscrizione anagrafica antimafia %s %s %s",
    $doc['company_name'],
    $doc['company_vat'],
    $codice
  ));
  $CI->email->message("in allegato CSV e PDF");
  $CI->email->attach(
    $fileinfo['path'],
    'attachment',
    'domanda-iscrizione-anagrafe_antimafia_esecutori-' . $codice . '.pdf'
  );
  $CI->email->attach(
    $csv['path'],
    'attachment',
    'elenco-componenti-antimafia-' . $codice . '.csv'
  );
  $esito = $CI->email->send();
  unlink($fileinfo['path']);
  unlink($csv['path']);
  return $esito;
}
