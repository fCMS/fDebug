<?php
/**
 * Created by SYNAXON AG.
 * User: Karl Spies
 * Date: 28.11.11
 */

// I assume you have Zend Framework in your include path in your PEAR installation
define('APPLICATION_PATH', '../../../../server');
// Ensure php/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH),
    realpath(APPLICATION_PATH . '/php'),
    get_include_path(),
)));
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

require_once 'fdebug.lib.php';

$config = array(
	'fDebug'	=> fDebug::getInstance(),
	'host'		=> 'localhost',
	'url'		=> '/',
	'remoteIP'	=> '127.0.0.1',
	'port'=> '5005'
);

//create writer model
$logger = Zend_Log::factory(array(array('writerName' => 'FDebug', 'writerParams' => $config)));
//set up logger with writer, if you would like to have more than one writer
//use $logger->addWriter();

$logger->log("Karls Test",Zend_Log::DEBUG);
$logger->log("Karls Test",Zend_Log::DEBUG);
$logger->log("Karls Test",Zend_Log::DEBUG);
