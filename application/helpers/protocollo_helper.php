<?php

/*$CI =& get_instance();
$user = $CI->config->item('protocollo_user');
$pwd = $CI->config->item('protocollo_pwd');*/

function create_xml ($dati,$protocollo_config,$file_name) {

  $xml = new SimpleXMLElement("<?xml version='1.0' ?>\n"."<SegnaturaGenerica></SegnaturaGenerica>");
  $xml->addAttribute('versione','2003-12-09');
  $dati_xml = $xml->addChild('Dati');
  $dati_xml->addAttribute('TipoProtIN', 'E');
  $id_utente = $dati_xml->addChild('IdUteIn','84022');
  $id_uo = $dati_xml->addChild('IdUOIn','96541');
  $flg_prot_gen = $dati_xml->addChild('FlgProtGen','S');
  $flg_conservazione = $dati_xml->addChild('FlgConservazione','S');
  $tipo_rep = $dati_xml->addChild('TipoRep');
  $data_upload = data_it(date("Y-m-d H:i:s"));
  $data_arrivo = $dati_xml->addChild('DtArrivoIn',$data_upload);
  $flag_comp_in = $dati_xml->addChild('FlgComplIn');
  $flag_rsv_in = $dati_xml->addChild('FlgRsvIn');
  $flag_evd_in = $dati_xml->addChild('FlgEvdIn');
  $tipo_fisico_in = $dati_xml->addChild('TipoFisicoIn');
  $tipo_logico_in = $dati_xml->addChild('TipoLogicoIn');
  $sttp_logico_in = $dati_xml->addChild('SttpLogicoIn');
  $oggetto_documento = $dati_xml->addChild('TxtOggIn','TEST WEB SERVICE PROTOCOLLAZIONE');
  $note = $dati_xml->addChild('NoteIn');
  $rif_prov_in = $dati_xml->addChild('RifProvIn');
  $prot_prov_in = $dati_xml->addChild('ProtProvIn');
  $dt_prov_in = $dati_xml->addChild('DtProvIn');
  $indice = $dati_xml->addChild('IdIndice',$protocollo_config['IdInd']);
  $id_titolazione = $dati_xml->addChild('IdTitolazione');
  $id_fascicolo = $dati_xml->addChild('IdFascicolo');
  $num_fasc = $dati_xml->addChild('NumFasc',$protocollo_config['ProgrFasc']);
  $num_sottofasc = $dati_xml->addChild('NumSottofasc',$protocollo_config['Numsottofasc']);
  $anno_fasc = $dati_xml->addChild('AnnoFasc',$protocollo_config['AnnoFasc']);
  $flag_nopubbl_in = $dati_xml->addChild('FlgNoPubblIn');
  $dt_term_nopubbl_in = $dati_xml->addChild('DtTermNoPubblIn');
  $num_rimanda = $dati_xml->addChild('NrimandaOrigIn');
  $firm = $dati_xml->addChild('Firm');
  $firm->addAttribute('flgTpAnag', 'D');
  $id_anagrafica = $firm->addChild('IdAnag');
  $ragione_sociale = $firm->addChild('RagioneSociale',$dati['ragione_sociale']);
  $par_iva = $firm->addChild('ParIva',$dati['sl_piva']);
  $indirizzi_firm = $firm->addChild('IndirizziFirm');
  $indirizzo_firm = $indirizzi_firm->addChild('Indirizzo');
  $via = $indirizzo_firm->addChild('DesInd',$dati['so_via']);
  $num_civico = $indirizzo_firm->addChild('NumCiv');
  $esp_civico = $indirizzo_firm->addChild('EspCiv');
  $comune = $indirizzo_firm->addChild('Comune',$dati['so_comune']);
  $comune->addAttribute('codiceISTAT','');
  $cap = $indirizzo_firm->addChild('CAP',$dati['so_cap']);
  $provincia = $indirizzo_firm->addChild('Provincia',$dati['so_provincia']);
  $nazione = $indirizzo_firm->addChild('Nazione');
  $telefono = $indirizzo_firm->addChild('Telefono');
  $telefono->addAttribute('note','');
  $fax = $indirizzo_firm->addChild('Fax');
  $fax->addAttribute('note','');
  $elenco_copie = $dati_xml->addChild('CopieArrIn');
  $elenco_copie->addAttribute('flgCC','');
  $elenco_copie->addAttribute('flgorig','S');
  $uo_assegnatario = $elenco_copie->addChild('UoAss');
  $uo = $uo_assegnatario->addChild('UO');
  $settore_uo = $uo->addChild('SettIn',$protocollo_config['SettIn']);
  $servizio_uo = $uo->addChild('ServIn',$protocollo_config['ServIn']);
  $uoc_uo = $uo->addChild('UOCIn',$protocollo_config['UOCIn']);
  $uos_uo = $uo->addChild('UOSIn',$protocollo_config['UOSIn']);
  $postazione_uo = $uo->addChild('PostIn',$protocollo_config['PostIn']);
  $id_ind = $elenco_copie->addChild('IdInd',$protocollo_config['IdInd']);
  $anno_fascicolo = $elenco_copie->addChild('AnnoFasc',$protocollo_config['AnnoFasc']);
  $fascicolo = $elenco_copie->addChild('ProgrFasc',$protocollo_config['ProgrFasc']);
  $sottofascicolo = $elenco_copie->addChild('Numsottofasc',$protocollo_config['Numsottofasc']);
  $documento_elettronico = $dati_xml->addChild('DocumentoElettronico');
  $documento_elettronico->addAttribute('NomeFile',$file_name);
  $documento_elettronico->addAttribute('AttivaVerificaFirma','1');
  $pdf_encode = base64_encode(file_get_contents('./uploads/'.$file_name));
  $pdf_base64 = encode($pdf_encode);
  //base64pdf($file_name);
  $dati_pdf = $documento_elettronico->addChild('dati',$pdf_base64);

  $xml_string = $xml->asXML();
  //echo $xml_string;
  return $xml_string;
}

