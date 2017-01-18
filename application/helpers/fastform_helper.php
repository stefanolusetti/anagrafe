<?php
 if (!defined('BASEPATH')) {
     exit('No direct script access allowed');
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

function list_fields()
{
    $CI = &get_instance();
    $fields = $CI->db->list_fields('dichiaraziones');
    foreach ($fields as $key => $value) {
        if (preg_match('/^id/', $value)) {
            unset($fields[$key]);
        }
    }
  #return implode(", ", $fields);
  $data = array();
    foreach ($fields as $key => $value) {
        $data[$value] = strip_tags($CI->input->post($value));
    }

    return $data;
}

function parse_date($date)
{
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

function mostra_errore($campo)
{
    if ($msg = form_error($campo)) {
        echo "<label for='{$campo}' class='errormsg'>{$msg}</label>";
    }
}

function last_value($campo)
{
    $CI = &get_instance();
    $pattern = "/^([a-zA-Z0-9_]{1,})\[([a-zA-Z0-9_]{1,})\]$/";
    if (preg_match($pattern, $campo, $nome)) {
        $valore = $CI->input->post($nome[1]);
        if (empty($valore)) {
            $valore = $CI->input->get($nome[1]);
        }
        if ($valore) {
            if (array_key_exists($nome[2], $valore)) {
                return $valore[$nome[2]];
            }
        }
    } else {
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


function opzioni_company_shape() {
  $CI = &get_instance();
  $resulsts = $CI->dichiarazione_model->get_company_shapes();
  $options = array();
  foreach ($resulsts as $result) {
    $options[$result['csid']] = $result['value'];
  }
  return $options;
}
