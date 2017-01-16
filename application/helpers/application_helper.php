<?php


function list_soas($dichiarazione_id)
{
    $CI =& get_instance();
    $soas = $CI -> admin_model ->elenco_soas($dichiarazione_id);
    if($soas):
    echo "<ul>";
    foreach ($soas as $soa): ?>
        <li class="soas"><strong><?php echo $soa['codice']; ?></strong> <?php echo $soa['denominazione']; ?>, classe <?php echo $soa['classe']; ?></div>
    <?php endforeach; 
    echo "</ul>";
    endif;
}

function list_atecos($dichiarazione_id)
{
    $CI =& get_instance();
    $atecos= $CI -> admin_model ->elenco_atecos($dichiarazione_id);
    if($atecos):
    echo "<ul>";
    foreach ($atecos as $ateco): ?>
        <li class="atecos"><strong><?php echo $ateco['codice']; ?></strong> <?php echo $ateco['denominazione']; ?></li>
    <?php endforeach;
    echo "</ul>";
    endif;
}


function current_controller($now){
    $CI =& get_instance();
    $controller = $CI->router->fetch_class();
    if($now == $controller){
        return TRUE;
    }
}

function logged_in(){
    $CI =& get_instance();
    return $CI -> session->userdata('logged_in');
}

function is_admin(){
    $CI =& get_instance();
    $result = $CI -> session->userdata('logged_in') && $CI -> session->userdata('admin');
    return $result;
}

function set_search_querystring(){
    $CI =& get_instance();
    $items = array('id', 'titolare_nome', 'ragione_sociale', 'data_firma', 'sl_piva', 'created_at', 'uploaded_at', 'sl_cf');
    $items_dropdown = array ('uploaded','durc','protesti','antimafia','pubblicato');
	$params = array();
	$params_dropdown = array();
    $date_array= array('created_at', 'uploaded_at', 'data_firma');
    foreach ($items as $item) {
        $parameter = $CI -> input -> get($item);
        $params[$item] = empty($parameter) ? "" : $parameter;
        if(in_array($item, $date_array))
        {
            $params[$item] = partial_parse_date($parameter);
        }
        
    }
	
	foreach ($items_dropdown as $item_dropdown) {
	$parameter_dropdown = $CI -> input -> get($item_dropdown);
	if($parameter_dropdown == "")
	$params[$item_dropdown]="";
	else
	$params[$item_dropdown]= $parameter_dropdown;
	}
	
    $order_by = $CI -> input -> get('order_by');
    $order_by = empty($order_by) ? "id desc" : $order_by;
	if (($order_by=="ragione_sociale asc") or ($order_by=="ragione_sociale desc") or ($order_by=="id desc") or ($order_by=="id asc") or ($order_by=="titolare_nome desc") or ($order_by=="titolare_nome asc") or
	($order_by=="data_firma asc") or ($order_by=="data_firma desc") or ($order_by=="sl_piva asc") or ($order_by=="sl_piva desc") or ($order_by=="created_at asc") or ($order_by=="created_at desc") or ($order_by=="uploaded asc") or ($order_by=="uploaded desc") or ($order_by=="sl_cf asc") or ($order_by=="sl_cf desc") 
	or ($order_by=="5bis asc") or ($order_by=="5bis desc") or ($order_by=="pubblicato asc") or ($order_by=="pubblicato desc") or ($order_by=="durc asc") or ($order_by=="durc desc") or ($order_by=="protesti asc") or ($order_by=="protesti desc")  
	or ($order_by=="antimafia asc") or ($order_by=="antimafia desc") or ($order_by=="uploaded_at asc") or ($order_by=="uploaded_at desc"))  {
    $qs = array_merge($params,$params_dropdown,array('order_by' => $order_by));
    return array('params' => $params,'order_by' => $order_by, 'qs' => $qs);
   }
   else {
   $qs = array_merge($params, $params_dropdown, array('order_by' => $order_by));
   return array('params' => $params,'order_by' => "id desc", 'qs' => $qs);
   
   }
   }
   


