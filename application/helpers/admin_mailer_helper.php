<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function sendMailRequest($req, $type = 1) {
  $CI =& get_instance();
  $CI->email->initialize(array(
    /*
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
    */
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.gmail.com',
    'smtp_port' => '465',
    'smtp_user' => 'bot@certhidea.it',
    'smtp_pass' => 'cip3ericiop',
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => TRUE,
    'crlf' => "\r\n",
    'newline' => "\r\n"
  ));

  $CI->email->from('bot@certhidea.it', 'Certhidea\'s bot - THIS IS A TEST');


  /*
  ██████  ███████ ██████  ██    ██  ██████
  ██   ██ ██      ██   ██ ██    ██ ██
  ██   ██ █████   ██████  ██    ██ ██   ███
  ██   ██ ██      ██   ██ ██    ██ ██    ██
  ██████  ███████ ██████   ██████   ██████
  */
  //$tos = array_filter(explode(', ', $req['to']));
  $tos = array(
    'Daniele Certhidea <dp@certhidea.it>',
    //'Stefano Lusetti <stefano.lusetti@certhidea.it>'
  );

  $CI->email->to($tos);
  $CI->email->subject($req['subject']);

  $file_to_delete = array();
  if ( isset( $req['attachments'] ) && !empty( $req['attachments'] ) ) {
    //58da82d1000b7:021.png|58da82d100c0f:028.png|58da82d116040:014.png|58da82d116ae9:lorem-ipsum-1.pdf|58da82d11d841:035.png|58da82d120fe6:lorem-ipsum-1-2.pdf|58da82d131279:023.png|58da82d1349b9:016.png|58da82d13ed62:040.png|58da82d143b0e:lorem-ipsum-2.pdf|
    $files = array_filter(explode( '|', $req['attachments'] ));

    foreach ($files AS $i => $tmp_file) {
      list($machine_name, $real_name) = explode( ':', $tmp_file );
      $file_to_delete[] = FCPATH . "mailer-uploads/$machine_name";
      $CI->email->attach(
        FCPATH . "mailer-uploads/$machine_name",
        'attachment',
        $real_name
      );
    }
  }


  if ( 1 == $type ) {
    // Attach the image template.
    //$CI->email->attach();
    $CI->email->attach(
      BASEPATH . '../mail-images/image001.png',
      'inline'
    );
    $a1_cid = $CI->email->attachment_cid(BASEPATH . '../mail-images/image001.png');

    $CI->email->attach(
      BASEPATH . '../mail-images/image002.png',
      'inline'
    );
    $a2_cid = $CI->email->attachment_cid(BASEPATH . '../mail-images/image002.png');

    $mailContent = sprintf(
      '<p><img src="cid:%s" alt="Header" /></p><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; font-weight: normal; margin: 0 0 10px;">%s</p><p><img src="cid:%s" alt="Footer" /></p>',
      $a1_cid,
      $req['text'],
      $a2_cid
    );

    $emailText = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Actionable emails e.g. reset password</title><link href="http://parcoinnovazione.it/email/styles.css" media="all" rel="stylesheet" type="text/css" /><link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,700" rel="stylesheet" /></head><body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6"><table style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0;" valign="top"></td><td width="900" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;" valign="top"><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; max-width: 800px; display: block; margin: 0 auto; padding: 0;"><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">          <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; background-color: #00264D; margin: 0;" bgcolor="#00264D">            <td width="140" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: middle; text-align: center; margin: 0; padding: 10px 20px 10px 10px;" align="center" valign="middle">              <img src="http://dp.certhidea.it/anagrafe/logo-anagrafe.svg" width="60" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; max-width: 100%; margin: 0;" />            </td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 33px; vertical-align: middle; text-align: left; color: #FFFFFF; margin: 0;" align="left" valign="middle">Ministero dell\'interno              <div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; font-weight: lighter; font-style: italic; margin: 0;">Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</div></td></tr>        </table>        <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 10px;" valign="top"><meta itemprop="name" content="Confirm Email" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;" /><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">                <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">' . $mailContent . '</td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">  </td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td></tr></table></td></tr></table><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;"><table width="100%" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"></td></tr></table></div></div></td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0;" valign="top"></td></tr></table></body></html>';

  }
  else {
    $mailContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">

    <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Actionable emails e.g. reset password</title>
    <link href="http://parcoinnovazione.it/email/styles.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,700" rel="stylesheet" />
    </head>
    <body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0; padding: 0;"
    bgcolor="#f6f6f6">
    <table style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0;" valign="top"></td>
    <td width="900" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;"
    valign="top">
    <div style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; max-width: 800px; display: block; margin: 0 auto; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; background-color: #00264D; margin: 0;" bgcolor="#00264D">
    <td width="140" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: middle; text-align: center; margin: 0; padding: 10px 20px 10px 10px;" align="center" valign="middle"><img src="http://dp.certhidea.it/anagrafe/logo-anagrafe.svg" width="60" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; max-width: 100%; margin: 0;" /></td>
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 33px; vertical-align: middle; text-align: left; color: #FFFFFF; margin: 0;"
    align="left" valign="middle">Ministero dell\'interno
    <div style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; font-weight: lighter; font-style: italic; margin: 0;">Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</div>
    </td>
    </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 10px;" valign="top">
    <meta itemprop="name" content="Confirm Email" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;" />
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
      <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align: right;" valign="top"><p style="font-size: 15px; text-align: right;">
        Spett.le<br />
        <strong>%ragione_sociale%</strong>
        </p>
        </td>
        </tr>
        <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><strong style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">OGGETTO: Comunicazione di avvio del procedimento.</strong>
    </td>
    </tr>
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">%MESSAGE%</td>
    </tr>
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">


    <p style="text-indent: 50px; font-size: 17px;">In relazione alla richiesta di iscrizione all’Anagrafe antimafia degli esecutori ex art. 30, comma 6, del D.L. 17 ottobre 2016, n. 189 (ora convertito in Legge 15 dicembre 2016 n.229) dell’operatore economico in indirizzo, si comunica che è stata avviata la relativa istruttoria.
    </p>
    <p style="text-indent: 50px; font-size: 17px;">
    Si precisa che i termini di conclusione del procedimento finalizzato all’iscrizione nella suddetta Anagrafe sono quelli indicati dall’art. 92, comma 2, del D. Lgs. 6 settembre 2011, n.  159.
    </p>
    <p style="text-indent: 50px; font-size: 17px;">
    L’Ufficio presso il quale si può prendere visione degli atti, previo appuntamento tramite la Segreteria utilizzando la PEC direzionestrutturamissioneantimafia@pec.interno.it è:
    </p>
    <p style="text-indent: 50px; font-size: 17px;">
    Ministero Interno – Struttura di Missione Prevenzione e Contrasto Antimafia Sisma, via Cavour, 6 – ROMA.
    </p>
    <p style="text-indent: 50px; font-size: 17px;">
    Il medesimo indirizzo PEC potrà essere, inoltre, utilizzato per qualsiasi altra comunicazione in merito al procedimento di cui in premessa.
    </p>

    </td>
    </tr>
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td>
    </tr>
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <div style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
    <table width="100%" style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <tr style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; margin: 0;">
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"></td>
    </tr>
    </table>
    </div>
    </div>
    </td>
    <td style="font-family: Arial, sans-serif; box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0;" valign="top"></td>
    </tr>
    </table>
    </body>
    </html>';


    $placeholders = array('%MESSAGE%');
    $values = array($req['text']);

    if ( "1" ==  $req['esecutore']['interesse_interventi']) {
      $placeholders[] = '%ARTICOLO8%';
      $values[] = '<li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 17px; list-style-position: inside; margin: 0 0 0 5px;"><strong>Art. 8 (Interventi di immediata esecuzione)</strong></li>';
    }
    else {
      $placeholders[] = '%ARTICOLO8%';
      $values[] = '';
    }

    foreach ( array_keys($req['esecutore']) AS $col ) {
      $placeholders[] = "%$col%";
      $values[] = $req['esecutore'][$col];
    }
    $emailText = str_replace($placeholders, $values, $mailContent);
  }

  $CI->email->message($emailText);
  $esito = $CI->email->send();

  $CI->email->clear(TRUE);

  // remove the files.
  if ( !empty($file_to_delete) ) {
    foreach ($file_to_delete AS $tmp_file) {
      unlink( $tmp_file );
    }
  }

  return $esito;
  //return true;
}
