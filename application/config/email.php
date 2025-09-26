<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com';
$config['smtp_port']   = 587;          // أو 465 مع ssl
$config['smtp_user']   = 'saadaouikarim2002@gmail.com';   // إيميلك الجيميل
$config['smtp_pass']   = 'sojn xfll ejwt qygk';      // الـ App Password من خطوة 2
$config['smtp_crypto'] = 'tls';        // لو استخدمت 465 استبدلها بـ 'ssl'
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['crlf']        = "\r\n";
$config['smtp_timeout']= 10;
$config['useragent']   = 'CodeIgniter';
