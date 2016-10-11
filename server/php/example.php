<?php

 require 'fdebug.lib.php';

 $fdebug = fDebug::getInstance();
 $fdebug->setSession('localhost','/');
 $fdebug->openSocket('127.0.0.1',5005);

 $fdebug->confirm('hallo welt', "Y");

 $fdebug->sendMessage('Test message');
 $fdebug->closeSocket();

