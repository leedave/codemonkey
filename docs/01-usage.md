# Usage

## Purpose 

This tool should help you automate boring tasks by letting you create code using
templates

## What you need

To generate code with this tool you'll need
* A file defining Constants required (example in components `config` folder)
* A JSON config file
* At least one template file

## Config File

All info on how to create a config file are [here](02-config.md)

## Template File

All info on how to create templates are [here](03-template.md)

## Run / Generate

The component automatically checks if you have posted a form or not. If not 
it supplies you with a HTML Form and all input fields you defined in your 
config.json. If post variables are detected, your code files will be generated
and returned to the browser in form of a zip file.

To create a page where you can run codemonkey, do the following:

* Create a php (web) script to load codemonkey
* Copy/Create the constants.php file to your project and load it in your script
* Create a config.json file
* Load the project class in your script
* Inject your json config to the project Class using `loadConfig`
* and run the `execute()` method on the project

Example
```php
<?php
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
```
