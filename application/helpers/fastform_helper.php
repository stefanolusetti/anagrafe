<?php
 if (!defined('BASEPATH')) {
     exit('No direct script access allowed');
 }

function f_hidden($campo){
  echo form_hidden('stmt_wl_interest', '');
  echo mostra_errore($campo);
}

function f_text($campo, $etichetta, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
  //$valore = set_value($campo);
  $valore = last_value($campo);
    $opzioni = extract_args($args, $id);
    echo   "<div class='{$opzioni['field']}'><label for='{$id}'>{$etichetta}</label>".
      "<input type='text' name='{$campo}' value='{$valore}' {$opzioni['input']}/>";
    echo mostra_errore($campo);
    echo '</div>';
}

function f_text_formdata($campo, $etichetta, $args = null) {
  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $id = str_replace(array('[', ']'), '', $campo);
  //$valore = set_value($campo);
  $valore = last_value_form($campo);
  $opzioni = extract_args($args, $id);
  echo "<div class='{$opzioni['field']}'><label for='{$id}'>{$etichetta}</label><input type='text' name='{$campo}' value='{$valore}' {$opzioni['input']}/>";
  if ( isset($errores[$campo]) ) {
    echo "<label for='{$campo}' class='errormsg'>'.$errores[$campo].'</label>";
  }
  echo '</div>';
}

function is_checked($data, $key){
  if ( isset($data[$key]) ) {
    if ( '1' == $data[$key] OR 'Yes' == $data[$key] ) {
      return true;
    }
  }
  return false;
}

function polish_date(&$data) {
  if ( isset($data['titolare_nascita_data']) ) {
    $data['titolare_nascita_data'] = grind_date($data['titolare_nascita_data']);
  }
  else {
    $data['titolare_nascita_data'] = '';
  }

  if ( isset($data['impresa_data_costituzione']) ) {
    $data['impresa_data_costituzione'] = grind_date($data['impresa_data_costituzione']);
  }
  else {
    $data['impresa_data_costituzione'] = '';
  }
  if ( isset($data['white_list_data']) ) {
    $data['white_list_data'] = grind_date($data['white_list_data']);
  }
  else {
    $data['white_list_data'] = '';
  }

  if ( isset($data['istanza_data']) ) {
    $data['istanza_data'] = grind_date($data['istanza_data']);
  }
  else {
    $data['istanza_data'] = '';
  }
  if ( isset($data['anagrafiche_antimafia']) ) {
    foreach ( $data['anagrafiche_antimafia'] AS $af => $anagrafica ) {
      $data['anagrafiche_antimafia'][$af]['antimafia_data_nascita'] = grind_date($data['anagrafiche_antimafia'][$af]['antimafia_data_nascita']);
      if( isset($anagrafica['familiari']) ) {
        foreach ( $anagrafica['familiari'] AS $nf => $familiare ){

          $data['anagrafiche_antimafia'][$af]['familiari'][$nf]['data_nascita'] = grind_date($data['anagrafiche_antimafia'][$af]['familiari'][$nf]['data_nascita']);
        }
      }
    }
  }
}

function grind_date($val = false){
  if ( $val == date('d/m/Y') ) {
    return $val;
  }
  if ( empty($val) ) {
    return '';
  }
  else {
    return date('d/m/Y', strtotime($val));
  }
}
function f_radio_edit($object, $key, $label, $classes = '', $attrs = '', $_value = '1') {
  $value = isset($object[$key]) ? $object[$key] : '';
  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  if ( 1 == $value || '1' === $value ) {
    $checked = ' checked="checked" ';
  }
  else {
    $checked = '';
  }
  $idField = grind_key($key);
  printf(
    '<div class="field inpreview checkbox">
      <input type="radio" %s id="%s" name="%s" value="%s" class="preview-field %s" %s>
      <label for="%s">%s</label>
      %s
      <div class="resizer"></div>
      </div>',
    $attrs,
    $idField,
    $key,
    $_value,
    $classes,
    $checked,
    $key,
    $label,
    $errore
  );
}
function f_checkbox_edit($object, $key, $label, $classes = '', $attrs = '') {
  $value = isset($object[$key]) ? $object[$key] : '';
  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  if ( 1 == $value || '1' === $value ) {
    $checked = ' checked="checked" ';
  }
  else {
    $checked = '';
  }
  $idField = grind_key($key);
  printf(
    '<div class="field inpreview checkbox">
      <input type="checkbox" %s id="%s" name="%s" value="1" class="preview-field %s" %s>
      <label for="%s">%s</label>
      %s
      <div class="resizer"></div>
      </div>',
    $attrs,
    $key,
    $idField,
    $classes,
    $checked,
    $key,
    $label,
    $errore
  );
}

