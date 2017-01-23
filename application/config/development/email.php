<?php
/*
 * Email configuration file
 * http://codeigniter.com/user_guide/libraries/email.html
 */

#$config['protocol'] = 'sendmail';
#$config['mailpath'] = '/usr/sbin/sendmail';
#$config['charset'] = 'iso-8859-1';
#$config['wordwrap'] = TRUE;


/*
 * configurazione smtp pec RER
 */

//$config['protocol'] = 'smtp';
//$config['smtp_host'] = 'ssl://smtp.pec.actalis.it';
//$config['smtp_port'] = '465';
//$config['smtp_user'] = 'elencomeritocostruzioni@postacert.regione.emilia-romagna.it';
//$config['smtp_pass'] = 'RvYwEMbV';
//$config[ 'mailtype'] = 'html';
//$config['charset'] = 'utf-8';
//$config['wordwrap'] = TRUE;
//$config['crlf'] = "\r\n";
//$config['newline'] = "\r\n";

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.telecompost.it';
$config['smtp_port'] = '465';
$config['smtp_user'] = 'anagrafeantimafiasisma@pec.interno.it';
$config['smtp_pass'] = 'Sisma@2017';
$config[ 'mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
