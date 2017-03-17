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
    'smtp_user' => 'bot@gotinsane.com',
    'smtp_pass' => 'botmyc002',
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => TRUE,
    'crlf' => "\r\n",
    'newline' => "\r\n"
  ));

  $CI->email->from('bot@gotinsane.com', 'GotInsane\'s bot - THIS IS A TEST');


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
    'Stefano Lusetti <stefano.lusetti@certhidea.it>'
  );

  $CI->email->to($tos);
  $CI->email->subject($req['subject']);

  if ( isset( $req['attachments'] ) && !empty( $req['attachments'] ) ) {
    foreach ($req['attachments']['tmp_name'] AS $i => $tmp_name) {
      $CI->email->attach(
        $req['attachments']['tmp_name'][$i],
        'attachment',
        $req['attachments']['name'][$i]
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
      '<p><img src="cid:%s" alt="Header" /></p><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">%s</p><p><img src="cid:%s" alt="Footer" /></p>',
      $a1_cid,
      $req['text'],
      $a2_cid
    );

    $emailText = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Actionable emails e.g. reset password</title><link href="http://parcoinnovazione.it/email/styles.css" media="all" rel="stylesheet" type="text/css" /><link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,700" rel="stylesheet" /></head><body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6"><table style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td><td width="900" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;" valign="top"><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 800px; display: block; margin: 0 auto; padding: 0;"><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">          <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; background-color: #00264D; margin: 0;" bgcolor="#00264D">            <td width="140" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: middle; text-align: center; margin: 0; padding: 10px 20px 10px 10px;" align="center" valign="middle">              <img src="http://dp.certhidea.it/anagrafe/logo-anagrafe.svg" width="60" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 100%; margin: 0;" />            </td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 33px; vertical-align: middle; text-align: left; color: #FFFFFF; margin: 0;" align="left" valign="middle">Ministero dell\'interno              <div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: lighter; font-style: italic; margin: 0;">Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</div></td></tr>        </table>        <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 10px;" valign="top"><meta itemprop="name" content="Confirm Email" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;" /><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">                <tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">' . $mailContent . '</td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">  </td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td></tr></table></td></tr></table><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;"><table width="100%" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"></td></tr></table></div></div></td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td></tr></table></body></html>';

  }
  else {
    $mailContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Actionable emails e.g. reset password</title><link href="http://parcoinnovazione.it/email/styles.css" media="all" rel="stylesheet" type="text/css" /><link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,700" rel="stylesheet" /></head><body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0; padding: 0;" bgcolor="#f6f6f6"><table style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td><td width="900" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; width: 100% !important; margin: 0 auto; padding: 0;" valign="top"><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 800px; display: block; margin: 0 auto; padding: 0;"><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; background-color: #00264D; margin: 0;" bgcolor="#00264D"><td width="140" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: middle; text-align: center; margin: 0; padding: 10px 20px 10px 10px;" align="center" valign="middle"><img src="http://dp.certhidea.it/anagrafe/logo-anagrafe.svg" width="60" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; max-width: 100%; margin: 0;" /></td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 33px; vertical-align: middle; text-align: left; color: #FFFFFF; margin: 0;" align="left" valign="middle">Ministero dell\'interno<div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: lighter; font-style: italic; margin: 0;">Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</div></td></tr></table><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 10px;" valign="top"><meta itemprop="name" content="Confirm Email" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;" /><table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">OGGETTO:</strong> Avvio istruttoria per richiesta di iscrizione nell Anagrafe Antimafia degli Esecutori istituita dallart. 30, comma 6 del d.l. n.189 del 2016 convertito in Legge n. 229 del 2016.<ul style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">%ARTICOLO8%<li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">Società: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">%ragione_sociale%</strong></li><li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">Sede: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">%sl_via% %sl_civico% %sl_comune% %sl_cap% (%sl_prov%)</strong></li><li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">Codice Fiscale / Partita IVA: <strong style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;">%partita_iva%</strong></li></ul></td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">%MESSAGE%</td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Con Circolare n.15006/2(1) del 28 novembre 2016, recante prime indicazioni operative concernenti le modalit di iscrizione nellAnagrafe Antimafia degli Esecutori, il Ministero dellInterno ha, tra laltro, richiamato lattenzione sullart. 8 del D.L. in oggetto (ora convertito in Legge 15 dicembre 2016 n.229) che detta disposizioni volte ad agevolare il rientro dei cittadini nelle unit immobiliari interessate da danni lievi che necessitano, quindi, soltanto di interventi di immediata riparazione.</p><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Le prime Linee Guida adottate dal Comitato di cui allart. 203 del d.lgs. n.50 del 2016 (in corso di pubblicazione) prevedono che, nel caso in cui loperatore economico non sia gi censito in Banca Dati Nazionale Unica ovvero sia iscritto nelle White List delle Prefetture in data anteriore a tre mesi precedenti lentrata in vigore del D.L. n. 189 (19 ottobre 2016) e, comunque, quando si rendano necessari approfondimenti istruttori, questa Struttura avvii una procedura speditiva per la verifica dellesistenza o meno delle situazioni ex artt. 67 e 84, comma 4, lett. a), b) e c) del D. Lgs. n. 159 del 2011 (Codice Antimafia) nei confronti dellimpresa esaminata attraverso la consultazione della Banca Dati Nazionale Unica richiedendo al contempo:</p><ol style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;"><li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">alla D.I.A. un riscontro sulla attualit delle eventuali segnalazioni di tentativi di infiltrazioni mafiose attraverso la consultazione della banca dati del Sistema Informatico Rilevamento Accesso Cantieri (SIRAC) e del Sistema di indagine delle Forze di Polizia (SDI);</li><li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;">alla Prefettura competente per territorio le eventuali risultanze esistenti agli atti nei confronti dei soggetti sottoposti alla verifica antimafia.</li></ol><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Tanto premesso, si trasmettono in allegato i prospetti riepilogativi relativi allimpresa da verificare (un estratto dalla B.D.N.U. in formato pdf ed un file in formato csv), con preghiera di far pervenire la risposta a questa Struttura entro 10 giorni dalla data del ricevimento della presente allindirizzo PEC antimafiasisma@pec.interno.it al fine di consentire a questa medesima Struttura liscrizione provvisoria in Anagrafe (la mancata risposta della DIA entro il predetto termine  considerata come comunicazione negativa).</p><p style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; font-weight: normal; margin: 0 0 10px;">Per la successiva iscrizione definitiva nellAnagrafe in oggetto, si prega, inoltre, codesta Prefettura di fornire, allegando copia della relativa documentazione, ogni ulteriore elemento utile di valutazione, desunto dagli atti e acquisito per il tramite delle Forze di polizia territoriali (ad esclusione del locale Centro Operativo DIA in quanto gi attivato dagli Uffici centrali della DIA di Roma), entro 30 giorni sempre decorrenti dalla data del ricevimento della presente nota.</p></td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td></tr><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"></td></tr></table></td></tr></table><div style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;"><table width="100%" style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><tr style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; margin: 0;"><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"></td></tr></table></div></div></td><td style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;" valign="top"></td></tr></table></body></html>';


    $placeholders = array('%MESSAGE%');
    $values = array($req['text']);

    if ( "1" ==  $req['esecutore']['interesse_interventi']) {
      $placeholders[] = '%ARTICOLO8%';
      $values[] = '<li style="font-family: \'Titillium Web\', Arial, sans-serif; box-sizing: border-box; font-size: 18px; list-style-position: inside; margin: 0 0 0 5px;"><strong>Art. 8 (Interventi di immediata esecuzione)</strong></li>';
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
  if ( isset( $req['attachments'] ) && !empty( $req['attachments'] ) ) {
    foreach ($req['attachments']['tmp_name'] AS $i => $tmp_name) {
      if ( "" != $tmp_name ) {
        unlink($tmp_name);
      }
    }
  }
  return $esito;
}