function f_textarea_edit ( $object, $key, $label, $classes = '', $rows = 4) {
  $value = isset($object[$key]) ? $object[$key] : '';
  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  $idField = grind_key($key);
  printf(
    '<div class="field inpreview">
      <label>%s</label>
      <textarea id="%s" name="%s" class="preview-field %s" rows="%s">%s</textarea>
      %s
      </div>',
    $label,
    $idField,
    $key,
    $classes,
    $rows,
    $value,
    $errore
  );
}

function f_text_edit ( $object, $key, $label, $classes = '' ){
  if ( stristr($key, 'imprese_partecipate') ) {
    preg_match_all('#imprese_partecipate\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $_k = isset($matches[2][0]) ? $matches[2][0] : '';
    if( isset($object['imprese_partecipate'][$n][$_k]) ) {
      $value = $object['imprese_partecipate'][$n][$_k];
    }
    else {
      $value = '';
    }
  }
  else if ( preg_match('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key) ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $f = isset($matches[2][0]) ? $matches[2][0] : '';
    $_k = isset($matches[3][0]) ? $matches[3][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k];
    }
    else {
      $value = '';
    }
  }
  else if ( preg_match('#anagrafiche_antimafia\[([0-9]+)\]\[(.*)\]#', $key) ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $_k = isset($matches[2][0]) ? $matches[2][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n][$_k];
    }
    else {
      $value = '';
    }
  }
  else {
    $value = isset($object[$key]) ? $object[$key] : '';
  }

  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  $idField = grind_key($key);
  printf(
    '<div class="field inpreview">
      <label>%s</label>
      <input type="text" id="%s" name="%s" class="preview-field %s" value="%s"/>
      %s
      </div>',
    $label,
    $idField,
    $key,
    $classes,
    $value,
    $errore
  );
  return $idField;
}

function f_textbox_edit ( $object, $key, $label, $classes = '' ){
  if ( stristr($key, 'imprese_partecipate') ) {
    preg_match_all('#imprese_partecipate\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $_k = isset($matches[2][0]) ? $matches[2][0] : '';
    if( isset($object['imprese_partecipate'][$n][$_k]) ) {
      $value = $object['imprese_partecipate'][$n][$_k];
    }
    else {
      $value = '';
    }
  }
  else if ( preg_match('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key) ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $f = isset($matches[2][0]) ? $matches[2][0] : '';
    $_k = isset($matches[3][0]) ? $matches[3][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k];
    }
    else {
      $value = '';
    }
  }
  else if ( preg_match('#anagrafiche_antimafia\[([0-9]+)\]\[(.*)\]#', $key) ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $_k = isset($matches[2][0]) ? $matches[2][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n][$_k];
    }
    else {
      $value = '';
    }
  }
  else {
    $value = isset($object[$key]) ? $object[$key] : '';
  }

  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  $idField = grind_key($key);
  printf(
    '<div class="field inpreview textbox">
      <input type="text" id="%s" name="%s" class="preview-field %s" value="%s"/>
      <label>%s</label>
      %s
      </div>',
    $idField,
    $key,
    $classes,
    $value,
    $label,
    $errore
  );
  return $idField;
}


