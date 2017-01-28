
<?php
class Elenco extends CI_Controller {

    public function __construct() {
        
        parent::__construct();
        
        $this -> load -> library('parser');
        $this -> load -> library('session');
        $this -> load -> library('pagination');
		
		$this -> load -> library('excel');
        
        $this -> load -> model('admin_model');
        $this -> load -> model('comuni_model');
		$this -> load -> model('elenco_model');
        
        $this -> load -> helper('url');
        $this -> load -> helper('form');
        $this -> load -> helper('application');
		
		
        
    } 
	
	
	 public function iscritti($offset = 0) {
	
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $limit = 25; //modifico in via temporanea il limite di paginazione
        //$limit = 50;
        $config['per_page'] = $limit;
        $params = $this->input->get(NULL, TRUE);
	
        if (empty($params))
        {
            
            $data['statements'] = $this -> admin_model -> find_items_esecutori_iscritti(FALSE, $offset, $limit);
            $config['uri_segment'] = 3;
            //$config['base_url'] = site_url('/elenco/index/');
			$config['base_url']=site_url("/elenco/iscritti?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
         
            
            
        }  else 
			
        { 
           
            //if($params['provincia'] == 'altre')
              //  $params['provincia'] = "altre";
            
            $data['statements'] = $this -> admin_model -> find_items_esecutori_iscritti($params, $this->input->get("per_page"), $limit);
            $config['base_url'] = site_url("/elenco/iscritti?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            
        }
        
        unset($data['statements']['rowcount']);
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('elenco/iscritti', $data);
        $this -> load -> view('templates/footer');
		
		
		
	}
	public function iscritti_provv($offset = 0) {
	
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $limit = 25; //modifico in via temporanea il limite di paginazione
        //$limit = 50;
        $config['per_page'] = $limit;
        $params = $this->input->get(NULL, TRUE);
	
        if (empty($params))
        {
         
            $data['statements'] = $this -> admin_model -> find_items_esecutori_iscritti_provv(FALSE, $offset, $limit);
            $config['uri_segment'] = 3;
            //$config['base_url'] = site_url('/elenco/index/');
			 $config['base_url'] = site_url("/elenco/iscritti_provv?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
    
            
            
        }  else 
			
        { 
       
           
            $data['statements'] = $this -> admin_model -> find_items_esecutori_iscritti_provv($params, $this->input->get("per_page"), $limit);
            $config['base_url'] = site_url("/elenco/iscritti_provv?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            
        }
        
        unset($data['statements']['rowcount']);
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('elenco/iscritti', $data);
        $this -> load -> view('templates/footer');
		
		
		
	}
		public function richiesta($offset = 0) {
	
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        
        //$limit = 25; //modifico in via temporanea il limite di paginazione
        $limit = 25;
        $config['per_page'] = $limit;
        $params = $this->input->get(NULL, TRUE);
	
        if (empty($params))
        {
         
            $data['statements'] = $this -> admin_model -> find_items_esecutori_richiesta(FALSE, $offset, $limit);
            $config['uri_segment'] = 3;
            //$config['base_url'] = site_url('/elenco/index/');
			$config['base_url'] = site_url("/elenco/richiesta?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            
            
        }  else 
			
        { 
            
        
           
            $data['statements'] = $this -> admin_model -> find_items_esecutori_richiesta($params, $this->input->get("per_page"), $limit);
            $config['base_url'] = site_url("/elenco/richiesta?ragione_sociale=&partita_iva=&codice_fiscale=&tipo_attivita=");
            $config['total_rows'] = $data['statements']['rowcount'];
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            
        }
        
        unset($data['statements']['rowcount']);
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('elenco/iscritti', $data);
        $this -> load -> view('templates/footer');
		
		
		
	}
	

    public function index($offset = 0) {
	
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $this -> admin_model -> inserisci_valore();
        //$limit = 25; //modifico in via temporanea il limite di paginazione
        $limit = 50;
        $config['per_page'] = $limit;
        $params = $this->input->get(NULL, TRUE);
        if (empty($params))
        {
            $params = array('tipo_contratto' => 'Edilizia','provincia' => '');
            $data['statements'] = $this -> admin_model -> find_items(FALSE, $offset, $limit);
            $config['uri_segment'] = 3;
            //$config['base_url'] = site_url('/elenco/index/');
			$config['base_url']=site_url("/elenco/index?provincia={$params['provincia']}&tipo_contratto={$params['tipo_contratto']}&ragione_sociale=&sl_piva=");
            $config['total_rows'] = $data['statements']['rowcount'];
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $_GET['tipo_contratto'] = "Edilizia"; // dirty hack per impostare il campo tipo contratto su Edilizia
            
            
        }  else 
        { 
            
            if($params['provincia'] == 'altre')
                $params['provincia'] = "altre";
            
            $data['statements'] = $this -> admin_model -> find_items($params, $this->input->get("per_page"), $limit);
            $config['base_url'] = site_url("/elenco/index?provincia={$params['provincia']}&ateco={$params['ateco']}&soa={$params['soa']}&tipo_contratto={$params['tipo_contratto']}&ragione_sociale=&sl_piva=");
            $config['total_rows'] = $data['statements']['rowcount'];
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            
        }
        
        unset($data['statements']['rowcount']);
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('elenco/index', $data);
        $this -> load -> view('templates/footer');
		
		
		
	}
public function open_data () {
$data['statements'] = $this -> admin_model -> find_items(FALSE, 0, 10000);


  $xml = new SimpleXMLElement("<Imprese></Imprese>");
  $xml->addAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
  $xml->addAttribute('copyright','http://dati.emilia-romagna.it/media/rdp/comune/licenze/licenza_Elenco_di_merito_CC-BY.pdf');
  $xml->addAttribute('source','Base dati realizzata da Regione Emilia-Romagna - Osservatorio dei contratti pubblici');
  
  foreach ($data['statements'] as $item):
  
  $impresa_xml = $xml->addChild('Impresa');
  $ragione_sociale = $impresa_xml->ragionesociale=$item['ragione_sociale'];
  $p_iva = $impresa_xml->addChild('PartitaIva', $item['sl_piva']);
  $n_dipendenti = $impresa_xml->addChild('NumeroDipendenti', $item['addetti_n_dipendenti']);
  $riferimenti = $impresa_xml->addChild('Riferimenti');
  $comune = $riferimenti->addChild('Comune',$item['sl_comune']);
  $provincia = $riferimenti->addChild('Provincia',$item['sl_prov']);
  $cap = $riferimenti->addChild('Cap',$item['sl_cap']);
  $via = $riferimenti->addChild('Via',$item['sl_via']);
  $civico = $riferimenti->addChild('Civico',$item['sl_civico']);
  $telefono = $riferimenti->addChild('Telefono',$item['sl_tel']);
  $mail = $riferimenti->addChild('Mail',$item['sl_email']);
  $attestazioni_soa=$impresa_xml->addChild('AttestazioniSOA');
  
  $soas['soa']= $this -> admin_model ->elenco_soas($item['id']);
  
  foreach ($soas['soa'] as $soa):
  
  $codice = $attestazioni_soa->addChild('Codice',$soa['codice']);
  $denominazione = $attestazioni_soa->addChild('Denominazione',$soa['denominazione']);
  $classe = $attestazioni_soa->addChild('Classe',$soa['classe']);
  endforeach;
  
  
  $classifica_ateco=$impresa_xml->addChild('ClassificaATECO');
  
  $atecos['ateco']= $this -> admin_model ->elenco_atecos($item['id']);
  
  foreach ($atecos['ateco'] as $ateco):
  $codice = $classifica_ateco->addChild('Codice',$ateco['codice']);
  $denominazione = $classifica_ateco->addChild('Denominazione',$ateco['denominazione']);
  endforeach;
  
  endforeach;

  $xml_string = $xml->asXML();
  header('Content-type: text/xml');

  echo $xml_string;
  //return $xml_string;

/*
foreach ($data['statements'] as $item):
$json_element = array(
"impresa"=>array (
"azienda" => array (
"ragione_sociale" => $item['ragione_sociale'],
"partita_iva" => $item['sl_piva'],
"numero_dipendenti"=>$item['addetti_n_dipendenti']
),
"riferimenti"=>array(
"comune" => $item['sl_comune'],
"provincia" =>$item['sl_prov'],
"cap" =>$item['sl_cap'],
"via"=>$item['sl_via'], 
"civico"=>$item['sl_civico'],
"telefono"=>$item['sl_tel'],
"mail"=>$item['sl_email']
),
"attestazioni soa" => $this -> admin_model ->elenco_soas_open_data($item['id']),
"classifica ateco"=>$this -> Admin_model ->elenco_atecos_open_data($item['id']),
)
);

//echo json_encode($json_element);
echo json_last_error();
endforeach;
}

*/
    
}
public function export_excel () {
	
	$comuni = $this->db->get('comuni');
	$data['statements'] = $this -> admin_model -> find_items(FALSE, 0, 10000);
	
	
$this->excel->setActiveSheetIndex(0)
							->setCellValue('A1', 'PARTITA_IVA')
                            ->setCellValue('B1', 'CODICE_FISCALE')
                            ->setCellValue('C1', 'RAGIONE_SOCIALE')
                            ->setCellValue('D1', 'INDIRIZZO')
                            ->setCellValue('E1', 'ANNO_INIZIO')
                            ->setCellValue('F1', 'NUMERO_DIPENDENTI')
							->setCellValue('G1', 'TELEFONO')
							->setCellValue('H1', 'EMAIL')
							->setCellValue('I1', 'SETTORE')
							->setCellValue('J1', 'OG1-Edifici civili e industriali')
						    ->setCellValue('K1', 'OG2-Restauro e manutenzione dei beni immobili sottoposti a tutela')
							->setCellValue('L1', 'OG3-Strade, autostrade, ponti, viadotti, ferrovie, metropolitane')
							->setCellValue('M1', 'OG4-Opere arte nel sottosuolo')
							->setCellValue('N1', 'OG5-Dighe')
							->setCellValue('O1', 'OG6-Acquedotti, gasdotti, oleodotti, opere di irrigazione e di evacuazione')
							->setCellValue('P1', 'OG7-Opere marittime e lavori di dragaggio')
							->setCellValue('Q1', 'OG8-Opere fluviali, di difesa, di sistemazione idraulica e di bonifica')
							->setCellValue('R1', 'OG9-Impianti per la produzione di energia elettrica')
							->setCellValue('S1', 'OG10-Impianti per la trasformazione alta/media tensione e per la distribuzione di energia elettrica in corrente alternata e continua ed impianti di pubblica illuminazione')
							->setCellValue('T1', 'OG11-Impianti tecnologici')
							->setCellValue('U1', 'OG12-Opere ed impianti di bonifica e protezione ambiente')
							->setCellValue('V1', 'OG13-Opere di ingegneria naturalistica')
							->setCellValue('W1', 'OS1-Lavori in terra')
							->setCellValue('X1', 'OS2-A-Superfici decorate di beni immobili del patrimonio culturale e beni culturali mobili di interesse storico, artistico, archeologico ed etnoantropologico')
							->setCellValue('Y1', 'OS2-B-Beni culturali mobili di interesse archivistico e librario')
							->setCellValue('Z1', 'OS3-Impianti idrico-sanitario, cucine, lavanderie')
							->setCellValue('AA1', 'OS4-Impianti elettromeccanici trasportatori')
							->setCellValue('AB1', 'OS5-Impianti pneumatici e antintrusione')
							->setCellValue('AC1', 'OS6-Finiture di opere generali in materiali lignei, plastici, metallici e vetrosi')
							->setCellValue('AD1', 'OS7-Finiture di opere generali di natura edile')
							->setCellValue('AE1', 'OS8-Opere di impermeabilizzazione')
							->setCellValue('AF1', 'OS9-Impianti per la segnaletica luminosa e la sicurezza del traffico')
							->setCellValue('AG1', 'OS10-Segnaletica stradale non luminosa')
							->setCellValue('AH1', 'OS11-Apparecchiature strutturali speciali')
							->setCellValue('AI1', 'OS12-A-Barriere stradali di sicurezza')
							->setCellValue('AJ1', 'OS12-B-Barriere paramassi, fermaneve e simili')
							->setCellValue('AK1', 'OS13-Impianti di smaltimento e recupero dei rifiuti')
							->setCellValue('AL1', 'OS14-Impianti per centrali di produzione energia elettrica')
							->setCellValue('AM1', 'OS15-Linee telefoniche ed impianti di telefonia')
							->setCellValue('AN1', 'OS16-Strutture prefabbricate in cemento armato')
							->setCellValue('AO1', 'OS17-Pulizie di acque marine, lacustri, fluviali')
							->setCellValue('AP1', 'OS18-A-Componenti strutturali in acciaio')
							->setCellValue('AQ1', 'OS18-B-Componenti per facciate continue')
							->setCellValue('AR1', 'OS19-Impianti di reti di telecomunicazione e di trasmissione dati')
							->setCellValue('AS1', 'OS20-A-Rilevamenti topografici')
							->setCellValue('AT1', 'OS20-B-Indagini geognostiche')
							->setCellValue('AU1', 'OS21-Opere strutturali speciali')
							->setCellValue('AV1', 'OS22-Impianti di potabilizzazione e depurazione')
							->setCellValue('AW1', 'OS23-Demolizione di opere')
							->setCellValue('AX1', 'OS24-Verde e arredo urbano')
							->setCellValue('AY1', 'OS25-Scavi archeologici')
							->setCellValue('AZ1', 'OS26-Pavimentazioni e sovrastrutture speciali')
							->setCellValue('BA1', 'OS27-Impianti per la trazione elettrica')
							->setCellValue('BB1', 'OS28-Impianti termici e di condizionamento')
							->setCellValue('BC1', 'OS29-Armamento ferroviario')
							->setCellValue('BD1', 'OS30-Impianti interni elettrici, telefonici, radiotelefonici e televisivi')
							->setCellValue('BE1', 'OS31-Impianti per la mobilità sospesa')
							->setCellValue('BF1', 'OS32-Strutture in legno')
							->setCellValue('BG1', 'OS33-Coperture speciali')
							->setCellValue('BH1', 'OS34-Sistemi antirumore per infrastrutture di mobilità')
							->setCellValue('BI1', 'OS35-Interventi a basso impatto ambientale')
							->setCellValue('BJ1', '41.10.00-Sviluppo di progetti immobiliari senza costruzione')
							->setCellValue('BK1', '41.20.00-Costruzione di edifici residenziali e non residenziali')
							->setCellValue('BL1', '42.11.00-Costruzione di strade, autostrade e piste aeroportuali')
							->setCellValue('BM1', '42.12.00-Costruzione di linee ferroviarie e metropolitane')
							->setCellValue('BN1', '42.13.00-Costruzione di ponti e gallerie')
							->setCellValue('BO1', '42.21.00-Costruzione di opere di pubblica utilità per il trasporto di fluidi')
							->setCellValue('BP1', '42.22.00-Costruzione di opere di pubblica utilità per energia elettrica e le telecomunicazioni')
							->setCellValue('BQ1', '42.91.00-Costruzione di opere idrauliche')
							->setCellValue('BR1', '42.99.01-Lottizzazione dei terreni connessa con urbanizzazione')
							->setCellValue('BS1', '42.99.09-Altre attività di costruzione di altre opere di ingegneria civile nca')
							->setCellValue('BT1', '43.11.00-Demolizione')
							->setCellValue('BU1', '43.12.00-Preparazione del cantiere edile e sistemazione del terreno')
							->setCellValue('BV1', '43.13.00-Trivellazioni e perforazioni')
							->setCellValue('BW1', '43.21.01-Installazione di impianti elettrici in edifici o in altre opere di costruzione (inclusa manutenzione e riparazione)')
							->setCellValue('BX1', '43.21.02-Installazione di impianti elettronici (inclusa manutenzione e riparazione)')
							->setCellValue('BY1', '43.21.03-Installazione impianti di illuminazione stradale e dispositivi elettrici di segnalazione, illuminazione delle piste degli aeroporti (inclusa manutenzione e riparazione)')
							->setCellValue('BZ1', '43.22.01-Installazione di impianti idraulici, di riscaldamento e di condizionamento di aria (inclusa manutenzione e riparazione) in edifici o in altre opere di costruzione')
							->setCellValue('CA1', '43.22.02-Installazione di impianti per la distribuzione del gas (inclusa manutenzione e riparazione)')
							->setCellValue('CB1', '43.22.03-Installazione di impianti di spegnimento antincendio (inclusi quelli integrati e la manutenzione e riparazione)')
							->setCellValue('CC1', '43.22.04-Installazione di impianti di depurazione per piscine (inclusa manutenzione e riparazione)')
							->setCellValue('CD1', '43.22.05-Installazione di impianti di irrigazione per giardini (inclusa manutenzione e riparazione)')
							->setCellValue('CE1', '43.29.01-Installazione, riparazione e manutenzione di ascensori e scale mobili')
							->setCellValue('CF1', '43.29.02-Lavori di isolamento termico, acustico o antivibrazioni')
							->setCellValue('CG1', '43.29.09-Altri lavori di costruzione e installazione nca')
							->setCellValue('CH1', '43.31.00-Intonacatura e stuccatura')
							->setCellValue('CI1', '43.32.01-Posa in opera di casseforti, forzieri, porte blindate')
							->setCellValue('CJ1', '43.32.02-Posa in opera di infissi, arredi, controsoffitti, pareti mobili e simili')
							->setCellValue('CK1', '43.33.00-Rivestimento di pavimenti e di muri')
							->setCellValue('CL1', '43.34.00-Tinteggiatura e posa in opera di vetri')
							->setCellValue('CM1', '43.39.01-Attività non specializzate di lavori edili (muratori)')
							->setCellValue('CN1', '43.39.09-Altri lavori di completamento e di finitura degli edifici nca')
							->setCellValue('CO1', '43.91.00-Realizzazione di coperture')
							->setCellValue('CP1', '43.99.01-Pulizia a vapore, sabbiatura e attività simili per pareti esterne di edifici')
							->setCellValue('CQ1', '43.99.02-Noleggio di gru ed altre attrezzature con operatore per la costruzione o la demolizione')
							->setCellValue('CR1', '43.99.09-Altre attività di lavori specializzati di costruzione nca')
							->setCellValue('CS1', '90.03.02-Attività di conservazione e restauro di opere di arte')
							->setCellValue('CT1', 'DATA_PUBBLICAZIONE');
							
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('IMPRESE_PUBBLICATE');

        
		

                        
                
        $i=2;
        //$exceldata="";
        foreach ($data['statements'] as $item):
		$soas['soa']= $this -> admin_model ->elenco_soas($item['id']);
		$atecos['ateco']= $this -> admin_model ->elenco_atecos($item['id']);
                //$exceldata[] = $item['sl_piva'];
				$this->excel->setActiveSheetIndex(0);
							
							$this->excel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$i,$item['sl_piva'],PHPExcel_Cell_DataType::TYPE_STRING );
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit('B'.$i,$item['sl_cf'],PHPExcel_Cell_DataType::TYPE_STRING );
                            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$i,$item['ragione_sociale']);
                            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$i,$item['sl_via'].",".$item['sl_civico'].",".$item['sl_comune'].",".$item['sl_cap'].",".$item['sl_prov']);
                            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$i,$item['anno_inizio']);
                            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$i,$item['addetti_n_dipendenti']);
							$this->excel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$i,$item['sl_tel'],PHPExcel_Cell_DataType::TYPE_STRING );
							$this->excel->setActiveSheetIndex(0)->setCellValue('H'.$i,$item['sl_email']);
							$this->excel->setActiveSheetIndex(0)->setCellValue('I'.$i,$item['tipo_contratto']);
							foreach ($soas['soa'] as $soa):
							
