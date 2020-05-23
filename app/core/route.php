<?php
/*hide version php*/

ini_set('max_execution_time', 0);
ini_set('expose_php',"Off");
header_remove('X-Powered-By');
ini_set('session.use_trans_sid', false);
date_default_timezone_set('Asia/Jakarta');



/* develop mode akan menampilkan error
 * sedangkan production akan menyembunyikan error
 * tapi mencatat dalam file /log/error.log
 */

$logfile=ROOT_DIR.DS.'log'.DS.'error.txt';

// tanpa notice
error_reporting(E_ERROR);
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//error_reporting(E_ERROR | E_PARSE);

if(DEV_MODE==1){
    ini_set('display_errors','On');
} else {
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    ini_set('error_log',$logfile);
}
/* purifikasi request dengan strip slash
 * nanti akan disanitasi sendiri dalam model
 */
function strips($_val) {
    return is_array($_val) ?
    array_map('strips', $_val) : stripslashes($_val);
}

if(get_magic_quotes_gpc()){
    $_GET    = strips($_GET);
    $_POST   = strips($_POST);
    $_COOKIE = strips($_COOKIE);
}

/* hapus semua variabel $_GLOBALS
 * bahayaaa!..
 */
if(ini_get('register_globals')){
    $_qry = array('_SESSION',
                  '_POST',
                  '_GET',
                  '_COOKIE',
                  '_REQUEST',
                  '_SERVER',
                  '_ENV',
                  '_FILES'
              );
    foreach ($_qry as $array) {
    	foreach ($GLOBALS[$array] as $key => $var) {
    		if ($var === $GLOBALS[$key])unset($GLOBALS[$key]);
    	}
    }
}

/* route dimulai disini */
function textFromUrl($text){
	$array=array('-','+',' ','|','!');
	$text=str_replace($array,'_',$text);
	$text=str_replace('//','/',$text);
	$text=str_replace('=/','',$text);
	// return strtolower($text);
   return $text;
}

function parseQry($get,$limit=10){
	$array=array();
	for ($i=0; $i < $limit; $i++){
		$array[]='';
	}

	$qry=textFromUrl($get);
	$a=explode('/',$qry);
	foreach($a as $key => $value) {
		$array[$key]=$value;
	}
	return $array;
}

$get_u=parseQry($_GET['u']);

/* default controller  class */
$s='Ctrl';

if(!empty($get_u[0])){
    $c=ucfirst($get_u[0]);
    if(class_exists($c)) {
        $s=$c;
        array_shift($get_u);
    }
}
$controller=$s;

/*default class method*/
$s='index';
if(!empty($get_u[0])){
    $c=$get_u[0];
    if(method_exists($controller,$c)) {
        $s=$c;
        array_shift($get_u);
    }
}

/*route begin*/
$method=$s;
$query=$get_u;

$object=new $controller;
$object->$method();