function grind_key($key){
  $idField = str_replace(array('][', '['), '_', $key);
  $idField = str_replace(array('__', ']'), array('_', ''), $idField);
  return $idField;
}
function f_select_edit ( $object, $key, $label, $options, $classes = '', $attrs = array()) {
  if ( preg_match('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key) ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[familiari\]\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $f = isset($matches[2][0]) ? $matches[2][0] : '';
    $_k = isset($matches[3][0]) ? $matches[3][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n]['familiari'][$f][$_k];
    }
    else {
      $value = '';
    }
  }
  else if ( stristr($key, 'anagrafiche_antimafia') ) {
    preg_match_all('#anagrafiche_antimafia\[([0-9]+)\]\[(.*)\]#', $key, $matches);
    $n = isset($matches[1][0]) ? $matches[1][0] : '';
    $_k = isset($matches[2][0]) ? $matches[2][0] : '';
    if( isset($object['anagrafiche_antimafia'][$n][$_k]) ) {
      $value = $object['anagrafiche_antimafia'][$n][$_k];
    }
    else {
      $value = '';
    }
  }
  else {
    $value = isset($object[$key]) ? $object[$key] : null;
  }
  $CI =& get_instance();
  $errores = $CI->form_validation->error_array();
  $errore = '';
  if ( isset($errores[$key]) ) {
    $errore = '<label for="' . $key . '" class="errormsg">' . $errores[$key] . '</label>';
  }
  $idField = grind_key($key);
  $attributi = array_merge(array('class' => $classes, 'id' => $idField), $attrs);
  printf(
    '<div class="field inpreview select">
      <label>%s</label>
      %s
      %s
      </div>',
    $label,
    form_dropdown($key, $options, $value, $attributi),
    $errore
  );
  return $idField;
}


function f_text_custom_value($campo, $etichetta, $valore, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
    $opzioni = extract_args($args, $id);
    echo   "<div class='field'><label for='{$id}'>{$etichetta}</label>".
      "<input type='text' name='{$campo}' value='{$valore}' {$opzioni['input']}/>";
    echo mostra_errore($campo);
    echo '</div>';
}

function f_textarea($campo, $etichetta, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
  //$valore = set_value($campo);
  $valore = last_value($campo);
    $opzioni = extract_args($args, $id);
    echo   "<div class='{$opzioni['field']}'><label for='{$id}'>{$etichetta}</label>".
      "<textarea name='{$campo}' {$opzioni['input']}>{$valore}</textarea>";
    echo mostra_errore($campo);
    echo '</div>';
}

function f_password($campo, $etichetta, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
    //$valore = set_value($campo);
    $valore = last_value($campo);
    $opzioni = extract_args($args, $id);
    echo    "<div class='{$opzioni['field']}'><label for='{$id}'>{$etichetta}</label>".
            "<input type='password' name='{$campo}' value='{$valore}' {$opzioni['input']}/>";
    echo mostra_errore($campo);
    echo '</div>';
}

function f_select($campo, $etichetta, $options, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
    $opzioni = extract_args($args, $id);
  //$valore = set_value($campo);
  //$CI =& get_instance();
    //$valore = $CI -> input -> post($campo);
    $valore = last_value($campo);
    echo "<div class='{$opzioni['field']} select'><label for='{$id}'>{$etichetta}</label>";
    echo form_dropdown($campo, $options, $valore, $opzioni['input']);
    mostra_errore($campo);
    echo '</div>';
}

function f_text_print($value, $label){
  printf(
    '<div class="field inpreview"><label>%s</label><div class="preview-field">%s</div></div>',
    $label, $value
  );
}

function f_checkbox_print($value, $label, $is_literal = false, $size = 1) {
  if ( true == $is_literal ) {
    $value = $value;
  }
  else {
    $value = 1 == (int)$value ? 'X' : '';
  }
  printf(
    '<div class="field inpreview inpreview-chk"><div class="fake-checkbox %s">%s</div><label>%s</label><div class="resizer"></div></div>',
    'size-' . $size,
    $value,
    $label
  );
}
function f_textarea_print($value, $label){
  printf(
    '<div class="field inpreview"><label>%s</label><div class="preview-field preview-textarea">%s</div></div>',
    $label, $value
  );
}

function f_checkbox($campo, $etichetta, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
  //$valore = set_value($campo);
  $valore = last_value($campo);
    $opzioni = extract_args($args, $id);
    echo "<div class='checkbox {$opzioni['field']}'><label for='{$id}'>{$etichetta}</label>";
    if ($valore == 'Yes') {
        echo form_checkbox($campo, 'Yes', true, $opzioni['input']);
    } else {
        echo form_checkbox($campo, 'Yes', false, $opzioni['input']);
    }
    mostra_errore($campo);
    echo '</div>';
}

function f_radio($campo, $etichetta, $args = null)
{
    $id = str_replace(array('[', ']'), '', $campo);
    //$valore = set_value($campo);
    $valore = last_value($campo);
    $opzioni = extract_args($args, $id);
    echo "<div class='checkbox {$opzioni['field']}'><label for='{$id}'>{$etichetta}</label>";
    if ($valore == 'Yes') {
        echo form_radio($campo, 'Yes', true, $opzioni['input']);
    } else {
        echo form_radio($campo, 'Yes', false, $opzioni['input']);
    }
    mostra_errore($campo);
    echo '</div>';
}