							if ($soa['codice']== "OG1") {
								$this->excel->setActiveSheetIndex(0)->setCellValue('J'.$i,$soa['classe']);
								
								
							}	
							
							if ($soa['codice']=="OG2") {
								$this->excel->setActiveSheetIndex(0)->setCellValue('K'.$i,$soa['classe']);
						
								
							}
							if ($soa['codice']=="OG3") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('L'.$i,$soa['classe']);
							
							}
							if ($soa['codice']=="OG4") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('M'.$i,$soa['classe']);
					
							}
							if ($soa['codice']=="OG5") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('N'.$i,$soa['classe']);
								
							}
							if ($soa['codice']=="OG6") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('O'.$i,$soa['classe']);
							
							}
							if ($soa['codice']=="OG7") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('P'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG8") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('Q'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG9") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('R'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG10") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('S'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG11") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('T'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG12") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('U'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OG13") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('V'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS1") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('W'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OGS2-A") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('X'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS2-B") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('Y'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS3") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('Z'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS4") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AA'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS5") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AB'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS6") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AC'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS7") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AD'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS8") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AE'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS9") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AF'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS10") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AG'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS11") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AH'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS12-A") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AI'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS12-B") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AJ'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS13") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AK'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS14") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AL'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS15") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AM'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS16") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AN'.$i,$soa['classe']);
								}		
							if ($soa['codice']=="OS17") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AO'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS18-A") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AP'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS18-B") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AQ'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS19") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AR'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS20-A") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AS'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS20-B") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AT'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS21") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AU'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS22") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AV'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS23") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AW'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS24") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AX'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS25") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AY'.$i,$soa['classe']);
								}	
							if ($soa['codice']=="OS26") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('AZ'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS27") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BA'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS28") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BB'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS29") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BC'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS30") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BD'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS31") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BE'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS32") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BF'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS33") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BG'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS34") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BH'.$i,$soa['classe']);
								}
							if ($soa['codice']=="OS35") { 
								$this->excel->setActiveSheetIndex(0)->setCellValue('BI'.$i,$soa['classe']);
								}
								
							endforeach;
							
							foreach ($atecos['ateco'] as $ateco):
			
							if ($ateco['codice']== "41.10.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BJ'.$i,'SI');
							}
								if ($ateco['codice']==  "41.20.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BK'.$i,'SI');
								}
							if ($ateco['codice']== "42.11.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BL'.$i,'SI');
							}
							if ($ateco['codice']== "42.12.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BM'.$i,'SI');
							}
								if ($ateco['codice']== "42.13.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BN'.$i,'SI');
								}
								if ($ateco['codice']== "42.21.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BO'.$i,'SI');
								}
								if ($ateco['codice']== "42.22.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BP'.$i,'SI');
								}
								if ($ateco['codice']== "42.91.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BQ'.$i,'SI');
								}
								if ($ateco['codice']== "42.99.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BR'.$i,'SI');
								}
								if ($ateco['codice']== "42.99.09"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BS'.$i,'SI');
								}
								if ($ateco['codice']== "43.11.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BT'.$i,'SI');
								}
								if ($ateco['codice']== "43.12.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BU'.$i,'SI');
								}
								if ($ateco['codice']== "43.13.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BV'.$i,'SI');
								}
								if ($ateco['codice']== "43.21.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BW'.$i,'SI');
								}
								if ($ateco['codice']== "43.21.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BX'.$i,'SI');
								}
								if ($ateco['codice']== "43.21.03"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BY'.$i,'SI');
								}
								if ($ateco['codice']== "43.22.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('BZ'.$i,'SI');
								}
								if ($ateco['codice']== "43.22.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CA'.$i,'SI');
								}
								if ($ateco['codice']== "43.22.03"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CB'.$i,'SI');
								}
								if ($ateco['codice']== "43.22.04"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CC'.$i,'SI');
								}
								if ($ateco['codice']== "43.22.05"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CD'.$i,'SI');
								}
								if ($ateco['codice']== "43.29.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CE'.$i,'SI');
								}
								if ($ateco['codice']== "43.29.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CF'.$i,'SI');
								}
								if ($ateco['codice']== "43.29.09"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CG'.$i,'SI');
								}
								if ($ateco['codice']== "43.31.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CH'.$i,'SI');
								}
								if ($ateco['codice']== "43.32.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CI'.$i,'SI');
								}
								if ($ateco['codice']== "43.32.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CJ'.$i,'SI');
								}
								if ($ateco['codice']== "43.33.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CK'.$i,'SI');
								}
								if ($ateco['codice']== "43.34.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CL'.$i,'SI');
								}
								if ($ateco['codice']== "43.39.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CM'.$i,'SI');
								}
								if ($ateco['codice']== "43.39.09"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CN'.$i,'SI');
								}
								if ($ateco['codice']== "43.91.00"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CO'.$i,'SI');
								}
								if ($ateco['codice']== "43.99.01"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CP'.$i,'SI');
								}
								if ($ateco['codice']== "43.99.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CQ'.$i,'SI');
								}
								if ($ateco['codice']== "43.99.09"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CR'.$i,'SI');
								}
								if ($ateco['codice']== "90.03.02"){
								$this->excel->setActiveSheetIndex(0)->setCellValue('CS'.$i,'SI');
								}
								
								endforeach;
						    
							   $this->excel->setActiveSheetIndex(0)->setCellValue('CT'.$i,format_date($item['published_at']));
		$i++;
        endforeach;
                //Fill data 
                //$this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
                 
                //$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //$this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 
                $filename='IMPRESE_PUBBLICATE.xlsx'; //save our workbook as this file name
                //header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  				
				ob_end_clean();
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
				
	
                 
    }
         
}
