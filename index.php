<?php
/*hide version php*/

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR',dirname(__FILE__).DS.'app');
define('UPLOAD_DIR',dirname(__FILE__).DS.'upload');

require_once (ROOT_DIR.DS.'core'.DS.'auto.php');
include ROOT_DIR.DS.'core'.DS.'config.php';

include ROOT_DIR.DS.'core'.DS.'route.php';
