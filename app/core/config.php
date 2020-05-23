<?php
/*  konfigurasi untuk koneksi dbase
 * offset untuk limit record yang ditampilkan
 */



$db=array(
  'host'=>'localhost',
   'user'=>'admin',
   'pwd' =>'admin',
  // 'user'=>'root',
  // 'pwd' =>'admin',
  'name'=>'aac',
  'offset' =>'50'
);
$prefix='Ac';

/* dev_mode -> develop mode
 * beri nilai selain satu untuk production
 */
define('DEV_MODE',1);
$secret=array(
  'code'=> 'aac',
  'alg' => 'HS256'
);

$mail=array(
  'host'    => 'mail.email.com',
  'port'    => '25',
  'from'    => 'Noreply<noreply@email.com>',
  'cc'      => 'Admin<admin@email.com>',
  'username'  => 'admin',
  'password'  => 'admin',
);
