// ============================================
// File: application/config/email.php
// ============================================
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com'; // Change to your SMTP host
$config['smtp_port'] = 587;
$config['smtp_user'] = 'your-email@gmail.com'; // Change this
$config['smtp_pass'] = 'your-app-password'; // Change this
$config['smtp_crypto'] = 'tls';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;