function extract_args($args, $campo = null)
{
    $result = array();
    $result['field'] = 'field ';
    $result['input'] = '';
    if ($campo) {
        $result['input'] = "id='{$campo}' ";
    }

    if (is_array($args)) {
        if (array_key_exists('field', $args)) {
            foreach ($args['field'] as $value) {
                $result['field'] .= "{$value} ";
            }
        }
        if (array_key_exists('input', $args)) {
            foreach ($args['input'] as $key => $value) {
                $result['input'] .= "{$key}='{$value}' ";
            }
        }
    }

    return $result;
}

function soa_options()
{
    return array(
    '0' => '-',
    '1' => '(1) fino a &euro; 258.000',
    '2' => '(2) fino a &euro; 516.000',
    '3' => '(3) fino a &euro; 1.033.000',
    '3bis' => '(3bis) fino a &euro; 1.500.000',
    '4' => '(4) fino a &euro; 2.582.000',
    '4bis' => '(4bis) fino a &euro; 3.500.000',
    '5' => '(5) fino a &euro; 5.165.000',
    '6' => '(6) fino a &euro; 10.329.000',
    '7' => '(7) fino a &euro; 15.494.000',
    '8' => '(8) oltre &euro; 15.494.000',
    );
}

function list_fields () {
    $CI = &get_instance();
    $fields = $CI->db->list_fields('esecutori');
    foreach ($fields as $key => $value) {
        if (preg_match('/^I/', $value)) {
            unset($fields[$key]);
        }
    }
  #return implode(", ", $fields);

  $data = array();
    foreach ($fields as $key => $value) {
        $data[$value] = strip_tags($CI->input->post($value));
    }

    unset($data['did']);
    unset($data['upd']);
    unset($data['ID']);
    return $data;
}

function list_tmp_fields () {
  $CI = &get_instance();
  $fields = $CI->db->list_fields('tmp_esecutori');
  foreach ($fields as $key => $value) {
    if (preg_match('/^I/', $value)) {
      unset($fields[$key]);
    }
  }
  #return implode(", ", $fields);

  $data = array();
  foreach ($fields as $key => $value) {
    $data[$value] = strip_tags($CI->input->post($value));
  }
  unset($data['ID']);
  return $data;
}

function parse_checkbox($v){
  return 'Yes' == $v ? 1 : 0;
}
function parse_decimal($v){
  if('' == $v) {
    return 0;
  }
  return $v;
}

function list_anagrafiche_fields()
{
    $CI = &get_instance();
    $fields = $CI->db->list_fields('anagrafiche_antimafia');
  #return implode(", ", $fields);
  $data = array();
    foreach ($fields as $key => $value) {
        $data[$value] = strip_tags($CI->input->post($value));
    }
    unset($data['did']);
    return $data;
}

function parse_date($date) {
  if(empty($date)) {
    return null;
  }
  $format = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})$@';
  preg_match($format, $date, $dateInfo);
  $mysql_datetime = '';
  if (array_key_exists('year', $dateInfo)) {
    $mysql_datetime = "{$dateInfo['year']}:{$dateInfo['month']}:{$dateInfo['day']} 00:00:00";
  }
  return $mysql_datetime;
}

function partial_parse_date($date)
{
    $format = '@^((?P<day>\d{2})/?)?((?P<month>\d{2})/)?(?P<year>\d{4})?@';
    preg_match($format, $date, $dateInfo);
    $return = '';
    if (array_key_exists('year', $dateInfo) && $dateInfo['year']) {
        $return .= $dateInfo['year'].'-';
    }
    if (array_key_exists('month', $dateInfo) && $dateInfo['month']) {
        $return .= $dateInfo['month'].'-';
    }
    if (array_key_exists('day', $dateInfo) && $dateInfo['day']) {
        $return .= $dateInfo['day'];
    }

    return $return;
}

function format_date($date)
{
    if ($date != '0000-00-00 00:00:00') {
        $datetime = strtotime($date);
        $mysqldate = date('d/m/Y', $datetime);

        return $mysqldate;
    }
}