function query_link($href, $name){
    $base = current_url();
    $criteria = set_search_querystring();
    
    $class = preg_match("/^{$href}/", $criteria['order_by']) ? "selected" : "unselected";
    
    $order_by = "{$href} asc" == $criteria['order_by'] ? "{$href} desc" : "{$href} asc";
    $qs = array_merge($criteria['params'], array('order_by' => $order_by));
    
    $url = htmlentities($base . "?" . http_build_query($qs));
    echo "<a href='{$url}' class='{$class}'>{$name}</a>";	
}

function valid_hash($hash){
    if($hash == FALSE){
        return FALSE;
    }
    $CI =& get_instance();
    $query = $CI -> db -> get_where('dichiaraziones', array('hash' => $hash, 'uploaded != ' => '1'), 1, 0);
                
    $results = $query->result_array();
    log_message('debug', print_r($results, TRUE));
    return $results ? $results[0] : FALSE;
}

function gestione_dropdown($nomecampo, $options)
{
    global $item;
    echo form_dropdown("{$nomecampo}[{$item['id']}]", $options, $item['"{$nomecampo}"'], "class='{$nomecampo}'");
}

function legenda(){
    $result = array(
                    'uploaded' => array('0' => 'no', '1' => 'si', '2' => 'non conforme'),
                    'durc' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'regolare', '3' => 'non regolare','4' => 'scaduto'),
                    'antimafia' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'nulla osta', '3' => 'ostativa','4' => 'interdittiva','5' => 'scaduta','6' => 'iscritta White List'),
                    'protesti' => array('0' => 'non fatta', '1' => 'in attesa', '2' => 'protestato', '3' => 'non protestato', '4' => 'scaduto'),
                    '5bis' => array('0' => 'non soggetto', '1' => 'soggetto', '2' => 'in attesa', '3' => 'pubblicato prefettura'),
                    'pubblicato' => array('1' => 'pubblicato', '0' => 'non pubblicato'),
                    'login' => array('1' => 'login', '2' => 'cred. errate', '3' => 'sospeso', '4' => 'logout')
    );
    return $result;
}



function durc_description ($id) {
	$CI =& get_instance();	
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();	
	switch ( $item['durc']) {
	case 0:
	$message = "Il controllo DURC è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo DURC è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo DURC è in stato di REGOLARE.";
	break;
	case 3:
	$message = "Il controllo DURC è in stato di NON REGOLARE.";
	break;
	case 4:
	$message = "Il controllo DURC è in stato di SCADUTO.";
	break;
	}
	return $message;
	}
	
	
function protesti_description ($id) {
	$CI =& get_instance();	
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();	
	switch ( $item['protesti']) {
	case 0:
	$message = "Il controllo PROTESTI è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo PROTESTI è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo PROTESTI è in stato di PROTESTATO.";
	break;
	case 3:
	$message = "Il controllo PROTESTI è in stato di NON PROTESTATO.";
	break;
	case 4:
	$message = "Il controllo PROTESTI è in stato di SCADUTO.";
	break;
	}
	return $message;
	}
	
	
function antimafia_description ($id) {
	$CI =& get_instance();	
	$query = $CI -> db -> get_where('dichiaraziones', array('id' => $id));
	$item =  $query->row_array();	
	switch ( $item['antimafia']) {
	case 0:
	$message = "Il controllo ANTIMAFIA è in stato di NON FATTO.";
	break;
	case 1:
	$message = "Il controllo ANTIMAFIA è in stato di ATTESA.";
	break;
	case 2:
	$message = "Il controllo ANTIMAFIA è in stato di NULLA OSTA.";
	break;
	case 3:
	$message = "Il controllo ANTIMAFIA è in stato di OSTATIVA.";
	break;
	case 4:
	$message = "Il controllo ANTIMAFIA è in stato di INTERDITTIVA.";
	break;
	case 5:
	$message = "Il controllo ANTIMAFIA è in stato di SCADUTO.";
	break;
	case 6:
	$message = "Il controllo antimafia è in stato di ISCRITTO ALLA WHITE LIST";
	break;
	}
	return $message;
	}
