<?php

namespace Leedch\Codemonkey\Core;

use Exception;
use ZipArchive;
use Leedch\Codemonkey\Core\Core;
use Leedch\Codemonkey\Core\File;
use Leedch\Html\Html5 as H;

/**
 * This stands for a bundle of generated files
 *
 * @author leed
 */
class Project extends Core {
    
    protected $config;
    
    /**
     * Loads a configuration file (JSON File)
     * 
     * @param string $pathConfigFile path to file
     */
    public function loadConfig($pathConfigFile) {
        $strJson = file_get_contents($pathConfigFile);
        $this->loadConfigJson($strJson);
    }
    
    /**
     * Load configuration from JSON Code
     * 
     * @param string $Json
     */
    public function loadConfigJson($Json) {
        $arrConfig = json_decode($Json, true);
        $this->config = $arrConfig;
    }
    
    /**
     * Creates a single file (and all required folders)
     * 
     * @param string $arrFile JSON string
     */
    protected function createFile($arrFile) {
        $file = new File();
        $file->setFilePath($arrFile['name']);
        foreach ($arrFile['templates'] as $template) {
            if (substr($template, 0, 1) != "/") {
                $template = codemonkey_pathTemplateDir.$template;
            }
            $file->addTemplate($template);
        }
        if (isset($arrFile['attributes'])) {
            $file->addAttributes($arrFile['attributes']);
        }
        
        if (isset($this->config['attributes'])) {
            $file = $this->addPostAttributes($file);
        }
        
        $file->generate();
    }
    
    /**
     * Catches all POST attributes that were sent from the webform, providing 
     * they are defined in the JSON Config
     * 
     * @return array
     */
    protected function getAllowedPostedAttributes() {
        $arrAddAttributes = [];
        $arrAllowedAttributes = [];
        foreach ($this->config['attributes'] as $attr) {
            $arrAllowedAttributes[] = $attr['name'];
        }
        
        foreach ($_POST as $key => $val) {
            if (!in_array($key, $arrAllowedAttributes)) {
                continue;
            }
            $arrAddAttributes[$key] = $val;
        }
        return $arrAddAttributes;
    }
    
    /**
     * Add the POST variables to the placeholders
     * 
     * @param File $file
     * @return File
     */
    protected function addPostAttributes(File $file) {
        $arrAddAttributes = $this->getAllowedPostedAttributes();
        $file->addAttributes($arrAddAttributes);
        return $file;
    }
    
    /**
     * Run the Page
     * 1. If no config set, empty
     * 2. If no POST params, show Webform
     * 3. If POST params, generate and Zip Files
     * 
     * @return type
     */
    public function execute() {
        if (!count($this->config['files'])) {
            return;
        }
        
        if (isset($_POST) && count($_POST) > 0) {
            $this->createFiles();
            $this->returnZipFile();
        } else {
            echo $this->renderInputForm();
        }
    }
    
    /**
     * Create all required files
     */
    public function createFiles() {
        foreach ($this->config['files'] as $file) {
            $this->createFile($file);
        }
    }
    
    /**
     * Render webform for dynamic placeholders
     * 
     * @return string HTML Page
     */
    protected function renderInputForm() {
        $arrTableBody = [];
        
        foreach ($this->config['attributes'] as $attribute) {
            $label = isset($attribute['label'])?$attribute['label']:"";
            $fieldName = isset($attribute['name'])?$attribute['name']:"";
            $value = isset($attribute['default'])?$attribute['default']:"";
            $arrTableBody[] = [
                H::label($label, $fieldName),
                H::input($fieldName, "input", $value),
            ];
        }
        
        $content = H::renderTable([], $arrTableBody)
                 . H::button("Generate Code");
        $output = H::form($_SERVER['REQUEST_URI'], $content);
        $headers = "";
        return H::htmlDocument("Codemonkey", $headers, $output);
    }
    
    /**
     * Empty the temp folder
     */
    public function clearTempFolder()
    {
        $this->flushDir(codemonkey_pathTempDir);
    }
    
    /**
     * Deletes contents of a folder and content
     * 
     * @param string $dir   Directory path
     */
    protected function flushDir($dir)
    {
        if (!file_exists($dir)) {
            echo "HELP cant find ".$dir."\n";
            return;
        }
        $objects = scandir($dir);
        foreach ($objects as $object) {
            try {
                $this->deleteFile($object, $dir);
            } catch (Exception $e) {
                echo "Can't remove Folder/File ".$dir.DIRECTORY_SEPARATOR.$object."<br />\n"
                        . $e->getMessage()."<br />\n";
            }
        }
    }
    
    /**
     * Delete File / Folder
     * 
     * @param string $file  filename
     * @param string $dir   filepath
     */
    protected function deleteFile($file, $dir) {
        if ($file != "." && $file != "..") {
            if (is_dir($dir."/".$file)) {
                $this->flushDir($dir."/".$file);
                rmdir($dir."/".$file);
            } else {
                unlink($dir."/".$file);
            }
        }
    }
    
    /**
     * Creates a Zip file from the temp folder and returns it immediately (terminates script)
     */
    public function returnZipFile() {
        chdir(codemonkey_pathTempDir);
        $zipname = $this->config['projectname'].".zip";
        $zip = new ZipArchive();
        $zip->open($zipname, ZipArchive::CREATE);

        foreach ($this->listTempDir() as $file) {
            $zip->addFile($file);
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile(codemonkey_pathTempDir.$zipname);
        unlink($zipname);
        $this->clearTempFolder();
        exit();
    }
    
    /**
     * Picks up all the file names in the temp folder. Needed to create a zip
     * 
     * @return array
     */
    protected function listTempDir($dir = ''){
        $arrFiles = [];
        $scandir = ($dir != '')?$dir:'.';
        $objects = scandir($scandir);
        foreach ($objects as $object) {
            if ($object == "." || $object == "..") {
                continue;
            }
            $objectPath = ($dir != '')?$dir.DIRECTORY_SEPARATOR.$object:$object;
            if (is_dir($objectPath)) {
                $arrFiles = array_merge($arrFiles, $this->listTempDir($objectPath));
            } else {
                $arrFiles[] = $objectPath;
            }
        }
        return $arrFiles;
    }    
}