function get_year($date)
{
    $datetime = strtotime($date);
    $mysqldate = date('Y', $datetime);

    return $mysqldate;
}

function opzioni_provincie()
{
    $CI = &get_instance();
    $base = array();
    $base[''] = '-';
    $estero['Provincia Estera'] = 'Provincia Estera';
    $options = $CI->comuni_model->get_province();
    $result = array_merge($base, $options, $estero);

    return $result;
}

function opzioni_ateco()
{
    $CI = &get_instance();
    $base = array();
    $base[''] = '-';
    $options = $CI->elenco_model->get_atecos_elenco();
    $result = array_merge($base, $options);

    return $result;
}

function opzioni_soa()
{
    $CI = &get_instance();
    $base = array();
    $base[''] = '-';
    $options = $CI->elenco_model->get_soas_elenco();
    $result = array_merge($base, $options);

    return $result;
}

function mostra_errore($campo) {
  if ($msg = form_error($campo)) {
    echo "<label for='{$campo}' class='errormsg'>{$msg}</label>";
  }
}

function last_value_form ( $campo ) {
  $CI = &get_instance();
  $formdata = $CI->session->userdata('formdata');
  $pattern = "/^([a-zA-Z0-9_]{1,})\[([a-zA-Z0-9_]{1,})\]$/";
  if ( preg_match($pattern, $campo, $nome) ) {
    $valore = $CI->input->post($nome[1]);
    if ( empty($valore) ) {
      $valore = $CI->input->get($nome[1]);
    }
    if ( $valore ) {
      if ( array_key_exists($nome[2], $valore) ) {
        return $valore[$nome[2]];
      }
    }
  }
  else {
    $valore = $CI->input->post($campo);
    if ( is_null($valore) ) {
      if ( isset($formdata[$campo]) ) {
        $valore = $formdata[$campo];
      }
    }
    return $valore;
  }
}

function last_value ( $campo ) {
  $CI = &get_instance();
  $pattern = "/^([a-zA-Z0-9_]{1,})\[([a-zA-Z0-9_]{1,})\]$/";
  if ( preg_match($pattern, $campo, $nome) ) {
    $valore = $CI->input->post($nome[1]);
    if ( empty($valore) ) {
      $valore = $CI->input->get($nome[1]);
    }
    if ( $valore ) {
      if ( array_key_exists($nome[2], $valore) ) {
        return $valore[$nome[2]];
      }
    }
  }
  else {
    $valore = $CI->input->post($campo);
    if (empty($valore)) {
      $valore = $CI->input->get($campo);
    }
    return $valore;
  }
}

/*
██████  ██    ██  ██████  ██      ██
██   ██ ██    ██ ██    ██ ██      ██
██████  ██    ██ ██    ██ ██      ██
██   ██ ██    ██ ██    ██ ██      ██
██   ██  ██████   ██████  ███████ ██

 █████  ███    ██  █████   ██████  ██████   █████  ███████ ██  ██████ ██   ██ ███████
██   ██ ████   ██ ██   ██ ██       ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
███████ ██ ██  ██ ███████ ██   ███ ██████  ███████ █████   ██ ██      ███████ █████
██   ██ ██  ██ ██ ██   ██ ██    ██ ██   ██ ██   ██ ██      ██ ██      ██   ██ ██
██   ██ ██   ████ ██   ██  ██████  ██   ██ ██   ██ ██      ██  ██████ ██   ██ ███████
*/
function opzioni_ruoli_anagrafiche($type = null)
{
  $CI = &get_instance();
  $options = $CI->dichiarazione_model->get_roles($type);
  return $options;
}


function opzioni_forma_giuridica_id() {
  $CI = &get_instance();
  $resulsts = $CI->dichiarazione_model->get_forme_giuridiche();
  $options = array();
  foreach ($resulsts as $result) {
    $options[$result['forma_giuridica_id']] = $result['valore'];
  }
  return $options;
}




function db_transaction_error_handler($errno, $errstr, $errfile, $errline)
{
  if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting, so let it fall
      // through to the standard PHP error handler
      return false;
  }

  switch ($errno) {
    case E_USER_ERROR:
      throw new Exception($errstr);
      break;

    case E_USER_WARNING:
      break;
    case E_USER_NOTICE:
      break;

    default:
      throw new Exception($errstr);
      break;
  }
  /* Don't execute PHP internal error handler */
  return true;
}
