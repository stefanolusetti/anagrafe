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
		$CI->email->from('stefano.lusetti@certhidea.it', 'Commissario Straordinario Ricostruzione Sisma 2016');
		$CI->email->to($item['sl_pec']);
		$CI->email->subject('Domanda di iscrizione elenco di merito');
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
            "Per completare l'iscrizione all'elenco di merito, La preghiamo di firmare digitalmente ".
            "il file qui allegato e di caricare quindi il file in formato P7M tramite la funzione disponibile ".
            "al seguente indirizzo web:\n\n {$url}".
            "\n\nCordiali Saluti\nIl Nucleo Operativo Gestione Elenco di Merito";
    return $msg;
}
