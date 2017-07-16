<?php

/**
 * Use this file in your webroot to generate tests via web
 */

//This nifty code converts PHP Errors from old functions into Exceptions for 
//better Handling
set_error_handler(function($errno, $errstr, $errfile, $errline){ 
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);

});

require_once '../vendor/autoload.php';
require_once '../configs/constants.php';
require_once '../src/autoload.php';

$project = new \Leedch\Codemonkey\Core\Project();
$project->loadConfig('../configs/codemonkey/project1.json');
$project->execute();

//It is best practice to reset error handling when finished
restore_error_handler();