<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function sendMailRequest($req) {
  return true; //DEBUG!
  $CI =& get_instance();
  $CI->email->initialize(array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.telecompost.it',
    'smtp_port' => '465',
    'smtp_user' => 'pcm.elencoprofessionistisisma2016_2',
    'smtp_pass' => 'U58pD8EmWSZzQLxu!',
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => TRUE,
    'crlf' => "\r\n",
    'newline' => "\r\n"
  ));

  $CI->email->from('elencoprofessionistisisma2016_2@pec.governo.it', 'Commissario Straordinario Ricostruzione Sisma 2016');

  $CI->email->to($req['to']);
  $CI->email->subject($req['subject']);
  $CI->email->message($req['text']);

  if ( isset( $req['attachments'] ) && !empty( $req['attachments'] ) ) {
    foreach ($req['attachments']['tmp_name'] AS $i => $tmp_name) {
      $CI->email->attach(
        $req['attachments']['tmp_name'][$i],
        'attachment',
        $req['attachments']['name'][$i]
      );
    }
  }
  $esito = $CI->email->send();
  $CI->email->clear(TRUE);
  // remove the files.
  if ( isset( $req['attachments'] ) && !empty( $req['attachments'] ) ) {
    foreach ($req['attachments']['tmp_name'] AS $i => $tmp_name) {
      unlink($tmp_name);
    }
  }
  return $esito;
}