function encode ($pdfencode) {
    $data64 = "";
    while (strlen($pdfencode) > 64) {
        $data64 .= substr($pdfencode, 0, 64) . "\n";
        $pdfencode = substr($pdfencode,64);
    }
    $data64 .= $pdfencode;
    return $data64;
}


function base64pdf ($file_nome) {
$pdf_file = './uploads/'.$file_nome;
$pdfbinary = fread(fopen($pdf_file,"r"),filesize($pdf_file));
$pdf_base64 = base64_encode(file_get_contents($pdf_file));
//$pdf_base64 = base64_encode($pdfbinary);
return  $pdf_base64;
}

function data_it($data)
{
  // Creo una array dividendo la data sulla base dello spazio

  $date = explode(" ",$data);
   // Creo una array dividendo la data sulla base del trattino
  $array = explode ("-", $date[0]);

  // Riorganizzo gli elementi in stile DD/MM/YYYY
  $data_it = $array[2]."/".$array[1]."/".$array[0];

  // Restituisco il valore della data in formato italiano
  return $data_it;
}

//MODIFICA DI SL PER IL CAMBIO PIATTAFORMA E-GRAMMATA 17/07/2014
function create_hash ($xml_ws,$psw) {
$concat_string = $xml_ws.$psw;
$sha_string = sha1($concat_string,TRUE);
$hash = base64_encode($sha_string);
return $hash;
}


function ws_crea_anagrafica ($dati,$protocollo_config,$file_name) {

try {
	//$wsdl_url="https://protocollosvil.ente.regione.emr.it/axisSviluppo/services/WSProtocollazioneAllegatiBase64?wsdl";

	//MODIFICA DI SL PER IL CAMBIO PIATTAFORMA E-GRAMMATA 17/07/2014
	$wsdl_url = "https://test-protocollo.ente.regione.emr.it/protoemilia/services/WSProtocollazioneAllegati?wsdl";

/*  $valid = checkdnsrr($wsdl_url, "ANY");
	if(!$valid) {


$string = <<<XML
<?xml version='1.0'?>
    <Risposta>
      <Stato>
        <Codice>1</Codice>
          <Messaggio>Non sei agganciato a nessun Web Service! Guardati intorno forse non sei in Regione!</Messaggio>
       </Stato>
    </Risposta>
XML;

	$dummy_object = simplexml_load_string($string);

	return $dummy_object;

}
else {*/

	$ente_id = "1";
	$user = "USRELMEEDIL";
	$psw = "ELMEEDIL_ENTE01";
	$xml_ws = create_xml($dati,$protocollo_config,$file_name);
	//MODIFICA DI SL PER IL CAMBIO PIATTAFORMA E-GRAMMATA 17/07/2014
	$hash = create_hash($xml_ws,$psw);

	$client= new SoapClient($wsdl_url);

    $results = $client->serviceBase64($ente_id,$user,$psw,$wsdl_url,$xml_ws,$hash);

	$object = simplexml_load_string($results);

	return $object;
	}
/*}*/

	catch (Exception $e) {
    var_dump($e->getMessage());


	}


}



?>
