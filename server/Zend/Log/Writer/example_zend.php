<?php
/**
 * Created by SYNAXON AG.
 * User: Karl Spies
 * Date: 28.11.11
 */

// I assume you have Zend Framework in your include path.
require_once 'FDebug.php';
require_once 'Zend/Log.php';
require_once '../../../../server/php/fdebug.lib.php';

$fdebug = fDebug::getInstance();
$fdebug->setSession('localhost', '/');
$fdebug->openSocket('127.0.0.1', 5005);

$writer = new Zend_Log_Writer_FDebug();
$writer->setFDebug($fdebug);

$logger = new Zend_Log($writer);

$logger->log("Karls Test",Zend_Log::DEBUG);
